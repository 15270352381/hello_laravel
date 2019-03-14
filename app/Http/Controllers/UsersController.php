<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Requests;
use App\Models\User;
use App\Http\Middleware;
use Auth;

class UsersController extends Controller
{
    //过滤器
    public function __construct()
    {
        $this->Middleware('auth', [
            'except' => ['show', 'create', 'store', 'index']
        ]);
        $this->Middleware('guest', [
            'only' => ['create']
        ]);
    }
    //用户列表
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }
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
        // echo $request;exit;
        //给表单添加验证条件
    	$this->validate($request, [
    			'name' => 'required|max:50',
			    'email' => 'required|email|unique:users|max:255',
			    'password' => 'required|confirmed|min:6'

    		]);
        //获取表单输入参数
    	$user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        //认证参数
        Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        //返回参数并加入数据表
        return redirect()->route('users.show', [$user]);
    }
    //更新用户信息显示页
    public function edit(User $user)
    { 
        // echo '<pre>';
        // print_r(Auth::user()->id);exit;
        $this->authorize('update', $user);  
        return view('users.edit', compact('user'));
    }
    //更新用户信息逻辑
    public function update(User $user, Request $request)
    {
        //需要验证的参数
        $this->validate($request, [
                'name' => 'required|max:50',
                'password' => 'required|confirmed|min:6'
            ]);
        $this->authorize('update', $user);
        //定义一个变量把数据里面
        $data = [];
        $data['name'] = $request->name;
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }
        // 对数据进行修改
        $user->update($data);
        session()->flash('success', '个人资料更新成功！');
        return redirect()->route('users.show', $user->id);
    }
    //删除用户信息
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }
}
