<?php
namespace app\Http\Controllers\Home;
use app\Http\Controllers\Controller;
use app\Services\ProductService;
use app\Services\MemberService;

class ProductController extends Controller
{
	public function __construct()
    {
        $this->baseService = new ProductService();
    }

	public function info()
	{
		$res = $this->baseService->loadData(1);
		
		print_r($res);

		$memberService = new MemberService();

		$res = $memberService->loadData(1);

		print_r($res);

		// dd(Mvc::$cfg);
		// DB::get();
	}
}