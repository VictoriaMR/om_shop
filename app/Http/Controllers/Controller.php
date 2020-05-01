<?php

namespace App\Http\Controllers;

class Controller 
{
	/**
	 * @method request  获取请求的参数
	 * @author Victoria
	 * @date   2020-04-08
	 */
    public function request($name = '', $rename = '')
    {
    	$params = \Mvc::$URL_PARAMS ?? [];

        if (!empty($_FILES)) {
            $params = array_merge($params, $_FILES);
        }

    	if (!empty($name)) {
            return $params[$name] ?? $rename;
        } else {
            return $params;
        }
    }

    /**
     * @method 校验权限
     * @author Victoria
     * @date   2020-04-14
     * @return boolean
     */
    protected function checkAdminRule()
    {
        if (empty($_SESSION['admin_user'])) return false;

        if (empty($_SESSION['admin_user']['is_super'])) return false;

        return true;
    }
}
