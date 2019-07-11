<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class indexController extends Controller
{
    public function index()
    {
        return view("index/index");
    }
}
