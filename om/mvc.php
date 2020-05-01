<?php
/**
 * MVC 框架文件
 */
final class mvc 
{
	/**
     * The Om framework version.
     *
     * @var string
     */
    const VERSION = '1.1.0';

	/* 配置 */
	public static $cfg = null;
	/* 加载类名 */
	public static $URL_CLASS = null;
	/* 加载类路径 */
	public static $URL_CLASS_PATH = null;
	/* 加载类方法 */
	public static $URL_METHOD     = null;
    /* 加载传参 */
    public static $URL_PARAMS     = null;
    /* 实际执行路径 */
    public static $PATH_INFO       = '';

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version()
    {
        return static::VERSION;
    }

	/**
	 * 框架初始化执行方法
	 */
	public static function execute($config = []) 
	{
		if(getenv('APP_DEBUG')){
			$GLOBALS['load_files'] = [];
		}

		/* 初始化类地图 */
		self::init();
		
		//分析网址
		$Info = self::analyze_url();

		self::$URL_CLASS = $Info['Class'];
		self::$URL_CLASS_PATH = $Info['ClassPath'];
		self::$URL_METHOD = $Info['Func'];
		self::$PATH_INFO = $Info['Class'].'/'.$Info['ClassPath'];
		self::$URL_PARAMS = $Info['Params'];

		if (getenv('APP_REQUEST_MIDDLE')) {
			//校验中间组件
			// self::middleware($Info);
		}

		$class = 'App\\Http\\Controllers\\'.$Info['Class'].'\\'.$Info['ClassPath'].'Controller';

		//调用方法
		call_user_func_array([self::autoload($class), $Info['Func']], $Info['Params']);

		//显示运行花费时间
		if (getenv('APP_DEBUG') && getenv('APP_RUNTIME')) {
			
            $msg = '<br/>总运行花费时间：'. (microtime(true) - OM_START_TIME) . ' 秒';
            $msg.= '<br/>实际执行路径：'.Mvc::$PATH_INFO;
            $msg.= '<br/>实际执行方法：'.Mvc::$URL_METHOD;
            $msg.= '<pre >参数：<br/><div style="margin-left:20px;">';
            $msg.= self::var_export_arr(Mvc::$URL_PARAMS);
            $msg.= '</pre>';

            $msg.= '<pre >引入的文件：<br/><div style="margin-left:20px;">';
            $msg.= implode(PHP_EOL, $GLOBALS['load_files']);
            $msg.= '</div></pre>';
			mvc_echo($msg, true);
		}
	}

	private static function var_export_arr($arr)
	{
		if (!is_array($arr)) return '';
		$str = '';
		foreach ($arr as $key => $value) {
			$str .= $key . ' => ' . $value . PHP_EOL;
		}
		return $str;
	}

	/**
	 * 中间组件
	 */
	private static function middleware($Info)
	{
		// $Kernel = new \app\Http\Kernel();
		$middlewareArr = app\Http\Kernel::$routeMiddleware ?? [];

		//一些中间组件
		if (!empty($middlewareArr)) {
			if (in_array($Info['Class'], array_keys($middlewareArr))) {
				$tempObj = new $middlewareArr[$Info['Class']]();
				$tempObj->handle($Info);
			}
		}
		return true;
	}
	
	//初始化
	private static function init() 
	{
		// 自动装载 # 注册__autoload()函数
		spl_autoload_register([__CLASS__, 'autoload']);
	}

	//自动加载
	public static function autoload($class)
	{
		$container = new Container();

		if (isset($GLOBALS['autoload'][$class])) {
			$class = $GLOBALS['autoload'][$class];
			echo $class;
		} 	

		$classfile = ROOT_PATH . str_replace(['\\', 'App/'], ['/', 'app/'], $class) . '.php';

		if (is_file($classfile)){
			if(getenv('APP_DEBUG')){
				$GLOBALS['load_files'][] = str_replace(ROOT_PATH, '', $classfile);
			}
			require_once $classfile;
		} else {
			throw new Exception( $classfile .' 不存在!');
		}

		$concrete = $container->autoload($class);

		return $concrete;
	}

    /**
     * @method 解析网址 解析成路由和参数
     * @author Victoria
     * @date   2020-04-08
     */
    public static function analyze_url()
	{
		/**
		 * URL
		 * 1、part/show?name=abc 
		 * 2、path/part/show/abc/?name=abc
		 * 3、en/path/part/show/abc/?name=abc
		 */
        self::$PATH_INFO = trim(str_replace('.html', '', $_SERVER['PATH_INFO'] ?? ''), '/');

		/* 对Url网址进行拆分 */
		$pathInfoArr = explode( '/', self::$PATH_INFO );

		/* 进行网址解析 */
		if (!empty(self::$cfg['route'])) {
			if (!in_array($pathInfoArr[0] ?? [], self::$cfg['route'])) {
				//压入默认站点到路由数组
				array_unshift($pathInfoArr, self::$cfg['route'][0]);
			}
		}

		/* 去除路由中间空格 */
		$pathInfoArr = array_map('trim', $pathInfoArr);

        /* 类名 */
        $Class 	   = array_shift($pathInfoArr);
        /* 方法名 */
        $Func 	   = array_pop($pathInfoArr);
        /* 中间路径 */
        $ClassPath = implode('/', $pathInfoArr);

        $Params = self::analyze_url_params();

        $MvcArr = [
			'Class'     => !empty($Class) ? $Class : 'home',
			'ClassPath' => !empty($ClassPath) ? $ClassPath : 'index',
			'Func'      => !empty($Func) ? $Func : 'index',
		];

		$MvcArr = self::analyze_array($MvcArr);
		$MvcArr['Params'] = $Params ?? [];

		return $MvcArr;
	}

