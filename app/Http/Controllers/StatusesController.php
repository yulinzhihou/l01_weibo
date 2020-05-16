<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusesController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 添加微博
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'content'   =>  'required|max:140'
        ]);

        Auth::user()->statuses()->create(['content'=>$request['content']]);
        session()->flash('success','发布成功～');
        return redirect()->back();
    }

    /**
     * 删除微博
     * @param Status $status
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Status $status)
    {
        $this->authorize('destroy',$status);
        $status->delete();
        session()->flash('success','微博已经被成功删除～');
        return redirect()->back();
    }
}
