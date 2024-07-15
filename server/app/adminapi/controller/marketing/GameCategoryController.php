<?php

namespace app\adminapi\controller\marketing;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\marketing\GameCategoryLogic;

/**
 * 数据中心
 * Class CenterController
 * @package app\adminapi\controller\data
 */
class GameCategoryController extends BaseAdminController
{
    /**
     * @notes 游戏子分类管理
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function lists()
    {
        $params = $this->request->get();
        $result = GameCategoryLogic::lists($params);
        return $this->data($result);
    }

    /**
     * @notes 游戏子分类删除
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function del()
    {
        $params = $this->request->post();
        GameCategoryLogic::del($params);
        return $this->success("删除成功");
    }

    /**
     * @notes 游戏子分类编辑
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function setInfo()
    {
        $params = $this->request->post();
        GameCategoryLogic::setInfo($params);
        return $this->success("编辑成功");
    }

    /**
     * @notes 游戏子分类新增
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function add()
    {
        $params = $this->request->post();
        $res = GameCategoryLogic::add($params);
        if($res) return $this->success("新增成功");
        return $this->fail("数量已存在");
    }
}
