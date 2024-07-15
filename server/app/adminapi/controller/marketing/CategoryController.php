<?php

namespace app\adminapi\controller\marketing;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\marketing\CategoryLogic;

/**
 * 游戏区域管理
 * Class CenterController
 * @package app\adminapi\controller\data
 */
class CategoryController extends BaseAdminController
{
    /**
     * @notes 游戏区域管理
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function lists()
    {
        $params = $this->request->get();
        $result = CategoryLogic::lists($params);
        return $this->data($result);
    }

    /**
     * @notes 游戏区域删除
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function del()
    {
        $params = $this->request->post();
        CategoryLogic::del($params);
        return $this->success("删除成功");
    }

    /**
     * @notes 游戏区域编辑
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function setInfo()
    {
        $params = $this->request->post();
        $res = CategoryLogic::setInfo($params);
        if($res == "success") return $this->success("编辑成功");
        return $this->fail($res);
    }

    /**
     * @notes 游戏区域新增
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function add()
    {
        $params = $this->request->post();
        $res = CategoryLogic::add($params);

        if($res == "success")  return $this->success("新增成功");
        return $this->fail($res);
    }

    public function gameItemList()
    {
        $params = $this->request->get();
        $result = CategoryLogic::gameItemList($params);
        return $this->success("", $result);
    }
}
