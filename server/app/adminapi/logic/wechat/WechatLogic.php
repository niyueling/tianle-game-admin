<?php

namespace app\adminapi\logic\wechat;

use app\common\enum\DefaultEnum;
use app\common\enum\OfficialAccountEnum;
use app\common\logic\BaseLogic;
use app\common\service\ConfigService;
use app\common\service\WeChatConfigService;
use app\common\service\WeChatService;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Exceptions\BadRequestException;
use EasyWeChat\Kernel\Exceptions\Exception;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Request;

/**
 * 微信逻辑层
 * Class WechatLogic
 * @package app\shopapi\logic
 */
class WechatLogic extends BaseLogic
{
    /**
     * @notes 微信JSSDK授权接口
     * @param $params
     * @return array|false|string
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @author Tab
     * @date 2021/8/30 19:20
     */
    public static function jsConfig($params)
    {
        try {
            $config = WeChatConfigService::getOaConfig();
            $app = Factory::officialAccount($config);
            $url = urldecode($params['url']);
            $app->jssdk->setUrl($url);
            $apis = [
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                'onMenuShareQQ',
                'onMenuShareWeibo',
                'onMenuShareQZone',
                'openLocation',
                'getLocation',
                'chooseWXPay',
                'updateAppMessageShareData',
                'updateTimelineShareData',
                'openAddress',
                'scanQRCode'
            ];

            $data = $app->jssdk->getConfigArray($apis, $debug = false, $beta = false);
            return $data;
        } catch (Exception |\think\Exception $e) {
            self::setError('公众号配置出错:' . $e->getMessage());
            return false;
        }
    }

    /**
     * @notes 获取微信请求code的链接
     * @param $url
     * @return string
     * @author cjhao
     * @date 2021/7/31 14:28
     */
    public function codeUrl(string $url)
    {
        return WeChatService::getCodeUrl($url);
    }

    /**
     * @notes 公众号登录
     * @param $params
     * @return array|bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author cjhao
     * @date 2021/8/2 17:54
     */
    public function oaLogin(array $params)
    {
        //微信调用
        try {
            //通过code获取微信 openid
            $response = WeChatService::getOaResByCode($params);

            //绑定openid
            $player = Db::connect('mongodb')->table('players')->where("_id", $response["unionid"])->find();

            if(!empty($player) && empty($player["openid"])) {
                Db::connect('mongodb')->table('players')->where("_id", $response["unionid"])->
                update(["openid" => $response["openid"], "headImgUrl" => $response["headimgurl"], "name" => $response["nickname"]]);
            }

            return $response;

        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }

    }

    /**
     * @notes 更新sessionKey
     * @param $params
     * @return array
     * @author cjhao
     * @date 2021/8/2 17:54
     */
    public static function updateWxGameSessionKey(array $params)
    {
        //获取网页登录access_token
        $accountSetting = WeChatConfigService::getWechatConfigByTerminal(1);
        if(empty($accountSetting["app_id"]) || empty($accountSetting["secret"])) return ["code" => false, "msg" => "请配置appid和secret"];
        $url = "https://api.weixin.qq.com/sns/jscode2session?grant_type=authorization_code&appid={$accountSetting['app_id']}&secret={$accountSetting['secret']}&js_code={$params['code']}";
        $data = curl_get($url);

        if(!empty($data["errcode"])) return ["code" => false, "msg" => $data["errmsg"]];

        Db::connect('mongodb')->table('players')->where("_id", $data["unionid"])->update([
            "sessionKey" => $data["session_key"]
        ]);

        Db::connect('mongodb')->table('mnpsessionkeylogs')->insert([
            "player" => $data["unionid"],
            "sessionKey" => $data["session_key"],
            "createAt" => new UTCDateTime(time() * 1000)
        ]);

        return ["code" => true, "msg" => "更新成功", "data" => [ "sessionKey" => $data["session_key"]]];

    }

