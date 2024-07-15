<?php

namespace app\adminapi\controller\marketing;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\marketing\ConversionLogic;

/**
 * 钻石兑换金豆
 * Class CenterController
 * @package app\adminapi\controller\data
 */
class ConversionController extends BaseAdminController
{
    /**
     * @notes 兑换管理
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function lists()
    {
        $params = $this->request->get();
        $result = ConversionLogic::lists($params);
        return $this->data($result);
    }

    /**
     * @notes 兑换删除
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function del()
    {
        $params = $this->request->post();
        ConversionLogic::del($params);
        return $this->success("删除成功");
    }

    /**
     * @notes 兑换编辑
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function setInfo()
    {
        $params = $this->request->post();
        ConversionLogic::setInfo($params);
        return $this->success("编辑成功");
    }

    /**
     * @notes 兑换新增
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function add()
    {
        $params = $this->request->post();
        $res = ConversionLogic::add($params);
        if($res) return $this->success("新增成功");
        return $this->fail("数量已存在");
    }
}
