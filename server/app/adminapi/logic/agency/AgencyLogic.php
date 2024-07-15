<?php

namespace app\adminapi\logic\agency;
use app\common\enum\DefaultEnum;
use app\common\logic\BaseLogic;
use app\common\service\pay\WeChatPayService;
use MongoDB\BSON\UTCDateTime;
use think\facade\Db;
use \MongoDB\BSON\ObjectId;

/**
 * 用户逻辑层
 * Class UserLogic
 * @package app\adminapi\logic\user
 */
class AgencyLogic extends BaseLogic
{
     /**
     * @notes  代理列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function lists($params)
    {
        $where = [];

        if(!empty($params["username"])) $where[] = ["username", "like", fuzzy_query($params["username"])];
        if(!empty($params["disable"])) $where[] = ["disable", "=", $params["disable"]];
        if(!empty($params["role"])) $where[] = ["role", "=", $params["role"]];
        $count = Db::connect('mongodb')->table('gms')->where($where)->count();
        $adminList = Db::connect('mongodb')->table('gms')->where($where)->order("spendGem", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($adminList as $key => $value) {
            $adminList[$key]["_id"] = reset($adminList[$key]["_id"]);
            $adminList[$key]["is_bind_club"] = !empty($adminList[$key]["club_id"]) && count($adminList[$key]["club_id"]) > 0 ? 1 : 0;
            if(!empty($adminList[$key]["login_time"])) $adminList[$key]["login_time"] = date("Y-m-d H:i:s", $adminList[$key]["login_time"]);
        }
        return [
            "count" => $count,
            "lists" => $adminList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes  成员审核列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function requestLists($params)
    {
        $where = [];
        $playerIds = [];
        $isSearch = false;

        if(!empty($params["realName"])) {
            $isSearch = true;
            $accounts = Db::connect('mongodb')->table('accounts')
                ->where("realName", 'like', fuzzy_query($params["realName"]))->select();
            if(!empty($accounts)) {
                foreach($accounts as $item) array_push($playerIds, $item["player"]);
            }
        }

        if(!empty($params["playerName"])) {
            $isSearch = true;
            $players = Db::connect('mongodb')->table('players')
                ->where("name", 'like', fuzzy_query($params["playerName"]))->select();
            if(!empty($players)) {
                foreach($players as $item) array_push($playerIds, $item["_id"]);
            }
        }

        if(!empty($params["playerPhone"])) {
            $isSearch = true;
            $player = Db::connect('mongodb')->table('players')
                ->where("phone", 'like', fuzzy_query($params["playerPhone"]))->find();
            if(!empty($player) && !in_array($player["_id"], $playerIds)) array_push($playerIds, $player["_id"]);
        }

        if(!empty($params["playerShortId"])){
            $isSearch = true;
            $player = Db::connect('mongodb')->table('players')->where("shortId", intval($params["playerShortId"]))->find();
            if(!empty($player) && !in_array($player["_id"], $playerIds)) array_push($playerIds, $player["_id"]);
        }

        if(empty($playerIds) && $isSearch) return [
            "count" => 0,
            "lists" => [],
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];

        if(!empty($playerIds)) $where[] = ["playerId", "in", $playerIds];
        $gm = Db::connect('mongodb')->table('gms')->where("_id", new ObjectId($params["_id"]))->find();
        foreach ($gm["club_id"] as $key => $value)  $gm["club_id"][$key] = intval($gm["club_id"][$key]);
        $where[] = ["clubShortId", "in", $gm["club_id"]];

        $count = Db::connect('mongodb')->table('clubrequests')->where($where)->count();
        $requestList = Db::connect('mongodb')->table('clubrequests')
            ->where($where)
            ->page($params["page_no"], $params["page_size"])->select()->all();

        try {
            foreach ($requestList as $key => $value) {
                $player = Db::connect('mongodb')->table('players')->where("shortId", intval($requestList[$key]["playerShortId"]))->find();
                if(!empty($player["phone"])) $requestList[$key]["phone"] =  $player["phone"];
                $requestList[$key]["gold"] =  $player["gold"];
                $requestList[$key]["gem"] =  $player["gem"];
                $requestList[$key]["redPocket"] =  round($player["redPocket"] / 100, 2);
                $requestList[$key]["isTourist"] =  $player["isTourist"];
                $requestList[$key]["_id"] = reset($requestList[$key]["_id"]);
                if(!empty($requestList[$key]["createAt"])) $requestList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($requestList[$key]["createAt"]) / 1000));
            }
        } catch(\Exception $e) {
            var_dump($e);
        }

        return [
            "count" => $count,
            "lists" => $requestList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 用户详情
     * @param int $userId
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/18 15:52
     */
    public function detail(string $userId)
    {
        $admin = Db::connect('mongodb')->table('gms')->where("_id", new ObjectId($userId))->find();
        if(!empty($admin["login_time"])) $admin["login_time"] = date("Y-m-d H:i:s", $admin["login_time"]);
        //基本信息
        $data = [
            'user_info'          => [//用户信息
                '_id'               => reset($admin["_id"]),
                'username'          => $admin['username'],
                'login_time'        => $admin['login_time'] ?? "",
                'role'              => $admin['role'],   //钱包金额
                'gem'               => $admin['gem'],         //可用金额
                'gold'              => $admin['gold'],      //可提现金额
                'num'               => 0,      //积分
                'disable'           => $admin['disable'] ?? 0,            //禁用状态
                'spendGem'          => $admin["spendGem"],
                'spendGold'         => $admin["spendGold"],
                'last_login_ip'     => $admin["last_login_ip"] ?? "",
                'partner'           => $admin["partner"] ?? "0",
                'club_ids'          => !empty($admin["club_id"]) && is_array($admin["club_id"]) ? implode(",", $admin["club_id"]) : ""
            ]

        ];
        return $data;
    }

