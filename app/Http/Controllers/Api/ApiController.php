<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * @method 返回api专用json格式
     * @author Victoria
     * @date   2020-04-12
     * @return [type]     [description]
     */
    public function getResult($code, $data=[], $options=[])
    {
        $data = [
            'code' => $code,
            'data' => $data
        ];

        $data = array_merge($data, $options);
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }
}
