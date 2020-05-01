<?php 

namespace om;

class view
{
	/**
	 * @method 模板输出
	 * @author Victoria
	 * @date   2020-04-11
	 */
	public static function view($data = [], $tplFile = '')
	{
		if(empty($tplFile)){
            if (!empty(mvc::$URL_CLASS)) $tplFile = mvc::$URL_CLASS . '/';
            $tplFile .= mvc::$URL_CLASS_PATH .'/'. mvc::$URL_METHOD;
        } else {
        	$tplFile = implode('/', $tplFile);
        }

        $tplFile = ROOT_PATH.'app/View/'.$tplFile.'.php';

        self::put($tplFile, $data);
	}

	/**
	  * @method 得到处理模板后的结果(HTML)
	  * @param  $tplFile  模板名称
	  * @author Victoria
	  */
	public static function fetch($tplFile='', $data = [])
	{
		ob_start();
		self::put($tplFile, $data);
		$html = ob_get_contents(); 
		ob_end_clean();
		return $html;
	}

	/**
	  * @method 输出模板
	  * @param  $tplFile  模板名称
	  * @author Victoria
	  */
	public static function put($tplFile='', $data = [])
	{
        if (!is_file($tplFile)) return '<div>错误信息</div>';
        foreach($data as $key=>$value) {
			$$key = $value;
		}
		include($tplFile);
	}

	public static function jump($tplFile='', $data = [])
    {
    	$tempArr = explode('/', $tplFile);

    	$tempArr = array_combine(['Class', 'ClassPath', 'Func'], $tempArr);

    	self::view($data, self::analyze_array($tempArr));
        exit;
    }
}