    /**
     * @notes 成员审核
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public function setRequestInfo(array $params):string
    {
        $request = Db::connect('mongodb')->table('clubrequests')->where("_id", new ObjectId($params["id"]))->find();
        if(empty($request)) return "申请记录不存在";

        if($params["status"] == 1) {
            $club = Db::connect('mongodb')->table('clubs')->where("shortId", intval($request["clubShortId"]))->find();

            if(empty($club)) return "俱乐部不存在";
            if($club["state"] != "on") return "俱乐部已解散";

            $player = Db::connect('mongodb')->table('players')->where("shortId", intval($request["playerShortId"]))->find();
            if(empty($player)) return "成员不存在";

            $clubmerber = Db::connect('mongodb')->table('clubmembers')->where("member", $player["_id"])
                ->where("club", $club["_id"])->find();
            if(!empty($clubmerber)) return "该成员已经加入俱乐部，请勿重复操作";

            Db::connect('mongodb')->table('clubmembers')->insert([
                "club" => $club["_id"],
                "clubGold" => 0,
                "member" => $player["_id"],
                "joinAt" => new UTCDateTime(strtotime(date("Y/m/d H:m:s", time())) * 1000)
            ]);
        }

        Db::connect('mongodb')->table('clubrequests')->where("_id", new ObjectId($params['id']))->delete();

        return "success";

    }

    /**
     * @notes 更新代理信息
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public function setUserInfo(array $params):bool
    {
        $admin = Db::connect('mongodb')->table('gms')->where("_id", new ObjectId($params["user_id"]))->find();
        if($params['field'] == "pass") $params["value"] = create_password($params["value"], env('project.unique_identification'));
        if($params['field'] == "club_id") {
            if(empty($admin["club_id"]) || !is_array($admin["club_id"])) $admin["club_id"] = [];
            array_push($admin["club_id"], $params["value"]);
            $params["value"] = $admin["club_id"];
        }

        Db::connect('mongodb')->table('gms')->where("_id", new ObjectId($params['user_id']))->update([$params['field']=>$params['value']]);

        return true;
    }

    /**
     * @notes 代理充值
     * @param array $params
     * @return string
     * @author cjhao
     * @date 2021/9/10 18:15
     */
    public function adjustUserWallet(array $params, $adminId)
    {
        $admin = Db::connect('mongodb')->table('gms')->where("_id", new ObjectId($params['user_id']))->find();

        switch ($params['type']){
            case 1:
                //增加
                if(1 == $params['action']){
                    //调整钻石
                    Db::connect('mongodb')->table('gms')->where("_id", new ObjectId($params['user_id']))->update(["gem" => $admin["gem"] + $params['num']]);
                }else{
                    Db::connect('mongodb')->table('gms')->where("_id", new ObjectId($params['user_id']))->update(["gem" => $admin["gem"] - $params['num']]);
                }

                break;
            case 2:
                //增加
                if(1 == $params['action']){
                    Db::connect('mongodb')->table('gms')->where("_id", new ObjectId($params['user_id']))->update(["gold" => $admin["gold"] + $params['num']]);
                }else{
                    Db::connect('mongodb')->table('gms')->where("_id", new ObjectId($params['user_id']))->update(["gold" => $admin["gold"] - $params['num']]);
                }

                break;
        }

        Db::connect('mongodb')->table('records')->insert([
            "amount" => intval($params['num']),
            "relation" => $admin["relation"] ?? [],
            "from" => new ObjectId($adminId),
            "to" => new ObjectId($params['user_id']),
            "type" => intval($params["recharge_type"]),
            "created" => new UTCDateTime(time() * 1000)
        ]);

        //获取代理充值金额
        $rechargeLists = Db::connect('mongodb')->table('records')->where("to", new ObjectId($params['user_id']))
        ->where("type", 1)->select();
        $rechargeOnlineAmount = Db::connect('mongodb')->table('gmextrecords')->where("gm", new ObjectId($params['user_id']))
            ->where("status", 'finish')->sum("price");

        foreach ($rechargeLists as $item) $rechargeOnlineAmount += DefaultEnum::GMPRICES[$item["amount"]];
        $gmLevel = self::checkGmLevel($rechargeOnlineAmount);
        if (empty($admin['level']) || $admin['level'] < $gmLevel) {
            Db::connect('mongodb')
                ->table('gms')
                ->where('_id', new ObjectId($params['user_id']))
                ->update(['level' => $gmLevel]);

            // 绑定勋章
            if (!empty($admin['club_id']) && is_array($admin['club_id'])) {
                foreach ($admin['club_id'] as $club_id) self::bindPlayerMedal($club_id, $gmLevel, $rechargeOnlineAmount, $params['num']);
            }
        }

        return true;
    }