	/**
	 * @method 路径匹配数组重构
	 * @author Victoria
	 * @date   2020-04-10
	 */
	private static function analyze_array($MvcArr)
	{
		if (empty($MvcArr) || !is_array($MvcArr)) return $MvcArr;

		foreach ($MvcArr as $key => $value) {
			if ($key == 'Func') continue;

			if ($key == 'ClassPath') {
				$temp = explode('/', $value);

				$tempArr = [];
				foreach ($temp as $k => $v) {
					$tempArr[] = self::upperFirstStr($v);
				}
				$MvcArr[$key] = implode('\\', $tempArr);
				continue;
			}
			$tempStr = mb_substr($value, 0, 1);
			$MvcArr[$key] = self::upperFirstStr($value);
		}

		return $MvcArr;
	}

	/**
	 * @method 大写首字母
	 * @author Victoria
	 * @date   2020-04-15
	 * @return string
	 */
	protected static function upperFirstStr($str = '')
	{
		if (empty($str)) return $str;

		return strtoupper(mb_substr($str, 0, 1)).mb_substr($str, 1);
	}

	/**
	 * @method 解析网址参数
	 * @author Victoria
	 * @date   2020-04-08
	 * @param  string   $QueryString 
	 * @return array
	 */
	public static function analyze_url_params()
	{
		$params = [];

		if (!empty($_SERVER['QUERY_STRING']))
		{
			/* 初始化传参数组 */
			/* 对传参字符进行拆分 */
			$queryStrArr = explode( '&', trim( $_SERVER['QUERY_STRING'], '&' ) );
			foreach( $queryStrArr as $v ) 
			{
				$tmp = explode( '=', $v );
				if (empty($tmp)) continue;
				$params[$tmp[0]] = urldecode($tmp[1] ?? '');
			}
		}

		if (!empty($_REQUEST)) {
			$params = array_merge($params, $_REQUEST);
		}

		//token 的一个特殊参数
		if (empty($params['token'])) {
			$params['token'] = $_SERVER['X-AUTH-ACCESS-TOKEN'] ?? ''; //头部传投投ken方式
		}
		return $params;
	}

}

//mvc信息输出
function mvc_echo($msg)
{
	echo '<div style="position: absolute; bottom: 1px;right:5px;z-index: 9999;"><div style="clear:both;word-wrap: break-word;font-family: Arial;font-size: 18px;border: 2px solid #c00;border-radius: 20px;margin:0 30px;padding: 20px;background-color:#FFFFE1;font-weight:600;">';
	echo $msg;
	echo '</div></div>';
}

//错误处理
function catch_error_debug()
{
	$_error=error_get_last();
	if($_error && in_array($_error['type'],array(1, 4, 16, 64, 256, 4096, E_ALL))){
		$msg = '';
		$msg .= '<div>网址:'.$_SERVER['REDIRECT_URL'].'</div>';
		$msg .= '<div>入口: <strong>'.Mvc::$URL_CLASS_PATH.'/'.Mvc::$URL_CLASS.'/'.Mvc::$URL_METHOD.'</strong></div>';
		$msg .= '<div>子目录: <strong>'.Mvc::$URL_CLASS_PATH.'</strong></div>';
		$msg .= '<div>类: <strong>'.Mvc::$URL_CLASS.'</strong></div>';
		$msg .= '<div>方法: <strong>'.Mvc::$URL_METHOD.'</strong></div>';
		$msg .= '<div>参数: <strong>'. json_encode(Mvc::$URL_PARAMS) .'</strong></div>';
		$msg .= '<div style="color:#c00">'.$_error['message'].'</div>';
		$msg .= '文件: <strong>'.$_error['file'].'</strong></br>';
		$msg .= '在第: <strong>'.$_error['line'].'</strong> 行</br>';
		mvc_echo($msg,true);
		exit;
	}
}

/**
 * @method 生产线上报错机制
 * @author Victoria
 */
function catch_error()
{
	$_error=error_get_last();
	$str = sprintf('[%s] file:%s, line:%s,msg:%s',date ( "Y-m-d H:i:s" ),$_error['file'],$_error['line'],$_error['message']);
	exit;
}

/**
 * 设置报错级别
 */
if(getenv('APP_DEBUG')){
	register_shutdown_function("catch_error_debug");
	error_reporting(E_ALL& ~E_NOTICE);
}else{
	register_shutdown_function("catch_error");
	error_reporting(0);
}
