<?php

namespace App\Http\Controllers\Jiekou;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Http\Tool\wechat;
use App\Http\Model\menu_model;

class CustomMenuController extends Controller
{
    public $wechat;
    public function __construct(wechat $wechat)
    {
        $this->wechat=$wechat;
    }

    //菜单添加页面
    public function menu_add()
    {
        return view('wechat/custom_menu/menu_add');
    }

    //菜单添加执行页面
    public function menu_do_add(Request $request)
    {

        $data = $request->all();
//        dd($data);
        $result = DB::connection('mysql_shop')->table('wechat_custom_menu')->insert([
            'menu_type' => $data['menu_type'],
            'event_type' => $data['event_type'],
            'first_menu_name' => $data['first_menu_name'],
            'second_menu_name' => $data['second_menu_name'],
            'menu_remark' => $data['menu_remark'],
        ]);
        $this->reload_menu_list();
//        dd($result);
//        if ($result) {
//            return redirect(url('/wechat/custom_menu/menu_list'));
//        } else {
//            echo "添加菜单失败";
//        }

    }

    public function menu_list()
    {
        $data=DB::connection('mysql_shop')->table('wechat_custom_menu')->get()->toArray();
        $data=array_map('get_object_vars',$data);
        /****************************************************************
//        $data=DB::connection('mysql_shop')->table('wechat_custom_menu')->get()->toArray();
//        $data=array_map('get_object_vars',$data);



         // 获取旗下有 去重复 的一级菜单

        //方法一 不完全转变为数组 外面是数组里面是对象
        $first_menu_info=DB::connection('mysql_shop')->table('wechat_custom_menu')->groupBy('first_menu_name')->select(['first_menu_name'])->orderBy('first_menu_name')->get()->toArray();


        //方法二完全变为数组 去重复
        //$first_menu_info=DB::connection('mysql_shop')->table('wechat_custom_menu')->select('first_menu_name')->get()->toArray();

        //array_unique第二个参数是 常规排序
        /**
         * array_unique语法
        array_unique(array)
        参数	描述
        array	必需。规定数组。
        sortingtype
        可选。规定如何比较数组元素/项目。可能的值：

        SORT_STRING - 默认。把项目作为字符串来比较。
        SORT_REGULAR - 把每一项按常规顺序排列（Standard ASCII，不改变类型）。
        SORT_NUMERIC - 把每一项作为数字来处理。
        SORT_LOCALE_STRING - 把每一项作为字符串来处理，基于当前区域设置（可通过 setlocale() 进行更改）。

      //$first_menu_info=array_unique(json_decode(json_encode($first_menu_info),1),SORT_REGULAR );
      //dd($first_menu_info);

        $info = [];
        for($i=0;$i<(count($first_menu_info)-1);$i++){

            $sub_menu = DB::connection('mysql_shop')->table('wechat_custom_menu')->where(['first_menu_name'=>$first_menu_info[$i]->first_menu_name])->orderBy('first_menu_name')->get()->toArray();
//            $sub_menu=json_decode(json_encode($sub_menu),1);
//            dd($sub_menu);
            if(!empty($sub_menu[0]->second_menu_name)){

                $info[] = [
                    'menu_str'=>'|',
                    'menu_name'=>$first_menu_info[$i]->first_menu_name,
                    'menu_type'=>2,
                    'second_menu_name'=>'',
                    'menu_num'=>0,
                    'event_type'=>'',
                    'menu_remark'=>''
                ];
                for($ii=0;$ii<=(count($sub_menu)-1);$ii++){
                    $sub_menu[$ii]->menu_str = '|-';
                    $info[] = (array)$sub_menu[$ii];
                }
            }else{
                $sub_menu[0]->menu_str = '|';
                $info[] = (array)$sub_menu[0];
            }
        }
//        print_r($info);die();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$this->wechat->get_access_token();
        $re = file_get_contents($url);
//        print_r(json_decode($re,1));
        return view('wechat.custom_menu.menu_list',['data'=>$info]);

//        return view('wechat/custom_menu/menu_list',['data'=>$data]);
        ************************************************************************/
        return view('wechat/custom_menu/menu_list',['data'=>$data]);
    }

