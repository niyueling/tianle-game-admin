<?php

namespace app\common\service\pay;

use app\common\enum\PayEnum;
use app\common\service\WeChatConfigService;
use EasyWeChat\Factory;
use EasyWeChat\Payment\Application;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use think\facade\Db;


/**
 * 微信支付
 * Class WeChatPayService
 * @package app\common\server
 */
class WeChatPayService extends BasePayService
{
    /**
     * 授权信息
     * @var gms|array|\think\Model
     */
    protected $auth;


    /**
     * 微信配置
     * @var
     */
    protected $config;


    /**
     * easyWeChat实例
     * @var
     */
    protected $pay;

    /**
     * 初始化微信配置
     * @param $terminal //用户终端
     * @param null $userId //用户id(获取授权openid)
     */
    public function __construct($type = 1, $from = "", $gm = null, $terminal = 2)
    {
        $this->config = WeChatConfigService::getWechatConfigByTerminal($terminal);
        $this->pay = Factory::payment($this->config);
        if ($gm !== null && $type == 1) {
            $this->auth = Db::connect('mongodb')->table('gms')->where("_id", new ObjectId($gm))->find();
        }

        if ($gm !== null && $type == 2) {
            $this->auth = Db::connect('mongodb')->table('players')->where("_id", new ObjectId($gm))->find();
        }
    }


