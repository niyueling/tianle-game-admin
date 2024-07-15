<?php

namespace app\adminapi\logic\alipay;

use app\common\enum\UserTerminalEnum;
use app\common\logic\BaseLogic;
use app\common\service\pay\AliPayService;
use app\common\service\WeChatConfigService;
use MongoDB\BSON\ObjectId;
use think\facade\Db;

/**
 * 微信逻辑层
 * Class WechatLogic
 * @package app\shopapi\logic
 */
class AlipayLogic extends BaseLogic
{
    /**
     * @notes H5支付
     * @author Zhou
     * @date 2021/7/30 11:58
     */
    public static function webPay($params)
    {
        $order = Db::connect('mongodb')->table('userrechargeorders')->where("_id", new ObjectId($params["orderId"]))->find();
        if(empty($order) || $order["status"] === "finish") return ["code" => false, "msg" => "订单不存在或订单已支付"];

        $payService = (new AliPayService(UserTerminalEnum::WEB));
        $result = $payService->pay($order);

        return ["code" => true, "data" => $result];
    }


}
