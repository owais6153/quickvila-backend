<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use App\Http\Requests\Admin\CancelRequest;
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

            ->toJson();
    }
    public function verficationRequests()
    {
        return view($this->dir . 'requests');
    }

    public function verficationRequestsGet(Request $request)
    {
        $model =  User::where('id', '!=', '1');
        $model = $model->whereIs(Customer())->where('identity_card', '!=', null)->where('is_identity_card_verified', false);

        return DataTables::eloquent($model)
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                if (Bouncer::can('view-user')) {
                    $actionBtn .= '<a href="" data-image="'.asset($row->identity_card).'" class="identitycard mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                    $actionBtn .= '<a href="'.route('user.requests.accept', ['user' => $row->id]).'" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-check"></i></a>';
                    $actionBtn .= '<a href="" data-id="'.$row->id.'" class="cancelRequest mr-1 btn btn-circle btn-sm btn-danger"><i class="fas fa-trash"></i></a>';
                }
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
    public function acceptRequest(User $user)
    {
        $user->update(['is_identity_card_verified' => true]);
        $user->verificationRequestStatusChange();
        return redirect()->route('user.requests')->with('success', 'User Account Verified');
    }
    public function cancelRequest(CancelRequest $request, User $user)
    {
        deleteFile($user->identity_card);
        $user->update(['is_identity_card_verified' => false, 'identity_card' => null]);
        $user->verificationRequestStatusChange($request->reason);
        return redirect()->route('user.requests')->with('success', 'User Verification Request Rejected');
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
