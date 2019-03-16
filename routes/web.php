<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//主页
Route::get('/', 'StaticPagesController@home')->name('home');
//帮助页
Route::get('/help', 'StaticPagesController@help')->name('help');
//关于页
Route::get('/about', 'StaticPagesController@about')->name('about');
//注册页
Route::get('/signup', 'UsersController@create')->name('signup');
//用户信息
Route::resource('users', 'UsersController');
//登录视图
Route::get('/login', 'SessionsController@create')->name('login');
//登录逻辑
Route::post('/login', 'SessionsController@store')->name('login');
//退出登录
Route::delete('logout', 'SessionsController@destroy')->name('logout');
//更新用户信息
Route::get('users/{user}/edit', 'UsersController@edit')->name('users.edit');
//激活用户
Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');
//显示重置密码邮箱发送
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
//邮箱发送重设链接
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//密码更新
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
//执行更新密码操作
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
