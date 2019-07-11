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

Route::get('/', function () {
    return view('welcome');
});


/**
 * 商品管理
 */
Route::prefix('/admin/goods/')->group(function() {
    Route::get('add','Admin\goodsController@add');
    Route::post('do_add','Admin\goodsController@do_add');
});

/**
 * 调用登录中间件 //整个模块加入登录中间件
 */
Route::group(['middleware' => ['login'],'prefix'=>'/admin/student/'], function () {

    /**
     * 学生管理
     *  curd、多条件搜索+分页、表单验证、两表联查、下拉框默认、单选框默认
     */
        Route::get('add','Admin\studentController@add');
        Route::get('do_add','Admin\studentController@do_add');
        Route::get('index','Admin\studentController@index');
        Route::get('edit/{id}','Admin\studentController@edit');
        Route::get('update','Admin\studentController@update');
        Route::get('delete/{id}','Admin\studentController@delete');

});

/**
 * 后台登录
 */
Route::prefix('/admin/login/')->group(function() {
    Route::get('login','Admin\loginController@login');
    Route::get('register','Admin\loginController@register');
    Route::post('do_login','Admin\loginController@do_login');
    Route::post('do_register','Admin\loginController@do_register');
});


/**
 * 前台
 */
Route::prefix('/')->group(function() {
    Route::any('/','Index\indexController@index');
    Route::any('/index/login/login','Index\loginController@login');
    Route::get('/index/login/register','Index\loginController@register');
    Route::any('/index/login/do_login','Index\loginController@do_login');
    Route::post('/index/login/do_register','Index\loginController@do_register');
});

