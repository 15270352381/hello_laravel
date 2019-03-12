<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Requests;
use App\Models\User;

class UsersController extends Controller
{
	//注册首页
    public function create()
    {
    	return view('users.create');
    }
    //获取邮箱头像
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
    //表单验证
    public function store(Request $request)
    {
    	// var_dump($request);
    	// exit;
    	$this->validate($request, [
    			'name' => 'required|max:50',
			    'email' => 'required|email|unique:users|max:255',
			    'password' => 'required|confirmed|min:6'

    		]);
    	$user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]);

    }
}
