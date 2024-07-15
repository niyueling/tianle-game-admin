<?php

namespace app\adminapi\logic\data;

use app\common\enum\DefaultEnum;
use app\common\logic\BaseLogic;
use app\common\service\TimeService;
use MongoDB\BSON\Regex;
use MongoDB\BSON\UTCDateTime;
use think\facade\Db;

/**
 * 数据中心
 * Class CenterLogic
 * @package app\adminapi\logic
 */
class CenterLogic extends BaseLogic
{
    /**
     * @notes 房间分析
     * @return array
     * @author Tab
     * @date 2021/9/27 10:31
     */
    public static function trafficAnalysis($params)
    {
        if (empty($params['start_time']) || empty($params['end_time'])) {
            if(empty($params["day"])) $params["day"] = 14;
            // 未传月份，默认获取当前月份数据
            $params['start_time'] = date("Y-m-d H:i:s", strtotime("-" . $params["day"] . " day"));
            $params['end_time'] = date("Y-m-d H:i:s", time());
        }

        $params['cle'] = strtotime($params['end_time']) - strtotime($params['start_time']);
        $params['start_time'] = new UTCDateTime(strtotime($params['start_time']) * 1000);
        $params['end_time'] = new UTCDateTime(strtotime($params['end_time']) * 1000);

        return [
            // 首页数据汇总
            'summary' => self::taSummary($params),
            // 房间图表
            'room' => self::taRoom($params),
        ];
    }

    /**
     * @notes 流量分板 - 首页数据汇总
     * @param $params
     * @return array
     * @author Tab
     * @date 2021/9/27 10:47
     */
    public static function taSummary($params)
    {
        $today = TimeService::today();
        // 累计房间数
        $totalRoomNum = Db::connect('mongodb')->table('roomscorerecords')
            ->where("createAt", "between", [$params["start_time"], $params["end_time"]])->count();

        // 麻将游戏数
        $majiangNum = Db::connect('mongodb')->table('roomscorerecords')->where("category", "majiang")
            ->where("createAt", "between",
                [$params["start_time"], $params["end_time"]])->count();

        return [
            'totalRoomNum' => $totalRoomNum,
            'majiangNum' => $majiangNum,
        ];
    }

    /**
     * @notes 房间分析 - 首页房间量图表数据
     * @param $params
     * @return array
     * @author Tab
     * @date 2021/9/27 11:04
     */
    public static function taRoom($params)
    {
        // 数据存储器
        $data = [];
        $datamajiang = [];
        // 日期存储器
        $date = [];
        $d = floor($params["cle"]/3600/24);
        for($i=0; $i <= $d; $i++) {
            $day = date("Ymd", intval((reset($params["start_time"]) / 1000) +$i*24*60*60));
            $start_time = new UTCDateTime(strtotime($day. ' 00:00:00') * 1000);
            $end_time = new UTCDateTime(strtotime($day. ' 23:59:59') * 1000);
            $date[] = date("md", intval((reset($params["start_time"]) / 1000) +$i*24*60*60));
            $data[] = Db::connect('mongodb')->table('roomrecords')
                ->where("createAt", "between", [$start_time, $end_time])->where("scores", "<>", [])->count();
            $datamajiang[] = Db::connect('mongodb')->table('roomscorerecords')->where("category", "majiang")
                ->where("createAt", "between", [$start_time, $end_time])
                ->where("scores", "<>", [])->count();
        }

        return [
            'date' => $date,
            'list' => [
                ['name' => '房间', 'data' => $data],
                ['name' => '麻将', 'data' => $datamajiang]
            ]
        ];
    }

    /**
     * @notes 用户分析
     * @param $params
     * @return array
     * @author Tab
     * @date 2021/9/27 11:21
     */
    public static function userAnalysis($params)
    {
        if (empty($params['start_time']) || empty($params['end_time'])) {
            if(empty($params["day"])) $params["day"] = 14;
            $params['start_time'] = date("Y-m-d H:i:s", strtotime("-" . $params["day"] . " day"));
            $params['end_time'] = date("Y-m-d H:i:s", time());
        }

        $params['cle'] = strtotime($params['end_time']) - strtotime($params['start_time']);
        $params['start_time'] = new UTCDateTime(strtotime($params['start_time']) * 1000);
        $params['end_time'] = new UTCDateTime(strtotime($params['end_time']) * 1000);

        return [
            'summary' => self::uaSummary($params),
            'user' => self::uaUser($params)
        ];
    }

    /**
     * @notes 用户分析 - 数据汇总
     * @param $params
     * @return array
     * @author Tab
     * @date 2021/9/27 11:27
     */
    public static function uaSummary($params)
    {
        // 用户总人数
        $user = Db::connect('mongodb')->table('players')->count();
        // 新增用户数
        $newUser = Db::connect('mongodb')->table('players')
            ->where('createAt', 'between', [$params['start_time'], $params['end_time']])->count();

        return [
            'user' => $user,
            'new_user' => $newUser
        ];
    }

