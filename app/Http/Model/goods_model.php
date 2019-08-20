<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class goods_model extends Model
{
    //
    protected $primaryKey = 'goods_id';
    protected $table = 'goods';
    const CREATED_AT = 'add_time';
    protected $connection = 'mysql_index';
//    protected $connection = 'mysql';//这是测试0715 等等变回去

}