    public function reload_menu_list()
    {
        /*$data=[
            "button"=>[
             [
                 "type"=>"click",
                  "name"=>"今日歌曲",
                  "key"=>"V1001_TODAY_MUSIC"
              ],
              [
                  "name"=>"菜单",
                   "sub_button"=>[
                   [
                       "type"=>"view",
                       "name"=>"搜索",
                       "url"=>"http://www.soso.com/"
                    ],
                    [
                        "type"=>"miniprogram",
                         "name"=>"wxa",
                         "url"=>"http://mp.weixin.qq.com",
                         "appid"=>"wx286b93c14bbf93aa",
                         "pagepath"=>"pages/lunar/index"
                     ],
                    [
                        "type"=>"click",
                       "name"=>"赞一下我们",
                       "key"=>"V1001_GOOD"
                    ]]
               ]]
        ];*/


        $menu_info = DB::connection('mysql_shop')->table('wechat_custom_menu')->groupBy('first_menu_name')->select(['first_menu_name'])->orderBy('first_menu_name')->get()->toArray();
//        dd($menu_info);
        foreach($menu_info as $v){
            $menu_list = DB::connection('mysql_shop')->table('wechat_custom_menu')->where(['first_menu_name'=>$v->first_menu_name])->get()->toArray();
//            dd($menu_list);
            $sub_button = [];
            foreach($menu_list as $k=>$vo){
                if($vo->menu_type == 1){ //一级菜单
                    if($vo->event_type == 'view'){
                        $data['button'][] = [
                            'type'=>$vo->event_type,
                            'name'=>$vo->first_menu_name,
                            'url'=>$vo->menu_remark
                        ];
                    }else{
                        $data['button'][] = [
                            'type'=>$vo->event_type,
                            'name'=>$vo->first_menu_name,
                            'key'=>$vo->menu_remark
                        ];
                    }
                }
                if($vo->menu_type == 2){ //二级菜单
                    //echo "<pre>";print_r($vo);
                    if($vo->event_type == 'view'){
                        $sub_button[] = [
                            'type'=>$vo->event_type,
                            'name'=>$vo->second_menu_name,
                            'url'=>$vo->menu_remark
                        ];
                    }elseif($vo->event_type == 'media_id'){
                    }else{
                        $sub_button[] = [
                            'type'=>$vo->event_type,
                            'name'=>$vo->second_menu_name,
                            'key'=>$vo->menu_remark
                        ];
                    }
                }
            }
            //如果有二级菜单
            if(!empty($sub_button)){
                $data['button'][] = ['name'=>$v->first_menu_name,'sub_button'=>$sub_button];
            }
        }
//        unset($data);die;
        echo "<pre>";print_r($data);
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->wechat->get_access_token();

        $re = $this->wechat->post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        echo json_encode($data,JSON_UNESCAPED_UNICODE).'<br/>';
        echo "<pre>"; print_r(json_decode($re,1));



        //
        /*
         $data=menu_model::all()->toArray();
        // dd($data);

        $data=$this->createTreeBySon($data,0);
        // dd($data);
        $url='https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->wechat->get_access_token().'';
        // dd($url);
        $typeArr =[
            'click'=>'key',
            'view'=>'url'
        ];
        // dd($typeArr);
        $postdata=[];
        foreach($data as $k => $v){
            if(!empty($v['son'])){
                //有子菜单
                $postdata['button'][$k]['name']=$v['first_menu_name'];
                //二级菜单
                foreach($v['son'] as $key=> $value){
                    $postdata['button'][$k]['sub_button'][]=[
                        'type'=>$value['menu_type'],
                        'name'=>$value['first_menu_name'],
                        $typeArr[$value['menu_type']]=>$value['menu_key']
                    ];
                }
            }else{
                //没有子菜单
                $postdata['button'][]=[
                    'type'=>$v['menu_type'],
                    'name'=>$v['first_menu_name'],
                    $typeArr[$v['menu_type']]=>$v['menu_key'],
                ];
            }
        }

        $postdata=json_encode($postdata,JSON_UNESCAPED_UNICODE);
        // dd($postdata);
        $res=$this->wechat->post($url,$postdata);
        dd($res);
        */
    }


    /**
     * 完全删除菜单
     */
    public function del_menu(){
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.$this->wechat->get_access_token();
        $re = file_get_contents($url);
        dd(json_decode($re));
    }

    /**
     * 自定义菜单查询接口
     */
    public function display_menu()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$this->wechat->get_access_token();
        $re = file_get_contents($url);
        echo "<pre>";
        print_r(json_decode($re,1));
    }


}
