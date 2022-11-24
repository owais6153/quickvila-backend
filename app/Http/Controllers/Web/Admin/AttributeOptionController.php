<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttributesOption;
use App\Models\Attribute;
use App\Http\Requests\Admin\AttributeOptionRequest;
use DataTables;
use Bouncer;
class AttributeOptionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view-attribute', ['index', 'getList']);
        $this->middleware('permission:create-attribute', ['create', 'store']);
        $this->middleware('permission:edit-attribute', ['edit', 'update']);
        $this->middleware('permission:delete-attribute', ['destroy']);
        $this->dir = 'admin.attributes.options.';
    }
    public function index(Attribute $attribute)
    {
        return view($this->dir . 'index', compact('attribute'));
    }
    public function getList(Attribute $attribute, Request $request)
    {
        $model =  AttributesOption::where('attr_id', $attribute->id);
        return DataTables::eloquent($model)
            ->addColumn('action', function ($row) {
                $html = '<div class="dropdown">
                <a class="btn btn-sm btn-icon-only dropdown-toggle text-light" role="button" style="color: #ced4da !important;"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                    <i class="fas fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">';
                if (Bouncer::can('edit-attribute')) {
                    $html .= '<a  href="' . route('attributeoption.edit', ['attribute' => $row->attr_id, 'attributeOption'=>$row->id]) . '" class="dropdown-item"><i class="mr-2 fas fa-pencil-alt"></i>Edit</a>';
                }
                if (Bouncer::can('delete-attribute') ) {
                    $html .= '<form method="POST" action="' . route('attributeoption.destroy', ['attribute' => $row->attr_id, 'attributeOption'=>$row->id]) . '">
                    <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                    <input type="hidden" name="_method" value="DELETE" />
                    <button class="dropdown-item d-block mr-1 btn-link delete"><i class="mr-2 fas fa-trash-alt"></i>Delete</button></form>';
                }


                $html .= '</div>
                </div>';
                return $html;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
    public function create(Attribute $attribute)
    {
        return view($this->dir . 'create', compact('attribute'));
    }
    public function store(Attribute $attribute, AttributeOptionRequest $request)
    {
        AttributesOption::create([
            'name' => $request->name,
            'media' => $request->has('media') ?  $request->media : null,
            'attr_id' => $attribute->id,
            'user_id' => $attribute->user_id,
            'store_id' => $request->has('store_id') ?  $request->store_id : null,
        ]);

        return redirect()->route('attributeoption.index', ['attribute' => $attribute->id])->with('success', 'Option Created');

    }
    public function edit(Attribute $attribute, $id)
    {
        $attributesoption = AttributesOption::findOrfail($id);
        return view($this->dir . 'edit', compact('attribute', 'attributesoption'));
    }
    public function update(Attribute $attribute, $id, AttributeOptionRequest $request)
    {
        $attributesoption = AttributesOption::findOrfail($id);
        $attributesoption->update([
            'name' => $request->name,
            'media' => $request->has('media') ?  $request->media : null,
        ]);
        return redirect()->route('attributeoption.index', ['attribute' => $attribute->id])->with('success', 'Option Updated');
    }

    public function destroy(Attribute $attribute, $id)
    {
        $attributesoption = AttributesOption::findOrfail($id)->delete();
        return redirect()->route('attributeoption.index', ['attribute' => $attribute->id])->with('success', 'Option Deleted');
    }

    public function listForVariations(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'variation_attr' => 'required',
        ]);
        if ($validator->fails()) {
            $error['errors'] = $validator->messages();
            $error['status'] = 400;
            return response()->json($error, 400);
        }
        $attributes = [];
        foreach($request->variation_attr as $attr){
           $a = Attribute::findOrFail($attr);
           if(!empty($a)){
                $attributes[] = [
                    'name' => $a->name,
                    'options' => $a->options,
                ];
           }
        }
        return response()->json($attributes, 200);
    }
}
