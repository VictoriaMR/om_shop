<?php

namespace app\Models;

use app\Models\Base as BaseModel;

class Attachment extends BaseModel
{
	//表名
    protected $table = 'attachment';

    //主键
    protected $primaryKey = 'attach_id';

	/**
	 * @method 新建系统文件记录
	 * @author Victoria
	 * @date   2020-01-15
	 * @return integer 文件记录ID attachment_id
	 */
    public function create($data)
    {
    	if (empty($data['name'])) return false;

    	$insert = [
    		'name' => $data['name'],
		  	'type' => $data['type'],
		  	'cate' => $data['cate'],
            'size' => $data['size'] ?? 0,
		  	'create_at' => time(),
    	];

    	return $this->insertGetId($insert);
    }

    /**
     * @method 根据hash获取文件信息
     * @author Victoria
     * @date   2020-01-15
     * @return array
     */
    public function getAttachmentByHash($checkno)
    {
    	if (empty($checkno)) return false;

        $result = $this->getOne($this->table, ['name' => $checkno], ['attach_id', 'type', 'name', 'cate']);

        return $result;
    }

    /**
     * @method 文件是否存在
     * @author Victoria
     * @date   2020-01-15
     * @param  string    $checkno 
     * @return boolean             
     */
    public function isExitsHash($checkno)
    {
    	if (empty($checkno)) return false;

        $result = $this->getOne($this->table, ['name' => $checkno], ['COUNT(*) count']);

        return (int) $result['count'] > 0;
    }

    /**
     * @method 获取文件列表
     * @author Victoria
     * @date   2020-04-25
     * @return array
     */
    public function getFileList($page, $pagesize)
    {
        $total =  $this->getOne($this->table, [], ['COUNT(*) count'])['count'] ?? 0;
        if ($total > 0) {
            $list = $this->getList($this->table, [], ['attach_id', 'name', 'cate', 'type', 'create_at'],['page'=>$page, 'pagesize'=>$pagesize], [['create_at']]);
        }
        return $this->getPaginationList($list, $total, $page, $pagesize);
    }
}
