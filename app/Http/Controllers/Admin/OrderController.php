<?php

namespace app\Http\Controllers\Admin;

use app\Http\Controllers\Controller;

use app\Services\OrderService;

class OrderController extends Controller
{
	public function __construct()
    {
        $this->baseService = new OrderService();
    }

    /**
     * @method 商品列表页面
     * @author Victoria
     * @date   2020-04-18
     */
    public function orderList()
    {
        $page = $this->request('page', 1);
        $pagesize = $this->request('pagesize', 20);

        $ordNo = $this->request('ord_no', ''); //订单编号
        $payment = $this->request('payment', null); //支付方式
        $status = $this->request('status', null); //状态
        $startTime = $this->request('start', ''); //创建时间开始
        $endTime = $this->request('end', ''); //创建时间结束

        $data = [];
        $data[] = ['is_deleted', 0];

        if ($status != null) 
            $data[] = ['status', (int) $status];

        if ($payment != null) 
            $data[] = ['payment', (int) $payment];
        
        if (!empty($ordNo))
            $data[] = ['ord_no', 'like', $ordNo.'%'];

        if (!empty($startTime))
            $data[] = ['create_at', '>=', strtotime($startTime)];

        if (!empty($endTime))
            $data[] = ['create_at', '<=', strtotime($endTime)];

        $list = $this->baseService->getList($data, $page, $pagesize);

    	return \Mvc::view(array_merge($list, $this->request()));
    }

    public function addPage()
    {
    	return \Mvc::view();
    }
}