    /**
     * @notes 发起微信支付统一下单
     * @param $from
     * @param $order
     * @return array|false|string
     * @author 段誉
     * @date 2021/8/4 15:05
     */
    public function pay($from, $order, $terminal = 2)
    {
        try {
            $result = $this->jsapiPay($from, $order, $terminal);
            return [
                'config' => $result,
                'pay_way' => PayEnum::WECHAT_PAY
            ];
        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 获取配置
     * @param $from
     * @param $order
     * @return array|false|string
     * @author 段誉
     * @date 2021/8/4 15:05
     */
    public function config($from, $order, $terminal = 2)
    {
        try {
            return [
                'config' => $this->config,
                'attributes' => $this->getAttributes($from, $order, $terminal)
            ];
        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes jsapi支付(小程序,公众号)
     * @param $from
     * @param $order
     * @return array|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author 段誉
     * @date 2021/8/4 15:05
     */
    public function jsapiPay($from, $order, $terminal = 2)
    {
        $result = $this->pay->order->unify($this->getAttributes($from, $order, $terminal));
        $this->checkResultFail($result);
        return $this->pay->jssdk->bridgeConfig($result['prepay_id'], false);
    }

    /**
     * @notes 验证微信返回数据
     * @param $result
     * @throws \Exception
     * @author 段誉
     * @date 2021/8/4 14:56
     */
    public function checkResultFail($result)
    {
        if ($result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS') {
            if (isset($result['return_code']) && $result['return_code'] == 'FAIL') {
                throw new \Exception($result['return_msg']);
            }
            if (isset($result['err_code_des'])) {
                throw new \Exception($result['err_code_des']);
            }
            throw new \Exception('未知原因');
        }
    }


    /**
     * @notes 支付请求参数
     * @param $from
     * @param $order
     * @return array
     * @author 段誉
     * @date 2021/8/4 15:07
     */
    public function getAttributes($from, $order, $terminal = 2)
    {
        $attributes = [
            'trade_type' => 'JSAPI',
            'body' => '游戏充值',
            'total_fee' => $order['price'] * 100, // 单位：分
            'openid' => $terminal == 1 ? $this->auth['openid'] : $order['openid'],
            'attach' => $from
        ];

        $suffix = mb_substr(time(), -3);
        $attributes['out_trade_no'] = $order['_id'] . $attributes['trade_type'] . $suffix;

        Db::connect('mongodb')->table('userrechargeorders')->where("_id", new ObjectId($order["_id"]))
            ->update(["out_trade_no" => $attributes['out_trade_no']]);

        return $attributes;
    }

    /**
     * @notes 支付回调
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     * @author 段誉
     * @date 2021/8/13 14:19
     */
    public function notify()
    {
        $app = new Application($this->config);
        $response = $app->handlePaidNotify(function ($message, $fail) {
            Db::connect('mongodb')->table('notifys')->insert($message);
            if ($message['return_code'] != 'SUCCESS') {
                return $fail('通信失败');
            }

            // 用户是否支付成功
            if ($message['result_code'] == 'SUCCESS') {
                $extra['transaction_id'] = $message['transaction_id'];
                $attach = $message['attach'];
                switch ($attach) {
                    case 'playerRecharge':
                        self::playerNotify($message);
                        break;
                    case 'agencyRecharge':
                        self::agencyNotify($message);
                        break;
                }
            } else {
                return false;
            }
            return true; // 返回处理完成
        });
        return $response->send();
    }

    public function playerNotify($message) {
        $order = Db::connect('mongodb')->table('userrechargeorders')->where("out_trade_no", $message['out_trade_no'])->find();
        if(empty($order) || $order["status"] == 1) {
            return true;
        }
        self::playerRecharge($message['out_trade_no']);
    }

    /**
     * @notes 充值成功回调
     * @param $orderSn
     * @param array $extra
     * @author Tab
     * @date 2021/8/11 14:43
     */
    public static function playerRecharge($orderSn)
    {
        $order = Db::connect('mongodb')->table('userrechargeorders')->where("out_trade_no", $orderSn)->find();
        $user = Db::connect('mongodb')->table('players')->where("_id", new ObjectId($order["playerId"]))->find();
        $user["diamond"] = $user["diamond"] + $order["diamond"];
        Db::connect('mongodb')->table('players')->where("_id", new ObjectId($order["playerId"]))->update(["diamond" => $user["diamond"]]);
        Db::connect('mongodb')->table('userrechargeorders')->where("out_trade_no", $orderSn)->update(["status" => 1]);
        Db::connect('mongodb')->table('diamondrecords')->insert([
            "player" => $order["playerId"],
            "amount" => $order["diamond"],
            "residue" => $user["diamond"],
            "type" => 5,
            "note" => "微信充值",
            "createAt" => new UTCDateTime(time() * 1000)
        ]);
    }

    public function agencyNotify($message) {
        $message['out_trade_no'] = mb_substr($message['out_trade_no'], 0, 24);
        $order = Db::connect('mongodb')->table('gmextrecords')->where("_id", new ObjectId($message['out_trade_no']))->find();
        if(empty($order) || $order["status"] == "finish") {
            return true;
        }
        self::agencyRecharge($message);
    }

    /**
     * @notes 充值成功回调
     * @param $orderSn
     * @param array $extra
     * @author Tab
     * @date 2021/8/11 14:43
     */
    public static function agencyRecharge($message)
    {
        $order = Db::connect('mongodb')->table('gmextrecords')->where("_id", new ObjectId($message['out_trade_no']))->find();
        $user = Db::connect('mongodb')->table('gms')->where("_id", $order["gm"])->find();
        $user["gem"] = $user["gem"] + $order["gem"];
        Db::connect('mongodb')->table('gms')->where("_id", $order["gm"])->update(["gem" => $user["gem"]]);
        Db::connect('mongodb')->table('gmextrecords')->where("_id", new ObjectId($message['out_trade_no']))
            ->update(["status" => "finish", "notification" => $message]);
    }

    /**
     * @notes 退款
     * @param $data //微信订单号、商户退款单号、订单金额、退款金额、其他参数
     * @return array|\EasyWeChat\Kernel\Support\Collection|false|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @author 段誉
     * @date 2021/8/4 14:57
     */
    public function refund($data)
    {
        if (!empty($data["transaction_id"])) {
            return $this->pay->refund->byTransactionId(
                $data['transaction_id'],
                $data['refund_sn'],
                $data['total_fee'],
                $data['refund_fee']
            );
        } else {
            return false;
        }
    }

}
