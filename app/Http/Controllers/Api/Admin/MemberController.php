<?php

namespace app\Http\Controllers\Api\Admin;

use app\Http\Controllers\Api\ApiController;

use app\Services\AdminMemberService;

class MemberController extends ApiController
{
    public function __construct()
    {
        $this->baseService = new AdminMemberService();
    }

	/**
     * 用户登陆验证
     * @author   Mingrong
     * @DateTime 2020-01-07
     */
    public function loginIn()
    {
    	$name = $this->request('name') ?? ''; //名称
    	$password = $this->request('password') ?? ''; //密码

    	if (empty($name))
    		return $this->getResult(100000, false, ['message' => '登陆失败, 用户名不能为空']);

    	if (empty($password))
    		return $this->getResult(100000, false, ['message' => '登陆失败, 密码不能为空']);

    	$memberService = new \app\Services\AdminMemberService();

    	if (!$memberService->isExistUserByName($name)) {
    		return $this->getResult(100000, false, ['message' => '登陆失败, 用户不存在']);
    	}

    	$result = $memberService->login($name, $password, false, true);

    	if ($result){
            
            //更新登陆时间
            $memberService->updateData($_SESSION['admin_user']['mem_id'], ['login_at'=>time()]);

    		return $this->getResult(200, $result, ['message' => '登陆成功']);
        }
    	else {
    		return $this->getResult(100000, $result, ['message' => '登陆失败']);
    	}
    }

    /**
     * @method 用户登出
     * @author Victoria
     * @date   2020-04-15
     */
    public function loginOut()
    {
    	$result = $this->baseService->loginOut(true);

    	if ($result){
    		return $this->getResult(200, $result, ['message' => '登出成功']);
        }
    	else {
    		return $this->getResult(100000, $result, ['message' => '登出失败']);
    	}
    }

    /**
     * @method 停用功能
     * @author Victoria
     * @date   2020-04-14
     * @return json
     */
    public function updateStatus()
    {
        $memId = $this->request('mem_id', ''); // 删除ID
        $status = $this->request('status', 0); // 状态

        if (empty($memId)) return $this->getResult(100000, [], ['message'=>'操作的ID不能为空']);

        if (!$this->checkAdminRule()) {
            return $this->getResult(100000, [], ['message'=>'非管理员不能使用该功能']);
        }

        $result = $this->baseService->updateDataByFilter([['mem_id', 'in', explode(',', $memId)], 'is_super' => 0], ['status' => $status]);

        if ($result)
            return $this->getResult(200, [], ['message'=>'更新成功']);
        else
            return $this->getResult(100000, [], ['message'=>'更新失败']);
    }

    /**
     * @method 删除功能
     * @author Victoria
     * @date   2020-04-15
     * @return [type]     [description]
     */
    public function delete()
    {
        $memId = $this->request('mem_id', ''); // 删除ID

        if (empty($memId)) return $this->getResult(100000, [], ['message'=>'删除的ID不能为空']);

        if (!$this->checkAdminRule()) {
            return $this->getResult(100000, [], ['message'=>'非管理员不能使用该功能']);
        }

        $result = $this->baseService->updateDataByFilter([['mem_id', 'in', explode(',', $memId)], 'is_super' => 0], ['status'=>$status, 'is_deleted'=>1]);

        if ($result)
            return $this->getResult(200, [], ['message'=>'删除成功']);
        else
            return $this->getResult(100000, [], ['message'=>'删除失败']);
    }

