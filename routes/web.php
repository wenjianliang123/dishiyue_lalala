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
 * 商品管理 图片上传
 */
Route::prefix('/admin/goods/')->group(function() {
    Route::get('/add','Admin\goodsController@add');
    Route::post('/do_add','Admin\goodsController@do_add');
    Route::get('/index','Admin\goodsController@index');
    Route::any('/update','Admin\goodsController@update');
    Route::get('/del/{goods_id}','Admin\goodsController@del');
    Route::any('/edit/{goods_id}','Admin\goodsController@edit');//商品 修改页面
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
    Route::get('loginout','Admin\loginController@loginout');
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
    Route::get('/goods_detail/{goods_id}','Index\indexController@goods_detail');
    Route::post('/index/buy_cart','Index\indexController@buy_cart');
    Route::get('/index/buy_cart_1','Index\indexController@buy_cart_1');
    Route::post('/index/order','Index\indexController@order');
    Route::get('/index/do_order','Index\indexController@do_order');
});

/**
 * 0715周考 商品管理 增删改查 上传、中间件限制进入、redis记录访问次数
 */
Route::prefix('/admin/zhoukao')->group(function() {
    Route::get('/add','Admin\diyicizhoukao0715@add');
    Route::post('/do_add','Admin\diyicizhoukao0715@do_add');
    Route::get('/index','Admin\diyicizhoukao0715@index');
    Route::post('/update','Admin\diyicizhoukao0715@update');
    Route::get('/del/{id}','Admin\diyicizhoukao0715@del');
});


/**
 * 0722周考
 */
Route::prefix('/admin/diercizhoukao')->group(function() {
    Route::get('/add','Admin\diercizhoukao_huoche_0722@add');
    Route::post('/do_add','Admin\diercizhoukao_huoche_0722@do_add');
    Route::get('/index','Admin\diercizhoukao_huoche_0722@index');
    Route::get('/edit/{id}','Admin\diercizhoukao_huoche_0722@edit');
    Route::post('/update','Admin\diercizhoukao_huoche_0722@update');
});

//超出时间限制进入
Route::group(['middleware' => ['limitTimeAccessInto'],'prefix'=>'/admin/zhoukao'], function () {
    Route::get('/edit/{id}','Admin\diyicizhoukao0715@edit');
});

//用户管理员的展示列表
Route::group(['middleware' => ['login'],'prefix'=>'/admin/user/'], function () {
    Route::get('/index','Admin\userController@index');
    Route::get('/edit/{id}','Admin\userController@edit');
    Route::get('/update','Admin\userController@update');
    Route::get('/delete/{id}','Admin\userController@delete');
});

/**
 * 未完成
 */
//管理员
Route::get('strator_add','admin\StratorController@strator_add');
Route::post('strator_add_do','admin\StratorController@strator_add_do');
Route::get('strator_list','admin\StratorController@strator_list');
Route::post('stratot_list_add','admin\StratorController@stratot_list_add');
Route::post('strator_list_acc','admin\StratorController@strator_list_acc');
Route::post('/strator_list_abb','admin\StratorController@strator_list_abb');
Route::get('strator_list_fff','admin\StratorController@strator_list_fff');
Route::post('strator_list_do','admin\StratorController@strator_list_do');
Route::get('strator_list_list','admin\StratorController@strator_list_list');


/**
 * 调用登录中间件 //整个模块加入登录中间件
 */
Route::group(['middleware' => ['login'],'prefix'=>'/admin/diaoyan/'], function () {

    /**
     * 调研
     * 启用||删除  没有做启用删除 和生成链接
     */
    Route::get('add','Admin\diaoyanController@diaoyan_xiangmu_add');
    Route::post('do_add','Admin\diaoyanController@diaoyan_xiangmu_do_add');
    Route::post('wenti1_do_add','Admin\diaoyanController@diaoyan_wenti1_do_add');
    Route::post('danxuan_answer_do_add','Admin\diaoyanController@danxuan_answer_do_add');
    Route::post('fuxuan_answer_do_add','Admin\diaoyanController@fuxuan_answer_do_add');
    Route::get('diaoyan_list_do_add','Admin\diaoyanController@diaoyan_list_do_add');


});



