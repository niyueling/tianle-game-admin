<?php

namespace app\adminapi\controller\alipay;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\alipay\AlipayLogic;
use app\common\service\pay\AliPayService;

/**
 * 微信控制器
 * Class WechatController
 * @package app\shopapi\controller
 */
class AlipayController extends BaseAdminController
{
    public array $notNeedLogin = ['webPay', 'aliNotify'];

    /**
     * @notes H5支付
     * @author Tab
     * @date 2021/7/30 11:58
     */
    public function webPay()
    {
        $params = $this->request->post();

        $data = AlipayLogic::webPay($params);
        if($data["code"]) return $this->success("请求成功", $data["data"]);

        return $this->fail($data["msg"]);
    }

    /**
     * @notes 公众号支付回调
     * @return \Symfony\Component\HttpFoundation\Response
     * @author 段誉
     * @date 2021/8/13 14:17
     */
    public function aliNotify()
    {
        $params = $this->request->post();
        $result = (new AliPayService())->notify($params);
        if (true === $result) {
            echo 'success';
        } else {
            echo 'fail';
        }
    }
}
