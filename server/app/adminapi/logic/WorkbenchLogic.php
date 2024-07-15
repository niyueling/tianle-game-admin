<?php

namespace app\adminapi\logic;

use app\common\logic\BaseLogic;
use app\common\service\ConfigService;
use app\common\service\FileService;
use app\common\service\TimeService;
use MongoDB\BSON\Regex;
use MongoDB\BSON\UTCDateTime;
use think\facade\Db;

/**
 * 工作台
 * Class WorkbenchLogic
 * @package app\adminapi\logic
 */
class WorkbenchLogic extends BaseLogic
{
    /**
     * @notes 工作台
     * @return int[]
     * @author Tab
     * @date 2021/9/10 14:12
     */
    public static function index($adminInfo, $get)
    {
        // 基本信息
        $shopInfo = self::shopInfo($adminInfo);
        // 今日数据
        $today = self::today();
        // 统计数据
        $pending = self::pending();
        // 近15日充值
        $business15 = self::business15($get);
        // 近15日访客数
        $visitor15 = self::visitor15($get);
        // 近15日房间数
        $room15 = self::room15($get);

        return [
            'shop_info' => $shopInfo,
            'today' => $today,
            'pending' => $pending,
            'business15' => $business15,
            'visitor15' => $visitor15,
            'room15' => $room15
        ];
    }

    /**
     * @notes 管理员信息
     * @param $adminInfo
     * @return array
     * @author Tab
     * @date 2021/9/10 15:02
     */
    public static function shopInfo($adminInfo)
    {
        $name = ConfigService::get('shop', 'name');
        $logo = ConfigService::get('shop', 'logo');
        $logo = FileService::getFileUrl($logo);
        // 管理员名称
        $adminName = $adminInfo['username'];

        return [
            'name' => $name,
            'logo' => $logo,
            'admin_name' => $adminName,
        ];
    }

    /**
     * @notes 今日数据
     * @return array
     * @author Tab
     * @date 2021/9/10 17:41
     */
    public static function today()
    {
        $today_start_time = new UTCDateTime(strtotime(date("Y/m/d") . " 00:00:00") * 1000);
        $today_end_time = new UTCDateTime(strtotime(date("Y/m/d") . " 23:59:59") * 1000);
        $today_user_num = Db::connect('mongodb')
            ->table('players')->where("createAt", "between", [$today_start_time, $today_end_time])->count();
        $today_room_num = Db::connect('mongodb')
            ->table('roomrecords')->where("createAt", "between", [$today_start_time, $today_end_time])->count();
        $recharge_amount = Db::connect('mongodb')
            ->table('userrechargeorders')->where("source", "wechat")
            ->where("created", "between", [$today_start_time, $today_end_time])->where("status", 1)->sum("price");
        $today_visit_num = Db::connect('mongodb')->table('useractivitylogs') ->where('day', 'between', [$today_start_time, $today_end_time])->sum("count");
        return [
            'today_user_num' => $today_user_num,
            'today_room_num' => $today_room_num,
            'recharge_amount' => $recharge_amount,
            'today_visitor_num' => $today_visit_num,
        ];
    }

