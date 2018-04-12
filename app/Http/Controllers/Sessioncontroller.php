<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class Sessioncontroller extends Controller
{
    //
    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $credentials=$this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);
        //Laravel 提供的 Auth 的 attempt 方法可以让我们很方便的完成用户的身份认证操作
        //参数是一个数组，即数据库索引和对应的值，这里直接传$credentials,第二个参数是记住我的布尔值
        if(Auth::attempt($credentials,$request->has('remember'))){
            //数据库中email和密码都匹配
            session()->flash('success','欢迎回来'.Auth::user()->name);
            //Laravel 提供的 Auth::user() 方法来获取 当前登录用户 的信息，并将数据传送给路由。
            return redirect()->route('users.show',[Auth::user()]);
        }
        else{
            session()->flash('danger','登录失败，邮箱与密码不匹配！');
            //后退
            return redirect()->back();
        }
    }
    //用户退出注销
    function destroy(){
        //注销方法
        Auth::logout();
        session()->flash('success','您已成功退出!');
        //重定向
        return redirect('login');
    }
}