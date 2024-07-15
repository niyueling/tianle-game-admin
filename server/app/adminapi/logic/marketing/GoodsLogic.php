<?php

namespace app\adminapi\logic\marketing;

use app\common\enum\DefaultEnum;
use app\common\logic\BaseLogic;
use MongoDB\BSON\ObjectId;
use think\facade\Db;

/**
 * 营销
 * Class CenterLogic
 * @package app\adminapi\logic
 */
class GoodsLogic extends BaseLogic
{
    /**
     * @notes 商品管理
     * @return array
     * @author Tab
     * @date 2021/9/27 10:31
     */
    public static function goodLists($params)
    {
        $where = [];
        if (!empty($params['goodsType'])) $where[] = ["goodsType", "=", $params['goodsType']];
        if (!empty($params['isOnline'])) $where[] = ["isOnline", "=", $params['isOnline'] == 1];

        $count = Db::connect('mongodb')->table('goods')->where($where)->count();
        $goodList = Db::connect('mongodb')->table('goods')->where($where)
            ->page($params["page_no"], $params["page_size"])->order("androidPrice", "asc")->select()->all();

        foreach ($goodList as $key => $value) {
            $goodList[$key]["_id"] = reset($goodList[$key]["_id"]);
            $goodList[$key]["iosPrice"] = DefaultEnum::IOSPRICE[$goodList[$key]["applePriceId"]];
            $goodList[$key]["androidPrice"] = round($goodList[$key]["androidPrice"] / 100, 2);
            $goodList[$key]["isOnline"] = $goodList[$key]["isOnline"] ? 1 : 2;
            $goodList[$key]["treasureBoxName"] = DefaultEnum::ISNOTRESULT;
            if(!empty($goodList[$key]["boxId"])) {
                if(empty($goodList[$key]["boxCount"])) $goodList[$key]["boxCount"] = 0;
                if(empty($goodList[$key]["star"])) $goodList[$key]["star"] = 0;
                $treasureBax = Db::connect('mongodb')->table('treasureboxes')->where(["_id" => new ObjectId($goodList[$key]["boxId"])])->find();

                if(empty($treasureBax["mahjong"]["cardlists"])) {
                    $goodList[$key]["mahjongStr"] = DefaultEnum::ISNOTRESULT;
                } else {
                    $cards = [];
                    foreach ($treasureBax["mahjong"]["cardlists"] as $v)  {
                        array_push($cards, "初始牌：{$v["cardName"]}");
                    }
                    $goodList[$key]["mahjongStr"] = implode(" + ", $cards);
                    $goodList[$key]["mahjongStr"] .= ",辅助摸牌次数：{$treasureBax["mahjong"]["moCount"]}次";
                }

                if(empty($treasureBax["pdk"])) {
                    $goodList[$key]["pdkStr"] = DefaultEnum::ISNOTRESULT;
                } else {
                    $cards = [];
                    foreach ($treasureBax["pdk"] as $v)  {
                        array_push($cards, "初始牌：{$v["cardName"]}");
                    }
                    $goodList[$key]["pdkStr"] = implode(" + ", $cards);
                }

                if(empty($treasureBax["sss"])) {
                    $goodList[$key]["sssStr"] = DefaultEnum::ISNOTRESULT;
                } else {
                    $cards = [];
                    foreach ($treasureBax["sss"] as $k => $v)  {
                        array_push($cards, "初始牌：{$v["cardName"]}");
                    }
                    $goodList[$key]["sssStr"] = implode(" + ", $cards);
                }

                if(empty($treasureBax["bf"])) {
                    $goodList[$key]["bfStr"] = DefaultEnum::ISNOTRESULT;
                } else {
                    $cards = [];
                    foreach ($treasureBax["bf"] as $v)  {
                        array_push($cards, "初始牌：{$v["cardName"]}");
                    }
                    $goodList[$key]["bfStr"] = implode(" + ", $cards);
                }

                if(empty($treasureBax["star"])) {
                    $goodList[$key]["zhadanStr"] = DefaultEnum::ISNOTRESULT;
                } else {
                    $treasureBax["star"] != 11 ? $goodList[$key]["zhadanStr"] = $treasureBax["star"] . '星' : $goodList[$key]["zhadanStr"] = "烧鸡重新发牌{$treasureBax["zdFpCount"]}次";
                }
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
     * @notes 删除商品
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function delGoods(array $params):bool
    {
        Db::connect('mongodb')->table('goods')->where(['_id' => new ObjectId($params['_id'])])->delete();
        return true;

    }

    /**
     * @notes 编辑商品
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function setGoodInfo(array $params):bool
    {
        Db::connect('mongodb')->table('goods')->where(['_id' => new ObjectId($params['_id'])])->update([
            "goodsType" => intval($params["goodsType"]),
            "amount" => intval($params["amount"]),
            "applePriceId" => $params["applePriceId"],
            "androidPrice" => intval($params["androidPrice"]* 100),
            "firstTimeAmount" => intval($params["firstTimeAmount"]) ?? 0,
            "isOnline" => $params["isOnline"] == 1,
            "boxId" => $params["boxId"] ?? "",
            "boxCount" => $params["boxCount"] ?? 0
        ]);
        return true;

    }

    /**
     * @notes 新增商品
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function addGoods(array $params):bool
    {
        $count = Db::connect('mongodb')->table('goods')->where("amount", intval($params["amount"]))->count();
        if($count > 0) return false;

        Db::connect('mongodb')->table('goods')->insert([
            "goodsType" => intval($params["goodsType"]),
            "amount" => intval($params["amount"]),
            "applePriceId" => $params["applePriceId"],
            "androidPrice" => intval($params["androidPrice"] * 100),
            "firstTimeAmount" => intval($params["firstTimeAmount"]) ?? 0,
            "isOnline" => $params["isOnline"] == 1,
            "originPrice" => 0,
            "boxId" => $params["boxId"] ?? "",
            "boxCount" => $params["boxCount"] ?? 0
        ]);

        return true;
    }
}