    /**
     * @notes 微信公众号回调
     * @throws BadRequestException
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws \ReflectionException
     * @author Tab
     * @date 2021/7/30 11:58
     */
    public static function index()
    {
        // 确认此次GET请求来自微信服务器，原样返回echostr参数内容，接入生效，成为开发者成功
        if(isset($_GET['echostr'])) {
            echo $_GET['echostr'];
            exit;
        }

        $officialAccountSetting = WeChatConfigService::getWechatConfigByTerminal(1);
        $config = [
            'app_id' => $officialAccountSetting['app_id'],
            'secret' => $officialAccountSetting['secret'],
            'response_type' => 'array',
        ];
        $app = Factory::officialAccount($config);

        $app->server->push(function ($message) {
            $message["createAt"] = new UTCDateTime(time() * 1000);
            $id = Db::connect('mongodb')->table('wechatmessageinfos')->insertGetId($message);

            switch ($message['MsgType']) { // 消息类型
                case OfficialAccountEnum::MSG_TYPE_MINIPROGRAMPAGE: // 事件
                    if(!empty($message['Title'])) {
                        //发送客服消息给用户
                        $accessToken = ConfigService::get('wechat', 'MnpAccessToken');
                        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$accessToken";
                        $data = [
                            "touser" => $message['FromUserName'],
                            "msgtype" => "link",
                            "link" => [
                                "title" => "天乐麻将充值",
                                "description" => "点击进行充值，获取更多好礼",
                                "url" => "https://phpadmin.tianle.fanmengonline.com/mobile/#/bundle/pages/user_recharge/user_recharge",
                                "thumb_url" => "https://phpadmin.tianle.fanmengonline.com/uploads/images/tianle.png"
                            ]
                        ];

                        curl_post($url, $data);

                        Db::connect('mongodb')->table('wechatmessageinfos')->where("_id", new ObjectId($id))->update([
                            "isSend" => true,
                            "data" => $data
                        ]);
                    }

                    break;

                case OfficialAccountEnum::MSG_TYPE_EVENT:
                    switch ($message['Event']) {
                        case OfficialAccountEnum::EVENT_TYPE_MINIGAME_DELIVER_GOODS:
                            $player = Db::connect('mongodb')->table('players')->where("openid", $message["MiniGame"]["ToUserOpenid"])->find();

                            if(!empty($player)) {
                                foreach ($message["MiniGame"]["GoodsList"] as $good) {
                                    Db::connect('mongodb')->table('mails')->insert([
                                        "type" => 'gift',
                                        "state" => 1,
                                        "giftState" => 1,
                                        "to" => reset($player["_id"]),
                                        "title" => DefaultEnum::GiftTitles[$message["MiniGame"]["GiftId"]],
                                        "content" => "恭喜你领取了" . DefaultEnum::GiftTitles[$message["MiniGame"]["GiftId"]] . ',获得' . self::getGiftContent($good),
                                        "gift" => [
                                            "diamond" => $good["Id"] == "game_gift_2" ? $good["Num"] : 0,
                                            "gold" => $good["Id"] == "game_gift_3" ? $good["Num"] * 10000 : 0
                                        ],
                                        "createAt" => new UTCDateTime(time() * 1000)
                                    ]);
                                }
                            }

                            echo json_encode([
                                "ErrCode" => 0,
                                "ErrMsg" => "Success"
                            ]);
                            break;

//                        case OfficialAccountEnum::EVENT_TYPE_MINIGAME_RECHARGE:
//                            //发送客服消息给用户
//                            $accessToken = ConfigService::get('wechat', 'MnpAccessToken');
//                            $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$accessToken";
//                            $data = [
//                                "touser" => $message['FromUserName'],
//                                "msgtype" => "link",
//                                "link" => [
//                                    "title" => "天乐麻将充值",
//                                    "description" => "点击进行充值，获取更多好礼",
//                                    "url" => "https://phpadmin.tianle.fanmengonline.com/mobile/#/bundle/pages/user_recharge/user_recharge",
//                                    "thumb_url" => "https://phpadmin.tianle.fanmengonline.com/uploads/images/tianle.png"
//                                ]
//                            ];
//                            $res = curl_post($url, $data);
//
//                            Db::connect('mongodb')->table('wechatmessageinfos')->where("_id", new ObjectId($id))->update([
//                                "isSend" => true,
//                                "sendResponse" => $res
//                            ]);
//                            break;
                    }
                    break;
            }
        });
        $response = $app->server->serve();
//        $response->send();
    }

    public static function getGiftContent($good) {
        if ($good["Id"] == "game_gift_2") return "{$good["Num"]}钻石";
        if ($good["Id"] == "game_gift_3") return ($good["Num"] * 10000) . "金豆";
    }

