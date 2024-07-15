<?php

namespace app\adminapi\controller;

use app\adminapi\logic\AccountLogLogic;

/***
 * 账户流水控制器
 * Class AccountLogController
 * @package app\adminapi\controller
 */
class AccountLogController extends BaseAdminController
{
    /**
     * @notes 查看用户充值流水列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/12 15:18
     */
    public function userRechargeLists()
    {
        $data = (new AccountLogLogic())->userRechargeLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 查看用户钻石流水列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/12 15:18
     */
    public function userGemLists()
    {
        $data = (new AccountLogLogic())->userGemLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 查看用户钻石流水列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/12 15:18
     */
    public function userDiamondLists()
    {
        $data = (new AccountLogLogic())->userDiamondLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 查看用户金豆流水列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/12 15:18
     */
    public function userGoldLists()
    {
        $data = (new AccountLogLogic())->userGoldLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 房间记录
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/10 16:49
     */
    public function roomLists()
    {
        $data = (new AccountLogLogic())->roomLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 房间详情
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/10 16:49
     */
    public function roomDetail()
    {
        $data = (new AccountLogLogic())->roomDetail($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 查看代理充值流水列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/12 15:18
     */
    public function agencyRechargeLists()
    {
        $data = (new AccountLogLogic())->agencyRechargeLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 查看代理自助充值流水列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/12 15:18
     */
    public function agencyAutoRechargeLists()
    {
        $data = (new AccountLogLogic())->agencyAutoRechargeLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 查看代理钻石流水列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/12 15:18
     */
    public function agencyGemLists()
    {
        $data = (new AccountLogLogic())->agencyGemLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 查看俱乐部流水列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/12 15:18
     */
    public function clubAccountLists()
    {
        $data = (new AccountLogLogic())->clubAccountLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 俱乐部房间记录
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/10 16:49
     */
    public function clubRoomLists()
    {
        $data = (new AccountLogLogic())->clubRoomLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 房间详情
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/10 16:49
     */
    public function clubRoomDetail()
    {
        $data = (new AccountLogLogic())->clubRoomDetail($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 俱乐部房间排行榜
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/10 16:49
     */
    public function clubRoomRanking()
    {
        $data = (new AccountLogLogic())->oldClubRoomRanking($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 用户战队金币记录
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/10 16:49
     */
    public function userClubGoldLists()
    {
        $data = (new AccountLogLogic())->userClubGoldLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 圈主操作金币记录
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/10 16:49
     */
    public function clubCreatorGoldLists()
    {
        $data = (new AccountLogLogic())->clubCreatorGoldLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 查看用户红包领取列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/12 15:18
     */
    public function userRedPocketLists()
    {
        $data = (new AccountLogLogic())->userRedPocketLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 查看用户红包提现列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/12 15:18
     */
    public function userRedPocketWithdrawLists()
    {
        $data = (new AccountLogLogic())->userRedPocketWithdrawLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 查看商品购买订单
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/12 15:18
     */
    public function payGoodsOrderLists()
    {
        $data = (new AccountLogLogic())->payGoodsOrderLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 查看用户充值统计列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/12 15:18
     */
    public function userRechargeStatisticsLists()
    {
        $data = (new AccountLogLogic())->userRechargeStatisticsLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 查看用户红包排行
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/12 15:18
     */
    public function userRedPocketRankingLists()
    {
        $data = (new AccountLogLogic())->userRedPocketRankingLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 查看宝箱领取记录
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/12 15:18
     */
    public function userGameRateLists()
    {
        $data = (new AccountLogLogic())->userGameRateLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 查看用户胜率排行
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/12 15:18
     */
    public function userGameRateRankLists()
    {
        $data = (new AccountLogLogic())->userGameRateRankLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 查看用户待救助
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/12 15:18
     */
    public function userGameHelpRecord()
    {
        $data = (new AccountLogLogic())->userGameHelpRecord($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 查看用户是否可以触发规则
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/12 15:18
     */
    public function userCanRateRuleLists()
    {
        $data = (new AccountLogLogic())->userCanRateRuleLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 查看4王游戏列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/12 15:18
     */
    public function gameHaveFourLists()
    {
        $data = (new AccountLogLogic())->gameHaveFourLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 查看4王游戏详情
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/12 15:18
     */
    public function gameHaveFourDetail()
    {
        $data = (new AccountLogLogic())->gameHaveFourDetail($this->request->get());
        return $this->data($data);
    }

    /**
     * @notes 对局详情
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/10 16:49
     */
    public function roomRuleDetail()
    {
        $data = (new AccountLogLogic())->roomRuleDetail($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 解散俱乐部
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/18 18:07
     */
    public function userRoomDissolve()
    {
        $res = AccountLogLogic::userRoomDissolve($this->request->post());

        if($res["code"]) return $this->success($res["msg"]);

        return $this->fail($res["msg"]);
    }
}
