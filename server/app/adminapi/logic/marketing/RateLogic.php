<?php

namespace app\adminapi\logic\marketing;

use app\common\enum\DefaultEnum;
use app\common\logic\BaseLogic;
use app\common\service\ConfigService;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use think\Collection;
use think\facade\Db;

/**
 * 县区管理
 * Class CenterLogic
 * @package app\adminapi\logic
 */
class RateLogic extends BaseLogic
{
    /**
     * @notes 救助概览
     * @return array
     * @author Tab
     * @date 2021/9/27 10:31
     */
    public static function index($params)
    {

        $params['start_time'] = date("Y-m-d H:i:s", strtotime("-7 day"));
        $params['end_time'] = date("Y-m-d H:i:s", time());
        $params['cle'] = strtotime($params['end_time']) - strtotime($params['start_time']);
        $params['start_time'] = new UTCDateTime(strtotime($params['start_time']) * 1000);
        $params['end_time'] = new UTCDateTime(strtotime($params['end_time']) * 1000);

        return [
            'summary' => self::summary($params),
//            'rateRankList' => self::getRateRankList($params),
            'rateRuleList' => self::getRateRuleList($params),
            'rateLevelList' => self::getRateLevelList($params),
        ];
    }

    /**
     * @notes 数据汇总
     * @param $params
     * @return array
     * @author Tab
     * @date 2021/9/27 11:27
     */
    public static function summary($params)
    {
        // 近7日开房数
        $roomRecords = Db::connect('mongodb')->table('roomrecords')->where("createAt", "between", [$params["start_time"], $params["end_time"]])->select();
        //近7日小局数
        $minJuCount = Db::connect('mongodb')->table('gamerecords')
            ->where("time", "between", [$params["start_time"], $params["end_time"]])->count();;

        //近7日新增用户数
        $newUserCount = Db::connect('mongodb')->table('players')->where("createAt", "between", [$params["start_time"], $params["end_time"]])->count();

        return [
            'juCount' => count($roomRecords),
            'minJuCount' => $minJuCount,
            'newUserCount' => $newUserCount
        ];
    }

