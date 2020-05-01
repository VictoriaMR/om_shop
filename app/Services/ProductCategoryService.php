<?php 
namespace app\Services;
use app\Services\Base as BaseService;
use app\Models\ProductCategory;

/**
 * 
 */
class ProductCategoryService extends BaseService
{
	public function __construct()
    {
        $this->baseModel = new ProductCategory();
    }

    /**
     * @method 分类列表
     * @author Victoria
     * @date   2020-04-17
     * @return array
     */
    public function getCategoryList()
    {
        //这里可能要用到缓存
        $list = $this->baseModel->getCategoryList();

        $list = $this->analyzeCategory($list);
        return $list;
    }

    private function analyzeCategory($data, $parentId = 0, $level = 1)
    {
        $returnData = array();  
        foreach ($data as $key => $val) {
            if ($val['parent_id'] == $parentId) {
                $val['level'] = $level;
                $val['son'] = $this->analyzeCategory($data, $val['cate_id'] ,$level+1);
                $returnData[] = $val;
            } 
        } 
        return $returnData;
    }

    /**
     * @method 删除分类
     * @author Victoria
     * @date   2020-04-18
     * @return boolean
     */
    public function delCategory($cateId)
    {
        if (empty($cateId)) return false;

        if (!is_array($cateId)) $cateId = explode(',', $cateId);

        $result = $this->baseModel->deleteData($cateId);

        return $result;
    }

    /**
     * @method 根据ID集获取
     * @author Victoria
     * @date   2020-04-25
     * @return array
     */
    public function getListByIds($id)
    {
        if (empty($id)) return [];

        if (!is_array($id)) $id = [$id];

        $data = [];
        $data[] = ['cate_id', 'in', $id];

        return $this->getDataList($data);
    }
	
}

?>