<?php

namespace App\Http\Controllers\Home;

use App\Services\MemberService;

/**
 * 默认入口控制器
 */

class IndexController
{
	public function index()
	{
		return view(['index'=>1]);
	}
}