    /**
     * @notes 苹果回调
     * @throws BadRequestException
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws \ReflectionException
     * @author Tab
     * @date 2021/7/30 11:58
     */
    public static function verifyApplePayInApp($params)
    {
        $prodVerify = 'https://buy.itunes.apple.com/verifyReceipt';
        $sandboxVerify = 'https://sandbox.itunes.apple.com/verifyReceipt';
        $isValid = false;

        $res = curl_post($prodVerify, [ 'receipt-data' => $params["receiptData"] ]);
        if ($res["status"] === 21007) {
            // 沙盒验证
            $res = curl_post($sandboxVerify, [ 'receipt-data' => $params["receiptData"] ]);
            $isValid = false;
        }

        if ($res["status"] === 21004) {
            // 错误密钥
            return ["code" => false, "msg" => "苹果密钥错误"];
        }

        if ($res["status"] === 0 && $res["receipt"]["bundle_id"] === env("app.bundleid")) {
            $thirdOrderNo = $res["receipt"]["in_app"][0]["transaction_id"];
            $data = self::playerNotify($params, $thirdOrderNo);
            if(!$data["code"]) return $data;

            return ["code" => true, "data"=> [ "status" => $res["status"], "receipt" => $res["receipt"], "isValid" => $isValid, "notifyData" => $data["data"] ]];
        } else {
            return ["code" => false, "msg" => "支付失败", "data" => $res];
        }
    }

    public static function playerNotify($params, $thirdOrderNo) {
        $order = Db::connect('mongodb')->table('userrechargeorders')->where("_id", new ObjectId($params['orderId']))->find();
        if(empty($order) || $order["status"] == "finish") {
            return ["code" => false, "msg" => "订单已经支付成功"];
        }

        // 查找支付凭据是否使用
        $isExists = Db::connect('mongodb')->table('userrechargeorders')->where("transactionId", $thirdOrderNo)->count();
        if ($isExists) {
            // 已经用过了
            return ["code" => false, "msg" => "重复购买"];
        }

        $res = self::playerRecharge($params['orderId'], $thirdOrderNo);

        //通知游戏
        self::noticeGame($res["data"]);

        return $res;
    }

    public static function noticeGame($params) {
        $redis = Cache::store('redis')->handler();

        // 在需要的地方发布消息到redis频道
        $message = [
            'payload' => [
                'playerId' => $params["playerId"]
            ],
            'cmd' => 'paySuccess'
        ];

        $redis->publish('adminChannelToDating', json_encode($message));
    }

    /**
     * @notes 充值成功回调
     * @param $orderSn
     * @param array $extra
     * @author Tab
     * @date 2021/8/11 14:43
     */
    public static function playerRecharge($orderSn, $thirdOrderNo)
    {
        $order = Db::connect('mongodb')->table('userrechargeorders')->where("_id", new ObjectId($orderSn))->find();
        $user = Db::connect('mongodb')->table('players')->where("_id", $order["to"])->find();
        $user["gem"] = $user["gem"] + $order["gem"];
        Db::connect('mongodb')->table('players')->where("_id", $order["to"])->update(["gem" => $user["gem"]]);
        Db::connect('mongodb')->table('userrechargeorders')->where("_id", new ObjectId($orderSn))->update(["status" => "finish", "transactionId" => $thirdOrderNo]);
        Db::connect('mongodb')->table('userrecords')->insert([
            "amount" => $order["price"],
            "relation" => $order["relation"],
            "source" => $order["source"],
            "currency" => $order["currency"],
            "kickback" => $order["kickback"],
            "kickback2" => $order["kickback2"],
            "transactionId" => $thirdOrderNo,
            "from" => $order["from"],
            "to" => $order["to"],
            "created" => new UTCDateTime(time() * 1000),
        ]);
        Db::connect('mongodb')->table('diamondrecords')->insert([
            "player" => $order["to"],
            "amount" => $order["gem"],
            "residue" => $user["gem"],
            "type" => 5,
            "note" => "微信充值",
            "createAt" => new UTCDateTime(time() * 1000)
        ]);

        if(!empty($order["boxId"]) && !empty($order["boxCount"])) self::playerRechargeHelp($user, $order);
        return ["code" => true, "data" => ["orderId" => $orderSn, "thirdOrderNo" => $thirdOrderNo, "gem" => $order["gem"], "playerId" => $user["_id"]]];
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
     * @notes 网页登录
     * @throws BadRequestException
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws \ReflectionException
     * @author Tab
     * @date 2021/7/30 11:58
     */
    public static function webLogin($params)
    {
        //获取网页登录access_token
        $officialAccountSetting = WeChatConfigService::getWechatConfigByTerminal(6);
        if(empty($officialAccountSetting["app_id"]) || empty($officialAccountSetting["secret"])) return ["code" => false, "msg" => "请配置appid和secret"];

        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?grant_type=authorization_code&appid={$officialAccountSetting['app_id']}&secret={$officialAccountSetting['secret']}&code={$params['code']}";
        $data = curl_get($url);

        if(!empty($data["errcode"])) return ["code" => false, "msg" => $data["errmsg"]];

        //获取网页登录用户信息
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$data["access_token"]}&openid={$data["openid"]}&lang=zh_CN";
        $userinfo = curl_get($url);

        if(!empty($userinfo["errcode"])) return ["code" => false, "msg" => $userinfo["errmsg"]];

        return ["code" => true, "data" => $userinfo];
    }