    /**
     * @notes 待办事项
     * @return array
     * @author Tab
     * @date 2021/9/10 17:57
     */
    public static function pending()
    {
        $today = TimeService::today();
        $yestoday = TimeService::yesterday();
        $today_start_time = new UTCDateTime($today[0] * 1000);
        $today_end_time = new UTCDateTime($today[1] * 1000);
        $yestoday_start_time = new UTCDateTime($yestoday[0] * 1000);
        $yestoday_end_time = new UTCDateTime($yestoday[1] * 1000);

        //新增用户
        $add_user_today_num = Db::connect('mongodb')
            ->table('players')->where("createAt", "between", [$today_start_time, $today_end_time])->count();
        $add_user_yestoday_num = Db::connect('mongodb')
            ->table('players')->where("createAt", "between", [$yestoday_start_time, $yestoday_end_time])->count();

        //访问量
        $user_visit_today_num = Db::connect('mongodb')
            ->table('useractivitylogs')->where("day", "between", [$today_start_time, $today_end_time])->sum("count");
        $user_visit_yestoday_num = Db::connect('mongodb')
            ->table('useractivitylogs')->where("day", "between", [$yestoday_start_time, $yestoday_end_time])->sum("count");

        //开房数
        $add_room_today_num = Db::connect('mongodb')
            ->table('roomrecords')->where("createAt", "between", [$today_start_time, $today_end_time])->count();
        $add_room_yestoday_num = Db::connect('mongodb')
            ->table('roomrecords')->where("createAt", "between", [$yestoday_start_time, $yestoday_end_time])->count();

        //在线充值
        $online_recharge_today_num = Db::connect('mongodb')
            ->table('userrechargeorders')->where("created", "between", [$today_start_time, $today_end_time])
            ->where("source", "wechat")->where("status", 1)->sum("price");
        $online_recharge_yestoday_num = Db::connect('mongodb')
            ->table('userrechargeorders')->where("created", "between", [$yestoday_start_time, $yestoday_end_time])
            ->where("source", "wechat")->where("status", 1)->sum("price");

        //后台充值
        $back_recharge_today_num = Db::connect('mongodb')
            ->table('diamondrecords')->where("createAt", "between", [$today_start_time, $today_end_time])
            ->where("type", 6)->sum("amount");
        $back_recharge_yestoday_num = Db::connect('mongodb')
            ->table('diamondrecords')->where("createAt", "between", [$yestoday_start_time, $yestoday_end_time])
            ->where("type", 6)->sum("amount");

        //金豆充值
        $gold_recharge_today_num = Db::connect('mongodb')
            ->table('goldrecords')->where("createAt", "between", [$today_start_time, $today_end_time])
            ->where("type", 40)->sum("amount");
        $gold_recharge_yestoday_num = Db::connect('mongodb')
            ->table('goldrecords')->where("createAt", "between", [$yestoday_start_time, $yestoday_end_time])
            ->where("type", 40)->sum("amount");

        //绑定手机
        $bind_phone_today_num = Db::connect('mongodb')
            ->table('consumerecords')->where("createAt", "between", [$today_start_time, $today_end_time])
            ->where("note", "like", "绑定手机")
            ->sum("gem");
        $bind_phone_yestoday_num = Db::connect('mongodb')
            ->table('consumerecords')->where("createAt", "between", [$yestoday_start_time, $yestoday_end_time])
            ->where("note", "like", fuzzy_query("绑定手机"))->sum("gem");

        //实名认证
        $auth_today_num = Db::connect('mongodb')
            ->table('consumerecords')->where("createAt", "between", [$today_start_time, $today_end_time])
            ->where("note", "like", "实名认证")->sum("gem");
        $auth_yestoday_num = Db::connect('mongodb')
            ->table('consumerecords')->where("createAt", "between", [$yestoday_start_time, $yestoday_end_time])
            ->where("note", "like", "实名认证")->sum("gem");

        //微信分享
        $wechat_share_today_num = Db::connect('mongodb')
            ->table('consumerecords')->where("createAt", "between", [$today_start_time, $today_end_time])
            ->where("note", "like", "微信分享")->sum("gem");
        $wechat_share_yestoday_num = Db::connect('mongodb')
            ->table('consumerecords')->where("createAt", "between", [$yestoday_start_time, $yestoday_end_time])
            ->where("note", "like", "微信分享")->sum("gem");

        return [
            'add_user' => [
                'today_num' => $add_user_today_num,
                'yestoday_num' => $add_user_yestoday_num,
                'grap_num' => round($add_user_today_num - $add_user_yestoday_num, 2)
            ],
            'add_room' => [
                'today_num' => $add_room_today_num,
                'yestoday_num' => $add_room_yestoday_num,
                'grap_num' => round($add_room_today_num - $add_room_yestoday_num, 2)
            ],
            'visit' => [
                'today_num' => $user_visit_today_num,
                'yestoday_num' => $user_visit_yestoday_num,
                'grap_num' => round($user_visit_today_num - $user_visit_yestoday_num, 2)
            ],
            'online_recharge' => [
                'today_num' => $online_recharge_today_num,
                'yestoday_num' => $online_recharge_yestoday_num,
                'grap_num' => round($online_recharge_today_num - $online_recharge_yestoday_num, 2)
            ],
            'back_recharge' => [
                'today_num' => $back_recharge_today_num,
                'yestoday_num' => $back_recharge_yestoday_num,
                'grap_num' => round($back_recharge_today_num - $back_recharge_yestoday_num, 2)
            ],
            'bind_mobile' => [
                'today_num' => $bind_phone_today_num,
                'yestoday_num' => $bind_phone_yestoday_num,
                'grap_num' => round($bind_phone_today_num - $bind_phone_yestoday_num, 2)
            ],
            'auth' => [
                'today_num' => $auth_today_num,
                'yestoday_num' => $auth_yestoday_num,
                'grap_num' => round($auth_today_num - $auth_yestoday_num, 2)
            ],
            'wechat_share' => [
                'today_num' => $wechat_share_today_num,
                'yestoday_num' => $wechat_share_yestoday_num,
                'grap_num' => round($wechat_share_today_num - $wechat_share_yestoday_num, 2)
            ],

            'gold_recharge' => [
                'today_num' => $gold_recharge_today_num,
                'yestoday_num' => $gold_recharge_yestoday_num,
                'grap_num' => round($gold_recharge_today_num - $gold_recharge_yestoday_num, 2)
            ],
        ];
    }

