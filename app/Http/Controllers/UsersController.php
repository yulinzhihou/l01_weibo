<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    public function __construct()
    {
        /*auth 中间件过滤方法*/
        $this->middleware('auth',[
            'except'=>['show','store','create']
        ]);

        $this->middleware('guest',[
           'only'   => ['create']
        ]);
    }
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

    /**
     * 用户编辑页面
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(User $user)
    {
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    /**
     * 用户数据更新
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(User $user,Request $request)
    {
        $this->authorize('update',$user);
        $this->validate($request,[
            'name'      => 'required|max:50',
            'password'  => 'nullable|confirmed|min:6|max:20'
        ]);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password']  = bcrypt($request->password);
        }
        $user->update($data);
        session()->flash('success','更新用户信息成功～');
        return redirect()->route('users.show',$user->id);
    }

}
