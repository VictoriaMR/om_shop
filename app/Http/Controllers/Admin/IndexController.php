<?php

namespace app\Http\Controllers\Admin;

use app\Http\Controllers\Controller;

class IndexController extends Controller
{
	public function index()
	{
		return view();
	}

	public function overview()
	{
		$data = $this->getSystemInfo();

		//商品总数
        $data = [];
        $data[] = ['is_deleted', 0];    
		$productService = new \app\Services\ProductService();
		$data['product_count'] = $productService->getDataCount($data);

		//人员总数
		$memberService = new \app\Services\MemberService();
		$data['member_count'] = $memberService->getDataCount();

		return view($data);
	}

	/**
     * 用户登陆页面
     * @author   Mingrong
     * @DateTime 2020-01-07
     */
    public function login($request)
    {
        $data = [
            'title' => '管理员登陆',
        ];
    	return view($data);
    }

    /**
     * @method 获取系统信息
     * @author Victoria
     * @date   2020-01-12
     * @return array
     */
    protected function getSystemInfo()
    {
        $returnData = [];
        $returnData['system_os'] = php_uname('s').php_uname('r');//获取系统类型
        $returnData['server_software'] = $_SERVER["SERVER_SOFTWARE"];//服务器版本
        $returnData['php_version'] = PHP_VERSION; //PHP版本
        $returnData['server_addr'] = $_SERVER['SERVER_ADDR'] ?? '0.0.0.0'; //服务器IP地址
        $returnData['server_name'] = $_SERVER['SERVER_NAME']; //服务器域名
        $returnData['server_port'] = $_SERVER['SERVER_PORT']; //服务器端口

        $returnData['php_sapi_name'] = php_sapi_name(); //PHP运行方式
        $returnData['mysql_version'] = \app\Helper\Helper::mysqlVersion(); //mysql 版本
        $returnData['max_execution_time'] = get_cfg_var('max_execution_time') . 's'; //最大执行时间
        $returnData['upload_max_filesize'] = get_cfg_var('upload_max_filesize'); //最大上传限制
        $returnData['memory_limit'] = get_cfg_var('memory_limit'); //最大内存限制
        $returnData['app_version'] = getenv('APP_VERSION'); //系统版本
        $returnData['processor_identifier'] = $_SERVER['PROCESSOR_IDENTIFIER'] ?? ''; //服务器cpu 数
        $returnData['disk_used_rate'] = sprintf('%.2f', 1 - disk_free_space('/') / disk_total_space('/')) * 100 . '%'; //磁盘使用情况
        $returnData['disk_free_space'] = sprintf('%.2f', disk_free_space('/') / 1024 / 1024) . 'M'; 

        return $returnData;
    }
}