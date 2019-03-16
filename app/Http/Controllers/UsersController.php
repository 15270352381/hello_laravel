<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Requests;
use App\Models\User;
use App\Http\Middleware;
use Auth;
use Mail;

class UsersController extends Controller
{
    //过滤器
    public function __construct()
    {
        //中间件
        $this->Middleware('auth', [
            'except' => ['show', 'create', 'store', 'index','confirmEmail']
        ]);
        // 除了未登录用户可访问
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
        //发邮件
        $this->sendEmailConfirmationTo($user);
        // print_r($this->sendEmailConfirmationTo);exit;
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收');
        //返回参数并加入数据表
        return redirect('/');
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
        // 设置只有管理员才能删除
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }
    //邮件发送内容
    public function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        // $from = 'aufree@yousails.com';
        // $name = 'Aufree';
        $to = $user->email;
        $subject = "感谢注册 Sample 应用！请确认你的邮箱。";
        Mail::send($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }
    //激活成功
    public function confirmEmail($token)
    {
        // 注册用户信息
        $user = User::where('activation_token', $token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();
        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);

    }
}
