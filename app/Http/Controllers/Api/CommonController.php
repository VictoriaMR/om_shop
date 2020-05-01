<?php

namespace app\Http\Controllers\Api;

use app\Http\Controllers\Api\ApiController;

class CommonController extends ApiController
{
    /**
     * @method 文件上传
     * @author Victoria
     * @date   2020-01-14
     */
    public function upload()
    {
        $file = $this->request('file'); //长传文件
        $cate = $this->request('cate', 'product'); //类型
        $action = $this->request('action');
        $page = $this->request('start', 1);
        $pagesize = $this->request('size', 20);

        switch ($action) {
            case 'config':
                $this->getUEditorConfig();
                break;
            case 'listimage':
                $attachmemtService = new \app\Services\AttachmentService();
                $list = $attachmemtService->getFileList($page, $pagesize);
                $temp = [
                    'state' => 'SUCCESS',
                    'list' => $list['list'],
                    'start' => $page,
                    'total' => $list['total']
                ];
                echo json_encode($temp);
                exit();
                break;
            
            default:
                # code...
                break;
        }

        if (empty($file))
            return $this->getResult(100000, false, ['message' => '文件不能为空!']);

        if (empty($cate))
            return $this->getResult(100000, false, ['message' => '类型不能为空!']);

        $result = \app\Services\FileUploader::upload($file, $cate);

        if (!empty($action)) {

            $temp = [
                'state' => $result ? 'SUCCESS' : 'FAILED',
                'url' => str_replace('small/', '', $result['file_url'] ?? ''),
                'type' => $result['type'] ?? ''
            ];


            echo json_encode($temp);
            exit();
        }

        if ($result)
            return $this->getResult(200, $result, ['message' => '上传成功']);
        else
            return $this->getResult(100000, $result, ['message' => '上传失败']);
    }

    public function getUEditorConfig()
    {
        $config = preg_replace('/\/\*[\s\S]+?\*\//', '', file_get_contents(ROOT_PATH.'public/ueditor/config.json'));
        echo $config;
        exit();
    }
}