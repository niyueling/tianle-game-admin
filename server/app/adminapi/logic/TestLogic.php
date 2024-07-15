<?php

namespace app\adminapi\logic;

use app\common\logic\BaseLogic;
use app\common\service\ConfigService;
use app\common\service\TimeService;
use app\common\service\WeChatConfigService;
use MongoDB\BSON\UTCDateTime;
use think\facade\Cache;
use think\facade\Request;
use think\helper\Str;
use think\facade\Db;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class TestLogic extends BaseLogic
{
    /**
     * @notes 测试更新用户补助信息
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public static function userHelperList()
    {
        $totalCount = Db::connect('mongodb')->table('players')->count();
        // 每批次处理的记录数
        $pagesize = 50;
        // 计算需要分成多少批次处理
        $pagecount = ceil($totalCount / $pagesize);
        Db::connect('mongodb')->table('playerhelps')->where("totalJu", ">=", 0)->delete();

        for ($page = 1; $page <= $pagecount; $page++) {
            $players = Db::connect('mongodb')->table('players')->page($page, $pagesize)->select()->toArray();
            foreach ($players as $key => $value) {
                TestLogic::updateUserHelper($value);
            }
        }

        return [];
    }

    /**
     * @notes 计算局数
     * @param $order
     * @author 段誉
     * @date 2021/9/15 14:32
     */
    protected static function updateUserHelper($params)
    {
        $rateRule = Db::connect('mongodb')->table('ratetemplate')->select()->toArray();

        foreach($rateRule as $key => $value) {
            $where = [];
            $limit = $value["juCount"];
            $where[] = ["category", "=", $value["game"]];
            $params['start_time'] = date("Y-m-d H:i:s", strtotime("-" . $value["day"] . " day"));
            $params['end_time'] = date("Y-m-d H:i:s", time());
            $params['cle'] = strtotime($params['end_time']) - strtotime($params['start_time']);
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];

            $where[] = ["players", "in", $params["_id"]];
            $rateRule[$key]["totalJu"] = Db::connect('mongodb')->table('roomrecords')->where($where)->count();
            if($rateRule[$key]["totalJu"] == 0) continue;
            $roomRecords = Db::connect('mongodb')->table('roomrecords')->where($where)->order("createAt", "desc")->limit($limit)->select();
            $rateRule[$key]["juRate"] = 0;
            $rateRule[$key]["winJu"] = 0;
            $rateRule[$key]["failJu"] = 0;
            $rateRule[$key]["winJuScore"] = 0;
            $rateRule[$key]["failJuScore"] = 0;
            $rateRule[$key]["totalJuScore"] = 0;
            $rateRule[$key]["juMinRate"] = 0;
            $rateRule[$key]["winMinJu"] = 0;
            $rateRule[$key]["failMinJu"] = 0;
            $rateRule[$key]["winMinJuScore"] = 0;
            $rateRule[$key]["failMinJuScore"] = 0;
            $rateRule[$key]["totalMinJuScore"] = 0;
            $rateRule[$key]["totalMinJu"] = 0;
            $rateRule[$key]["isHelp"] = 0;
            $rateRule[$key]["estimate"] = 0;

            foreach ($roomRecords as $k => $v) {
                foreach ($v["scores"] as $k1 => $v1) {
                    if(!empty($v1) && $v1["shortId"] == $params["shortId"]) {
                        $v1["score"] > 0 ? ++$rateRule[$key]["winJu"] : ++$rateRule[$key]["failJu"];
                        $v1["score"] > 0 ? $rateRule[$key]["winJuScore"] += $v1["score"] : $rateRule[$key]["failJuScore"] += $v1["score"];
                        $rateRule[$key]["totalJuScore"] += $v1["score"];
                    }
                }

                $games = Db::connect('mongodb')->table('gamerecords')->field("record, playersInfo")->where("room", $v["room"])->select();
                foreach ($games as $k2 => $v2) {
                    foreach ($v2["record"] as $k3 => $v3) {
                        if(empty($v3) || empty($v2["playersInfo"][$k3]) || empty($v2["playersInfo"][$k3]["model"]["shortId"]) ||
                            $v2["playersInfo"][$k3]["model"]["shortId"] != $params["shortId"]) continue;
                        $v3["score"] > 0 ? ++$rateRule[$key]["winMinJu"] : ++$rateRule[$key]["failMinJu"];
                        $v3["score"] > 0 ? $rateRule[$key]["winMinJuScore"] += $v3["score"] : $rateRule[$key]["failMinJuScore"] += $v3["score"];
                        $rateRule[$key]["totalMinJuScore"] += $v3["score"];
                        $rateRule[$key]["totalMinJu"]++;
                    }
                }
            }

            if(count($roomRecords) > 0) {
                $rateRule[$key]["juRate"] = round($rateRule[$key]["winJu"] / count($roomRecords), 2);
            }

            //判断局数,胜率,输局积分是否满足条件
            if($rateRule[$key]["totalJu"] >= intval($rateRule[$key]["juCount"]) && $rateRule[$key]["juRate"] <= $rateRule[$key]["juRank"]
                && abs($rateRule[$key]["failJuScore"]) >= intval($rateRule[$key]["juMinScore"])) $rateRule[$key]["isHelp"] = 1;

            if($rateRule[$key]["totalMinJu"] > 0) {
                $rateRule[$key]["juMinRate"] = round($rateRule[$key]["winMinJu"] / $rateRule[$key]["totalMinJu"], 2);
            }

            $rateRule[$key]["rateLevel"] = Db::connect('mongodb')->table('ratelevels')
                ->where("level", ">=", $rateRule[$key]["minLevel"])->where("level", "<=", $rateRule[$key]["maxLevel"])
                ->order("createAt", "desc")->limit($limit)->select();
            $rateRule[$key]["scorestr"] = $rateRule[$key]["juMinScore"] . '~' . $rateRule[$key]["juMaxScore"];
            $rateRule[$key]["levelstr"] = $rateRule[$key]["minLevel"] . '~' . $rateRule[$key]["maxLevel"];
            $rateRule[$key]["estimateLevel"] = 0;

            if($rateRule[$key]["isHelp"] == 1) {
                //计算预估救助等级
                $level = $rateRule[$key]["maxLevel"] - $rateRule[$key]["minLevel"];
                $score = round(($rateRule[$key]["juMaxScore"] - $rateRule[$key]["juMinScore"]) / $level, 2);
                $userScore = round(abs($rateRule[$key]["failJuScore"]) - $rateRule[$key]["juMinScore"], 2);

                if($userScore < $score) $rateRule[$key]["estimateLevel"] = $rateRule[$key]["minLevel"];
                else {
                    $calcLevel = $rateRule[$key]["minLevel"] += floor(($userScore / $score));
                    $rateRule[$key]["estimateLevel"] = $calcLevel >= $rateRule[$key]["maxLevel"] ? $rateRule[$key]["maxLevel"] : $calcLevel;
                }
            }

            if($rateRule[$key]["totalJu"] > 0 && $rateRule[$key]["isHelp"] == 1) {
                Db::connect('mongodb')->table('playerhelps')->insert([
                    "player" => $params["_id"],
                    "shortId" => $params["shortId"],
                    "totalJu" => $rateRule[$key]["totalJu"],
                    "juRate" => $rateRule[$key]["juRate"],
                    "winJu" => $rateRule[$key]["winJu"],
                    "failJu" => $rateRule[$key]["failJu"],
                    "totalJuScore" => $rateRule[$key]["totalJuScore"],
                    "winJuScore" => $rateRule[$key]["winJuScore"],
                    "failJuScore" => $rateRule[$key]["failJuScore"],
                    "totalMinJu" => $rateRule[$key]["totalMinJu"],
                    "minJuRate" => $rateRule[$key]["juMinRate"],
                    "winMinJu" => $rateRule[$key]["winMinJu"],
                    "failMinJu" => $rateRule[$key]["failMinJu"],
                    "totalMinJuScore" => $rateRule[$key]["totalMinJuScore"],
                    "winMinJuScore" => $rateRule[$key]["winMinJuScore"],
                    "failMinJuScore" => $rateRule[$key]["failMinJuScore"],
                    "ruleId" => reset($rateRule[$key]["_id"]),
                    "isHelp" => $rateRule[$key]["isHelp"],
                    "estimateLevel" => intval($rateRule[$key]["estimateLevel"]),
                    "createAt" => new UTCDateTime(time() * 1000)
                ]);
            }
        }
    }

    /**
     * @notes 获取4王列表
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public static function getGameHaveFourJoker($params)
    {
        $yestoday_arr = TimeService::day($params["day"]);
        $where = [["type", "=", "zhadan"]];

        if(!empty($yestoday_arr[0]) && !empty($yestoday_arr[1])) {
            $where[] = ["time", "between", [
                new UTCDateTime($yestoday_arr[0] * 1000),
                new UTCDateTime($yestoday_arr[1] * 1000)
            ]];
        }
        Db::connect('mongodb')->table('gamefourjokerrecords')->where("dayId", "=", date("Ymd", $yestoday_arr[0]))->delete();

        // 每批次处理的记录数
        $pagesize = 500;
        // 获取符合条件的记录总数
        $total = Db::connect('mongodb')->table('gamerecords')->where($where)->count();
        // 计算需要分成多少批次处理
        $pagecount = ceil($total / $pagesize);

        for ($page = 1; $page <= $pagecount; $page++) {
            $gamelists = Db::connect('mongodb')->table('gamerecords')->where($where)->page($page, $pagesize)->select();

            foreach ($gamelists as $key => $value) {
                $isHaveFourJoker = false;
                $shortId = '';
                $joker = 0;
                $cards = [];
                foreach ($value["events"] as $key1 => $value1) {
                    if ($value1["type"] == "shuffle") {
                        $jokerCount = 0;
                        foreach ($value1["info"]["cards"] as $card) {
                            if (in_array($card["value"], [16, 17])) $jokerCount++;
                        }
                        if ($jokerCount >= 4) {
                            $isHaveFourJoker = true;
                            $shortId = $value["states"][$value1["index"]]["model"]["shortId"];
                            $joker = $jokerCount;
                            $cards = $value1["info"]["cards"];
                            break;
                        }
                    }
                }
                if (!$isHaveFourJoker) {
                    continue;
                }
                $insertData = [
                    "shortId" => $shortId,
                    "room" => $value["room"],
                    "type" => $value["type"],
                    "juShu" => $value["juShu"],
                    "juCount" => $value["game"]["rule"]["juShu"],
                    "jokerNum" => $joker,
                    "jokerCount" => $value["game"]["rule"]["jokerCount"],
                    "todayJuCount" => $total,
                    "dayId" => date('Ymd', intval(reset($value["time"]) / 1000)),
                    "cards" => $cards,
                    "roomId" => $value["game"]["roomId"],
                    "gameId" => reset($value["_id"]),
                    "gameTime" => new UTCDateTime(reset($value["time"])),
                    "createAt" => new UTCDateTime(time() * 1000)
                ];
                $isExist = Db::connect('mongodb')->table('gamefourjokerrecords')->where("gameId", reset($value["_id"]))->count();
                if(!$isExist) {
                    Db::connect('mongodb')->table('gamefourjokerrecords')->insertGetId($insertData);
                }
            }
        }

        return [];
    }

    /**
     * @notes 测试邮件报警
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public static function mailNotice()
    {
        $mailslists = [];
        $open_mail_notice = ConfigService::get('shop', 'open_mail_notice');
        $mail_1 = ConfigService::get('shop', 'mail_1');
        $mail_2 = ConfigService::get('shop', 'mail_2');
        $mail_3 = ConfigService::get('shop', 'mail_3');

        if(empty($open_mail_notice)) return "未开放报警功能";
        if(!empty($mail_1)) array_push($mailslists, $mail_1);
        if(!empty($mail_2)) array_push($mailslists, $mail_2);
        if(!empty($mail_3)) array_push($mailslists, $mail_3);

        //判断是否有提现异常记录
        $res = self::checkWithdrawRecord(15);
        if(!$res["flag"]) {
            self::addSystemWarmLogs($res["flag"], $res["logLists"], "无需报警", false, "", "");
            return "无需报警";
        }

        // 实例化 PHPMailer
        $mail = new PHPMailer(true);
        try {
            // 邮件服务器设置
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = config('mail.host');
            $mail->SMTPAuth = true;
            $mail->Username = config('mail.username');
            $mail->Password = config('mail.password');
            $mail->SMTPSecure = config('mail.secure');
            $mail->CharSet = 'UTF-8';
            $mail->Port = config('mail.port');
            $mail->setFrom(config('mail.username'),config('mail.from_name'));
            $mail->Subject = '提现记录警报';
            $mail->Body    = '检测到红包提现异常,详细原因如下：';
            foreach ($res["logLists"] as $log)
                if($log["flag"]) $mail->Body .= "昵称：" . $log["name"] . "(" . $log["shortId"] . ")" .
                    "报错原因：" . $log["state"] . "提现金额:" . intval($log["amountInFen"] / 100) . ',';

            foreach ($mailslists as $address) {
                $mail->addAddress($address);
                $mail->send();
                self::addSystemWarmLogs($res["flag"], $res["logLists"], "邮件报警成功", true, $mail->Subject, $mail->Body);
            }

            return "邮件报警成功";
        } catch (Exception $e) {
            self::addSystemWarmLogs($res["flag"], $res["logLists"], '邮件发送失败：' . $mail->ErrorInfo, false, $mail->Subject, $mail->Body);
            return ['邮件发送失败：' . $mail->ErrorInfo];
        }
    }

    public static function addSystemWarmLogs($flag, $logLists, $info, $state, $subject, $body) {
        Db::connect('mongodb')->table('systemwarns')->insert([
            "flag" => $flag,
            "subject" => $subject,
            "body" => $body,
            "recordlists" => $logLists,
            "createAt" => new UTCDateTime(time() * 1000),
            "state" => $state,
            "info" => $info
        ]);
    }

    public static function checkWithdrawRecord($minutes) {
        //获取上次报警时间
        $warn = Db::connect('mongodb')->table('systemwarns')->order("createAt", "desc")->limit(1)->select()->toArray();
        $flag = false;
        $start_time = !empty($warn) ? intval(reset($warn[0]["createAt"]) / 1000) : time() - 60 * 60 * 24;
        $end_time = time();

        $logsList = Db::connect('mongodb')->table('redpocketwithdrawrecords')
            ->where("state","403")->where("createAt", "between",
                [new UTCDateTime($start_time * 1000),
                new UTCDateTime($end_time * 1000)])->select()->toArray();

        foreach ($logsList as $key => $value) {
            $player = Db::connect('mongodb')->table('players')->where("_id", $value["player"])->find();
            $logsList[$key]["shortId"] = $player["shortId"];
            $logsList[$key]["name"] = $player["name"];
            if(empty($player) || (!empty($player["warnTime"]) && time() < $player["warnTime"])) {
                $logsList[$key]["flag"] = false;
                continue;
            }

            $logsList[$key]["flag"] = true;
            $flag = true;
            Db::connect('mongodb')->table('players')->where("_id", $value["player"])->update(["warnTime" => time() + 60 * $minutes]);
        }

        return ["flag" => $flag, "logLists" => $logsList];
    }

    public static function getWechatAccessToken() {
        $officialAccountSetting = WeChatConfigService::getWechatConfigByTerminal(1);
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$officialAccountSetting['app_id']}&secret={$officialAccountSetting['secret']}";

        $data = curl_get($url);
        Cache::set("wechatAccessToken", $data["access_token"]);

        return $data;
    }

    public static function wechatMessageSend($get) {
        $accessToken = ConfigService::get('wechat', 'MnpAccessToken');
        $arr = explode(":", $get['Title']);
        $arr[1] = trim($arr[1]);
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$accessToken}";
        $data = [
            "touser" => "oa1Cs4o0Dp-31RU6NFcglHE1pBJg",
            "msgtype" => "link",
            "link" => [
                "title" => "天乐麻将充值",
                "description" => "充值订单号：{$arr[1]},点击进行充值",
                "url" => "https://phpadmin.tianle.fanmengonline.com/mobile/#/bundle/pages/user_recharge/user_recharge?order_id={$arr[1]}",
                "thumb_url" => "https://phpadmin.tianle.fanmengonline.com/uploads/images/tianle.png"
            ]
        ];

        $res = curl_post($url, $data);
        return $res;
    }

    /**
     * @notes  解散房间
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public static function dissolveManyRoom($params)
    {
        $rooms = Db::connect('mongodb')->table('roomrecords')
            ->where("createAt", "<", new UTCDateTime(strtotime("2023-04-01 00:00:00") * 1000))
            ->where("roomState", "normal")->select();

        foreach ($rooms as $room) {
            $redis = new \Redis();
            $redis->connect(env('CACHE.REDISHOST'), env('CACHE.REDISPORT'));
            $redis->select(env('CACHE.REDISSELECT'));

            // 在需要的地方发布消息到redis频道
            $message = [
                'payload' => [
                    'roomId' => intval($room["roomNum"]),
                    'gameType' => $room["category"]
                ],
                'cmd' => 'dissolveRoom'
            ];

            $redis->publish('adminChannelToGame', json_encode($message));
        }

        return ["code" => true, "msg" => "操作成功"];
    }

    /**
     * @notes  清除用户补助时间
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public static function clearUserHelp()
    {
        Db::connect('mongodb')->table('players')->where("shortId", ">", 0)->update([
            "isHelp" => false, "coolingcycle" => 0, "helpTime" => null
        ]);

        return ["code" => true, "msg" => "操作成功"];
    }

    /**
     * @notes  用户注册
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public static function regist()
    {
        try {
            $redis = new \Redis();
            $redis->connect(env('CACHE.REDISHOST'), env('CACHE.REDISPORT'));
            $redis->select(env('CACHE.REDISSELECT'));

            // 在需要的地方发布消息到redis频道
            $message = [
                'payload' => [
                    "unionid" => Str::random(28),
                    "name" => "周周周周周",
                    "headImgUrl" => "https://thirdwx.qlogo.cn/mmopen/vi_32/BpHRyr9ffcn2ShUsTO6zXm1WT3mpY7lTMOdGVwKBxwbkibtKFq0gAibIIy2ibHcOf9ebnWRh21eExLL4PFdjkjSHA/132",
                    "phone" => "18506920590"
                ],
                'cmd' => 'createNewPlayer'
            ];

            $redis->publish('adminChannelToDating', json_encode($message));
        } catch (\RedisException $e) {
            return ["code" => false, "msg" => $e->getMessage()];
        }

        return ["code" => true, "msg" => "操作成功"];
    }

    /**
     * @notes  增加游戏币
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public static function present($params)
    {
        $player = Db::connect('mongodb')->table('players')->where(["_id" => $params["userId"]])->find();
        $payBody = [
            "openid" => $player["miniOpenid"],
            "offer_id" => ConfigService::get('wxgame', 'offerid') . '',
            "ts" => time(),
            "zone_id" => '1',
            "env" => intval($params["env"]),
            "user_ip" => Request::ip(),
            "amount" => intval($params["price"]),
            "bill_no" => generate_sn("userrechargeorders", "orderSn", 18)
        ];

        $accessToken = ConfigService::get('wechat', 'MnpAccessToken');
        $appKey = "O6NMpYOInbMvIRDzVuqSQasNAGXZ322B";
        $sign =  hash_hmac('sha256', json_encode($payBody), $player["sessionKey"]);
        $needSign = "/wxa/game/present&" . json_encode($payBody);
        $paySig = hash_hmac('sha256', $needSign, $appKey);

        $payUrl = "https://api.weixin.qq.com/wxa/game/present?access_token={$accessToken}&signature={$sign}&sig_method=hmac_sha256&pay_sig={$paySig}";
        $response = curl_post($payUrl, $payBody);

        if ($response["errcode"] != 0) return ["code" => false, "msg" => $response["errmsg"]];

        return ["code" => true, "msg" => "操作成功", "data" => $response];
    }

    /**
     * @notes  录入商城靓号数据
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public static function beautyNumber($params)
    {

        $numberIds = [];

        for ($i = 1; $i <= 6; $i++) {
            for ($j = 1; $j <= 9; $j++) {
                for ($k = 1; $k <= 9; $k++) {
                    if ($k == $j) {
                        continue;
                    }

                    if ($i == 1) {
                        $numberIds[] = "$j$k$k$k$k$k";
                    }
                    if ($i == 2) {
                        $numberIds[] = "$k$j$k$k$k$k";
                    }
                    if ($i == 3) {
                        $numberIds[] = "$k$k$j$k$k$k";
                    }
                    if ($i == 4) {
                        $numberIds[] = "$k$k$k$j$k$k";
                    }
                    if ($i == 5) {
                        $numberIds[] = "$k$k$k$k$j$k";
                    }
                    if ($i == 6) {
                        $numberIds[] = "$k$k$k$k$k$j";
                    }
                }
            }
        }

        foreach ($numberIds as $id) {
            Db::connect('mongodb')->table('goodsbeautynumbers')->insert([
                "numberId" => $id,
                "price" => 500,
                "originalPrice" => 999,
                "currency" => "diamond",
                "createAt" => new UTCDateTime(time() * 1000),
            ]);
        }

        return ["code" => true, "msg" => "操作成功", "data" => $numberIds];
    }
}
