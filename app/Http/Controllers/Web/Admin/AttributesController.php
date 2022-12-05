<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\AttributeRequest;
use App\Repositories\Repository;
use App\Models\Attribute;
use App\Models\Store;
use DataTables;
use Bouncer;

class AttributesController extends Controller
{
    function __construct(Attribute $attribute)
    {
        $this->middleware('permission:view-attribute', ['index', 'getList']);
        $this->middleware('permission:create-attribute', ['create', 'store']);
        $this->middleware('permission:edit-attribute', ['edit', 'update']);
        $this->middleware('permission:delete-attribute', ['destroy']);
        $this->dir = 'admin.attributes.';
        $this->model = new Repository($attribute);
    }

    public function index(Store $store)
    {
        return view($this->dir . 'index', compact('store'));
    }
    public function getList(Request $request)
    {
        $model =  $this->model->withCount(['options']);
        $model = $model->where('store_id', $request->store);
        return DataTables::eloquent($model)
            ->addColumn('action', function ($row) {
                $html = '<div class="dropdown">
                <a class="btn btn-sm btn-icon-only dropdown-toggle text-light" role="button" style="color: #ced4da !important;"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                    <i class="fas fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">';
                if (Bouncer::can('edit-attribute')) {
                    $html .= '<a  href="' . route('attribute.edit', ['store'=>$row->store_id, 'attribute' => $row->id]) . '" class="dropdown-item"><i class="mr-2 fas fa-pencil-alt"></i>Edit</a>';
                }
                if (Bouncer::can('delete-attribute') ) {
                    $html .= '<form method="POST" action="' . route('attribute.destroy', ['store'=>$row->store_id, 'attribute' => $row->id]) . '">
                    <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                    <input type="hidden" name="_method" value="DELETE" />
                    <button class="dropdown-item d-block mr-1 btn-link delete"><i class="mr-2 fas fa-trash-alt"></i>Delete</button></form>';
                }

                $html .= '<li class="dropdown-header">Options</li>';

                if (Bouncer::can('edit-attribute')) {
                    $html .= '<a  href="' . route('attributeoption.create', ['attribute' => $row->id]) . '" class="dropdown-item"><i class="mr-2 fa  fa-plus"></i>Add Option</a>
                    <a  href="' . route('attributeoption.index', ['attribute' => $row->id]) . '" class="dropdown-item"><i class="mr-2 fa  fa-eye"></i>All Options</a>';
                }

                $html .= '</div>
                </div>';
                return $html;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
    public function create(Store $store)
    {
        return view($this->dir . 'create', compact('store'));
    }
    public function store(Store $store, AttributeRequest $request)
    {
        $this->model->create([
            'name' => $request->name,
            'type' => $request->type,
            'user_id' => $request->user_id,
            'store_id' => $store->id,
        ]);

        return redirect()->route('attribute.index', ['store'=>$store->id])->with('success', 'Attribute Created');
    }
    public function edit(Store $store, Attribute $attribute)
    {
        return view($this->dir . 'edit', compact('attribute', 'store'));
    }
    public function update(AttributeRequest $request, Store $store, $id)
    {
        $this->model->update([
            'name' => $request->name,
            'type' => $request->type,
        ], $id);

        return redirect()->route('attribute.index', ['store'=>$store->id])->with('success', 'Attribute Updated');
    }
    public function destroy(Store $store, Attribute $attribute)
    {
        if($attribute->options->count() > 0)
            $attribute->options->delete();

        $attribute->delete();

        return redirect()->route('attribute.index', ['store'=>$store->id])->with('success', 'Attribute Deleted');
    }
    public function attributesDropdown(Request $request)
    {
        $search = trim($request->search);

        $model = $this->model->getModel();
        $attr = $model::where('name', 'LIKE', '%' . $search . '%')->where('store_id', $request->store)->get();

        $formatted_attr = [];
        foreach ($attr as $a) {
            $formatted_attr[] = ['id' => $a->id, 'text' => $a->name];
        }

        return \Response::json($formatted_attr);
    }
}
