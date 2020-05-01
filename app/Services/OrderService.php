<?php 
namespace app\Services;
use app\Services\Base as BaseService;
use app\Models\Order;

/**
 *  订单接口
 */
class OrderService extends BaseService
{
	public function __construct()
    {
        $this->baseModel = new Order();
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

    public function getList($data, $page = 1, $pagesize = 10)
    {
        $total = $this->getDataCount($data);

        if ($total > 0) {
            $list = $this->getDataList($data, $field, ['page'=>$page, 'pagesize'=>$pagesize], [['create_at']]);
        }

        return $this->getPaginationList($list ?? [], $total, $page, $pagesize);
    }
}