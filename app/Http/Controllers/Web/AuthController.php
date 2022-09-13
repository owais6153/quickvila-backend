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
        $data['store'] = Bouncer::can('all-store') ? Store::count() : Store::where('user_id', Auth::id())->count() ;
        $data['product'] = Bouncer::can('all-product') ? Product::count() : Product::where('user_id', Auth::id())->count() ;
        return view('admin.index')->with($data);
    }

    public function authenticate(LoginRequest $request)
    {
        $credentials = $request->getCredentials();

        $fieldType = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (Auth::attempt(array($fieldType => $credentials['email'], 'password' => $credentials['password']), request()->has('remember') )) {
            $request->session()->regenerate();
            return redirect()->intended('/admin')->with('message', 'Login successfuly');
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
    public function forgetPasswordEmail(Request $request)
    {
        $request->validateWithBag('forgetpassword', ['email' => 'required|email|exists:users']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with('message', __($status))
                    : back()->withErrors(['email' => __($status)]);
    }

    public function resetPaassword($token)
    {
       return view('index', ['token' => $token, 'resetPaassword' => true, 'title' => 'Reset Password']);
    }
    public function paasswordUpdate(Request $request)
    {
        $request->validateWithBag('resetPassword', [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('account')->with('message', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }
    public function register(RegisterRequest $request)
    {

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if(env('APP_ENV') == 'local')
            $user->email_verified_at = date("Y-m-d",time());

        $user->save();

        if(env('APP_ENV') != 'local')
            $user->sendEmailVerificationNotification();

        Auth::loginUsingId($user->id);
        return redirect('/')->with('success', 'Regiseter Succesfuuly.');
    }
    public function verificationNotice(){
        return redirect()->route('home')->with('verificationpopup', 'true');
    }
    public function __invoke(Request $request): RedirectResponse
    {
        $user = User::find($request->route('id')); //takes user ID from verification link. Even if somebody would hijack the URL, signature will be fail the request
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended('/account');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        $message = __('Your account has been verified.');

        return redirect('/')->with('message', $message); //if user is already logged in it will redirect to the dashboard page
    }
}
