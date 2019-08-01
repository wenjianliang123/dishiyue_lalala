<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class diercizhoukao_huoche_0722 extends Controller
{
    public function add()
    {
        return view("admin/diercizhoukao/add");
    }

    public function do_add(Request $request)
    {
        $data=$request->except(['_token']);
        $res=DB::table("erzhoukao")->insert([
            'checi'=>$data['checi'],
            'chufadi'=>$data['chufadi'],
            'daodadi'=>$data['daodadi'],
            'jiaqian'=>$data['jiaqian'],
            'zhangshu'=>$data['zhangshu'],
            'chufashijian'=>strtotime($data['chufashijian']),
            'daodashijian'=>strtotime($data['daodashijian']),
        ]);
        if ($res)
        {
            echo '添加成功';
            return redirect('admin/diercizhoukao/index');
        }else{
            echo "<script>alert('添加失败!');history.back();</script>";

        }
    }

    public function index(Request $request)
    {



        $chufadi = $request->get('chufadi');//接收到的出发地

        $daodadi = $request->get('daodadi');//接收到的到达地



        if(!empty($chufadi||$daodadi)){

            $where=[];
            if(!empty($chufadi)){
                $where[]=['chufadi','like','%'.$chufadi.'%'];
            }
            if(!empty($daodadi)){
                $where[]=['daodadi','like','%'.$daodadi.'%'];
            }
            $data=DB::table('erzhoukao')
                ->where($where)
                ->orderBy('huoche_id', 'desc')
                ->paginate(2);


            $redis = new \Redis();
            $redis->connect('127.0.0.1', '6379');
            $shuju=$redis->incr('check111');
            $huoche = $redis->get('check111');
            echo "搜索次数为".$huoche.'次';
            echo "<br/>"."<br/>";



        }else{
            $data = DB::table('erzhoukao')
                ->orderBy('huoche_id', 'desc')
                ->paginate(2);
        }

        //第六个问题有问题 应该每查一个显示一个次数不太会做
        //这个题最后一个将$data用set存起来
        //在取出来用json_encode和json_decode
        //做分页的话非常复杂




        return view('admin/diercizhoukao/index',['data'=>$data,'chufadi'=>$chufadi,'daodadi'=>$daodadi]);
    }

}
