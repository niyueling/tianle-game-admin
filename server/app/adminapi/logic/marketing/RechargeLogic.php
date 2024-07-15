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
class RechargeLogic extends BaseLogic
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

        $count = Db::connect('mongodb')->table('rechargetemplates')->where($where)->count();
        $goodList = Db::connect('mongodb')->table('rechargetemplates')->where($where)
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($goodList as $key => $value) {
            $goodList[$key]["_id"] = reset($goodList[$key]["_id"]);
            $goodList[$key]["iosPrice"] = DefaultEnum::IOSPRICE[$goodList[$key]["applePriceId"]];
            $goodList[$key]["level"] = DefaultEnum::AGENCYRECHARGELEVEL[$goodList[$key]["level"]];
            $goodList[$key]["androidPrice"] = round($goodList[$key]["androidPrice"] / 100, 2);
            $goodList[$key]["isOnline"] = $goodList[$key]["isOnline"] ? 1 : 2;
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
        Db::connect('mongodb')->table('rechargetemplates')->where(['_id' => new ObjectId($params['_id'])])->delete();
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
        Db::connect('mongodb')->table('rechargetemplates')->where(['_id' => new ObjectId($params['_id'])])->update([
            "goodsType" => intval($params["goodsType"]),
            "amount" => intval($params["amount"]),
            "applePriceId" => $params["applePriceId"],
            "androidPrice" => intval($params["androidPrice"] * 100),
            "extraAmount" => intval($params["extraAmount"]) ?? 0,
            "level" => intval($params["level"]),
            "isOnline" => $params["isOnline"] == 1,
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
        $count = Db::connect('mongodb')->table('rechargetemplates')
            ->where("amount", intval($params["amount"]))->where("level", intval($params["level"]))->count();
        if($count > 0) return false;

        Db::connect('mongodb')->table('rechargetemplates')->insert([
            "goodsType" => intval($params["goodsType"]),
            "amount" => intval($params["amount"]),
            "applePriceId" => $params["applePriceId"],
            "androidPrice" => intval($params["androidPrice"] * 100),
            "extraAmount" => intval($params["extraAmount"]) ?? 0,
            "isOnline" => $params["isOnline"] == 1,
            "level" => intval($params["level"]),
            "originPrice" => 0
        ]);
        return true;

    }
}
