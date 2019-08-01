<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class jiekouController extends Controller
{
    public function index()
    {
        $path = "http://api.map.baidu.com/geocoder/v2/?address=北京市昌平区沙河地铁站&output=json&ak=CxF13N48UHZ12G8sIVpa2YTG";
        $result=file_get_contents($path);

//

        var_dump($result);
    }
}
