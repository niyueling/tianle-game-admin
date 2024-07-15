<?php

namespace app\adminapi\controller\marketing;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\marketing\QianLogic;

/**
 * 抽签管理
 * Class CenterController
 * @package app\adminapi\controller\data
 */
class QianController extends BaseAdminController
{
    public array $notNeedLogin = ["upload"];

    /**
     * @notes 抽签列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function lists()
    {
        $params = $this->request->get();
        $result = QianLogic::lists($params);
        return $this->data($result);
    }

    /**
     * @notes 抽签删除
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function del()
    {
        $params = $this->request->post();
        QianLogic::del($params);
        return $this->success("删除成功");
    }

    /**
     * @notes 抽签编辑
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function setInfo()
    {
        $params = $this->request->post();
        QianLogic::setInfo($params);
        return $this->success("编辑成功");
    }

    /**
     * @notes 抽签新增
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function add()
    {
        $params = $this->request->post();
        $res = QianLogic::add($params);
        if($res) return $this->success("新增成功");
        return $this->fail("抽签名称已存在");
    }

    /**
     * @notes 导入excel
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function upload()
    {
        $file = $this->request->file('file');
        $res = QianLogic::upload($file);
        return $this->success("导入成功", $res);
    }
}
