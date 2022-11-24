<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\AttributeRequest;
use App\Models\Attribute;
use DataTables;
use Bouncer;

class AttributesController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view-attribute', ['index', 'getList']);
        $this->middleware('permission:create-attribute', ['create', 'store']);
        $this->middleware('permission:edit-attribute', ['edit', 'update']);
        $this->middleware('permission:delete-attribute', ['destroy']);
        $this->dir = 'admin.attributes.';
    }

    public function index()
    {
        return view($this->dir . 'index');
    }
    public function getList(Request $request)
    {
        $model =  Attribute::withCount(['options']);
        return DataTables::eloquent($model)
            ->addColumn('action', function ($row) {
                $html = '<div class="dropdown">
                <a class="btn btn-sm btn-icon-only dropdown-toggle text-light" role="button" style="color: #ced4da !important;"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                    <i class="fas fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">';
                if (Bouncer::can('edit-attribute')) {
                    $html .= '<a  href="' . route('attribute.edit', ['attribute' => $row->id]) . '" class="dropdown-item"><i class="mr-2 fas fa-pencil-alt"></i>Edit</a>';
                }
                if (Bouncer::can('delete-attribute') ) {
                    $html .= '<form method="POST" action="' . route('attribute.destroy', ['attribute' => $row->id]) . '">
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
    public function create()
    {
        return view($this->dir . 'create');
    }
    public function store(AttributeRequest $request)
    {
        Attribute::create([
            'name' => $request->name,
            'type' => $request->type,
            'user_id' => $request->user_id,
            'store_id' => $request->has('store_id') ? $request->store_id : null
        ]);

        return redirect()->route('attribute.index')->with('success', 'Attribute Created');
    }
    public function edit(Attribute $attribute)
    {
        return view($this->dir . 'edit', compact('attribute'));
    }
    public function update(AttributeRequest $request, Attribute $attribute)
    {
        $attribute->update([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return redirect()->route('attribute.index')->with('success', 'Attribute Updated');
    }
    public function destroy(Attribute $attribute)
    {
        $attribute->options->delete();
        $attribute->delete();

        return redirect()->route('attribute.index')->with('success', 'Attribute Deleted');
    }

}
