<?php

namespace app\adminapi\controller\marketing;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\marketing\RechargeLogic;

/**
 * 数据中心
 * Class CenterController
 * @package app\adminapi\controller\data
 */
class RechargeController extends BaseAdminController
{
    /**
     * @notes 商品管理
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function goodLists()
    {
        $params = $this->request->get();
        $result = RechargeLogic::goodLists($params);
        return $this->data($result);
    }

    /**
     * @notes 商品删除
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function delGoods()
    {
        $params = $this->request->post();
        RechargeLogic::delGoods($params);
        return $this->success("删除成功");
    }

    /**
     * @notes 商品编辑
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function setGoodInfo()
    {
        $params = $this->request->post();
        RechargeLogic::setGoodInfo($params);
        return $this->success("编辑成功");
    }

    /**
     * @notes 商品新增
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function addGoods()
    {
        $params = $this->request->post();
        $res = RechargeLogic::addGoods($params);
        if($res) return $this->success("新增成功");
        return $this->fail("数量已存在");
    }
}
