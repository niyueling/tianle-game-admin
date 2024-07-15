<?php

namespace app\adminapi\controller\marketing;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\marketing\RegionLogic;

/**
 * 县区管理
 * Class CenterController
 * @package app\adminapi\controller\data
 */
class RegionController extends BaseAdminController
{
    /**
     * @notes 县区管理
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function regionLists()
    {
        $params = $this->request->get();
        $result = RegionLogic::regionLists($params);
        return $this->data($result);
    }

    /**
     * @notes 县区删除
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function delRegion()
    {
        $params = $this->request->post();
        RegionLogic::delRegion($params);
        return $this->success("删除成功");
    }

    /**
     * @notes 县区编辑
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function setRegionInfo()
    {
        $params = $this->request->post();
        RegionLogic::setRegionInfo($params);
        return $this->success("编辑成功");
    }

    /**
     * @notes 县区新增
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function addRegion()
    {
        $params = $this->request->post();
        $res = RegionLogic::addRegion($params);

        if($res) return $this->success("新增成功");
        return $this->fail("县区已存在");

    }
}