    /**
     * @notes 用户分析 - 用户总数图表数据
     * @param $params
     * @return array
     * @author Tab
     * @date 2021/9/27 14:52
     */
    public static function uaUser($params)
    {
        // 数据存储器
        $data = [];
        // 日期存储器
        $date = [];
        $d = floor($params["cle"]/3600/24);
        for($i=0; $i <= $d; $i++) {
            $day = date("Ymd", intval((reset($params["start_time"]) / 1000) +$i*24*60*60));
            $start_time = new UTCDateTime(strtotime($day. ' 00:00:00') * 1000);
            $end_time = new UTCDateTime(strtotime($day. ' 23:59:59') * 1000);
            $date[] = date("md", intval((reset($params["start_time"]) / 1000) +$i*24*60*60));
            $data[] = Db::connect('mongodb')->table('players')
                ->where('createAt', 'between', [$start_time, $end_time])->count();
        }

        return [
            'date' => $date,
            'list' => [
                ['name' => '用户总数', 'data' => $data]
            ]
        ];
    }

    /**
     * @notes 充值分析
     * @param $params
     * @return array
     * @author Tab
     * @date 2021/9/27 11:21
     */
    public static function rechargeAnalysis($params)
    {
        if (empty($params['start_time']) || empty($params['end_time'])) {
            if(empty($params["day"])) $params["day"] = 14;
            $params['start_time'] = date("Y-m-d H:i:s", strtotime("-" . $params["day"] . " day"));
            $params['end_time'] = date("Y-m-d H:i:s", time());
        }

        $params['cle'] = strtotime($params['end_time']) - strtotime($params['start_time']);
        $params['start_time'] = new UTCDateTime(strtotime($params['start_time']) * 1000);
        $params['end_time'] = new UTCDateTime(strtotime($params['end_time']) * 1000);

        return [
            'summary' => self::rechargeSummary($params),
            'recharge' => self::totalAnalysis($params)
        ];
    }

    /**
     * @notes 充值分析 - 数据汇总
     * @param $params
     * @return array
     * @author Tab
     * @date 2021/9/27 11:27
     */
    public static function rechargeSummary($params)
    {
        // 微信充值
        $wechat = Db::connect('mongodb')->table('userrechargeorders')
            ->where("created", "between", [$params["start_time"], $params["end_time"]])
            ->where("source", "wechat")->where("status", 1)->sum("price");
        // 后台充值
        $admin = Db::connect('mongodb')->table('diamondrecords')
            ->where("createAt", "between", [$params["start_time"], $params["end_time"]])
            ->where("type", 6)->sum("amount");

        return [
            'total' => $wechat + $admin,
            'wechat' => $wechat,
            'admin' => $admin
        ];
    }

    /**
     * @notes 充值分析 - 累计充值图表数据
     * @param $params
     * @return array
     * @author Tab
     * @date 2021/9/27 14:52
     */
    public static function totalAnalysis($params)
    {
        // 数据存储器
        $data1 = [];
        $data2 = [];
        // 日期存储器
        $date = [];
        $d = floor($params["cle"]/3600/24);
        for($i=0; $i <= $d; $i++) {
            $day = date("Ymd", intval((reset($params["start_time"]) / 1000) + $i * 24 * 60 * 60));
            $start_time = new UTCDateTime(strtotime($day. ' 00:00:00') * 1000);
            $end_time = new UTCDateTime(strtotime($day. ' 23:59:59') * 1000);
            $date[] = date("md", intval((reset($params["start_time"]) / 1000) + $i * 24 * 60 * 60));
            $data1[] = Db::connect('mongodb')->table('userrechargeorders')
                ->where("created", "between", [$start_time, $end_time])
                ->where("source", "wechat")->where("status", 1)->sum("price");
            $data2[] = Db::connect('mongodb')->table('diamondrecords')
                ->where("createAt", "between", [$start_time, $end_time])
                ->where("type", 6)->sum("amount");
        }

        return [
            'date' => $date,
            'list' => [
                ['name' => '微信充值', 'data' => $data1],
                ['name' => '后台充值', 'data' => $data2]
            ]
        ];
    }

    /**
     * @notes 消耗分析
     * @param $params
     * @return array
     * @author Tab
     * @date 2021/9/27 11:21
     */
    public static function consumeAnalysis($params)
    {
        if (empty($params['start_time']) || empty($params['end_time'])) {
            if(empty($params["day"])) $params["day"] = 14;
            $params['start_time'] = date("Y-m-d H:i:s", strtotime("-" . $params["day"] . " day"));
            $params['end_time'] = date("Y-m-d H:i:s", time());
        }

        $params['cle'] = strtotime($params['end_time']) - strtotime($params['start_time']);
        $params['start_time'] = new UTCDateTime(strtotime($params['start_time']) * 1000);
        $params['end_time'] = new UTCDateTime(strtotime($params['end_time']) * 1000);

        return [
            'summary' => self::consumeSummary($params),
            'consume' => self::gemAnalysis($params)
        ];
    }

