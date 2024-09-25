<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return  view('auth.login');
    }

    public function handleLogin(Request $request): RedirectResponse
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $messages = [
            'email.required' => 'Bạn phải nhập địa chỉ email.',
            'email.email' => 'Email sai định dạng.',
            'password.required' => 'Bạn phải nhập mật khẩu.',
        ];
        $credentials = $request->validate($rules, $messages);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng!',
        ])->onlyInput('email');
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('login');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot_password');
    }

    public function submitForgotPasswordForm(Request $request)
    {
        $rules = [
            'email' => 'required|email|exists:users',
        ];
        $messages = [
            'email.required' => 'Bạn phải nhập địa chỉ email.',
            'email.email' => 'Email sai định dạng.',
            'email.exists' => 'Email không tồn tại trên hệ thống.',
        ];
        $request->validate($rules,$messages);

        $password_reset_request = DB::table('password_reset_tokens')
                                    ->where([
                                    'email' => $request->email,
                                    ])
                                    ->first();

        if($password_reset_request){
            return back()->withInput()->with('flash_message_error', 'Yêu cầu cấp lại mật khẩu cho tài khoản này đã được tạo. Bạn vui lòng kiểm tra email!');
        }

        $token = Str::random(64);
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Notification::route('mail' , $request->email)->notify(new ForgotPassword($request->email, $token));

        return back()->with('flash_message_success', 'Chúng tôi vừa gửi đường link cấp lại mật khẩu tới email của bạn!');
    }

    public function showResetPasswordForm($token)
    {
        return view('auth.recover_password', ['token' => $token]);
    }

    public function submitResetPasswordForm(Request $request)
    {
        $rules = [
            'email' => 'required|email|exists:users',
            'password' => 'required|confirmed|min:6',
        ];
        $messages = [
            'email.required' => 'Bạn phải nhập địa chỉ email.',
            'email.email' => 'Email sai định dạng.',
            'email.exists' => 'Email không tồn tại',
            'password.required' => 'Bạn phải nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải dài ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu không khớp.',
        ];
        $request->validate($rules,$messages);

        $updatePassword = DB::table('password_reset_tokens')
                            ->where([
                            'email' => $request->email,
                            'token' => $request->token
                            ])
                            ->first();

        if(!$updatePassword){
            return back()->withInput()->with('flash_message_error', 'Token không hợp lệ!');
        }

        $user = User::where('email', $request->email)
                    ->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();

        return redirect('/login')->with('flash_message_success', 'Mật khẩu được khôi phục thành công. Bạn hãy đăng nhập với mật khẩu mới!');
    }
}
