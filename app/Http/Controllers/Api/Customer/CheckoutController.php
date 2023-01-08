<?php

namespace App\Http\Controllers\Api\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Models\CartProduct;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\Auth;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Details;
use PayPal\Api\Transaction;
use Exception;

class CheckoutController extends Controller
{

    public $order;
    public $error;
    public function add_new_order(Cart $cart, Request $request){
        try{


            $user_id = $request->user()->id;

            $order = Order::create([
                'count' => $cart->count,
                'sub_total' => $cart->sub_total,
                'platform_charges' => $cart->platform_charges,
                'tax' => $cart->tax,
                'total' => $cart->total,
                'tip' => $request->tip,
                'delivery_charges' => $cart->delivery_charges,
                'order_no' => $cart->identifier,
                'status' => PendingPayment(),
                'user_id' => $user_id
            ]);
            foreach($cart->items as $item){
                $orderItem = OrderProduct::create([
                    'line_total' => $item->line_total,
                    'qty' => $item->qty,
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'store_id' => $item->product->store_id,
                    'variation_id' => $item->variations_id,
                ]);
            }

            $this->order = $order;

            return true;
        }
        catch (\Throwable $th) {
            $this->error = $th->getMessage();
            return false;
        }
    }
    public function paynow()
    {
        try{
            $order = $this->order;
            $apiContext = app('paypal');

            $payer = new Payer();
            $payer->setPaymentMethod('paypal');
            $items = [];

            foreach($order->items as $item){
                $paypalitem = new Item();
                $paypalitem->setName($item->variation_id != null ? $item->variation->name : $item->product->name)
                ->setCurrency(env('PAYPAL_CURRENCY'))
                ->setQuantity($item->qty)
                ->setPrice(($item->line_total / $item->qty));
                $items [] = $paypalitem;
            }

            $taxitem = new Item();
            $taxitem->setName('Tax & Charges')->setCurrency(env('PAYPAL_CURRENCY'))
            ->setQuantity(1)
            ->setPrice($order->tax + $order->platform_charges + $order->tip + $order->delivery_charges);
            $items[] = $taxitem;

            $itemList = new ItemList();
            $itemList->setItems($items);

            // $details = new Details();
            // $details->setTax(($order->tax + $order->platform_charges + $order->tip + $order->delivery_charges));

            $amount = new Amount();
            $amount->setCurrency(env('PAYPAL_CURRENCY'))
                ->setTotal(($order->tax + $order->platform_charges + $order->tip + $order->sub_total + $order->delivery_charges))
                // ->setDetails($details)
                ;


            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($itemList)
                ->setDescription("ORDER NO# " . $order->order_no)
                ->setInvoiceNumber($order->order_no);

            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl('http://quickvila.store/payment-suceess')
                ->setCancelUrl('http://quickvila.store/payment-cancel');


            $payment = new Payment();
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions([$transaction]);

            $payment->create($apiContext);
            $this->paymentLink = $payment->getApprovalLink();
            $order->update([
                'payment_id' => $payment->getId(),
            ]);

            return true;

        }
        catch (\Throwable $th) {
            $this->error = $th->getMessage();
            return false;
        }
    }
    public function checkout(Request $request){

        try{
            $validator = \Validator::make($request->all(), [
                'name' => 'required|min:3',
                'phone' => 'required|numeric|min:10',
                'email' => 'required|email',
                'address1' => 'required|min:3',
                'address2' => 'required|min:3',
                'latitude' => 'required|between:-90,90',
                'longitude' => 'required|between:-180,180',
                'note' => 'nullable',
                'tip' => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;
                return response()->json($error, 400);
            }

            $user_id = $request->user()->id;


                $cart  = Cart::where('identifier', $request->identifier)->orWhere('user_id', $user_id)->first();
                if(!empty($cart)){
                    if($cart->items->count()){
                       $newOrder =  $this->add_new_order($cart, $request);
                       if($newOrder === true){

                        $is_payment_successfull = $this->paynow();

                        if($is_payment_successfull){
                            $cart->items()->delete();
                            $cart->delete();

                            $data = array(
                                'order' => $this->order,
                                'payment_link' => $this->paymentLink,
                                'status' => 200
                            );
                            return response()->json($data, 200);
                        }
                        throw new Exception($this->error, 503);
                       }

                       throw new Exception($this->error, 503);

                    }

                    throw new Exception('No item in cart', 404);
                }


                throw new Exception('Cart Not Found Against This User', 404);

        }
        catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;

            return response()->json($error, 500);
        }
    }
    public function paymentSuccess(Request $request)
    {
        $apiContext = app('paypal');

        // retrieve the payment ID from the request
        $paymentId = $request->paymentId;

        // retrieve the payment object
        $payment = Payment::get($paymentId, $apiContext);
        $amount = $payment->getTransactions()[0]->getAmount();
        $chargedAmount = $amount->getTotal();
        $order = Order::where('payment_id', $paymentId)->first();
        // check the payment status
        if ($payment->getState() == 'approved') {
            // the payment was successful
            // you can process the order now
            $order->update([
                'status' => InProcess(),
            ]);
        } else {
            // the payment failed
            // you can redirect the user back to the checkout page or show an error message
            $order->update([
                'status' => Canceled(),
            ]);
        }

        return response()->json(['message' => 'Order status updated', 'status' => 200, 'chargedAmount' => $chargedAmount], 200);

    }
    public function paymentCancel(Request $request)
    {
        $apiContext = app('paypal');

        // retrieve the payment ID from the request
        $paymentId = $request->paymentId;
        $order = Order::where('payment_id', $paymentId)->first();
        $order->update([
            'status' => Canceled(),
        ]);

        return response()->json(['message' => 'Order status updated', 'status' => 200], 200);
    }
}
