<?php


namespace app\common\command;

use app\common\enum\DefaultEnum;
use app\common\service\ConfigService;
use Exception;
use MongoDB\BSON\UTCDateTime;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Db;

/**
 * 计算用户
 * Class OrderClose
 * @package app\common\command
 */
class GameJuCount extends Command
{

    protected function configure()
    {
        $this->setName('game_ju_count')->setDescription('统计胜率');
    }

    protected function execute(Input $input, Output $output)
    {
        $totalCount = Db::connect('mongodb')->table('players')->count();
        $pagesize = 50;
        $pagecount = ceil($totalCount / $pagesize);

        for ($page = 1; $page <= $pagecount; $page++) {
            $players = Db::connect('mongodb')->table('players')->page($page, $pagesize)->select()->toArray();
            foreach ($players as $value) {
                if(empty($value["helpTime"]) || !is_array($value["helpTime"])) $value["helpTime"] = ["zhadanHelpTime" => "", "majiangHelpTime" => "", "paodekuaiHelpTime" => "", "shisanshuiHelpTime" => "", "biaofenHelpTime" => ""];
                if(empty($value["coolingcycle"]) || !is_array($value["coolingcycle"])) $value["coolingcycle"] = ["zhadanCoolingcycle" => "", "majiangCoolingcycle" => "", "paodekuaiCoolingcycle" => "", "shisanshuiCoolingcycle" => "", "biaofenCoolingcycle" => ""];
                self::updateUserHelper($value);
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
    protected function updateUserHelper($params)
    {
        $rateRule = Db::connect('mongodb')->table('ratetemplate')->order("minLevel", "desc")->select()->toArray();
        foreach($rateRule as $key => $value) {
            //判断救助是否开启
            $isOpen = ConfigService::getGlobal('number', $value["game"] . 'Help');
            if($isOpen === DefaultEnum::HELPCLOSE) continue;

            //获取游戏上次救助时间
            if(!empty($params["helpTime"][$value["game"] . 'HelpTime'])) {
                $value["helpTimeStamp"] = intval(reset($params["helpTime"][$value["game"] . 'HelpTime']) / 1000);
            }

            $where = [];
            $limit = $value["juCount"];
            $time = time() - 60 * 60 * 24 * $value["day"];
            if(!empty($value["helpTimeStamp"])) {
                if($value["helpTimeStamp"] > $time) $time = $value["helpTimeStamp"];
            }
            $where[] = ["category", "=", $value["game"]];
            $where[] = ["rule.isPublic", "=", false];
            $params['start_time'] = $time;
            $params['end_time'] = time();
            $params['cle'] = $params['end_time'] - $params['start_time'];
            $where[] = ["createAt", "between",
                [new UTCDateTime($params['start_time'] * 1000),
                    new UTCDateTime($params['end_time'] * 1000)]];
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
            $rateRule[$key]["isHelp"] = 0;
            $rateRule[$key]["estimate"] = 0;

            foreach ($roomRecords as $v) {
                foreach ($v["scores"] as $v1) {
                    if(!empty($v1) && $v1["shortId"] == $params["shortId"]) {
                        $v1["score"] > 0 ? ++$rateRule[$key]["winJu"] : ++$rateRule[$key]["failJu"];
                        $v1["score"] > 0 ? $rateRule[$key]["winJuScore"] += $v1["score"] : $rateRule[$key]["failJuScore"] += $v1["score"];
                        $rateRule[$key]["totalJuScore"] += $v1["score"];
                    }
                }
            }

            if(count($roomRecords) > 0) {
                $rateRule[$key]["juRate"] = round($rateRule[$key]["winJu"] / count($roomRecords), 2);
            }

            //判断局数,胜率,输局积分是否满足条件
            if($rateRule[$key]["totalJu"] >= intval($rateRule[$key]["juCount"]) && $rateRule[$key]["juRate"] <= $rateRule[$key]["juRank"]
                && $rateRule[$key]["totalJuScore"] < 0 && abs($rateRule[$key]["totalJuScore"]) >= intval($rateRule[$key]["juMinScore"])
                && abs($rateRule[$key]["totalJuScore"]) <= intval($rateRule[$key]["juMaxScore"])) $rateRule[$key]["isHelp"] = 1;

            $rateRule[$key]["rateLevel"] = Db::connect('mongodb')->table('treasureboxes')
                ->where("level", ">=", $rateRule[$key]["minLevel"])->where("level", "<=", $rateRule[$key]["maxLevel"])
                ->order("createAt", "desc")->limit($limit)->select();
            $rateRule[$key]["scorestr"] = $rateRule[$key]["juMinScore"] . '~' . $rateRule[$key]["juMaxScore"];
            $rateRule[$key]["levelstr"] = $rateRule[$key]["minLevel"] . '~' . $rateRule[$key]["maxLevel"];
            $rateRule[$key]["estimateLevel"] = 0;

            if($rateRule[$key]["isHelp"] == 1) {
                $rateRule[$key]["estimateLevel"] = $rateRule[$key]["minLevel"];

                //计算预估救助等级
                if($rateRule[$key]["maxLevel"] > $rateRule[$key]["minLevel"]) {
                    $level = $rateRule[$key]["maxLevel"] - $rateRule[$key]["minLevel"];
                    $score = round(($rateRule[$key]["juMaxScore"] - $rateRule[$key]["juMinScore"]) / $level, 2);
                    $userScore = round(abs($rateRule[$key]["failJuScore"]) - $rateRule[$key]["juMinScore"], 2);

                    if($userScore >= $score) {
                        $calcLevel = $rateRule[$key]["minLevel"] += floor(($userScore / $score));
                        $rateRule[$key]["estimateLevel"] = $calcLevel >= $rateRule[$key]["maxLevel"] ? $rateRule[$key]["maxLevel"] : $calcLevel;
                    }
                }
            }

            if($rateRule[$key]["totalJu"] > 0 && $rateRule[$key]["isHelp"] == 1) {
                try {
                    $treasurebox = Db::connect('mongodb')->table('treasureboxes')->where("level", intval($rateRule[$key]["estimateLevel"]))->find();
                    $orderCount = Db::connect('mongodb')->table('playerhelpdetails')->where("player", $params["_id"])->where("type", 2)->count();
                    $coolingcycle = self::getCoolingCycle($params, $value["game"]);
                    if($orderCount > 0 && $rateRule[$key]["totalJu"] < intval($rateRule[$key]["juCount"]) + ($coolingcycle ?? 0)) continue;
                    $order = Db::connect('mongodb')->table('playerhelpdetails')->where("estimateLevel", intval($treasurebox["star"]))
                        ->where("player", $params["_id"])->where("type", $value["game"] === "zhadan" && $treasurebox["star"] == 11 ? 3: 2)
                        ->where("game", $value["game"])->find();

                    if(!empty($order)) {
                        self::updateUserHpleDetail($order, $treasurebox, $value);
                        $orderId = reset($order["_id"]);
                    } else {
                        $orderId = self::saveUserHpleDetail($params, $treasurebox, $value);
                    }

                    Db::connect("mongodb")->table('treasureboxrecords')->insert([
                        "player" => $params["_id"],
                        "shortId" => $params["shortId"],
                        "isHelp" => $rateRule[$key]["isHelp"],
                        "level" => intval($treasurebox["level"]),
                        "star" => $value["game"] === "zhadan" ? intval($treasurebox["star"]) : 0,
                        "mahjong" => $value["game"] === "majiang" ? $treasurebox["mahjong"] : [],
                        "pdk" => $value["game"] === "paodekuai" ? $treasurebox["pdk"] : [],
                        "sss" => $value["game"] === "shisanshui" ? $treasurebox["sss"] : [],
                        "bf" => $value["game"] === "biaofen" ? $treasurebox["bf"] : [],
                        "game" => $value["game"],
                        "type" => $value["game"] === "zhadan" && $treasurebox["star"] == 11 ? 3 : 2,
                        "orderId" => $orderId,
                        "createAt" => new UTCDateTime(time() * 1000)
                    ]);

                    Db::connect("mongodb")->table('playerhelps')->insert([
                        "player" => $params["_id"],
                        "shortId" => $params["shortId"],
                        "totalJu" => $rateRule[$key]["totalJu"],
                        "juRate" => $rateRule[$key]["juRate"],
                        "winJu" => $rateRule[$key]["winJu"],
                        "failJu" => $rateRule[$key]["failJu"],
                        "totalJuScore" => $rateRule[$key]["totalJuScore"],
                        "winJuScore" => $rateRule[$key]["winJuScore"],
                        "failJuScore" => $rateRule[$key]["failJuScore"],
                        "ruleId" => reset($rateRule[$key]["_id"]),
                        "isHelp" => $rateRule[$key]["isHelp"],
                        "estimateLevel" => intval($treasurebox["star"]),
                        "orderId" => $orderId,
                        "createAt" => new UTCDateTime(time() * 1000)
                    ]);

                    Db::connect('mongodb')->table('players')->where("_id", $params["_id"])->update([
                        "isHelp" => false,
                        "coolingcycle" => [
                            "zhadanCoolingcycle" => $value["game"] == "zhadan" ? intval($treasurebox["coolingCount"]) : ($params["coolingcycle"]["zhadanCoolingcycle"] ?? 0),
                            "majiangCoolingcycle" => $value["game"] == "majiang" ? intval($treasurebox["coolingCount"]) : ($params["coolingcycle"]["majiangCoolingcycle"] ?? 0),
                            "paodekuaiCoolingcycle" => $value["game"] == "paodekuai" ? intval($treasurebox["coolingCount"]) : ($params["coolingcycle"]["paodekuaiCoolingcycle"] ?? 0),
                            "shisanshuiCoolingcycle" => $value["game"] == "shisanshui" ? intval($treasurebox["coolingCount"]) : ($params["coolingcycle"]["shisanshuiCoolingcycle"] ?? 0),
                            "biaofenCoolingcycle" => $value["game"] == "biaofen" ? intval($treasurebox["coolingCount"]) : ($params["coolingcycle"]["biaofenCoolingcycle"] ?? 0),
                        ],
                        "helpTime" => [
                            "zhadanHelpTime" => $value["game"] == "zhadan" ? new UTCDateTime(time() * 1000) : ($params["helpTime"]["zhadanHelpTime"] ?? ""),
                            "majiangHelpTime" => $value["game"] == "majiang" ? new UTCDateTime(time() * 1000) : ($params["helpTime"]["majiangHelpTime"] ?? ""),
                            "paodekuaiHelpTime" => $value["game"] == "paodekuai" ? new UTCDateTime(time() * 1000) : ($params["helpTime"]["paodekuaiHelpTime"] ?? ""),
                            "shisanshuiHelpTime" => $value["game"] == "shisanshui" ? new UTCDateTime(time() * 1000) : ($params["helpTime"]["shisanshuiHelpTime"] ?? ""),
                            "biaofenHelpTime" => $value["game"] == "biaofen" ? new UTCDateTime(time() * 1000) : ($params["helpTime"]["biaofenHelpTime"] ?? ""),
                        ]
                    ]);
                } catch(Exception $e) {
                    var_dump($e);
                }

                break;
            }
        }
    }

    protected static function getCoolingCycle($user, $game) {
        return !empty($user["coolingcycle"]["{$game}Coolingcycle"]) ?
            intval($user["coolingcycle"]["{$game}Coolingcycle"]) : 0;
    }

    protected static function saveUserHpleDetail($params, $treasurebox, $value) {
        try {
            return Db::connect('mongodb')->table('playerhelpdetails')->insertGetId([
                "player" => $params["_id"],
                "shortId" => intval($params["shortId"]),
                "type" => $value["game"] === "zhadan" && $treasurebox["star"] == 11 ? 3 : 2,
                "isHelp" => 1,
                "estimateLevel" => intval($treasurebox["star"]),
                "mahjong" => $value["game"] === "majiang" ? $treasurebox["mahjong"] : [],
                "pdk" => $value["game"] === "paodekuai" ? $treasurebox["pdk"] : [],
                "sss" => $value["game"] === "shisanshui" ? $treasurebox["sss"] : [],
                "bf" => $value["game"] === "biaofen" ? $treasurebox["bf"] : [],
                "game" => $value["game"],
                "coolingcycle" => intval($treasurebox["coolingCount"]),
                "count" => 1,
                "juCount" => intval($treasurebox["juCount"]),
                "juIndex" => 0,
                "treasureLevel" => $treasurebox["level"],
                "createAt" => new UTCDateTime(time() * 1000)
            ]);
        } catch(Exception $e) {
            var_dump($e);
        }
    }

    protected static function updateUserHpleDetail($order, $treasurebox, $value) {
        try {
            Db::connect('mongodb')->table('playerhelpdetails')->where("_id", $order["_id"])->update([
                "isHelp" => 1,
                "count" => $order["count"] + 1,
                "juCount" => $order["count"] == 0 ? $treasurebox["juCount"] : $order["juCount"],
                "treasureLevel" => $treasurebox["level"],
                "mahjong" => $value["game"] === "majiang" ? $treasurebox["mahjong"] : [],
                "pdk" => $value["game"] === "paodekuai" ? $treasurebox["pdk"] : [],
                "sss" => $value["game"] === "shisanshui" ? $treasurebox["sss"] : [],
                "bf" => $value["game"] === "biaofen" ? $treasurebox["bf"] : [],
                "game" => $value["game"],
                "createAt" => new UTCDateTime(time() * 1000)
            ]);
        } catch(Exception $e) {
            var_dump($e);
        }
    }
}
