<?php

namespace App\Http\Controllers;

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
}
