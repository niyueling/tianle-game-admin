<?php

namespace app\adminapi\logic\marketing;

use app\common\logic\BaseLogic;
use MongoDB\BSON\ObjectId;
use think\facade\Db;

/**
 * 营销
 * Class CenterLogic
 * @package app\adminapi\logic
 */
class ConversionLogic extends BaseLogic
{
    /**
     * @notes 兑换管理
     * @return array
     * @author Tab
     * @date 2021/9/27 10:31
     */
    public static function lists($params)
    {
        $count = Db::connect('mongodb')->table('goodsexchangerubies')->count();
        $goodList = Db::connect('mongodb')->table('goodsexchangerubies')
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($goodList as $key => $value) {
            $goodList[$key]["rubyAmountStr"] = formatNumber($goodList[$key]["rubyAmount"]);
            $goodList[$key]["_id"] = reset($goodList[$key]["_id"]);
        }

        return [
            "count" => $count,
            "lists" => $goodList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 兑换删除
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function del(array $params):bool
    {
        Db::connect('mongodb')->table('goodsexchangerubies')->where(['_id' => new ObjectId($params['_id'])])->delete();
        return true;

    }

    /**
     * @notes 兑换编辑
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function setInfo(array $params):bool
    {
        Db::connect('mongodb')->table('goodsexchangerubies')->where(['_id' => new ObjectId($params['_id'])])->update([
            "gemCount" => intval($params["gemCount"]),
            "rubyAmount" => intval($params["rubyAmount"])
        ]);

        return true;
    }

    /**
     * @notes 兑换新增
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function add(array $params):bool
    {
        Db::connect('mongodb')->table('goodsexchangerubies')->insert([
            "gemCount" => intval($params["gemCount"]),
            "rubyAmount" => intval($params["rubyAmount"])
        ]);
        return true;

    }
}
