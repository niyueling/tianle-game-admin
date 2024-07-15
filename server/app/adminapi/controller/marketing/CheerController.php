<?php

namespace app\adminapi\controller\marketing;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\marketing\CheerLogic;

/**
 * 助威管理
 * Class CenterController
 * @package app\adminapi\controller\data
 */
class CheerController extends BaseAdminController
{
    /**
     * @notes 助威列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function lists()
    {
        $params = $this->request->get();
        $result = CheerLogic::lists($params);
        return $this->data($result);
    }

    /**
     * @notes 助威删除
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function del()
    {
        $params = $this->request->post();
        CheerLogic::del($params);
        return $this->success("删除成功");
    }

    /**
     * @notes 助威编辑
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function setInfo()
    {
        $params = $this->request->post();
        CheerLogic::setInfo($params);
        return $this->success("编辑成功");
    }

    /**
     * @notes 助威新增
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function add()
    {
        $params = $this->request->post();
        $res = CheerLogic::add($params);
        if($res) return $this->success("新增成功");
        return $this->fail("助威名称已存在");
    }
}
