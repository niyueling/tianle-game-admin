<?php
namespace app\adminapi\controller\marketing;
use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\marketing\TreasureBoxLogic;
use think\response\Json;

/**
 * 宝箱管理
 * Class CenterController
 * @package app\adminapi\controller\data
 */
class TreasureBoxController extends BaseAdminController
{
    /**
     * @notes 宝箱管理
     * @return Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function lists()
    {
        $params = $this->request->get();
        $result = TreasureBoxLogic::lists($params);
        return $this->data($result);
    }

    /**
     * @notes 宝箱管理
     * @return Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function del()
    {
        $params = $this->request->post();
        TreasureBoxLogic::del($params);
        return $this->success("删除成功");
    }

    /**
     * @notes 宝箱管理
     * @return Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function setInfo()
    {
        $params = $this->request->post();
        TreasureBoxLogic::setInfo($params);
        return $this->success("编辑成功");
    }

    /**
     * @notes 宝箱管理
     * @return Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function add()
    {
        $params = $this->request->post();
        $res = TreasureBoxLogic::add($params);
        if($res) return $this->success("新增成功");
        return $this->fail("宝箱等级已存在");
    }
}
