<?php

namespace app\adminapi\logic\marketing;

use app\common\enum\DefaultEnum;
use app\common\logic\BaseLogic;
use app\common\service\ConfigService;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use think\facade\Db;

/**
 * 县区管理
 * Class CenterLogic
 * @package app\adminapi\logic
 */
class PrizeLogic extends BaseLogic
{
    /**
     * @notes 获取排行榜列表
     * @return array
     * @author Tab
     * @date 2021/8/10 17:19
     */
    public static function index($params)
    {
        $where = [];
        if (!empty($params['name'])) $where[] = ["name", "=", $params['name']];

        $count = Db::connect('mongodb')->table('rankconfigs')->where($where)->count();
        $list = Db::connect('mongodb')->table('rankconfigs')->where($where)
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($list as $key => $value) {
            $list[$key]["_id"] = reset($list[$key]["_id"]);
            $list[$key]["rankNoStr"] = '第' . $value["rankNo"] . "期";
            $list[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($list[$key]["createAt"]) / 1000));
        }

        return [
            "count" => $count,
            "lists" => $list,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 获取救助设置
     * @return array
     * @author Tab
     * @date 2021/8/10 17:19
     */
    public static function rule($params)
    {
        $config = [
            'set' => self::getSet(),
            'prizes' => self::getPrizes($params)
        ];

        return $config;
    }

    /**
     * @notes 救助设置
     * @return array
     * @author Tab
     * @date 2021/8/10 17:31
     */
    public static function getSet()
    {
        $activeStartAt = ConfigService::getGlobal('number', 'activeStartAt');
        $activeEndAt = ConfigService::getGlobal('number', 'activeEndAt');
        $set = [
            'player' => [
                "open" => ConfigService::getGlobal('number', 'open'),
                "activeStartAt" => date("Y-m-d H:i:s", intval($activeStartAt / 1000)),
                "activeEndAt" => date("Y-m-d H:i:s", intval($activeEndAt / 1000))
            ]
        ];
        return $set;
    }

    /**
     * @notes 获取奖品
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author Tab
     * @date 2021/8/11 9:36
     */
    public static function getPrizes($params)
    {
        $where = [["type", "=", $params["type"]]];
        if(!empty($params["rankId"])) $where[] = ["rankId", "=", $params["rankId"]];
        $gem = Db::connect('mongodb')->table('lotteryprizes')->where($where)
            ->order("probability", "desc")->select()->toArray();
        $player = Db::connect('mongodb')->table('lotteryprizes')->where($where)
            ->order("probability", "desc")->select()->toArray();

        foreach($gem as $key => $value) {
            $gem[$key]["probability"] = round($gem[$key]["probability"] * 100, 1);
            if($player[$key]["source"] == "redpocket") $gem[$key]["quantity"] = intval($gem[$key]["quantity"] / 100);
        }
        foreach($player as $key => $value) {
            $player[$key]["probability"] = round($player[$key]["probability"] * 100, 1);
            if($player[$key]["source"] == "redpocket") $player[$key]["quantity"] = intval($player[$key]["quantity"] / 100);
        }
        return ["gem" => $gem, "player" => $player];
    }

    /**
     * @notes 救助设置
     * @param $params
     * @author Tab
     * @date 2021/8/10 17:59
     */
    public static function setRule($params)
    {
        try {
            // 更新设置
            self::updateSet($params);
            // 更新奖品
            self::updatePrizes($params);

            return true;
        } catch(\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 更新设置
     * @param $params
     * @author Tab
     * @date 2021/8/10 17:59
     */
    public static function updateSet($params)
    {
        if(isset($params['gem'])) {
            ConfigService::set('gem', 'open', intval($params['gem']["open"]));
            ConfigService::set('gem', 'startTime', $params['gem']["start_time"]);
            ConfigService::set('gem', 'endTime', $params['gem']["end_time"]);
        }
        if(isset($params['player'])) {
            ConfigService::setGlobal('number', 'open', intval($params['player']["open"]));
            ConfigService::setGlobal('number', 'activeStartAt', strtotime($params['player']["activeStartAt"]) * 1000);
            ConfigService::setGlobal('number', 'activeEndAt',  strtotime($params['player']["activeEndAt"]) * 1000);
        }
    }

    /**
     * @notes 更新规则
     * @param $params
     * @throws \think\Exception
     * @author Tab
     * @date 2021/8/10 19:14
     */
    public static function updatePrizes($params)
    {
        // 未设置充值规则,直接返回
        if(empty($params['prizes']["gem"]) && empty($params['prizes']["player"])) {
            return true;
        }

        Db::connect('mongodb')->table('lotteryprizes')->where("type", $params["type"])->delete();
        if (!empty($params['prizes']["gem"]) && is_array($params['prizes']["gem"])) {
            foreach($params['prizes']["gem"] as $item) {
                $residueNum = intval($item["residueNum"]);
                $item["createAt"] = new UTCDateTime(time() * 1000);
                $item["num"] = intval($item["num"]);
                $item["price"] = intval($item["price"]);
                $item["quantity"] = checkQuantity(intval($item["quantity"]),$item["source"]);
                $item["residueNum"] = 1;
                $item["isHit"] = false;
                !empty($item["playerShortId"]) ? $item["playerShortId"] = intval($item["playerShortId"]) : $item["playerShortId"] = 0;
                $item["probability"] = round($item["probability"] / 100, 2);
                for($i=0; $i<$residueNum; $i++) Db::connect('mongodb')->table('lotteryprizes')->insert($item);
            }
        }

        if (!empty($params['prizes']["player"]) && is_array($params['prizes']["player"])) {
            foreach($params['prizes']["player"] as $item) {
                $item["createAt"] = new UTCDateTime(time() * 1000);
                $item["quantity"] = checkQuantity(intval($item["quantity"]),$item["source"]);
                $item["residueNum"] = intval($item["num"]);
                !empty($item["playerShortId"]) ? $item["playerShortId"] = intval($item["playerShortId"]) : $item["playerShortId"] = 0;
                $item["num"] = intval($item["num"]);
                $item["price"] = intval($item["price"]);
                $item["probability"] = round($item["probability"] / 100, 2);
                Db::connect('mongodb')->table('lotteryprizes')->insert($item);
            }
        }

        return true;
    }

    /**
     * @notes 编辑排行榜
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function modifyInviteRank(array $params):bool
    {
        Db::connect('mongodb')->table('rankconfigs')->where(['_id' => new ObjectId($params['_id'])])->update([
            "name" => $params["name"],
            "startTime" => $params["start_time"],
            "endTime" => $params["end_time"],
            "isOpen" => intval($params["isOpen"]) == 1,
            "rankNo" => intval($params["rankNo"])
        ]);
        return true;

    }

    /**
     * @notes 删除排行榜
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function deleteInviteRank(array $params):bool
    {
        Db::connect('mongodb')->table('rankconfigs')->where(['_id' => new ObjectId($params['_id'])])->delete();
        return true;
    }

    /**
     * @notes 新增排行榜
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function addInviteRankLists(array $params):bool
    {
        Db::connect('mongodb')->table('rankconfigs')->insert([
            "name" => $params["name"],
            "startTime" => $params["start_time"],
            "endTime" => $params["end_time"],
            "rankNo" => intval($params["rankNo"]),
            "isOpen" => intval($params["isOpen"]) == 1,
            "createAt" => new UTCDateTime(time() * 1000),
            "isSettle" => false,
            "settleAt" => new UTCDateTime(time() * 1000)
        ]);
        return true;

    }

    /**
     * @notes 抽奖记录
     * @return array
     * @author Tab
     * @date 2021/9/27 10:31
     */
    public static function record($params)
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

        if(empty($playerIds) && $isSearch) return nullResultReturn();
        if(!empty($playerIds)) $where[] = ["playerId", 'in', $playerIds];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];
        }

        $count = Db::connect('mongodb')->table('lotteryrecords')->where($where)->count();
        $recordList = Db::connect('mongodb')->table('lotteryrecords')->where($where)
            ->order("createAt", "desc")->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($recordList as $key => $value) {
            $recordList[$key]["playerInfo"] = Db::connect('mongodb')->table('players')->where("_id", $value["playerId"])->find();
            $recordList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($recordList[$key]["createAt"]) / 1000));
            $recordList[$key]["_id"] = reset($recordList[$key]["_id"]);
            $recordList[$key]["hitStr"] = DefaultEnum::HitLists[$recordList[$key]["isHit"]];
            $recordList[$key]["receiveStr"] = DefaultEnum::ReceiveLists[$recordList[$key]["isReceive"]];
            $recordList[$key]["receiveAt"] = date('Y-m-d H:i:s',intval(reset($recordList[$key]["receiveAt"]) / 1000));
        }

        return [
            "count" => $count,
            "lists" => $recordList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }
}
