<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class jingcaiController extends Controller
{
    public function add()
    {
        return view('admin/jingcai.add');
    }

    public function doadd(Request $request)
    {
        $data=$request->all();
        //dd($data);
        $res=DB::table('guess')->insert($data);
        if($res){
            //return redirect('admin/jingcai/index');
            echo json_encode(['msg'=>1]);
        }else{
            echo json_encode(['msg'=>2]);
        }
    }

    public function index()
    {
        $data=DB::table('guess')->get();
        return view('admin/jingcai.index', ['re' => $data]);
    }

    public function guess()
    {
        $id=$_GET['id'];
        $qw=DB::table('quiz')->where('guess',$id)->first();
        if($qw){
            return view('admin/jingcai.error');
        }else{
            $user = DB::table('guess')->where('id',$id)->first();
            return view('admin/jingcai.guess', ['re' => $user]);
        }


    }

    public function doguess(Request $request)
    {
        $data=$request->all();
        //dd($data);
        $date['guess']=$data['guess'];
        $date['quiz']=$data['quiz'];
        $res=DB::table('quiz')->insert($date);
        if($res){
            return redirect('kaoshi/index');
        }else{
            return redirect('kaoshi/guess');
        }
    }

    public function goguess()
    {
        $id=$_GET['id'];
        $res = DB::table('guess')->where('id', $id)->value('result');
        if($res){
            return view('admin/jingcai.errors');
        }else{
            $user = DB::table('guess')->where('id',$id)->first();
            return view('admin/jingcai.goguess', ['re' => $user]);
        }

    }

    public function result(Request $request)
    {
        $data=$request->all();
        //dd($data);
        $date['result']=$data['result'];
        $date['id']=$data['id'];
        $res=DB::table('guess')->where('id', $date['id'])->update(['result' => $date['result']]);
        if($res){
            return redirect('admin/jingcai/index');
        }else{
            return redirect('admin/jingcai/goguess');
        }
    }

    public function results()
    {
        $id=$_GET['id'];
        $re=DB::table('guess')->join('quiz', 'guess.id', '=', 'quiz.guess')->where('id',$id)->first();
        if($re){
            return view('admin/jingcai.loindex', ['user' => $re]);
        }else{
            return view('admin/jingcai.errorss');
        }

    }


}
