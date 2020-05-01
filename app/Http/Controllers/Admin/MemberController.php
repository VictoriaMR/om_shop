<?php

namespace app\Http\Controllers\Admin;

use app\Http\Controllers\Controller;

use app\Services\AdminMemberService;

class MemberController extends Controller
{
	public function __construct()
    {
        $this->baseService = new AdminMemberService();
    }

	/**
	 * @method 获取列表
	 * @author Victoria
	 * @date   2020-01-12
	 */
	public function memberList()
	{
		$name = $this->request('name', ''); // 名称
		$mobile = $this->request('mobile', ''); // 手机
		$page = $this->request('page', 1); // 当前页
		$pagesize = $this->request('pagesize', 20); // 每页数量

		$data = [];

		$data[] = ['is_deleted' , 0];

		if (!empty($name)) {
			$data[] = ['name', 'like', '%'.$name.'%'];
		}

		if (!empty($mobile)) {
			$data[] = ['mobile', 'like', '%'.$mobile.'%'];
		}

		$list = $this->baseService->getList($data, $page, $pagesize);
		$list = array_merge($list, $this->request());

		$list['info'] = $_SESSION['admin_user'];

		return \Mvc::view($list);
	}

	/**
	 * @method 新增用户页面
	 * @author Victoria
	 * @date   2020-01-12
	 */
	public function add()
	{
		if (!$this->checkAdminRule()) {
            return $this->getResult(100000, [], ['message'=>'非管理员不能使用该功能']);
        }

        $memId = $this->request('mem_id', 0);

        $info = $this->baseService->loadData($memId);

		return \Mvc::view($info);
	}
}