    /**
     * @notes 近15天营业额
     * @return array
     * @author Tab
     * @date 2021/9/10 18:06
     */
    public static function business15($params)
    {
        if(empty($params["day"]))$params["day"] = 14;
        $params['start_time'] = date("Y-m-d H:i:s", strtotime('-' . $params["day"] . 'day'));
        $params['end_time'] = date("Y-m-d H:i:s", time());
        $params['cle'] = strtotime($params['end_time']) - strtotime($params['start_time']);
        $params['start_time'] = new UTCDateTime(strtotime($params['start_time']) * 1000);
        $params['end_time'] = new UTCDateTime(strtotime($params['end_time']) * 1000);
        $dataUserWechatRecharge = [];
        $dataUserAdminRecharge = [];
        $dataAgencyWechatRecharge = [];
        $dataAgencyAdminRecharge = [];
        $date = [];
        $d = floor($params["cle"]/3600/24);

        for($i=0; $i <= $d; $i++) {
            $day = date("Ymd", intval((reset($params["start_time"]) / 1000) +$i*24*60*60));
            $start_time = new UTCDateTime(strtotime($day. ' 00:00:00') * 1000);
            $end_time = new UTCDateTime(strtotime($day. ' 23:59:59') * 1000);
            $date[] = date("md", intval((reset($params["start_time"]) / 1000) +$i*24*60*60));;
            $dataUserWechatRecharge[] = Db::connect('mongodb')->table('userrecords')->where("source", "wechat")
                ->where("created", "between", [$start_time,$end_time])->sum("amount");
            $dataUserAdminRecharge[] = Db::connect('mongodb')->table('userrecords')->where("source", "admin")
                ->where("created", "between", [$start_time,$end_time])->sum("amount");
            $dataAgencyWechatRecharge[] = Db::connect('mongodb')->table('gmextrecords')->where("source", "wechat")
                ->where("status", "finish")->where("createAt", "between", [$start_time,$end_time])->sum("price");
            $dataAgencyAdminRecharge[] = Db::connect('mongodb')->table('records')
                ->where("created", "between", [$start_time,$end_time])->sum("amount");
        }

        return [
            'date' => $date,
            'list' => [
                ['name' => '用户微信充值', 'data' => $dataUserWechatRecharge],
                ['name' => '用户后台充值', 'data' => $dataUserAdminRecharge],
                ['name' => '代理自助充值', 'data' => $dataAgencyWechatRecharge],
                ['name' => '代理后台充值', 'data' => $dataAgencyAdminRecharge],
            ]
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
        if(empty($params["day"]))$params["day"] = 14;
        $params['start_time'] = date("Y-m-d H:i:s", strtotime('-' . $params["day"] . 'day'));
        $params['end_time'] = date("Y-m-d H:i:s", time());
        $params['cle'] = strtotime($params['end_time']) - strtotime($params['start_time']);
        $params['start_time'] = new UTCDateTime(strtotime($params['start_time']) * 1000);
        $params['end_time'] = new UTCDateTime(strtotime($params['end_time']) * 1000);
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
            $date[] = date("md", intval((reset($params["start_time"]) / 1000) +$i*24*60*60));;
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

    /**
     * @notes 近15天房间数
     * @return mixed
     * @author Tab
     * @date 2021/9/10 18:51
     */
    public static function room15($params)
    {
        if(empty($params["day"]))$params["day"] = 14;
        $params['start_time'] = date("Y-m-d H:i:s", strtotime('-' . $params["day"] . 'day'));
        $params['end_time'] = date("Y-m-d H:i:s", time());
        $params['cle'] = strtotime($params['end_time']) - strtotime($params['start_time']);
        $params['start_time'] = new UTCDateTime(strtotime($params['start_time']) * 1000);
        $params['end_time'] = new UTCDateTime(strtotime($params['end_time']) * 1000);
        $data = [];
        $date = [];
        $d = floor($params["cle"]/3600/24);

        for($i=0; $i <= $d; $i++) {
            $day = date("Ymd", intval((reset($params["start_time"]) / 1000) +$i*24*60*60));
            $start_time = new UTCDateTime(strtotime($day. ' 00:00:00') * 1000);
            $end_time = new UTCDateTime(strtotime($day. ' 23:59:59') * 1000);
            $date[] = date("md", intval((reset($params["start_time"]) / 1000) +$i*24*60*60));
            $data[] = Db::connect('mongodb')->table('roomrecords')
                ->where('createAt', 'between', [$start_time, $end_time])
                ->where("scores", "<>", [])->count();
        }
        return [
            'date' => $date,
            'list' => [
                ['name' => '房间统计', 'data' => $data]
            ]
        ];
    }
}
