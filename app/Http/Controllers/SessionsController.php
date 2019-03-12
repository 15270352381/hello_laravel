<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

class SessionsController extends Controller
{
	/**
	 * 登录页面
	 */
    public function create()
    {
    	return view('sessions.create');
    }
    /**
     * 登录验证
     */
    public function store(Request $request)
    {
    	$credentials = $this->validate($request, [
    		'email' => 'required|email',
    		 'password' => 'required|min:6'
		]);
		// var_dump($credentials);
		// exit;
		if(Auth::attempt($credentials, $request->has('remember')))
		{
			session()->flash('success', '欢迎回来');
			return redirect()->route('users.show', [Auth::user()]);
		} else {
			session()->flash('danger', '用户名或密码不匹配');
			return redirect()->back();
		}
		// return;
		

    }
    /**
     * 退出登录
     */
    public function destroy()
    {
    	Auth::logout();
    	session()->flash('success', '您已成功退出');
    	return redirect('login');
    }
}
