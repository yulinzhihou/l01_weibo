<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * 用户注册
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * 用户展示
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }


    /**
     * 用户注册
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|unique:users|max:50',
            'email' => 'required|unique:users|email|max:255',
            'password'=>'required|confirmed|min:6|max:20'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password'  => bcrypt($request->password)
        ]);
        Auth::login($user);
        session()->flash('success','欢迎，您将这里开启一段新的旅程～～');
        return redirect()->route('users.show',[$user]);
    }
}