    /**
     * @method 创建用户
     * @author Victoria
     * @date   2020-01-12
     */
    public function addMember()
    {
        $name = $this->request('name', ''); //姓名
        $nickname = $this->request('nickname', ''); //昵称
        $mobile = $this->request('mobile', ''); //手机号码
        $isSuper = $this->request('is_super', 0); //是否添加超级管理员
        $password = $this->request('password', ''); //密码
        $repass = $this->request('repass', ''); //检验密码

        $isSuper = $isSuper === 'on' ? 1 : 0;

        if (!$this->checkAdminRule()) 
            return $this->getResult(100000, [], ['message'=>'非管理员不能使用该功能']);

        if (empty($name))
            return $this->getResult(100000, false, ['message' => '姓名不能为空']);

        if (empty($mobile))
            return $this->getResult(100000, false, ['message' => '手机号码不能为空']);

        if (empty($password))
            return $this->getResult(100000, false, ['message' => '密码不能为空']);

        if ($password != $repass)
            return $this->getResult(100000, false, ['message' => '两次输入密码不一致']);

        if ($this->baseService->isExistUserByName($name)) 
            return $this->getResult(100000, false, ['message' => '用户名已存在']);
        
        if ($this->baseService->isExistUserByMobile($mobile)) 
            return $this->getResult(100000, false, ['message' => '手机号已存在']);

        $data = [
            'name' => $name,
            'nickname' => $nickname,
            'mobile' => $mobile,
            'is_super' => $isSuper,
            'password' => $password,
        ];

        $result = $this->baseService->create($data);

        if ($result)
            return $this->getResult(200, $result, ['message' => '新增成功']);
        else
            return $this->getResult(100000, $result, ['message' => '新增失败']);
    }

    /**
     * @method 编辑用户
     * @author Victoria
     * @date   2020-01-12
     */
    public function editMember()
    {
        $memId = $this->request('mem_id', 0); //目标ID
        $name = $this->request('name', ''); //姓名
        $nickname = $this->request('nickname', ''); //昵称
        $mobile = $this->request('mobile', ''); //手机号码
        $isSuper = $this->request('is_super', 0); //是否添加超级管理员
        $password = $this->request('password', ''); //密码
        $repass = $this->request('repass', ''); //检验密码

        $isSuper = $isSuper === 'on' ? 1 : 0;

        if (!$this->checkAdminRule()) 
            return $this->getResult(100000, [], ['message'=>'非管理员不能使用该功能']);

        if (empty($memId))
            return $this->getResult(100000, false, ['message' => '修改的用户不能为空']);

        if (empty($name))
            return $this->getResult(100000, false, ['message' => '姓名不能为空']);

        if (empty($mobile))
            return $this->getResult(100000, false, ['message' => '手机号码不能为空']);

        if (empty($password))
            return $this->getResult(100000, false, ['message' => '密码不能为空']);

        if ($password != $repass)
            return $this->getResult(100000, false, ['message' => '两次输入密码不一致']);

        $info = $this->baseService->loadData($memId);

        if (empty($info))
            return $this->getResult(100000, false, ['message' => '修改的用户不存在']);

        if ($name != $info['name']) {
            if ($this->baseService->isExistUserByName($name)) 
                return $this->getResult(100000, false, ['message' => '用户名已存在']);
        }

        if ($mobile != $info['mobile']) {
            if ($this->baseService->isExistUserByMobile($mobile)) 
                return $this->getResult(100000, false, ['message' => '手机号已存在']);
        }

        $data = [
            'name' => $name,
            'nickname' => $nickname,
            'mobile' => $mobile,
            'is_super' => $isSuper,
            'password' => $password,
            'salt' => $info['salt'],
            'status' => $info['status'],
        ];

        $result = $this->baseService->updateData($memId, $data);

        if ($result)
            return $this->getResult(200, $result, ['message' => '修改成功']);
        else
            return $this->getResult(100000, $result, ['message' => '修改失败']);
    }

    public function searchMember()
    {
        $page = $this->request('page', 1);
        $pagesize = $this->request('pagesize', 5);//获取条数
        $keyword = $this->request('keywords', '');//关键字

        $data = [];
        $data[] = [['name', 'like', "%{$keyword}%"], ['mobile', 'like', "%{$keyword}%"]];
        $field = ['mem_id', 'name', 'mobile'];
        $memberService = new \app\Services\MemberService;
        $list = $memberService->getDataList($data, $field, ['page'=>$page, 'pagesize'=>$pagesize]);

        echo json_encode(['data'=> $list, 'code'=>200]);
        exit();

        return $this->getResult(200, is_array($list) ? $list : [], ['message' => '获取成功']);
    }
}