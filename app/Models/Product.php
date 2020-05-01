<?php

namespace app\Models;

use app\Models\Base as BaseModel;

class Product extends BaseModel
{
    //表名
    protected $table = 'product';

    //主键
    protected $primaryKey = 'pro_id';

    public function getProductCount()
    {
    	$result = $this->getOne($this->table, [], ['COUNT(*) count']);

    	return $result['count'] ?? 0;
    }
}