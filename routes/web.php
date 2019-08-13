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

/**
 * 八月接口
 */


Route::prefix('/wechat')->group(function() {

    //八月接口测试

    Route::any('ceshijiekou/','Jiekou\wechat_user_controller@ceshijiekou');

    //八月获取——access_token、获取用户列表、获取用户信息、
    Route::get('/get_user_info','Jiekou\wechat_user_controller@get_user_info');
    //获取用户列表、循环入库
    Route::get('/get_user_list','Jiekou\wechat_user_controller@get_user_list');

    //测试八月——用户信息展示
    Route::get('/user_list_zhanshi','Jiekou\wechat_user_controller@user_list_zhanshi');
    //测试八月——用户详情
    Route::get('/user_detail/{id}','Jiekou\wechat_user_controller@user_detail');
    //微信授权的登录
    Route::get('/login','Jiekou\wechat_user_controller@login');
    //微信授权获取code
    Route::get('/get_code','Jiekou\wechat_user_controller@get_code');
    //获取模板id --不可用
    Route::get('/get_moban_id','Jiekou\wechat_moban_controller@get_moban_id');
    //获取模板列表
    Route::get('/get_moban_list','Jiekou\wechat_moban_controller@get_moban_list');
    //删除模板
    Route::get('/delete_moban','Jiekou\wechat_moban_controller@delete_moban');
    //推送模板信息
    Route::get('/push_moban_info','Jiekou\wechat_moban_controller@push_moban_info');

    //上传临时和永久的素材的视图
    Route::get('/upload_sucai','Jiekou\wechat_upload_sucai@upload_sucai');
    //处理临时/永久的素材
    Route::post('/do_upload_sucai','Jiekou\wechat_upload_sucai@do_upload_sucai');
    //获取临时图片素材
    Route::get('/get_image_source','Jiekou\wechat_upload_sucai@get_image_source');
    //获取临时视频素材
    Route::get('/get_video_source','Jiekou\wechat_upload_sucai@get_video_source');
    //获取临时音频素材
    Route::get('/get_voice_source','Jiekou\wechat_upload_sucai@get_voice_source');
    //获取（永久--图片）素材列表
    Route::get('/get_yongjiu_image_sucai_list','Jiekou\wechat_upload_sucai@get_yongjiu_image_sucai_list');
    //获取（永久--音频）素材列表
    Route::get('/get_yongjiu_voice_sucai_list','Jiekou\wechat_upload_sucai@get_yongjiu_voice_sucai_list');
    //获取（永久--视频）素材列表
    Route::get('/get_yongjiu_video_sucai_list','Jiekou\wechat_upload_sucai@get_yongjiu_video_sucai_list');
    //删除永久素材
    Route::get('/delete_yongjiu_sucai','Jiekou\wechat_upload_sucai@delete_yongjiu_sucai');
    //获取素材总数
    Route::get('/get_sucai_count','Jiekou\wechat_upload_sucai@get_sucai_count');
    //获取永久素材
    Route::get('/get_yongjiu_sucai','Jiekou\wechat_upload_sucai@get_yongjiu_sucai');

});

Route::prefix('/wechat/biaoqian')->group(function() {
    //创建标签
    Route::post('/create_biaoqian','Jiekou\wechat_biaoqian_controller@create_biaoqian');
    //获取公众号已创建的标签
    Route::get('/get_tag','Jiekou\wechat_biaoqian_controller@get_tag');
    //编辑标签
    Route::post('/edit_tag','Jiekou\wechat_biaoqian_controller@edit_tag');
    //删除标签
    Route::get('/delete_tag/{id}','Jiekou\wechat_biaoqian_controller@delete_tag');
    //批量为用户打标签 batch批量
    Route::post('/Batch_tag_users','Jiekou\wechat_biaoqian_controller@Batch_tag_users');
    //5. 获取标签下粉丝列表
    Route::get('/get_tag_user/{id}','Jiekou\wechat_biaoqian_controller@get_tag_user');
    //批量为用户取消标签 a链接带两个参数
    Route::any('/Batch_tag_user_delete','Jiekou\wechat_biaoqian_controller@Batch_tag_user_delete');
    //获取用户身上的标签列表
    Route::get('/get_user_tag/{openid}','Jiekou\wechat_biaoqian_controller@get_user_tag');

    //根据标签群发消息
    Route::post('/Batch_send_tag_user_info','Jiekou\wechat_biaoqian_controller@Batch_send_tag_user_info');


});

/**
 * 八月微信模板在项目后台管理
 */

Route::prefix('/admin')->group(function() {
    //模板列表
    Route::get('/moban_list','Admin\wechat_moban_controller@moban_list');
    //删除模板
    Route::post('/del_moban','Admin\wechat_moban_controller@del_moban');
});

/**
 * 八月微信标签在项目后台管理
 */

Route::prefix('/admin')->group(function() {
    //标签列表
    Route::get('/biaoqian_list_1','Admin\biaoqian_guanli_controller@biaoqian_list');
    //添加标签的视图
    Route::get('/create_biaoqian_view','Admin\biaoqian_guanli_controller@create_biaoqian_view');
    //修改标签的视图
    Route::get('/eidt_biaoqian_view/{id}','Admin\biaoqian_guanli_controller@edit_biaoqian_view');

    //获取标签下用户列表的视图
    Route::get('get_tag_user_view/{id}','Admin\biaoqian_guanli_controller@get_tag_user_view');

    //根据标签群发消息
    Route::get('Batch_send_tag_user_info_view','Admin\biaoqian_guanli_controller@Batch_send_tag_user_info_view');




});

//接口配置的url ---设置测试号的接口配置url 第一次无法配置成功
Route::any('admin/jiekou_peizhi_url','Admin\biaoqian_guanli_controller@jiekou_peizhi_url');


//接收普通消息(自动回复)
Route::prefix('/admin')->group(function() {
    //接收普通消息(自动回复)
    Route::get('/Receive_normal_messages','Admin\biaoqian_guanli_controller@Receive_normal_messages');
});

//分销
Route::prefix('/admin/fenxiao')->group(function() {
    //用户列表
    Route::get('/user_list','Admin\fenxiaoController@user_list');
    //创建专属二维码
    Route::get('/create_qrcode','Admin\fenxiaoController@create_qrcode');
    //（下属表）分销用户列表
    Route::get('/agent_list','Admin\fenxiaoController@agent_list');
});
 


Route::group(['middleware' => ['checkLogin_xinwen'],'prefix'=>'/yuekao/xinwen/'], function () {
    Route::get('index','Yuekao\xinwenController@index');
    Route::get('xinwen_add','Yuekao\xinwenController@xinwen_add');
    Route::post('xinwen_do_add','Yuekao\xinwenController@xinwen_do_add');
    Route::get('del/{id}','Yuekao\xinwenController@del');
    Route::get('xinwen_detail/{id}','Yuekao\xinwenController@xinwen_detail');
});
