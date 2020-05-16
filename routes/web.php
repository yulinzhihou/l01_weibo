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

Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/about', 'StaticPagesController@about')->name('about');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('signup','UsersController@create')->name('signup');
Route::resource('users','UsersController');
Route::get('login','SessionsController@create')->name('login');
Route::post('login','SessionsController@store')->name('login');
Route::delete('logout','SessionsController@destroy')->name('logout');

//邮箱激活路由
Route::get('signup/confirm/{token}','UsersController@confirmEmail')->name('confirm_email');
//忘记密码
Route::get('password/reset','Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');/*记录密码页面展示*/
Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');/*发送忘记密码的链接到邮箱*/
Route::get('password/reset/{token}','Auth\ResetPasswordController@showResetForm')->name('password.reset');/*打开发送的链接页面*/
Route::post('password/reset','Auth\ResetPasswordController@reset')->name('password.update');/*修改密码*/
//微博资源路由
Route::resource('statuses','StatusesController',['only' => ['store','destroy']]);
//粉丝关注
Route::get('/users/{user}/followers','UsersController@followers')->name('users.followers');/*粉丝*/
Route::get('/users/{user}/followings','UsersController@followings')->name('users.followings'); /*我是别人粉丝*/
//关注与取消关注
Route::post('/users/followers/{user}','FollowersController@store')->name('followers.store');
Route::delete('/users/follwers/{user}','FollowersController@destroy')->name('followers.destroy');
