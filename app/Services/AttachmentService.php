<?php 

namespace app\Services;

use app\Services\Base as BaseService;
use app\Models\Attachment;

/**
 * 	系统文件公共类
 */
class AttachmentService extends BaseService
{	
	public function __construct()
    {
        $this->baseModel = new Attachment();
    }

    /**
	 * @method 新建系统文件记录
	 * @author Victoria
	 * @date   2020-01-15
	 * @return integer 文件记录ID attachment_id
	 */
    public function create($data)
    {
    	return $this->baseModel->create($data);
    }

    /**
     * @method 文件是否存在
     * @author Victoria
     * @date   2020-01-15
     * @param  string    $checksum 
     * @return boolean             
     */
    public function isExitsHash($checkno)
    {
    	return $this->baseModel->isExitsHash($checkno);
    }

    /**
     * @method 根据hash获取文件信息
     * @author Victoria
     * @date   2020-01-15
     * @return array
     */
    public function getAttachmentByHash($checkno)
    {
    	$info = $this->baseModel->getAttachmentByHash($checkno);

    	$info['file_url'] = str_replace('\\', '/', getenv('FILE_DOMAIN').$info['cate'].'/'.'small/'.$info['name'].'.'.$info['type']);

    	return $info;
    }

    /**
     * @method 获取文件列表
     * @author Victoria
     * @date   2020-04-25
     * @return array
     */
    public function getFileList($page = 1, $pagesize = 20)
    {
        $list = $this->baseModel->getFileList($page, $pagesize);

        if (!empty($list['list'])) {
            foreach ($list['list'] as $key => $value) {
                $list['list'][$key]['url'] = str_replace('\\', '/', getenv('FILE_DOMAIN').$value['cate'].'/'.$value['name'].'.'.$value['type']);
            }
        }

        return $list;
    }

    /**
     * @method 根据ID集获取文件
     * @author Victoria
     * @date   2020-04-25
     * @return array
     */
    public function getListByIds($id)
    {
        if (empty($id)) return [];

        if (!is_array($id)) $id = [$id];

        $data = [];
        $data[] = ['attach_id', 'in', $id];

        $list = $this->getDataList($data);

        if (!empty($list)) {
            foreach ($list as $key => $value) {
                $list[$key]['url'] = str_replace('\\', '/', getenv('FILE_DOMAIN').$value['cate'].'/small/'.$value['name'].'.'.$value['type']);
            }
        }

        return $list;
    }
}
