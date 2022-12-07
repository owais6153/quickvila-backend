<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttributesOption;
use App\Models\Attribute;
use App\Repositories\Repository;

class AttributeOptionController extends Controller
{
    function __construct(AttributesOption $attributesOption)
    {
        $this->model = new Repository($attributesOption);
    }
    public function index(Request $request)
    {
        $mystore = $request->mystore;
        $data = [
            'options' => $mystore->attribute_options,
            'status' => 200
        ];
        return response()->json($data, 200);
    }
    public function create(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'name' => 'required|min:3',
                'media' => 'nullable|min:4',
            ]);

            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;
                return response()->json($error, 400);
            }

            $mystore = $request->mystore;
            $this->model->create([
                'name' => $request->name,
                'media' => $request->media,
                'user_id' => auth()->id(),
                'attr_id' => $request->attribute,
                'store_id' => $mystore->id,
            ]);


            return response()->json(['status' => 200, 'message' => 'Attribute Option Created'], 200);
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
    public function show(Request $request, Attribute $attribute, $id)
    {
        try{
            $mystore = $request->mystore;
            $option = $mystore->attribute_options()->where('id', $id)->where('attr_id', $attribute->id)->first();
            if(empty($option)){
                $error['errors'] = ['option' => ['Option Not Found.']];
                $error['status'] = 400;
                return response()->json($error, 404);
            }

            return response()->json(['status' => 200, 'option' => $option], 200);

        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
    public function update(Request $request, Attribute $attribute, $id)
    {
        try{
            $mystore = $request->mystore;
            $option = $mystore->attribute_options()->where('id', $id)->where('attr_id', $attribute->id)->first();
            if(empty($option)){
                $error['errors'] = ['option' => ['Option Not Found.']];
                $error['status'] = 400;
                return response()->json($error, 404);
            }
            $this->model->update([
                'name' => $request->name,
                'media' => $request->media,
            ], $option->id);
            return response()->json(['status' => 200, 'message' => 'Attribute Option Updated'], 200);

        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
    public function destroy(Request $request, Attribute $attribute, $id)
    {
        $mystore = $request->mystore;
        $option = $mystore->attribute_options()->where('id', $id)->where('attr_id', $attribute->id)->first();
        if(empty($option)){
            $error['errors'] = ['attribute' => ['Option Not Found.']];
            $error['status'] = 400;
            return response()->json($error, 404);
        }

        $option->delete();
        return response()->json(['status' => 200, 'message' => 'Product Option Deleted'], 200);

    }
}