    /**
     * @notes 消耗分析 - 数据汇总
     * @param $params
     * @return array
     * @author Tab
     * @date 2021/9/27 11:27
     */
    public static function consumeSummary($params)
    {
        // 累计钻石
        $totalGem = Db::connect('mongodb')->table('players')->sum("gem");
        // 累计红包
        $totalRedPacket = Db::connect('mongodb')->table('players')->sum("redPocket");
        // 消耗钻石
        $totalConsume = Db::connect('mongodb')->table('consumerecords')
            ->where("createAt", "between", [$params["start_time"], $params["end_time"]])
            ->where("note", "=", ['$not' => new Regex("wechat", 'i')])
            ->where("note","=", ['$not' => new Regex("后台", 'i')])
            ->where("note", "=", ['$not' => new Regex("礼物", 'i')])
            ->where("note","=", ['$not' => new Regex("公共礼物", 'i')])
            ->where("note","=", ['$not' => new Regex("绑定手机", 'i')])
            ->where("note","=", ['$not' => new Regex("实名认证", 'i')])
            ->where("note","=", ['$not' => new Regex("微信分享", 'i')])
            ->sum("gem");

        $totalConsume += self::calcCreatorRoomConsume($params["start_time"], $params["end_time"]);

        return [
            'totalGem' => $totalGem,
            'totalRedPacket' => round($totalRedPacket / 100, 2),
            'totalConsume' => $totalConsume
        ];
    }

    /**
     * @notes 充值分析 - 累计充值图表数据
     * @param $params
     * @return array
     * @author Tab
     * @date 2021/9/27 14:52
     */
    public static function gemAnalysis($params)
    {
        // 数据存储器
        $data = [];
        // 日期存储器
        $date = [];
        $d = floor($params["cle"]/3600/24);
        for($i=0; $i <= $d; $i++) {
            $day = date("Y-m-d", intval((reset($params["start_time"]) / 1000) + $i*24*60*60));
            $start_time = new UTCDateTime(strtotime($day. ' 00:00:00') * 1000);
            $end_time = new UTCDateTime(strtotime($day. ' 23:59:59') * 1000);
            $date[] = date("md", intval((reset($params["start_time"]) / 1000) +$i*24*60*60));
            $data[] = Db::connect('mongodb')->table('consumerecords')
                ->where("createAt", "between", [$start_time, $end_time])
                ->where("note", "=", ['$not' => new Regex("wechat", 'i')])
                ->where("note","=", ['$not' => new Regex("后台", 'i')])
                ->where("note", "=", ['$not' => new Regex("礼物", 'i')])
                ->where("note","=", ['$not' => new Regex("公共礼物", 'i')])
                ->where("note","=", ['$not' => new Regex("绑定手机", 'i')])
                ->where("note","=", ['$not' => new Regex("实名认证", 'i')])
                ->where("note","=", ['$not' => new Regex("微信分享", 'i')])
                ->sum("gem");

            $data[$i] += self::calcCreatorRoomConsume($start_time, $end_time);
        }

        return [
            'date' => $date,
            'list' => [
                ['name' => '累计消耗', 'data' => $data]
            ]
        ];
    }

    /**
     * @notes 访客分析
     * @param $params
     * @return array
     * @author Tab
     * @date 2021/9/27 11:21
     */
    public static function visitorAnalysis($params)
    {
        if (empty($params['start_time']) || empty($params['end_time'])) {
            if(empty($params["day"])) $params["day"] = 14;
            $params['start_time'] = date("Y-m-d H:i:s", strtotime("-" . $params["day"] . " day"));
            $params['end_time'] = date("Y-m-d H:i:s", time());
        }

        $params['cle'] = strtotime($params['end_time']) - strtotime($params['start_time']);
        $params['start_time'] = new UTCDateTime(strtotime($params['start_time']) * 1000);
        $params['end_time'] = new UTCDateTime(strtotime($params['end_time']) * 1000);

        return [
            'summary' => self::visitorSummary($params),
            'visitor' => self::visitor15($params)
        ];
    }

