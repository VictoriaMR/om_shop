<?php

namespace app\Models;

use app\Models\Base as BaseModel;

class Order extends BaseModel
{
    //表名
    protected $table = 'market_order';

    //主键
    protected $primaryKey = 'ord_id';
}