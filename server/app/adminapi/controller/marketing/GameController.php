<?php

namespace app\adminapi\controller\marketing;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\marketing\GameLogic;

/**
 * 游戏区域管理
 * Class CenterController
 * @package app\adminapi\controller\data
 */
class GameController extends BaseAdminController
{
    /**
     * @notes 游戏区域管理
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function gameLists()
    {
        $params = $this->request->get();
        $result = GameLogic::gameLists($params);
        return $this->data($result);
    }

    /**
     * @notes 游戏区域搜索条件
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function searchLists()
    {
        $result = GameLogic::searchLists();
        return $this->data($result);
    }

    /**
     * @notes 游戏区域删除
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function delGame()
    {
        $params = $this->request->post();
        GameLogic::delGame($params);
        return $this->success("删除成功");
    }

    /**
     * @notes 游戏区域编辑
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function setGameInfo()
    {
        $params = $this->request->post();
        $res = GameLogic::setGameInfo($params);
        if($res == "success") return $this->success("编辑成功");
        return $this->fail($res);
    }

    /**
     * @notes 游戏区域新增
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function addGame()
    {
        $params = $this->request->post();
        $res = GameLogic::addGame($params);

        if($res == "success")  return $this->success("新增成功");
        return $this->fail($res);
    }
}