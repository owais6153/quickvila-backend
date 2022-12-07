<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Repositories\Repository;

class AttributeController extends Controller
{
    function __construct(Attribute $attribute)
    {
        $this->model = new Repository($attribute);
    }
    public function index(Request $request)
    {
        $mystore = $request->mystore;
        $data = [
            'attributes' => $mystore->attributes,
            'status' => 200
        ];
        return response()->json($data, 200);
    }
    public function create(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'name' => 'required|min:3',
                'type' => 'required|min:3',
            ]);

            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;
                return response()->json($error, 400);
            }

            $mystore = $request->mystore;
            $this->model->create([
                'name' => $request->name,
                'type' => $request->type,
                'user_id' => auth()->id(),
                'store_id' => $mystore->id,
            ]);


            return response()->json(['status' => 200, 'message' => 'Product Attribute Created'], 200);
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
    public function show(Request $request, $id)
    {
        try{
            $mystore = $request->mystore;
            $attribute = $mystore->attributes()->where('id', $id)->with(['options'])->first();
            if(empty($attribute)){
                $error['errors'] = ['attribute' => ['Attribute Not Found.']];
                $error['status'] = 400;
                return response()->json($error, 404);
            }

            return response()->json(['status' => 200, 'attribute' => $attribute], 200);

        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
    public function update(Request $request, $id)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'name' => 'required|min:3',
                'type' => 'required|min:3',
            ]);

            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;
                return response()->json($error, 400);
            }

            $mystore = $request->mystore;
            $attribute = $mystore->attributes()->where('id', $id)->first();
            if(empty($attribute)){
                $error['errors'] = ['attribute' => ['Attribute Not Found.']];
                $error['status'] = 400;
                return response()->json($error, 404);
            }

            $this->model->update([
                'name' => $request->name,
                'type' => $request->type,
            ], $attribute->id);

            return response()->json(['status' => 200, 'message' => 'Product Attribute Updated'], 200);
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
    public function destroy(Request $request, $id)
    {
        $mystore = $request->mystore;
        $attribute = $mystore->attributes()->where('id', $id)->first();
        if(empty($attribute)){
            $error['errors'] = ['attribute' => ['Attribute Not Found.']];
            $error['status'] = 400;
            return response()->json($error, 404);
        }
        if($attribute->options->count() > 0)
            $attribute->options->delete();
        $attribute->delete();
        return response()->json(['status' => 200, 'message' => 'Product Attribute Deleted'], 200);

    }
}