/**
 * 竞猜 -kezhi yang
 */

    Route::get('kaoshi/add','Admin\jingcaiController@add');
    Route::post('kaoshi/doadd','Admin\jingcaiController@doadd');
    Route::any('kaoshi/index','Admin\jingcaiController@index');
    Route::get('kaoshi/guess','Admin\jingcaiController@guess');
    Route::get('kaoshi/goguess','Admin\jingcaiController@goguess');
    Route::any('kaoshi/q','Admin\jingcaiController@doguess');
    Route::post('kaoshi/result','Admin\jingcaiController@result');
    Route::get('kaoshi/results','Admin\jingcaiController@results');

/**
 * 竞猜 （除了比赛结果的后台添加和验证两只球队不能一样都做了） -wen jianliang
 */
Route::prefix('/admin/jingcai')->group(function() {
    Route::get('/add','Admin\jingcai_controller@add');
    Route::post('/do_add','Admin\jingcai_controller@do_add');
    Route::get('/index','Admin\jingcai_controller@index');
    Route::get('/yonghu_guess/{jingcai_id}','Admin\jingcai_controller@yonghu_guess');
    Route::post('/do_guess','Admin\jingcai_controller@do_guess');
    Route::get('/chakan_bisai_jieguo/{jingcai_id}','Admin\jingcai_controller@chakan_bisai_jieguo');
    Route::get('/chakan_jingcai_jieguo/{jingcai_id}','Admin\jingcai_controller@chakan_bisai_jieguo');
//    Route::post('/update','Admin\diercizhoukao_huoche_0722@update');
});

/**
 * 月考 —— 车库（除了计算价格和时间其他都好） -yuxin gao
 */
Route::get('login','Admin\chekuController@login');
Route::post('dologin','Admin\chekuController@dologin');
Route::get('logout','Admin\chekuController@logout');

Route::group(['middleware' => ['chekuLogin'],'prefix'=>'/admin/cheku/'], function () {

    Route::get('index','Admin\chekuController@index');
    Route::get('addcar','Admin\chekuController@addcar');
    Route::post('doaddcar','Admin\chekuController@doaddcar');
    Route::get('addmenwei','Admin\chekuController@addmenwei');
    Route::post('doaddmenwei','Admin\chekuController@doaddmenwei');
    Route::get('admin','Admin\chekuController@admin');
    Route::get('carin','Admin\chekuController@carin');
    Route::post('docarin','Admin\chekuController@docarin');
    Route::get('carout','Admin\chekuController@carout');
    Route::post('docarout','Admin\chekuController@docarout');
    Route::get('detail','Admin\chekuController@detail');
    Route::get('info','Admin\chekuController@info');


});
/**
 * 接口 --七月
*/
Route::get('jiekou/index','Admin\jiekouController@index');
Route::post('/zhifubao/pay','PayController@do_pay');


/**
 *月考 -- 新闻管理 缓存优化+访问量不同+删除只能删除自己添加的并且是半小时内的数据
 */
Route::get('yuekao/login','Yuekao\xinwenController@login');
Route::post('yuekao/do_login','Yuekao\xinwenController@do_login');

//八月接口
Route::post('ceshijiekou/','Yuekao\xinwenController@ceshijiekou');

//八月获取——access_token、获取用户列表、获取用户信息、
Route::get('/wechat/get_user_info','Yuekao\xinwenController@get_user_info');
Route::get('/wechat/get_user_list','Yuekao\xinwenController@get_user_list');
//测试八月——用户openid和subscribe foreach 入库
Route::get('/wechat/user_list_do_add','Yuekao\xinwenController@user_list_do_add');
//测试八月——用户信息展示
Route::get('/wechat/user_list_zhanshi','Yuekao\xinwenController@user_list_zhanshi');
//测试八月——用户详情
Route::get('/wechat/user_detail/{id}','Yuekao\xinwenController@user_detail');

Route::group(['middleware' => ['checkLogin_xinwen'],'prefix'=>'/yuekao/xinwen/'], function () {
    Route::get('index','Yuekao\xinwenController@index');
    Route::get('xinwen_add','Yuekao\xinwenController@xinwen_add');
    Route::post('xinwen_do_add','Yuekao\xinwenController@xinwen_do_add');
    Route::get('del/{id}','Yuekao\xinwenController@del');
    Route::get('xinwen_detail/{id}','Yuekao\xinwenController@xinwen_detail');
});
