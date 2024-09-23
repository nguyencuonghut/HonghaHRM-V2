<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

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
}