    public static function bindPlayerMedal($club_id, $level, $amount, $num) {
        $club = Db::connect('mongodb')->table('clubs')->where("shortId", intval($club_id))->find();
        if(empty($club)) return ;

        $player = Db::connect('mongodb')->table('players')->where("_id", $club["owner"])->find();
        if(empty($player)) return ;

        $medal = Db::connect('mongodb')->table('playermedals')
            ->where("playerId", $club["owner"])->where("medalType", 2)->find();

        if(empty($medal)) {
            self::addPlayerMedal($player, $level, $amount, $num);
        } else {
            self::updatePlayerMedal($club, $level, $amount, $num);
        }

        self::addPlayerMedalRecord($player, $level, $amount, $num);
    }

    public static function addPlayerMedalRecord($player, $level, $amount, $num) {
        Db::connect('mongodb')->table('playermedalrecords')->insert([
            "playerId" => $player["_id"],
            "shortId" => intval($player["shortId"]),
            "medalType" => 2,
            "level" => $level,
            "achievement" => [
                "totalRechargeAmount" => $amount,
                "rechargeAmount" => DefaultEnum::GMPRICES[intval($num)],
                "upgrade_time" => new UTCDateTime(time() * 1000)
            ],
            "gameType" => "recharge",
            "createAt" => new UTCDateTime(time() * 1000)
        ]);
    }

