<?php

namespace app\adminapi\controller\wechat;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\wechat\WechatLogic;
use app\adminapi\validate\wechat\WechatLoginValidate;
use app\adminapi\validate\wechat\WechatValidate;
use app\common\service\pay\WeChatPayService;

/**
 * 微信控制器
 * Class WechatController
 * @package app\shopapi\controller
 */
class WechatController extends BaseAdminController
{
    public array $notNeedLogin = ['jsConfig', 'codeUrl', 'oaLogin', 'notifyOa', 'notifyMnp', 'index',
        'verifyApplePayInApp', 'webLogin', 'wxGameNotify', 'updateWxGameSessionKey'];
    /**
     * @notes 微信JSSDK授权接口
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/30 19:20
     */
    public function jsConfig()
    {
        $params = (new WechatValidate())->goCheck('jsConfig');
        $result = WechatLogic::jsConfig($params);
        if ($result === false) {
            return $this->fail(WechatLogic::getError(),[],0,0);
        }
        return $this->data($result);

    }

    /**
     * @notes 获取微信请求code的链接
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/7/31 14:17
     */
    public function codeUrl()
    {
        $url = $this->request->get('url');
        return $this->success('获取成功', ['url' => (new WechatLogic)->codeUrl($url)], 1);
    }

    /**
     * @notes 公众号登录
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/7/31 14:27
     */
    public function oaLogin()
    {
        $params = (new WechatLoginValidate())->post()->goCheck('oa');
        $res = (new WechatLogic)->oaLogin($params);
        if(false === $res){
            return $this->fail(WechatLogic::getError());
        }
        return $this->success('',$res);
    }

    /**
     * @notes 小程序登录
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/7/31 14:27
     */
    public function updateWxGameSessionKey()
    {
        $res = WechatLogic::updateWxGameSessionKey($this->request->post());
        if(!$res["code"]) return $this->fail($res["msg"]);

        return $this->success("请求成功", $res["data"]);
    }

    /**
     * @notes 公众号支付回调
     * @return \Symfony\Component\HttpFoundation\Response
     * @author 段誉
     * @date 2021/8/13 14:17
     */
    public function notifyOa()
    {
        return (new WeChatPayService())->notify();
    }

    /**
     * @notes 微信公众号回调
     * @author Tab
     * @date 2021/7/30 11:58
     */
    public function index()
    {
        WechatLogic::index();
    }

    /**
     * @notes 苹果回调
     * @author Tab
     * @date 2021/7/30 11:58
     */
    public function verifyApplePayInApp()
    {
        $data = WechatLogic::verifyApplePayInApp($this->request->post());

        if(!$data["code"]) return $this->fail($data["msg"], $data["data"] ?? []);
        return $this->success("请求成功", $data["data"]);
    }

    /**
     * @notes 网页登录获取用户信息
     * @author Tab
     * @date 2021/7/30 11:58
     */
    public function webLogin()
    {
        $params = $this->request->post();
        $data = $params["terminal"] == 2 ? WechatLogic::officalAccountLogin($params) : WechatLogic::webLogin($params);
        if($data["code"]) return $this->success("请求成功", $data["data"]);

        return $this->fail($data["msg"]);
    }

    /**
     * @notes 虚拟支付回调
     * @author Tab
     * @date 2021/7/30 11:58
     */
    public function wxGameNotify()
    {
        $data = WechatLogic::wxGameNotify($this->request->post());

        if(!$data["code"]) return $this->fail($data["msg"], $data["data"] ?? []);
        return $this->success("请求成功", $data["data"]);
    }
}
