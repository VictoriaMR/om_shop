<?php

namespace app\Models;

use app\Models\Base as BaseModel;

class ProductCategory extends BaseModel
{
    //表名
    protected $table = 'product_category';

    //主键
    protected $primaryKey = 'cate_id';

    /**
     * @method 分类列表
     * @author Victoria
     * @date   2020-04-17
     * @return array
     */
    public function getCategoryList()
    {
        return $this->getList($this->table, [], [], [], [['sort', 'DESC']]);
    }
}