<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class cart_model extends Model
{
    //
    protected $primaryKey = 'cart_id';
    protected $table = 'cart';
    const CREATED_AT = 'add_time';
    protected $connection = 'mysql_index';
}