    /**
     * @notes 访客分析 - 数据汇总
     * @param $params
     * @return array
     * @author Tab
     * @date 2021/9/27 11:27
     */
    public static function visitorSummary($params)
    {
        $params['start_time'] = new UTCDateTime(strtotime(date('Y-m-d', time()) . ' 00:00:00') * 1000);
        $params['end_time'] = new UTCDateTime(strtotime(date('Y-m-d', time()) . ' 23:59:59') * 1000);
        //累计昨日访问量
        $totalCount = Db::connect('mongodb')->table('useractivitylogs')
            ->where("day", "between", [$params['start_time'], $params['end_time']])->sum("count");
        $majiang = Db::connect('mongodb')->table('useractivitylogs')->where("category", "majiang")
            ->where("day", "between", [$params['start_time'], $params['end_time']])->sum("count");
        $zhadan = Db::connect('mongodb')->table('useractivitylogs')->where("category", "zhadan")
            ->where("day", "between", [$params['start_time'], $params['end_time']])->sum("count");
        $biaofen = Db::connect('mongodb')->table('useractivitylogs')->where("category", "biaofen")
            ->where("day", "between", [$params['start_time'], $params['end_time']])->sum("count");
        $paodekuai = Db::connect('mongodb')->table('useractivitylogs')->where("category", "paodekuai")
            ->where("day", "between", [$params['start_time'], $params['end_time']])->sum("count");
        $shisanshui = Db::connect('mongodb')->table('useractivitylogs')->where("category", "shisanshui")
            ->where("day", "between", [$params['start_time'], $params['end_time']])->sum("count");

        return [
            'totalCount' => $totalCount,
            'majiang' => $majiang,
            'zhadan' => $zhadan,
            'biaofen' => $biaofen,
            'paodekuai' => $paodekuai,
            'shisanshui' => $shisanshui,
        ];
    }

    /**
     * @notes 近15天访客数
     * @return mixed
     * @author Tab
     * @date 2021/9/10 18:51
     */
    public static function visitor15($params)
    {
        $datamajiang = [];
        $datazhadan = [];
        $datapaodekuai = [];
        $databiaofen = [];
        $datashisanshui = [];
        $date = [];
        $d = floor($params["cle"]/3600/24);

        for($i=0; $i <= $d; $i++) {
            $day = date("Ymd", intval((reset($params["start_time"]) / 1000) +$i*24*60*60));
            $start_time = new UTCDateTime(strtotime($day. ' 00:00:00') * 1000);
            $end_time = new UTCDateTime(strtotime($day. ' 23:59:59') * 1000);
            $date[] = date("md", intval((reset($params["start_time"]) / 1000) +$i*24*60*60));
            $datamajiang[] = Db::connect('mongodb')->table('useractivitylogs')->where("category", "majiang")
                ->where('day', 'between', [$start_time, $end_time])->sum("count");
            $databiaofen[] = Db::connect('mongodb')->table('useractivitylogs')->where("category", "biaofen")
                ->where('day', 'between', [$start_time, $end_time])->sum("count");
            $datapaodekuai[] = Db::connect('mongodb')->table('useractivitylogs')->where("category", "paodekuai")
                ->where('day', 'between', [$start_time, $end_time])->sum("count");
            $datashisanshui[] = Db::connect('mongodb')->table('useractivitylogs')->where("category", "shisanshui")
                ->where('day', 'between', [$start_time, $end_time])->sum("count");
            $datazhadan[] = Db::connect('mongodb')->table('useractivitylogs')->where("category", "zhadan")
                ->where('day', 'between', [$start_time, $end_time])->sum("count");
        }
        return [
            'date' => $date,
            'list' => [
                ['name' => '麻将', 'data' => $datamajiang],
                ['name' => '标分', 'data' => $databiaofen],
                ['name' => '跑得快', 'data' => $datapaodekuai],
                ['name' => '十三水', 'data' => $datashisanshui],
                ['name' => '炸弹', 'data' => $datazhadan]
            ]
        ];
    }

    //计算战队房间扣除战队主的钻石
    public static function calcCreatorRoomConsume($start_time, $end_time) {
        $consumeGem = 0;
        $roomlists =  Db::connect('mongodb')->table('roomrecords')
            ->where("createAt", "between", [$start_time, $end_time])
            ->where("club", "<>", null)
            ->select();

        foreach ($roomlists as $value) {
            if(in_array($value["category"], ["zhadan"])) $consumeGem += $value["rule"]["juShu"] / 2;
            if(in_array($value["category"], ["biaofen", "majiang"])) $consumeGem += $value["rule"]["juShu"] / 4;
            if(in_array($value["category"], ["paodekuai"])) $consumeGem += $value["rule"]["juShu"] / 3;
            if(in_array($value["category"], ["shisanshui"])) {
                if(in_array($value["rule"]["juShu"], [6, 12])) $consumeGem += $value["rule"]["juShu"] / 6;
                if(in_array($value["rule"]["juShu"], [24])) $consumeGem += $value["rule"]["juShu"] / 8;
            }
        }
        return $consumeGem;
    }
}
