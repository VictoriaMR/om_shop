<?php 
namespace app\Services;
use app\Services\Base as BaseService;
use app\Models\Product;

/**
 * 
 */
class ProductService extends BaseService
{
	public function __construct()
    {
        $this->baseModel = new Product();
    }

    protected function createProducrNo()
    {
        return sprintf("S%s%03d", date("YmdHis"), rand(0, 999));
    }

    public function create($data)
    {
        $data['create_at'] = time();
        $data['pro_no'] = $this->createProducrNo();
        return $this->baseModel->insertGetId($data);
    }

    public function update($proId, $data = [])
    {
        if (empty($proId) || empty($data)) return false;

        $data['update_at'] = time();
        return $this->baseModel->updateDataById($proId, $data);
    }

    /**
     * @method 保存商品数据
     * @author Victoria
     * @date   2020-04-25
     * @return boolean
     */
    public function save($proId = 0, $data = [])
    {
        if (empty($data)) return false;

        $tempInfo = [];

        $infoFields = ['pro_name', 'status', 'origin_price', 'sale_price', 'pro_stock', 'pro_cate', 'pro_imgs'];

        foreach ($infoFields as $value) {
            if (isset($data[$value]))
                $tempInfo[$value] = $data[$value];
        }

        $tempData = [];
        if (!empty($data['pro_param']))
            $tempData['pro_param'] = $data['pro_param'];

        if (!empty($data['pro_intro']))
            $tempData['pro_intro'] = $data['pro_intro'];

        if (empty($proId)) {
            //插入新商品
            $proId = $this->create($tempInfo);
        } else {
            $this->update($proId, $tempInfo);
        }

        $productDataService = new \app\Services\ProductDataService();
        $productDataService->save($proId, $tempData);

        return true;
    }

    /**
     * @method 获取列表
     * @author Victoria
     * @date   2020-04-25
     * @return [type]     [description]
     */
    public function getList($data, $page = 1, $pagesize = 10)
    {
        $total = $this->getDataCount($data);

        if ($total > 0) {
            $list = $this->getDataList($data, $field, ['page'=>$page, 'pagesize'=>$pagesize], [['create_at']]);
            if (!empty($list)) {
                //获取图片
                $imgArr = array_column($list, 'pro_imgs');
                if (!empty($imgArr)) {
                    $tempArr = [];
                    foreach ($imgArr as $value) {
                        $tempArr = array_merge($tempArr, explode(',', $value));
                    }
                    $imgArr = array_unique($tempArr);
                }

                if (!empty($imgArr)) {
                    $attachmentService = new \app\Services\AttachmentService();

                    $imgArr = $attachmentService->getListByIds($imgArr);

                    $imgArr = array_column($imgArr, null, 'attach_id');
                }

                //获取分类ID
                $cateArr = array_column($list, 'pro_cate');
                if (!empty($cateArr)) {
                    $tempArr = [];
                    foreach ($cateArr as $value) {
                        $tempArr = array_merge($tempArr, explode(',', $value));
                    }
                    $cateArr = array_unique($tempArr);
                }

                if (!empty($cateArr)) {
                    $categoryService = new ProductCategoryService();

                    $cateArr = $categoryService->getListByIds($cateArr);

                    $cateArr = array_column($cateArr, null, 'cate_id');
                }


                foreach ($list as $key => $value) {
                    $list[$key]['create_at'] = date('Y-m-d H:i:s', $value['create_at']);

                    //图片
                    $list[$key]['pro_imgs'] = [];
                    if (!empty($value['pro_imgs'])) {
                        foreach (explode(',', $value['pro_imgs']) as $ivalue) {
                            if (!empty($imgArr[$ivalue])) {
                                $list[$key]['pro_imgs'][] = $imgArr[$ivalue];
                            }
                        }
                    }

                    //分类
                    $tempArr = [];
                    if (!empty($value['pro_cate'])) {
                        foreach (explode(',', $value['pro_cate']) as $ivalue) {
                            if (!empty($cateArr[$ivalue])) {
                                $tempArr[] = $cateArr[$ivalue];
                            }
                        }
                    }
                    $list[$key]['cate_name'] = implode(',', array_column($tempArr, 'name'));
                }
            }
        }

        return $this->getPaginationList($list ?? [], $total, $page, $pagesize);
    }

    /**
     * @method 获取商品详情
     * @author Victoria
     * @date   2020-04-25
     * @return array
     */
    public function getInfo($proId = 0)
    {
        if (empty($proId)) return [];

        $info = $this->loadData($proId);

        $imgArr = array_unique(explode(',', $info['pro_imgs']));

        if (!empty($imgArr)) {
            $attachmentService = new \app\Services\AttachmentService();

            $imgArr = $attachmentService->getListByIds($imgArr);

            $imgArr = array_column($imgArr, null, 'attach_id');
        }

        //图片
        $tempArr = [];
        if (!empty($info['pro_imgs'])) {
            foreach (explode(',', $info['pro_imgs']) as $ivalue) {
                if (!empty($imgArr[$ivalue])) {
                    $tempArr[] = $imgArr[$ivalue];
                }
            }
        }
        $info['pro_imgs'] = $tempArr;

        //分类
        $categoryService = new ProductCategoryService();
        $cateArr = $categoryService->getListByIds($info['pro_cate']);
        
        $info['cate_id'] = $cateArr[0]['cate_id'] ?? 0;
        $info['cate_name'] = $cateArr[0]['name'] ?? '';

        $cateArr = $categoryService->getListByIds($cateArr[0]['parent_id']);

        $info['cate_parent_id'] = $cateArr[0]['cate_id'] ?? 0;
        $info['cate_parent_name'] = $cateArr[0]['name'] ?? '';

        //data数据
        
        $productDataService = new \app\Services\ProductDataService();
        $data = $productDataService->loadData($proId);

        $data['pro_param'] = json_decode($data['pro_param'], true);

        $info = array_merge($info, $data);

        // dd($info);

        return $info;
    }
	
}

?>