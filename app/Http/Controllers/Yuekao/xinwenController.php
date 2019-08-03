<?php

namespace App\Http\Controllers\Yuekao;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;


class xinwenController extends Controller
{
    public function login()
    {
        return view('yuekao/login');
    }
    public function do_login(Request $request)
    {
        $data=$request->all();
//        dd($data);
        if(empty($data['user_name'])){
            echo "<script>alert('用户名不能为空');history.back(0)</script>";die;
        }
        if(empty($data['user_pwd'])){
            echo "<script>alert('密码不能为空');history.back(0)</script>";die;
        }
        $login=DB::connection('mysql_shop')->table('qiehuan_user')->where('user_name',$data['user_name'])->first();
        if(empty($login)){
            echo "<script>alert('用户名不存在');history.back(0)</script>";die;
        }
        if($data['user_pwd']!=$login->user_pwd){
            echo "<script>alert('密码错误');history.back(0)</script>";die;
        }
        session(['user_info'=>$data]);
        return redirect('yuekao/xinwen/index');
    }
    public function xinwen_add()
    {
        return view('yuekao/xinwen_add');
    }
    public function xinwen_do_add(Request $request)
    {
        $data=$request->all();
//        dd($data);
        if($request->file('xinwen_picture')=='')
        {
            $res=DB::table('yuekao_xinwen')->insert([
                'xinwen_name'=>$data['xinwen_name'],
                'xinwen_zuozhe'=>$data['xinwen_zuozhe'],
                'xinwen_detail'=>$data['xinwen_zuozhe'],
                'add_time'=>$data['add_time'],
                'user_name'=>session('user_info')['user_name'],
            ]);
            if ($res){
                return redirect('/yuekao/xinwen/index');
            }
        }else{
            $path=$request->file('xinwen_picture')->store('/xinwen_picture');
            $path_url='storage/'.$path;
            $data['xinwen_picture']=$path_url;

//        dd($data);
            $res=DB::table('yuekao_xinwen')->insert([
                'xinwen_name'=>$data['xinwen_name'],
                'xinwen_zuozhe'=>$data['xinwen_zuozhe'],
                'xinwen_detail'=>$data['xinwen_detail'],
                'xinwen_picture'=>$data['xinwen_picture'],
                'add_time'=>time(),
                'user_name'=>session('user_info')['user_name'],
            ]);
            if ($res){
                return redirect('yuekao/xinwen/index');
            }
        }
    }
    public function index()
    {
        $data=DB::table('yuekao_xinwen')->orderBy('xinwen_id','desc')->paginate(2);
//        dd($data);
        return view('yuekao/xinwen_index',['data'=>$data]);
    }
    public function del($id)
    {
        $user_name=Session('user_info')['user_name'];

        $db_info=DB::table('yuekao_xinwen')->where('xinwen_id',$id)->select('add_time','user_name')->first();

        if($user_name!=$db_info->user_name){
            echo "<script>alert('您只能删除自己添加的新闻');history.back(0)</script>";die;
        }else{
            if(time()-$db_info->add_time>1800){
                echo "删除时间已超时,不能删除，您只能删除半小时内的数据";die;
            }else{
                $res=DB::table('yuekao_xinwen')->where('xinwen_id',$id)->delete();
            }


        }

        if($res)
        {
            return redirect('yuekao/xinwen/index');
        }else{
            echo "<script>alert('删除失败');history.back(0)</script>";die;
        }

    }
    public function xinwen_detail($id)
    {
        $redis= new \Redis();
        $redis->connect('127.0.0.1','6379');
        //访问量
        $key1=$id.'1';
        $redis->incr($key1);
        $xinwen=$redis->get($key1);

        //缓存优化
        $key = $id;
        if($redis->exists($key)){
            $redis_info = $redis->get($key);
            $info = json_decode($redis_info,1);
            echo "这是从缓存中拿的";
        }else{
            $info = DB::table('yuekao_xinwen')->where('xinwen_id',$id)->get()->toarray();
            $info = json_decode(json_encode($info),1);
            $redis->set($key,json_encode($info),180);
        }

        return view('/yuekao/xinwen_detail',['data'=>$info,'fangwen'=>$xinwen]);
    }


