<?php
namespace app\adminapi\controller\marketing;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\marketing\PrizeLogic;
use app\adminapi\logic\marketing\RateLogic;

/**
 * 奖品管理
 * Class CenterController
 * @package app\adminapi\controller\data
 */
class PrizeController extends BaseAdminController
{
    /**
     * @notes  获取排行榜列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/10 17:00
     */
    public function index()
    {
        $result = PrizeLogic::index($this->request->get());
        return $this->data($result);
    }
    /**
     * @notes  获取抽奖设置
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/10 17:00
     */
    public function rule()
    {
        $result = PrizeLogic::rule($this->request->get());
        return $this->data($result);
    }

    /**
     * @notes 抽奖设置
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/10 18:13
     */
    public function setRule()
    {
        $params = $this->request->post();
        $result = PrizeLogic::setRule($params);
        if($result) {
            return $this->success('设置成功');
        }
        return $this->fail(RateLogic::getError());
    }

    /**
     * @notes 新增排行榜
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/10 18:13
     */
    public function addInviteRankLists()
    {
        $params = $this->request->post();
        $result = PrizeLogic::addInviteRankLists($params);
        if($result) {
            return $this->success('设置成功');
        }
        return $this->fail(RateLogic::getError());
    }

    /**
     * @notes 编辑排行榜
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/10 18:13
     */
    public function modifyInviteRank()
    {
        $params = $this->request->post();
        $result = PrizeLogic::modifyInviteRank($params);
        if($result) {
            return $this->success('设置成功');
        }
        return $this->fail(RateLogic::getError());
    }

    /**
     * @notes 删除排行榜
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/10 18:13
     */
    public function deleteInviteRank()
    {
        $params = $this->request->post();
        $result = PrizeLogic::deleteInviteRank($params);
        if($result) {
            return $this->success('删除成功');
        }
        return $this->fail(RateLogic::getError());
    }

    /**
     * @notes  救助记录
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/10 17:00
     */
    public function record()
    {
        $result = PrizeLogic::record($this->request->get());
        return $this->data($result);
    }
}