    public static function updatePlayerMedal($club, $level, $amount, $num) {
        Db::connect('mongodb')->table('playermedals')
            ->where("playerId", $club["owner"])->where("medalType", 2)->update([
                "level" => $level,
                "achievement" => [
                    "totalRechargeAmount" => $amount,
                    "rechargeAmount" => DefaultEnum::GMPRICES[intval($num)],
                    "upgrade_time" => new UTCDateTime(time() * 1000)
                ],
                "updateAt" => new UTCDateTime(time() * 1000)
            ]);
    }

    public static function addPlayerMedal($player, $level, $amount, $num) {
        Db::connect('mongodb')->table('playermedals')->insert([
            "playerId" => $player["_id"],
            "shortId" => intval($player["shortId"]),
            "medalType" => 2,
            "level" => $level,
            "achievement" => [
                "totalRechargeAmount" => $amount,
                "rechargeAmount" => DefaultEnum::GMPRICES[intval($num)],
                "upgrade_time" => new UTCDateTime(time() * 1000)
            ],
            "gameType" => "recharge",
            "updateAt" => new UTCDateTime(time() * 1000)
        ]);
    }

    public static function checkGmLevel($amount) {
        if($amount < 1000) return 0;
        if($amount < 10000) return 1;
        if($amount < 50000) return 2;
        if($amount < 100000) return 3;
        else return 4;
    }

    /**
     * @notes 添加代理
     * @author Tab
     * @date 2021/9/13 19:33
     */
    public static function addAgency($params)
    {
        $admin = Db::connect('mongodb')->table('gms')->where("username", $params['username'])->find();
        if(!empty($admin)) return "管理员已存在，请勿重复新增";

        Db::connect('mongodb')->table('gms')->insert([
            "role" => $params['role'],
            "username" => $params['username'],
            "pass" => create_password($params["pass"], env('project.unique_identification')),
            "gold" => 0,
            "gem" => 0,
            "spendGold" => 0,
            "inviteCode" => generate_code(),
            "spendGem" => 0,
            "root" => 0,
            "disable" => 1,
            "club_id" => [],
            "partner" => true,
            "relation" => []
        ]);

        return "success";
    }

    /**
     * @notes 用户详情
     * @param int $userId
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/18 15:52
     */
    public static function getRechargeTemplate(string $adminId)
    {
        $admin = Db::connect('mongodb')->table('gms')->where("_id", new ObjectId($adminId))->find();
        if(empty($admin["level"])) $admin["level"] = 0;

        $rechargeLists = Db::connect('mongodb')->table('records')->where("to", $admin['_id'])->where("type", 1)->select();
        $admin["totalRechargeAmount"] = Db::connect('mongodb')->table('gmextrecords')->where("gm", $admin['_id'])
        ->where("status", 'finish')->sum("price");
        foreach ($rechargeLists as $item) $admin["totalRechargeAmount"] += DefaultEnum::GMPRICES[$item["amount"]];
        $admin["nextLevelRechargeAmount"] = DefaultEnum::AGENCYNEXTRECHARGEAMOUNT[$admin["level"] +1 >= 4 ? 4 : $admin["level"]+1];
        $admin["nextLevelRechargeInfo"] = DefaultEnum::AGENCYNEXTRECHARGEINFO[$admin["level"] +1 >= 4 ? 4 : $admin["level"]+1];

        $templates = Db::connect('mongodb')->table('rechargetemplates')
            ->where(["level" => intval($admin["level"]), "isOnline" => true])->select()->toArray();
        foreach ($templates as $key => $value) {
            $templates[$key]["_id"] = reset($value["_id"]);
            $templates[$key]["androidPrice"] = round($value["androidPrice"] / 100, 2);
        }
        $admin["level_str"] = DefaultEnum::AGENCYRECHARGELEVEL[$admin["level"]];

        $levelTemplate = DefaultEnum::AGENCYNEXTRECHARGEAMOUNT;
        $levellists = [];
        foreach ($levelTemplate as $level => $amount) array_push($levellists, ["level" => $level, "amount" => $amount]);
        return ["admin" => $admin, "templates" => $templates, "levellists" => $levellists];
    }

