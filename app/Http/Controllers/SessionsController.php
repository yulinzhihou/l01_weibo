<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest',[
            'only'  => ['create']
        ]);
    }

    /**
     * 用户登录
     */
    public function create()
    {
        return view('sessions.create');
    }

    /**
     * 用户登录
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $credentials = $this->validate($request,[
            'email' => 'required|max:255|email',
            'password'  => 'required'
        ]);

        if (Auth::attempt($credentials,$request->has('remember'))) {
            if (Auth::user()->activated) {
                //表示已经激活
                //登录成功
                session()->flash('success','恭喜你，登录成功～～');
                $fallback = route('users.show',[Auth::user()]);
                return redirect()->intended($fallback);
            } else {
                //表示没有激活
                Auth::logout();
                session()->flash('warning','您的账号未激活，请检查邮箱中的注册邮件进行激活');
                return redirect('/');
            }
        } else {
            //登录失败
            session()->flash('danger','很抱歉，你的邮箱和密码不匹配～');
            return redirect()->back()->withInput();
        }
    }

    /**
     * 用户退出
     */
    public function destroy()
    {
        Auth::logout();
        session()->flash('success','您已经成功退出了～');
        return redirect()->route('login');
    }
}
