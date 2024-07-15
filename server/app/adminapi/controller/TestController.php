<?php

namespace app\adminapi\controller;
use app\adminapi\logic\TestLogic;

class TestController extends BaseAdminController
{
    public array $notNeedLogin = ['userHelperList', 'getGameHaveFourJoker', 'mailNotice', 'getWechatAccessToken', 'wechatMessageSend',
        'dissolveManyRoom', 'clearUserHelp', 'regist', 'clearUserHelp', 'present', 'beautyNumber'];
    /**
     * @notes 测试更新用户补助信息
     * @date 2023/02/10 13:17
     * @return \think\response\Json
     * @author zhou
     */
    public function userHelperList()
    {
        $res = TestLogic::userHelperList();
        return $this->success("更新成功", $res);
    }

    /**
     * @notes 获取4王列表
     * @date 2023/02/10 13:17
     * @return \think\response\Json
     * @author zhou
     */
    public function getGameHaveFourJoker()
    {
        $res = TestLogic::getGameHaveFourJoker($this->request->get());
        return $this->success("获取成功", $res);
    }

    /**
     * @notes 测试邮件报警
     * @date 2023/02/10 13:17
     * @return \think\response\Json
     * @author zhou
     */
    public function mailNotice()
    {
        $res = TestLogic::mailNotice();
        return $this->success($res);
    }

    /**
     * @notes 测试获取微信access_token
     * @date 2023/02/10 13:17
     * @return \think\response\Json
     * @author zhou
     */
    public function getWechatAccessToken()
    {
        $res = TestLogic::getWechatAccessToken();
        return $this->data($res);
    }

    /**
     * @notes 测试推送客服消息
     * @date 2023/02/10 13:17
     * @return \think\response\Json
     * @author zhou
     */
    public function wechatMessageSend()
    {
        $res = TestLogic::wechatMessageSend($this->request->get());
        return $this->data($res);
    }

    /**
     * @notes 测试解散房间
     * @date 2023/02/10 13:17
     * @return \think\response\Json
     * @author zhou
     */
    public function dissolveManyRoom()
    {
        $res = TestLogic::dissolveManyRoom($this->request->get());
        return $this->data($res);
    }

    /**
     * @notes 清除用户补助时间
     * @date 2023/02/10 13:17
     * @return \think\response\Json
     * @author zhou
     */
    public function clearUserHelp()
    {
        $res = TestLogic::clearUserHelp();
        return $this->data($res);
    }

    /**
     * @notes 新用户注册
     * @date 2023/02/10 13:17
     * @return \think\response\Json
     * @author zhou
     */
    public function regist()
    {
        $res = TestLogic::regist();
        return $this->data($res);
    }

    /**
     * @notes 新用户注册
     * @date 2023/02/10 13:17
     * @return \think\response\Json
     * @author zhou
     */
    public function present()
    {
        $res = TestLogic::present($this->request->post());
        if($res["code"]) return $this->success($res["msg"], $res["data"]);

        return $this->fail($res["msg"]);
    }

    /**
     * @notes 生成靓号数据
     * @date 2023/02/10 13:17
     * @return \think\response\Json
     * @author zhou
     */
    public function beautyNumber()
    {
        $res = TestLogic::beautyNumber($this->request->post());
        if($res["code"]) return $this->success($res["msg"], $res["data"]);

        return $this->fail($res["msg"]);
    }
}