    /**
     * @notes 公众号登录
     * @param $params
     * @return array|bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author cjhao
     * @date 2021/8/2 17:54
     */
    public static function officalAccountLogin(array $params)
    {
        //通过code获取微信 openid
        $config = WeChatConfigService::getOaConfig();
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?grant_type=authorization_code&appid={$config['app_id']}&secret={$config['secret']}&code={$params['code']}";
        $response = curl_get($url);
        if(!empty($response["errcode"])) return ["code" => false, "msg" => $response["errmsg"]];

        //获取网页登录用户信息
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$response["access_token"]}&openid={$response["openid"]}&lang=zh_CN";
        $userinfo = curl_get($url);

        if(!empty($userinfo["errcode"])) return ["code" => false, "msg" => $userinfo["errmsg"]];

        return ["code" => true, "data" => $userinfo];
    }

    /**
     * @notes 虚拟支付回调
     * @throws BadRequestException
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws \ReflectionException
     * @author Tab
     * @date 2021/7/30 11:58
     */
    public static function wxGameNotify($params)
    {
        // 1. 通过订单号获取订单
        $order = Db::connect('mongodb')->table('userrechargeorders')->where("_id", new ObjectId($params["orderId"]))->find();
        if (empty($order) || $order["status"] == "finish") return ["code" => false, "msg" => "订单不存在或订单已支付"];

        // 2. 获取用户openid
        $player = Db::connect('mongodb')->table('players')->where("_id", $order["to"])->find();
        if(empty($player) || empty($player["miniOpenid"]) || empty($player["sessionKey"])) return ["code" => false, "msg" => "用户不存在，请先小游戏授权"];

        $accessToken = ConfigService::get('wechat', 'MnpAccessToken');
        $appKey = ConfigService::get('wxgame', 'appkey');

        $postBody = [
          "openid" => $player["miniOpenid"],
          "offer_id" => ConfigService::get('wxgame', 'offerid') . '',
          "ts" => time(),
          "zone_id" => ConfigService::get('wxgame', 'zoneid') . '',
          "env" => intval($params["env"]),
          "user_ip" => Request::ip()
        ];

        // 3. 生成登录态签名和支付请求签名
        $signature =  hash_hmac('sha256', json_encode($postBody), $player["sessionKey"]);
        $needSignMsg = "/wxa/game/getbalance&" . json_encode($postBody);
        $paySign = hash_hmac('sha256', $needSignMsg, $appKey);

        // 4. 查询用户游戏币余额
        $balanceUrl = "https://api.weixin.qq.com/wxa/game/getbalance?access_token={$accessToken}&signature={$signature}&sig_method=hmac_sha256&pay_sig={$paySign}";
        $res = curl_post($balanceUrl, $postBody);

        if ($res["errcode"] != 0) return ["code" => false, "msg" => $res["errmsg"]];
        if ($res["balance"] < $order["price"] * 10) return ["code" => false, "msg" => "用户游戏币不足", "data" => $res];

        // 5. 扣除游戏币
        $payBody = [
            "openid" => $player["miniOpenid"],
            "offer_id" => ConfigService::get('wxgame', 'offerid') . '',
            "ts" => time(),
            "zone_id" => ConfigService::get('wxgame', 'zoneid') . '',
            "env" => intval($params["env"]),
            "user_ip" => Request::ip(),
            "amount" => $order["price"] * 10,
            "bill_no" => $params["orderId"]
        ];

        $sign =  hash_hmac('sha256', json_encode($payBody), $player["sessionKey"]);
        $needSign = "/wxa/game/pay&" . json_encode($payBody);
        $paySig = hash_hmac('sha256', $needSign, $appKey);

        $payUrl = "https://api.weixin.qq.com/wxa/game/pay?access_token={$accessToken}&signature={$sign}&sig_method=hmac_sha256&pay_sig={$paySig}";
        $response = curl_post($payUrl, $payBody);

        if ($response["errcode"] != 0) return ["code" => false, "msg" => $response["errmsg"]];

        $data = self::playerRecharge($params["orderId"], $response["bill_no"]);
        if(!$data["code"]) return $data;

        //通知游戏
        self::noticeGame(["playerId" => $order["to"]]);

        return ["code" => true, "data"=> $response];
    }
}
