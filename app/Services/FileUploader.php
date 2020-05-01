<?php

namespace app\Services;

class FileUploader
{
    const FILE_TYPE = ['avatar', 'product'];
    const FILE_COMPERSS = ['jpg', 'jpeg', 'png'];

    /**
     * 通过文件路径上传
     *
     * @param string $filePath 待上传文件路径
     * @param string $module 图片业务类别（可不传）,比如头像 avatar 商品 product
     * @return mix 失败返回 false
     */
    public static function upload($file, $cate)
    {
        if (empty($file['size']) || !in_array($cate, self::FILE_TYPE)) return false;

        $extension = explode('.', $file['name'])[1] ?? ''; //后缀
        $tmpFile = $file['tmp_name']; //上传文件路径

        $hash = hash_file('md5', $tmpFile); //生成文件hash值

        $attachmentService = new \app\Services\AttachmentService();

        $returnData = [];
        if ($attachmentService->isExitsHash($hash)) { 
            //文件已存在
            $returnData = $attachmentService->getAttachmentByHash($hash);
        } else {

            $insert = [
                'name' => $hash,
                'type' => $extension,
                'cate' => $cate,
                'size' => $file['size'],
            ];

            //保存文件地址
            $saveUrl = getenv('FILE_CENTER').$cate.'/'.$hash.'.'.$extension;

            $savePath = pathinfo($saveUrl, PATHINFO_DIRNAME);

            //创建目录
            if (!is_dir($savePath)) {
                mkdir($savePath, 0777, true);
            }
            $result = move_uploaded_file($tmpFile, $saveUrl);

            if ($result) {
                //水印
                // $imageWater = new \App\Service\Utils\ImageWater($saveUrl);
                // $imageWater->output();
                //图片文件压缩存放
                if (in_array(strtolower($extension), self::FILE_COMPERSS)) {
                    $imageCompress = new \app\Services\ImageCompress($saveUrl);
                    $imageCompress->compressImg(getenv('FILE_CENTER').$cate.'/small/'.$hash.'.'.$extension);
                }

                $attachmentId = $attachmentService->create($insert);
                if (!$attachmentId) return false;
                $insert['attach_id'] = $attachmentId;
                $insert['file_url'] = str_replace('\\', '/', getenv('FILE_DOMAIN').$cate.'/small/'.$hash.'.'.$extension);
            }
            $returnData = $insert;
        }

        return $returnData;
    }

    /**
     * 通过文件内容上传
     *
     * @param string $content
     * @param string $module 图片业务类别，比如头像 avatar 商品 product
     * @return mix 失败返回 false
     */
    public static function uploadByString($content, $filename, $module)
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => env('FILE_UPLOAD_URL'),
            'timeout'  => 30.0,
        ]);
        
        $response = $client->request('POST', 'file/upload/save_path/' . $module, [
            'multipart' => [
                [
                    'name'     => $filename,
                    'contents' => $content,
                    'filename' => $filename,
                ]  
            ]
        ]);

        $result = json_decode($response->getBody(), true);


        if (empty($result) || $result['code'] != '200') return false;

        return $result['data'];
    }

    /**
     * 通过base64编码上传
     *
     * @param mix $file
     */
    public static function uploadByBase64String($file, $module='')
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => env('FILE_UPLOAD_URL'),
            'timeout'  => 30.0,
        ]);

        $params = [
            'upload_method' => 'base64'
        ];
        
        if (!is_array($file)) {
            $params['file'] = $file;
        } else {
            foreach ($file as $item) {
                $params['file[]'] = $item;
            }
        }
        
        $response = $client->request('POST', 'file/upload/save_path/' . $module, ['form_params' => $params]);

        $result = json_decode($response->getBody(), true);

        if (!empty($result) || $result['code'] != 't') return false;

        return $result['data'];
    }

    public static function uploadByUrl($url, $module='avatar')
    {
        $filename = Http::download($url);
        
        if (empty($filename)) return false;
        
        return self::upload(storage_path('app/{$filename}'), $module);
    }
}
