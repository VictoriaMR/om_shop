<?php

namespace app\Models;

use app\Models\Base as BaseModel;

class ProductData extends BaseModel
{
    //表名
    protected $table = 'product_data';

    //主键
    protected $primaryKey = 'pro_id';
}