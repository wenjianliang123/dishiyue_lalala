<?php

namespace App\http\Model;

use Illuminate\Database\Eloquent\Model;

class menu_model extends Model
{
    protected $table = 'wechat_custom_menu';
    protected $pk = 'menu_id';
    public $timestamps = false;
    protected $connection = 'mysql_shop';
}
