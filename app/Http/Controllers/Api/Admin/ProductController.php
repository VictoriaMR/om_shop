<?php

namespace app\Http\Controllers\Api\Admin;

use app\Http\Controllers\Api\ApiController;

use app\Services\ProductService;
use app\Services\ProductCategoryService;

class ProductController extends ApiController
{
    public function __construct()
    {
        $this->baseService = new ProductService();
    }

     /**
     * @method 保存分类
     * @author Victoria
     * @date   2020-04-18
     */
    public function saveCategory()
    {
        $cateId = $this->request('cate_id', 0); // 分类ID
        $parentId = $this->request('parent_id', 0); // 父ID
        $sort = $this->request('sort', 0); // 排序
        $name = $this->request('name', ''); // 名称

        if (empty($name)) 
            return $this->getResult(100000, [], ['message'=>'名称不能为空']);

        $data = [
            'name' => trim($name),
            'parent_id' => $parentId,
            'sort' => (int) $sort,
        ];

        $categoryService = new ProductCategoryService();

        if (empty($cateId))
            $result = $categoryService->addData($data);
        else
            $result = $categoryService->updateData($cateId, $data);

        if ($result)
            return $this->getResult(200, $result, ['message' => '操作成功']);
        else
            return $this->getResult(100000, $result, ['message' => '操作失败']);

    }

    /**
     * @method 删除分类
     * @author Victoria
     * @date   2020-04-18
     */
    public function delCategory()
    {
        $cateId = $this->request('cate_id', 0); // 分类ID

        if (empty($cateId))
            return $this->getResult(100000, [], ['message'=>'删除的分类不能为空']);

        $categoryService = new ProductCategoryService();
        $result = $categoryService->delCategory($cateId);

        if ($result)
            return $this->getResult(200, $result, ['message' => '删除成功']);
        else
            return $this->getResult(100000, $result, ['message' => '删除失败']);

    }

    /**
     * @method 保存商品
     * @author Victoria
     * @date   2020-04-25
     * @return json
     */
    public function save()
    {
        $proId = (int) $this->request('pro_id', 0); // 商品ID
        $proCate = (int) $this->request('cate_id', 0); // 分类ID
        $proName = $this->request('pro_name', ''); // 商品名称
        $proImg = $this->request('pro_img', '');  // 商品图片
        $oprice = $this->request('original_price', 0.00); // 原价
        $sprice = $this->request('sale_price', 0.00); // 售价
        $proStock = (int) $this->request('pro_stock', ''); // 库存
        $proParam = $this->request('param', ''); // 参数
        $status = (int) $this->request('status', 0); //状态
        $intro = $this->request('detail', ''); // 详情

        $tempParam = [];
        if (!empty($proParam)) {
            foreach ($proParam as $value) {
                if (empty($value)) continue;
                $arr = explode(':', $value);
                $tempParam[$arr[0]] = $arr[1] ?? '';
            }
        }

        $data = [
            'pro_name' => $proName,
            'status' => $status,
            'origin_price' => $oprice,
            'sale_price' => $sprice,
            'pro_stock' => $proStock,
            'pro_cate' => $proCate,
            'pro_imgs' => implode(',', array_filter($proImg)),
            'pro_param' => json_encode($tempParam, JSON_UNESCAPED_UNICODE),
            'pro_intro' => $intro,
        ];

        $result = $this->baseService->save($proId, $data);

        if ($result)
            return $this->getResult(200, $result, ['message' => '操作成功']);
        else
            return $this->getResult(100000, $result, ['message' => '操作失败']);
    }

    /**
     * @method 删除商品
     * @author Victoria
     * @date   2020-04-25
     * @return json
     */
    public function del()
    {
        $proId = $this->request('pro_id', ''); // 删除ID

        if (empty($proId)) return $this->getResult(100000, [], ['message'=>'删除的ID不能为空']);

        if (!$this->checkAdminRule()) {
            return $this->getResult(100000, [], ['message'=>'非管理员不能使用该功能']);
        }

        $result = $this->baseService->updateDataByFilter([['pro_id', $proId]], ['status'=>0, 'is_deleted'=>1, 'update_at'=>time()]);

        if ($result)
            return $this->getResult(200, [], ['message'=>'删除成功']);
        else
            return $this->getResult(100000, [], ['message'=>'删除失败']);
    }
}