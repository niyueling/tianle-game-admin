<?php

namespace app\adminapi\logic\marketing;

use app\common\enum\DefaultEnum;
use app\common\logic\BaseLogic;
use MongoDB\BSON\ObjectId;
use think\facade\Db;

/**
 * 宝箱管理
 * Class CenterLogic
 * @package app\adminapi\logic
 */
class TreasureBoxLogic extends BaseLogic
{
    /**
     * @notes 宝箱管理
     * @return array
     * @author Tab
     * @date 2021/9/27 10:31
     */
    public static function lists($params)
    {
        $where = [];
        if (!empty($params['name'])) $where[] = ["name", "=", $params['name']];
        if (!empty($params['isOnline'])) $where[] = ["isOnline", "=", $params['isOnline'] == 1];

        $count = Db::connect('mongodb')->table('treasureboxes')->where($where)->count();
        $goodList = Db::connect('mongodb')->table('treasureboxes')->where($where)
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($goodList as $key => $value) {
            $goodList[$key]["_id"] = reset($goodList[$key]["_id"]);
            $goodList[$key]["isOnline"] =$goodList[$key]["isOnline"] ? 1 : 2;
            if(!empty($goodList[$key]["gamelists"])) {
                $games = [];
                foreach ($goodList[$key]["gamelists"] as $k => $v)  {
                    array_push($games, DefaultEnum::GAMElIST[$v]);
                }
                $goodList[$key]["gameStr"] = implode(" | ", $games);
            }

            if(empty($goodList[$key]["mahjong"]["cardlists"])) {
                $goodList[$key]["mahjong"] = ["moCount" => "", "cardlists" => [["cardType" => "", "cardName" => "", "index" => "", "cardCount" => "", "shunziCount" => ""]]];
                $goodList[$key]["mahjongStr"] = DefaultEnum::ISNOTRESULT;
            } else {
                $cards = [];
                foreach ($goodList[$key]["mahjong"]["cardlists"] as $k => $v)  {
                    array_push($cards, "初始牌：{$v["cardName"]}");
                }
                $goodList[$key]["mahjongStr"] = implode(" + ", $cards);
                $goodList[$key]["mahjongStr"] .= ",辅助摸牌次数：{$goodList[$key]["mahjong"]["moCount"]}次";
            }

            if(empty($goodList[$key]["pdk"])) {
                $goodList[$key]["pdk"] = [["cardType" => "", "cardName" => "", "index" => "", "cardCount" => "", "shunziCount" => ""]];
                $goodList[$key]["pdkStr"] = DefaultEnum::ISNOTRESULT;
            } else {
                $cards = [];
                foreach ($goodList[$key]["pdk"] as $k => $v)  {
                    array_push($cards, "初始牌：{$v["cardName"]}");
                }
                $goodList[$key]["pdkStr"] = implode(" + ", $cards);
            }

            if (empty($goodList[$key]["sss"])) {
                $goodList[$key]["sss"] = [["cardType" => "", "cardName" => "", "index" => "", "cardCount" => "", "shunziCount" => ""]];
                $goodList[$key]["sssStr"] = DefaultEnum::ISNOTRESULT;
            } else {
                $cards = [];

                foreach ($goodList[$key]["sss"] as $v) array_push($cards, "初始牌：{$v["cardName"]}");
                $goodList[$key]["sssStr"] = implode(" + ", $cards);
            }

            if(empty($goodList[$key]["bf"])) {
                $goodList[$key]["bf"] = [["cardType" => "", "cardName" => "", "index" => "", "cardCount" => "", "shunziCount" => ""]];
                $goodList[$key]["bfStr"] = DefaultEnum::ISNOTRESULT;
            } else {
                $cards = [];
                foreach ($goodList[$key]["bf"] as $k => $v)  {
                    array_push($cards, "初始牌：{$v["cardName"]}");
                }
                $goodList[$key]["bfStr"] = implode(" + ", $cards);
            }

            if(empty($goodList[$key]["star"])) {
                $goodList[$key]["zhadanStr"] = DefaultEnum::ISNOTRESULT;
            } else {
                $goodList[$key]["star"] != 11 ? $goodList[$key]["zhadanStr"] = $goodList[$key]["star"] . '星' : $goodList[$key]["zhadanStr"] = "烧鸡重新发牌{$goodList[$key]["zdFpCount"]}次";
            }
        }

        return [
            "count" => $count,
            "lists" => $goodList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 删除宝箱
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function del(array $params):bool
    {
        Db::connect('mongodb')->table('treasureboxes')->where(['_id' => new ObjectId($params['_id'])])->delete();
        return true;

    }

    /**
     * @notes 编辑宝箱
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function setInfo(array $params):bool
    {
        Db::connect('mongodb')->table('treasureboxes')->where(['_id' => new ObjectId($params['_id'])])->update([
            "level" => intval($params["level"]),
            "name" => $params["name"],
            "star" => intval($params["star"] ?? ""),
            "mahjong" => $params["mahjong"],
            "pdk" => $params["pdk"],
            "sss" => $params["sss"],
            "bf" => $params["bf"],
            "count" => 1,
            "juCount" => intval($params["juCount"]),
            "coolingCount" => intval($params["coolingCount"]),
            "gamelists" => $params["gamelists"],
            "isOnline" => $params["isOnline"] == 1,
            "zdFpCount" => intval($params["zdFpCount"])
        ]);
        return true;

    }

    /**
     * @notes 新增宝箱
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function add(array $params):bool
    {
        $count = Db::connect('mongodb')->table('treasureboxes')->where("level", intval($params["level"]))->count();
        if($count > 0) return false;

        Db::connect('mongodb')->table('treasureboxes')->insert([
            "level" => intval($params["level"]),
            "name" => $params["name"],
            "star" => intval($params["star"] ?? ""),
            "mahjong" => $params["mahjong"],
            "pdk" => $params["pdk"],
            "sss" => $params["sss"],
            "bf" => $params["bf"],
            "count" => 1,
            "juCount" => intval($params["juCount"]),
            "coolingCount" => intval($params["coolingCount"]),
            "gamelists" => $params["gamelists"],
            "isOnline" => $params["isOnline"] == 1,
            "zdFpCount" => intval($params["zdFpCount"])
        ]);
        return true;

    }
}
