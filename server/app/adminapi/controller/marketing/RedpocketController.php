<?php

namespace app\adminapi\controller\marketing;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\marketing\RedpocketLogic;

/**
 * 红包管理
 * Class CenterController
 * @package app\adminapi\controller\data
 */
class RedpocketController extends BaseAdminController
{
    /**
     * @notes 红包管理
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function redPocketLists()
    {
        $params = $this->request->get();
        $result = RedpocketLogic::redPocketLists($params);
        return $this->data($result);
    }

    /**
     * @notes 红包删除
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function delRedPocket()
    {
        $params = $this->request->post();
        RedpocketLogic::delRedPocket($params);
        return $this->success("删除成功");
    }

    /**
     * @notes 红包编辑
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function setRedPocketInfo()
    {
        $params = $this->request->post();
        $res = RedpocketLogic::setRedPocketInfo($params);
        if($res == "success") return $this->success("编辑成功");
        return $this->fail($res);
    }

    /**
     * @notes 红包新增
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function addRedPocket()
    {
        $params = $this->request->post();
        $res = RedpocketLogic::addRedPocket($params);

        if($res == "success")  return $this->success("新增成功");
        return $this->fail($res);
    }
}