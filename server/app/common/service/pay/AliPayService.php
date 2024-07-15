<?php

namespace app\common\service\pay;


use Alipay\EasySDK\Kernel\Factory;
use Alipay\EasySDK\Kernel\Config;
use Alipay\EasySDK\Payment\Common\Models\AlipayTradeQueryResponse;
use Alipay\EasySDK\Payment\Common\Models\AlipayTradeRefundResponse;
use app\common\enum\PayEnum;
use app\common\enum\UserTerminalEnum;
use app\common\model\PayConfig;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use think\facade\Db;
use think\facade\Log;

/**
 * 支付宝支付
 * Class AliPayService
 * @package app\common\server
 */
class AliPayService extends BasePayService
{

    /**
     * 用户客户端
     * @var
     */
    protected $terminal;

    /**
     * 支付实例
     * @var
     */
    protected $pay;

    /**
     * 初始化设置
     * AliPayService constructor.
     * @throws \Exception
     */
    public function __construct($terminal = null)
    {
        //设置用户终端
        $this->terminal = $terminal;
        //初始化支付配置
        Factory::setOptions($this->getOptions());
        $this->pay = Factory::payment();
    }


    /**
     * @notes 支付设置
     * @return Config
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2021/7/28 17:43
     */
    public function getOptions()
    {
        $config = (new PayConfig())->where(['pay_way' => PayEnum::ALI_PAY])->find();
        if (empty($config)) {
            throw new \Exception('请配置好支付设置');
        }
        $options = new Config();
        $options->protocol = 'https';
        $options->gatewayHost = 'openapi.alipay.com';
//        $options->gatewayHost = 'openapi.alipaydev.com'; //测试沙箱地址
        $options->signType = 'RSA2';
        $options->appId = $config['config']['app_id'] ?? '';
        // 应用私钥
        $options->merchantPrivateKey = $config['config']['private_key'] ?? '';
        //支付宝公钥
        $options->alipayPublicKey = $config['config']['ali_public_key'] ?? '';
        //回调地址
        $options->notifyUrl = (string)url('alipay.alipay/aliNotify', [], false, true);
        return $options;
    }


