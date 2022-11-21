<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use DataTables;
use Bouncer;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view-user', ['index', 'getList']);
        $this->dir = 'admin.user.';
    }

    public function index()
    {
        return view($this->dir . 'index');
    }

    public function getList(Request $request)
    {
        $model =  User::where('id', '!=', '1');

        if($request->has('role')){
            $model->whereIs($request->role);
        }

        return DataTables::eloquent($model)
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                if (Bouncer::can('view-order')) {
                    $actionBtn .= '<a href="' . route('order.show', ['order' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                }
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
    public function create()
    {
        abort(404);
    }
    public function store(Request $request)
    {
        abort(404);
    }

    public function show($id)
    {
        abort(404);
    }

    public function edit($id)
    {
        abort(404);
    }

    public function update(Request $request, $id)
    {
        abort(404);
    }

    public function destroy($id)
    {
        abort(404);
    }
}
