<?php

if (!function_exists('view')) {
    function view(...$vars)
    {
    	list($data, $tplFile) = $vars;

    	if(empty($tplFile)){
            if (!empty(mvc::$URL_CLASS)) $tplFile = mvc::$URL_CLASS . '/';
            $tplFile .= mvc::$URL_CLASS_PATH .'/'. mvc::$URL_METHOD;
        } else {
        	$tplFile = implode('/', $tplFile);
        }

        $tplFile = ROOT_PATH.'app/View/'.$tplFile.'.php';

        put($tplFile, $data);
    }
}

function put($tplFile='', $data = [])
{
    if (!is_file($tplFile)) return '<div>错误信息</div>';
    if (!empty($data)) {
	    foreach($data as $key=>$value) {
			$$key = $value;
		}
    }
	include($tplFile);
}