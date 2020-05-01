<?php

namespace app\Http\Controllers\Admin;

use app\Http\Controllers\Controller;

use app\Services\ProductService;

use app\Services\ProductCategoryService;

class ProductController extends Controller
{
	public function __construct()
    {
        $this->baseService = new ProductService();
    }

    /**
     * @method 商品分类
     * @author Victoria
     * @date   2020-04-17
     */
    public function category()
    {
    	$page = $this->request('page', 1); // 当前页
		$pagesize = $this->request('pagesize', 20); // 每页数量

		$categoryService = new ProductCategoryService();
		$list = $categoryService->getCategoryList();

		return \Mvc::view(['list' => $list]);
    }

    /**
     * @method 编辑分类
     * @author Victoria
     * @date   2020-04-18
     */
    public function categoryEdit()
    {
    	$cateId = $this->request('cate_id', 0); // 分类ID
    	$parentId = $this->request('parent_id', 0); // 父ID

    	$categoryService = new ProductCategoryService();
    	$info = $categoryService->loadData($cateId);
    	$info['parent_id'] = $parentId;
    	return \Mvc::view($info);
    }

    /**
     * @method 商品列表页面
     * @author Victoria
     * @date   2020-04-18
     */
    public function productList()
    {
        $page = $this->request('page', 1);
        $pagesize = $this->request('pagesize', 20);
        $proNo = $this->request('pro_no', ''); //商品编号
        $name = $this->request('keyword', ''); //商品名称
        $status = $this->request('status', null); //状态
        $startTime = $this->request('start', ''); //创建时间开始
        $endTime = $this->request('end', ''); //创建时间结束

        $data = [];
        $data[] = ['is_deleted', 0];

        if ($status != null) {
            $data[] = ['status', (int) $status];
        }
        if (!empty($proNo))
            $data[] = ['pro_no', 'like', $proNo.'%'];

        if (!empty($name)) 
            $data[] = ['name', 'like', "%{$name}%"];

        if (!empty($startTime))
            $data[] = ['create_at', '>=', strtotime($startTime)];

        if (!empty($endTime))
            $data[] = ['create_at', '<=', strtotime($endTime)];

        $list = $this->baseService->getList($data, $page, $pagesize);

    	return \Mvc::view(array_merge($list, $this->request()));
    }

    /**
     * @method 商品详情页面
     * @author Victoria
     * @date   2020-04-22
     */
    public function productPage()
    {
        $proId = $this->request('pro_id', 0); //商品编号
        $categoryService = new ProductCategoryService();
        $cateList = $categoryService->getCategoryList();

        $info = $this->baseService->getInfo($proId);

        return \Mvc::view(['cate_list'=>$cateList, 'info' => $info]);
    }
}