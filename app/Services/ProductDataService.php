<?php 
namespace app\Services;
use app\Services\Base as BaseService;
use app\Models\ProductData;

/**
 * 
 */
class ProductDataService extends BaseService
{
	public function __construct()
    {
        $this->baseModel = new ProductData();
    }

    public function save($proId, $data = [])
    {
        if (empty($proId) || empty($data)) return false;

        //存在更新 不存在插入
        if ($this->isExistData($proId)) {
            $this->baseModel->updateDataById($proId, $data);
        } else {
            $data['pro_id'] = $proId;
            $this->baseModel->addData($data);
        }

        return true;

    }

    /**
     * @method 是否存在数据
     * @author Victoria
     * @date   2020-04-25
     * @return boolean  
     */
    public function isExistData($proId)
    {
        return $this->baseModel->isExistData($proId);
    }
}