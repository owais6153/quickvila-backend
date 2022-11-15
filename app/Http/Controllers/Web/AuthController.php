<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Verified;
use Hash;
use Str;
use Bouncer;

class AuthController extends Controller
{
    public function index()
    {
        $data = [];
        $data['store'] = Bouncer::can('all-store') ? Store::count() : Store::where('user_id', Auth::id())->count();
        $data['product'] = Bouncer::can('all-product') ? Product::count() : Product::where('user_id', Auth::id())->count();

        $store_ids = Store::where('user_id', auth()->id())->pluck('id');
        $data['order'] = Bouncer::can('all-orders') ? Order::sum('total') : Order::whereHas('items', function($query) use($store_ids) {
            $query->whereIn('store_id', $store_ids);
        })->sum('total');

        return view('admin.index')->with($data);
    }

    public function authenticate(LoginRequest $request)
    {
        $credentials = $request->getCredentials();

        $fieldType = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (Auth::attempt(array($fieldType => $credentials['email'], 'password' => $credentials['password']), request()->has('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('message', 'Login successfuly');
        }

        return back()->withErrors([
            'authenticate' => 'Credentials do not match our records.'
        ], 'login')->withInput();
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('successs', 'Logout successfuly');
    }
}
