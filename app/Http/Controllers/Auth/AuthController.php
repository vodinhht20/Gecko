<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegisted;
use App\Http\Controllers\Controller;
use App\Repositories\userRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct(private userRepository $userRepo)
    {
    }
    public function loginForm(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email không được để trống !',
            'email.email' => 'Email không đúng định dạng !',
            'password.required' => 'Vui lòng nhập mật khẩu !',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('message.error', $validator->messages()->first())->withInput();
        }
        $user = $this->userRepo->checkPassword($request->email, $request->password);
        if ($user) {
            if (!$user->email_verified_at) {
                return redirect()->back()->with('message.error', 'Tài khoản của bạn chưa được kích hoạt!')->withInput();
            }
        } else {
            return redirect()->back()->with('message.error', 'Tài khoản hoặc mật khẩu không chính xác !')->withInput();
        }
        Auth::login($user, true);
        // return $user->hasAnyRole(['admin', 'verified_customer']) ? redirect()->route('admin.dashboard') : redirect()->route('home');
        return redirect()->route('admin.dashboard');
    }

    public function signUpForm(Request $request)
    {
        return view('auth.signup');
    }
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'term_of_use' => 'accepted',
        ], [
            'name.required' => 'Họ và Tên không được đẻ trống',
            'email.required' => 'Email không được để trống',
            'email.unique' => 'Email này đã tồn tại, vui lòng nhập mail khác hoặc đăng nhập',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'term_of_use.accepted' => 'Vui lòng chấp nhận điều khoản sử dụng',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('message.error', $validator->messages()->first())->withInput();
        }
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'cash' => 0,
        ];
        $user = $this->userRepo->register($data);
        if ($user) {
            event(new UserRegisted($user));
            return redirect()->route('account-verify', ['id' => $user->id]);
        }
        return redirect()->back()->with('message.error', "Đăng ký thất bại. Vui lòng thử lại !")->withInput();
    }
    public function accountVerifyForm(Request $request)
    {
        return view('auth.accountVerifyForm');
    }
    public function resetPassForm(Request $request)
    {
        return view('auth.resetPass');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
