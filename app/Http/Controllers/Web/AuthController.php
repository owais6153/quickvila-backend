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
use App\Services\AuthServices\WebAuthService;

use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Verified;
use Hash;
use Str;
use Bouncer;

class AuthController extends Controller
{
    protected $service;
    function __construct()
    {
        $this->service = new WebAuthService();
    }

    public function index()
    {
        $data = [];
        $data['store'] = Store::count();
        $data['product'] =  Product::count();
        $data['order'] =  Order::sum('total');

        return view('admin.index')->with($data);
    }

    public function authenticate(LoginRequest $request)
    {
        $credentials = $request->getCredentials();
        if ($this->service->authenticate($credentials, $request)) {
            return redirect()->intended('/')->with('message', 'Login successfuly');
        }
        return back()->withErrors([
            'authenticate' => 'Credentials do not match our records.'
        ], 'login')->withInput();
    }
    public function logout()
    {
        $this->service->signout();
        return redirect('/login')->with('successs', 'Logout successfuly');
    }
}