    /**
     * @notes 查看游戏胜率
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public static function getRateRankList($params)
    {
        $minZhadanJuCount = Db::connect('mongodb')->table('gamerecords')
            ->where("type", "zhadan")
            ->where("time", "between", [$params["start_time"], $params["end_time"]])->count();
        $minBiaofenJuCount = Db::connect('mongodb')->table('gamerecords')
            ->where("type", "biaofen")
            ->where("time", "between", [$params["start_time"], $params["end_time"]])->count();
        $minPaodekuaiJuCount = Db::connect('mongodb')->table('gamerecords')
            ->where("type", "paodekuai")
            ->where("time", "between", [$params["start_time"], $params["end_time"]])->count();
        $minShisanshuiJuCount = Db::connect('mongodb')->table('gamerecords')
            ->where("type", "shisanshui")
            ->where("time", "between", [$params["start_time"], $params["end_time"]])->count();
        $minMajiangJuCount = Db::connect('mongodb')->table('gamerecords')
            ->where("type", "majiang")
            ->where("time", "between", [$params["start_time"], $params["end_time"]])->count();

        $roomZhadanCount = Db::connect('mongodb')->table('roomrecords')->where("category", "zhadan")
            ->where("createAt", "between", [$params["start_time"], $params["end_time"]])->count();
        $roomBiaofenCount = Db::connect('mongodb')->table('roomrecords')->where("category", "biaofen")
            ->where("createAt", "between", [$params["start_time"], $params["end_time"]])->count();
        $roomPaodekuaiCount = Db::connect('mongodb')->table('roomrecords')->where("category", "paodekuai")
            ->where("createAt", "between", [$params["start_time"], $params["end_time"]])->count();
        $roomShisanshuiCount = Db::connect('mongodb')->table('roomrecords')->where("category", "shisanshui")
            ->where("createAt", "between", [$params["start_time"], $params["end_time"]])->count();
        $roomMajiangCount = Db::connect('mongodb')->table('roomrecords')->where("category", "majiang")
            ->where("createAt", "between", [$params["start_time"], $params["end_time"]])->count();

        $rateRankList = [
            ["juCount" => $roomZhadanCount, "minJuCount" => $minZhadanJuCount, "name" => "炸弹"],
            ["juCount" => $roomBiaofenCount, "minJuCount" => $minBiaofenJuCount, "name" => "标分"],
            ["juCount" => $roomPaodekuaiCount, "minJuCount" => $minPaodekuaiJuCount, "name" => "跑得快"],
            ["juCount" => $roomShisanshuiCount, "minJuCount" => $minShisanshuiJuCount, "name" => "十三水"],
            ["juCount" => $roomMajiangCount, "minJuCount" => $minMajiangJuCount, "name" => "麻将"],
        ];

        $sort = array_column($rateRankList, 'juCount');
        array_multisort($sort, SORT_DESC, $rateRankList);

        return $rateRankList;
    }

    /**
     * @notes 查看规则排行榜
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public static function getRateRuleList($params)
    {
        $rateRuleRecords = Db::connect('mongodb')->table('ratetemplate')->select()->toArray();

        foreach($rateRuleRecords as $key => $value) {
            $rateRuleRecords[$key]["juScore"] = $rateRuleRecords[$key]["juMinScore"] . '~' . $rateRuleRecords[$key]["juMaxScore"];
        }

        $sort = array_column($rateRuleRecords, 'juCount');
        array_multisort($sort, SORT_DESC, $rateRuleRecords);

        return $rateRuleRecords;
    }

    /**
     * @notes 查看规则排行榜
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public static function getRateLevelList($params)
    {
        $rateLevelRecords = Db::connect('mongodb')->table('treasureboxes')->order("level", "asc")->select()->toArray();

        foreach($rateLevelRecords as $key => $value) {
            $games = [];
            foreach ($value["gamelists"] as $k => $v)  {
                array_push($games, DefaultEnum::GAMElIST[$v]);
            }
            $rateLevelRecords[$key]["gameList"] = implode(" | ", $games);
        }

        return $rateLevelRecords;
    }

    /**
     * @notes 获取救助设置
     * @return array
     * @author Tab
     * @date 2021/8/10 17:19
     */
    public static function rule()
    {
        $config = [
            'set' => self::getSet(),
            'rule' => self::getRule()
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
        $set = [
            'zhadan' => ConfigService::getGlobal('number', 'zhadanHelp'),
            'majiang' => ConfigService::getGlobal('number', 'majiangHelp'),
            'biaofen' => ConfigService::getGlobal('number', 'biaofenHelp'),
            'shisanshui' => ConfigService::getGlobal('number', 'shisanshuiHelp'),
            'paodekuai' => ConfigService::getGlobal('number', 'paodekuaiHelp'),
        ];
        return $set;
    }

    /**
     * @notes 获取规则
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author Tab
     * @date 2021/8/11 9:36
     */
    public static function getRule()
    {
        $lists = Db::connect('mongodb')->table('ratetemplate')->order("maxLevel", "asc")->select()->toArray();

        foreach($lists as $key => $value) {
            $lists[$key]["juRank"] = round($lists[$key]["juRank"] * 100, 1);
        }
        return $lists;
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
            // 更新规则
            self::updateRule($params);

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
        if(isset($params['majiang'])) {
            ConfigService::setGlobal('number', 'majiangHelp', intval($params['majiang']));
        }
        if(isset($params['zhadan'])) {
            ConfigService::setGlobal('number', 'zhadanHelp', intval($params['zhadan']));
        }
        if(isset($params['biaofen'])) {
            ConfigService::setGlobal('number', 'biaofenHelp', intval($params['biaofen']));
        }
        if(isset($params['paodekuai'])) {
            ConfigService::setGlobal('number', 'paodekuaiHelp', intval($params['paodekuai']));
        }
        if(isset($params['shisanshui'])) {
            ConfigService::setGlobal('number', 'shisanshuiHelp', intval($params['shisanshui']));
        }
    }

    /**
     * @notes 更新规则
     * @param $params
     * @throws \think\Exception
     * @author Tab
     * @date 2021/8/10 19:14
     */
    public static function updateRule($params)
    {
        // 未设置充值规则,直接返回
        if(!isset($params['rule']) || empty($params['rule'])) {
            return true;
        }

        if (!is_array($params['rule'])) {
            throw new \Exception('充值规则格式不正确');
        }

        // 清除旧数据
        Db::connect('mongodb')->table('ratetemplate')->where("juCount", ">", 0)->delete();

        foreach($params['rule'] as $key => $item) {
            $item["createAt"] = new UTCDateTime(time() * 1000);
            $item["juRank"] = round($item["juRank"] / 100, 2);
            $item["juCount"] = intval($item["juCount"]);
            $item["juMinScore"] = intval($item["juMinScore"]);
            $item["juMaxScore"] = intval($item["juMaxScore"]);
            $item["minLevel"] = intval($item["minLevel"]);
            $item["maxLevel"] = intval($item["maxLevel"]);
            $item["day"] = intval($item["day"]);

            Db::connect('mongodb')->table('ratetemplate')->insert($item);
        }

        return true;

    }

    /**
     * @notes 救助记录
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
        if(!empty($playerIds)) $where[] = ["_id", 'in', $playerIds];
        if(!empty($params["useJoker"])) $where[] = ["useJoker", '=', intval($params["useJoker"])];
        if(!empty($params["level"])) $where[] = ["level", '=', intval($params["level"])];
        if(!empty($params["roomId"])) $where[] = ["roomId", '=', intval($params["roomId"])];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["gameTime", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];
        }

        $count = Db::connect('mongodb')->table('raterecords')->where($where)->count();
        $recordList = Db::connect('mongodb')->table('raterecords')->where($where)
            ->order("dayId", "desc")->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($recordList as $key => $value) {
            $recordList[$key]["playerInfo"] = Db::connect('mongodb')->table('players')->where("_id", $value["player"])->find();
            $recordList[$key]["rule"] = Db::connect('mongodb')->table('ratelevels')->where("_id", new ObjectId($value["ruleId"]))->find();
            $recordList[$key]["record"] = Db::connect('mongodb')->table('playerhelps')->where("_id", new ObjectId($value["recordId"]))->find();
            $recordList[$key]["useJoker"] = DefaultEnum::JOKERTYPE[$recordList[$key]["useJoker"]];
            $recordList[$key]["cardStr"] = '';
            foreach ($recordList[$key]["cardLists"] as $item)
                $recordList[$key]["cardStr"] .= (DefaultEnum::CARDTYPE[$item["type"]] . (DefaultEnum::POINTTYPE[$item["point"]] ?? $item["point"]) . '|');
            $recordList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($recordList[$key]["createAt"]) / 1000));
            $recordList[$key]["_id"] = reset($recordList[$key]["_id"]);
        }

        return [
            "count" => $count,
            "lists" => $recordList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }
}