    public static function recharge($params)
    {
        try {
            return self::rechargeByTemplate($params);
        } catch(\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 选择模板充值
     * @param $params
     * @throws \think\Exception
     * @author Tab
     * @date 2021/8/11 11:25
     */
    public static function rechargeByTemplate($params)
    {
        $template = Db::connect('mongodb')->table('rechargetemplates')
            ->where(["_id" => new ObjectId($params["_id"])])->find();
        if(empty($template)) {
            throw new \think\Exception('充值模板不存在');
        }
        $params['money'] = $template["amount"];
        $params['price'] = round($template["androidPrice"] / 100, 2);
        $params['award'] = $template["extraAmount"];
        return self::addRechargeOrder($params);
    }

    /**
     * @notes 添加充值订单
     * @param $params
     * @author Tab
     * @date 2021/8/11 11:23
     */
    public static function addRechargeOrder($params)
    {
        try {
            $data = [
                'status'      => "pending",
                'source'      => "wechat",
                'gem'       => intval($params['amount'] + $params["award"]),
                'gm'    => new ObjectId($params["admin_id"]),
                'price'       => intval($params['price']),
                'oid'  => "",
                'extras'   => [

                ],
                'createAt'         => new UTCDateTime(time() * 1000),
                "notification" => [

                ]
            ];

            $data["_id"] = Db::connect('mongodb')->table('gmextrecords')->insertGetId($data);
            $payService = (new WeChatPayService(1, "agencyRecharge", $params["admin_id"]));
            $result = $payService->config("agencyRecharge", $data);
            $extras = [
                "appid" => $result["config"]["app_id"],
                "mch_id" => $result["config"]["mch_id"],
                "openid" => $result["attributes"]["openid"],
                "body" => $result["attributes"]["body"],
                "detail" => $params['amount'] . "元 " . $data["gem"] . "点券",
                "out_trade_no" => $result["attributes"]["out_trade_no"],
                "notify_url" => $result["config"]["notify_url"],
                "spbill_create_ip" => request()->ip(),
                "total_fee" => $result["attributes"]["total_fee"],
                "trade_type" => $result["attributes"]["trade_type"]
            ];
            Db::connect('mongodb')->table('gmextrecords')->where("_id", new ObjectId($data["_id"]))
                ->update(["extras" => $extras, "oid" => $extras["openid"]]);

            return [
                'order_id' => $data["_id"],
                'from' => 'agencyRecharge'
            ];
        } catch(\Exception $e) {
            var_dump($e);
        }
    }

    /**
     * @notes 获取准备预支付订单信息
     * @param $params
     * @author 段誉
     * @date 2021/8/3 11:57
     */
    public static function getPayOrderInfo($params)
    {
        try {
            $order = Db::connect('mongodb')->table('gmextrecords')->where("_id", new ObjectId($params['order_id']))->find();
            if(empty($order)) {
                throw new \Exception('充值订单不存在');
            }

            if ($order['status'] == "finish") {
                throw new \Exception('订单已支付');
            }
            $order["gm"] = reset($order["gm"]);
            $order["_id"] = reset($order["_id"]);
            return $order;
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }

    /**
     * @notes 支付
     * @param $payWay // 支付方式
     * @param $from //订单来源(商品订单?充值订单?其他订单?)
     * @param $order //订单信息
     * @param $terminal //终端
     * @return array|bool|string|void
     * @throws \Exception
     * @author 段誉
     * @date 2021/7/29 14:49
     */
    public static function pay($from, $order)
    {
        $payService = (new WeChatPayService(1, $from, $order['gm']));
        $result = $payService->pay($from, $order);

        //支付成功, 执行支付回调
        if (false === $result && !self::hasError()) {
            self::setError($payService->getError());
        }
        return $result;
    }

    /**
     * @notes 查看订单支付状态
     * @param $params
     * @return array|false
     * @author Tab
     * @date 2021/11/30 15:54
     */
    public static function getPayStatus($params)
    {
        try {
            $order = Db::connect('mongodb')->table('gmextrecords')->where("_id", new ObjectId($params['order_id']))->find();
            if (empty($order)) {
                throw new \Exception('订单不存在');
            }
            return [
                'pay_status' => $order["status"],
                'order' => $order
            ];
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }
}
