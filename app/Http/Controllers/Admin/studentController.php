<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class studentController extends Controller
{



    /**
     * !!这是简便的 但适用情况略差 应该再写一遍老师的那种!!
     */



    public function add()
    {
        $data=DB::table("class")->get()->toArray();
        return view("admin/student/add",['data'=>$data]);
    }
    public function do_add(Request $request)
    {
        $data=$request->except(['_token']);

        $data['create_time']=time();
//        dd($data);
        $validatedData = $request->validate([
            'student_name' => 'required|unique:student|max:3',
            'student_sex' => 'required',
            'student_class' => 'required',
        ],[
            'student_name.required'=>'学生姓名必填',
            'student_name.unique'=>'学生姓名已存在',
            'student_name.max'=>'学生姓名长度不能超过三个字符',
            'student_sex.required'=>'学生性别必选',
            'student_class.required'=>'学生班级必选',
        ]);

        $res=DB::table("student")->insert([
            'student_name'=>$data['student_name'],
            'student_sex'=>$data['student_sex'],
            'student_class'=>$data['student_class'],
            'create_time'=>$data['create_time'],
        ]);
        if ($res)
        {
            echo '添加成功';
            return redirect('admin/student/index');
        }else{
            echo "<script>alert('添加失败!');history.back();</script>";

        }

    }

    public function index(Request $request)
    {
        $redis= new \Redis();
        $redis->connect('127.0.0.1','6379');
        $redis->incr('num');
        $num1=$redis->get('num');
//        echo "访问次数为：".$num1."次";
        $student_name='';
        $student_name=$request->get('student_name');//接收到的学生姓名
//        dd($student_name);

        $student_class=$request->get('student_class');//接收到的班级信息

//        dd($student_class);
        $class_info=DB::table("class")->get()->toArray();//所有班级信息
//        dd($class_info);

        /**
         * 搜索方法1---优化版
         * 1、说明 先声明一个变量where 使其变为空数组
         * 2、
         */
        $where=[];
        if (!empty($student_name)) {
            $where[]=['student_name','like','%'.$student_name.'%'];
        }
        if (!empty($student_class)) {
            $where[]=['student_class',$student_class];
        }
        $data = DB::table('student')
                ->join('class', 'student.student_class', '=', 'class.class_id')
                ->where($where)
                ->select('student.*', 'class.*')
                ->orderBy('student_id','desc')
                ->paginate(2);

        /**
         * 搜索方法2---易理解版
         */
//        if(empty($student_name) && empty($student_class))
//        {
//            $data = DB::table('student')
//                ->join('class', 'student.student_class', '=', 'class.class_id')
//                ->select('student.*', 'class.*')
//                ->orderBy('student_id','desc')
//                ->paginate(2);
//        }elseif(!empty($student_name) && empty($student_class)){
//            $data = DB::table('student')
//                ->join('class', 'student.student_class', '=', 'class.class_id')
//                ->where('student_name','like','%'.$student_name.'%')
//                ->select('student.*', 'class.*')
//                ->orderBy('student_id','desc')
//                ->paginate(2);
//        }elseif(empty($student_name) && !empty($student_class)){
//            $data = DB::table('student')
//            ->join('class', 'student.student_class', '=', 'class.class_id')
//                ->where('student_class',$student_class)
//            ->select('student.*', 'class.*')
//            ->orderBy('student_id','desc')
//            ->paginate(2);
//        }else{
//            $data = DB::table('student')
//                ->join('class', 'student.student_class', '=', 'class.class_id')
//                ->where('student_name','like','%'.$student_name.'%')
//                ->where('student_class',$student_class)
//                ->select('student.*', 'class.*')
//                ->orderBy('student_id','desc')
//                ->paginate(2);
//
//
//        }

//        dd($data);
        return view("admin/student/index",['data'=>$data,'student_name'=>$student_name,'class_info'=>$class_info,'student_class'=>$student_class]);
    }
    public function edit($id)
    {
//        $data = DB::table('student')->where('student_id',$id)->get();
//        dd($data);
        $data = DB::table('class')
            ->join('student', 'class.class_id', '=', 'student.student_class')
            ->where('student_id','=',$id)
            ->select('student.*', 'class.*')
            ->get();
        $class_info=DB::table('class')->get();
//        dd($class_info);

        return view("admin/student/edit",['data1'=>$data,'class_info'=>$class_info]);
    }
    public function update(Request $request)
    {
        $data=$request->except(['_token']);
        $student_id=$request->get('student_id');
//        dd($student_id);
//        dd($data);
        $result=DB::table("student")->where('student_id',$student_id)->update([
            'student_name'=>$data['student_name'],
            'student_sex'=>$data['student_sex'],
            'student_class'=>$data['student_class'],
            'create_time'=>time(),
        ]);
        if($result)
        {
            echo "ok";
        }else{
            echo "no";
        }
    }
    public function delete($id)
    {
        $result=DB::table("student")->where('student_id',$id)->delete();
        if($result)
        {
//            echo "ok";
//            return redirect('admin/student/index');
            echo "<script>alert('删除成功!');history.back();</script>";
        }else{
//            echo "no";
//            return redirect('admin/student/index');
            echo "<script>alert('删除失败!');history.back();</script>";
        }
    }
}