    /**
     * @notes 支付
     * @param $order //订单信息
     * @return false|string[]
     * @author 段誉
     * @date 2021/8/13 17:08
     */
    public function pay($order)
    {
        try {
            switch ($this->terminal) {
                case UserTerminalEnum::PC:
                    $result = $this->pagePay($order);
                    break;
                case UserTerminalEnum::IOS:
                case UserTerminalEnum::ANDROID:
                    $result = $this->appPay($order);
                    break;
                case UserTerminalEnum::WEB:
                    $result = $this->wapPay($order);
                    break;
                default:
                    throw new \Exception('支付方式错误');
            }
            return [
                'config' => $result
            ];
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }


    /**
     * Notes: 支付回调
     * @param $data
     * @return bool
     * @author 段誉(2021/3/22 17:22)
     */
    public function notify($data)
    {
        try {
            $verify = $this->pay->common()->verifyNotify($data);
            $data["verify"] = $verify;
            Db::connect('mongodb')->table('alinotifys')->insert($data);
            if (false === $verify) {
                throw new \Exception('异步通知验签失败');
            }
            if (!in_array($data['trade_status'], ['TRADE_SUCCESS', 'TRADE_FINISHED'])) {
                return true;
            }
            $data['transaction_id'] = $data['trade_no'];
            //验证订单是否已支付
            self::playerNotify($data);

            return true;
        } catch (\Exception $e) {
            $record = [
                __CLASS__,
                __FUNCTION__,
                $e->getFile(),
                $e->getLine(),
                $e->getMessage()
            ];
            Log::write(implode('-', $record));
            $this->setError($e->getMessage());
            return false;
        }
    }

    public function playerNotify($message) {
        $order = Db::connect('mongodb')->table('userrechargeorders')->where("orderSn", $message['out_trade_no'])->find();
        if(empty($order) || $order["status"] == "finish") {
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
        $order = Db::connect('mongodb')->table('userrechargeorders')->where("orderSn", $orderSn)->find();
        $user = Db::connect('mongodb')->table('players')->where("_id", $order["to"])->find();
        $user["gem"] = $user["gem"] + $order["gem"];
        Db::connect('mongodb')->table('players')->where("_id", $order["to"])->update(["gem" => $user["gem"]]);
        Db::connect('mongodb')->table('userrechargeorders')->where("orderSn", $orderSn)->update(["status" => "finish"]);
        Db::connect('mongodb')->table('userrecords')->insert([
            "amount" => $order["price"],
            "relation" => $order["relation"],
            "source" => $order["source"],
            "currency" => $order["currency"],
            "kickback" => $order["kickback"],
            "kickback2" => $order["kickback2"],
            "from" => $order["from"],
            "to" => $order["to"],
            "created" => new UTCDateTime(time() * 1000),
        ]);
        Db::connect('mongodb')->table('diamondrecords')->insert([
            "player" => $order["to"],
            "amount" => $order["gem"],
            "residue" => $user["gem"],
            "type" => 5,
            "note" => "支付宝充值",
            "createAt" => new UTCDateTime(time() * 1000)
        ]);

        if(!empty($order["boxId"]) && !empty($order["boxCount"])) self::playerRechargeHelp($user, $order);
    }

    public static function playerRechargeHelp($user, $order) {
        $treasureBox = Db::connect('mongodb')->table('treasureboxes')->where(["_id" => new ObjectId($order["boxId"])])->find();
        $detail = Db::connect('mongodb')->table('playerhelpdetails')->where(["player" => $user["_id"], "estimateLevel" => intval($treasureBox["star"])])->find();

        if(!empty($detail)) {
            $orderId = reset($detail["_id"]);
            self::updatePlayerRechargeHelp($user, $treasureBox, $detail, $order["boxCount"]);
        } else {
            $orderId = self::insertPlayerRechargeHelp($user, $treasureBox, $order["boxCount"]);
        }

        Db::connect("mongodb")->table('treasureboxrecords')->insert([
            "player" => $user["_id"],
            "shortId" => $user["shortId"],
            "isHelp" => 1,
            "level" => intval($treasureBox["level"]),
            "star" => intval($treasureBox["star"]),
            "type" => 1,
            "orderId" => $orderId,
            "createAt" => new UTCDateTime(time() * 1000)
        ]);
    }

    public static function updatePlayerRechargeHelp($user, $treasureBox, $detail, $boxCount) {
        Db::connect('mongodb')->table('playerhelpdetails')->where(["player" => $user["_id"], "estimateLevel" => intval($treasureBox["star"])])->update([
            "isHelp" => 1,
            "count" => $detail["count"] + intval($boxCount),
            "juCount" => $detail["juCount"],
            "treasureLevel" => intval($treasureBox["level"]),
            "createAt" => new UTCDateTime(time() * 1000)
        ]);
    }

    public static function insertPlayerRechargeHelp($user,  $treasureBox, $boxCount) {
        return Db::connect('mongodb')->table('playerhelpdetails')->insertGetId([
            "player" => $user["_id"],
            "shortId" => intval($user["shortId"]),
            "type" => 1,
            "isHelp" => 1,
            "estimateLevel" => intval($treasureBox["star"]),
            "treasureLevel" => intval($treasureBox["level"]),
            "count" => intval($boxCount),
            "juCount" => intval($treasureBox["juCount"]),
            "juIndex" => 0,
            "coolingcycle" => 0,
            "createAt" => new UTCDateTime(time() * 1000)
        ]);
    }


    /**
     * @notes PC支付
     * @param $order //订单信息
     * @return string
     * @author 段誉
     * @date 2021/7/28 17:34
     */
    public function pagePay($order)
    {
        $domain = request()->domain();
        $result = $this->pay->page()->pay(
            '订单:' . $order['orderSn'],
            $order['orderSn'],
            $order['price'],
            $domain . '/pc/user/order'
        );
        return $result->body;
    }


    /**
     * @notes APP支付
     * @param $order //订单信息
     * @return string
     * @author 段誉
     * @date 2021/7/28 17:34
     */
    public function appPay($order)
    {
        $result = $this->pay->app()->pay(
            $order['orderSn'],
            $order['orderSn'],
            $order['price']
        );
        return $result->body;
    }


    /**
     * @notes 手机网页支付
     * @param $order //订单信息
     * @return string
     * @author 段誉
     * @date 2021/7/28 17:34
     */
    public function wapPay($order)
    {
        $result = $this->pay->wap()->pay(
            '订单:' . $order['orderSn'],
            $order['orderSn'],
            $order['price'],
            "http://h5.hfdsdas.cn/index.html?payStatus=quit",
            "http://h5.hfdsdas.cn/index.html?payStatus=success"
        );
        return $result->body;
    }


    /**
     * @notes 查询订单
     * @param $orderSn
     * @return AlipayTradeQueryResponse
     * @throws \Exception
     * @author 段誉
     * @date 2021/7/28 17:36
     */
    public function checkPay($orderSn)
    {
        return $this->pay->common()->query($orderSn);
    }


    /**
     * @notes 退款
     * @param $orderSn
     * @param $orderAmount
     * @param $outRequestNo
     * @return AlipayTradeRefundResponse
     * @throws \Exception
     * @author 段誉
     * @date 2021/7/28 17:37
     */
    public function refund($orderSn, $orderAmount, $outRequestNo)
    {
        return $this->pay->common()->optional('out_request_no', $outRequestNo)->refund($orderSn, $orderAmount);
    }

    /**
     * @notes 查询退款
     * @author Tab
     * @date 2021/9/13 11:38
     */
    public function queryRefund($orderSn, $refundSn)
    {
        return $this->pay->common()->queryRefund($orderSn, $refundSn);
    }
}

