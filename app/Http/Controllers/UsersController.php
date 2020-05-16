<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{

    public function __construct()
    {
        /*auth 中间件过滤方法*/
        $this->middleware('auth',[
            'except'=>['show','store','create','index','confirmEmail']
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
        $statuses = $user->statuses()->orderBy('created_at','desc')
            ->paginate(5);
        return view('users.show',compact('user','statuses'));
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
//        Auth::login($user);
        $this->sendEmailConfirmationTo($user);
//        session()->flash('success','欢迎，您将这里开启一段新的旅程～～');
        session()->flash('success','恭喜你，注册还差最后一步，请打开邮箱点击验证激活短信链接即可！');
//        return redirect()->route('users.show',[$user]);
        return redirect('/');
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


    /**
     * 用户列表
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index',compact('users'));
    }

    /**
     * 删除用户
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $this->authorize('destroy',$user);
        $user->delete();
        session()->flash('success','成功删除用户～');
        return back();
    }

    /**
     * 发送邮件信息
     * @param $user
     */
    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
//        $from = 'yulinzhihou@163.com';
        $name = 'yulinzhihou';
        $to   =  $user->email;
        $subject = '感谢注册 Weibo App 应用！请确认你的邮箱。';

        Mail::send($view,$data,function ($message) use ($name,$to,$subject) {
            $message->to($to)->subject($subject);
        });
    }

    /**
     * 确认邮箱
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmEmail($token)
    {
        $user = User::where('activation_token',$token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success','恭喜你，激活成功！');
        return redirect()->route('users.show',[$user]);
    }

    /**
     * 显示粉丝
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function followers(User $user)
    {
        $users = $user->followers()->paginate(10);
        $title = $user->name . '的粉丝';
        return view('users.show_follow', compact('users', 'title'));
    }

    /**
     * 显示关注人
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function followings(User $user)
    {
        $users = $user->followings()->paginate(10);
        $title = $user->name . '关注的人';
        return view('users.show_follow',compact('users','title'));
    }

    public function feed()
    {
        $user_ids = $this->followings->pluck('id')->toArray();
        array_push($user_ids,$this->id);
    }
}