    //测试八月——第一个接口 用的postman辅助判断自定义的access_token
    public function ceshijiekou(Request $request)
    {
        if(empty($request->all()['access_token'])||$request->all()['access_token']!='ACCESS_TOKEN')
        {
            return json_encode(['errno'=>'40014']);
        }
        $info =DB::table('yuekao_xinwen')->get()->toArray();
        $info=json_decode(json_encode($info),1);
        echo json_encode($info);


    }

    //测试八月——获取用户列表
    public function get_user_list()
    {
        //$this-》get_access_token方法获取access_token next_openid可以为空  用户不多时可以为空
        $access_token='';
        $access_token=$this->get_access_token();
//        dd($access_token);
        //file_get_contents是为了实现模拟get请求方式 参数用{$变量名输出}
        $user_list=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/get?access_token={$access_token}&next_openid=");
//        ！！！需要注意的是 access_token 要将其变为一定时间内不会改变的值 ！！！否则会报40001的错
//                    说你的access_token错误
        $user_list=json_decode($user_list,1);
        //查看时 json_decode 解析一下
        return $user_list;


    }

    //测试八月——单独将access_token封装起来 成为一个方法
    //！！！需要注意的是 access_token 要将其变为一定时间内不会改变的值 ！！！否则会报40001的错
    public function get_access_token()
    {
        $access_token_key='wechat_access_token';
        $redis=new \Redis();
        $redis->connect('127.0.0.1','6379');

        //在方法中判断key
        if($redis->exists($access_token_key))
        {
            //从缓存中拿access_token
            $access_token=$redis->get($access_token_key);
//            echo '这是从缓存中拿到的access_token';
//            dd($access_token);
        }else{
            //如果没有 调用接口拿access_token 并存入redis
            $access_token_info=file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".env('WECHAT_APPID')."&secret=".env('WECHAT_APPSECRET')."");
            $access_token_info=json_decode($access_token_info,1);
//            dd($access_token_info);
            //数组的操作需要json_decode($data,1)变为关联数组
            $access_token=$access_token_info['access_token'];
            $expires_in=$access_token_info['expires_in'];
            $redis->set($access_token_key,$access_token,$expires_in);
        }
        //最终返回一个access_token
        return $access_token;
    }

    //测试八月——获取用户信息
    public function get_user_info()
    {
        //从封装的地方拿access_token
        $access_token=$this->get_access_token();
        //用户的openid是从用户列表中拿到的
        $open_id='oMbARt3Rgq6oiDPsVLwAgmLxJ7sc';
        //根据用户openid和access_token调用接口查询
        $user_info=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$open_id}&lang=zh_CN");
        $user_info=json_decode($user_info,1);
        dd($user_info);
    }
    //以上都需去官网中查看文档的接口将参数放进去然后打印查看数据

    //测试八月——循环入库
    public function user_list_do_add()
    {
        $access_token=$this->get_access_token();
        $user_list_info=$this->get_user_list();
        $open_id=$user_list_info['data']['openid'];
//        $open_id=implode(',',$open_id);
//        $open_id=implode(',',$open_id);
//        dd($open_id);

//        $user_info='';
        $subscribe='';
        $arr = [];
        foreach ($open_id as $v)
        {
            $user_info=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$v}&lang=zh_CN");
            $user_info=json_decode($user_info,1);
            $arr[] = [
                'open_id' => $v,
                'subscribe' => $user_info['subscribe'],
                'add_time'=>time(),
            ];
        }
        $res=DB::connection('mysql_shop')->table('wechat_openid')->insert($arr);
        if($res)
        {
            return redirect('wechat/user_list_zhanshi');
        }else{
            echo "no";die;
        }

    }

    public function user_list_zhanshi()
    {
        $user_list_info=DB::connection('mysql_shop')->table('wechat_openid')->get()->toArray();
        return view('dibayue/user_list',['data'=>$user_list_info]);
    }
    public function user_detail($id)
    {
        $info=DB::connection('mysql_shop')->table('wechat_openid')->where('id',$id)->get()->toArray();
        $info=array_map('get_object_vars',$info);
        $info=json_decode(json_encode($info),1);
//        $info=json_decode(json_encode($info));
//        dd($info);
        $open_id=$info[0]['open_id'];
//        dd($open_id);
        $access_token=$this->get_access_token();
        $user_detail=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$open_id}&lang=zh_CN");
//        dd($access_token);
        $user_detail=json_decode($user_detail,1);
//        dd($user_detail);
        return view('dibayue/user_detail',['data'=>$user_detail,'id'=>$id]);
    }



}
