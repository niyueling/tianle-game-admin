<?php

namespace app\adminapi\logic\marketing;

use app\common\logic\BaseLogic;
use MongoDB\BSON\ObjectId;
use think\facade\Db;

/**
 * 助威管理
 * Class CenterLogic
 * @package app\adminapi\logic
 */
class CheerLogic extends BaseLogic
{
    /**
     * @notes 助威管理
     * @return array
     * @author Tab
     * @date 2021/9/27 10:31
     */
    public static function lists($params)
    {
        $count = Db::connect('mongodb')->table('luckyblesses')->count();
        $goodList = Db::connect('mongodb')->table('luckyblesses')
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($goodList as $key => $value) {
            $goodList[$key]["_id"] = reset($goodList[$key]["_id"]);
            $goodList[$key]["timesStr"] = min($goodList[$key]["times"]) . '~' . max($goodList[$key]["times"]) . '倍';
            $goodList[$key]["gemStr"] = min($goodList[$key]["gem"]) . '~' . max($goodList[$key]["gem"]) . '钻石';
            $goodList[$key]["blessStr"] = min($goodList[$key]["bless"]) * 100 . '%~' . max($goodList[$key]["bless"]) * 100 . '%运势';
        }

        return [
            "count" => $count,
            "lists" => $goodList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 助威删除
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function del(array $params):bool
    {
        Db::connect('mongodb')->table('luckyblesses')->where(['_id' => new ObjectId($params['_id'])])->delete();
        return true;

    }

    /**
     * @notes 助威编辑
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function setInfo(array $params):bool
    {
        Db::connect('mongodb')->table('luckyblesses')->where(['_id' => new ObjectId($params['_id'])])->update([
            "name" => $params["name"],
            "times" => array_map('intval', $params["times"]),
            "gem" => array_map('intval', $params["gem"]),
            "bless" => array_map('intval', $params["bless"]),
            "orderIndex" => intval($params["orderIndex"])
        ]);

        return true;
    }

    /**
     * @notes 助威新增
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function add(array $params):bool
    {
        $count = Db::connect('mongodb')->table('luckyblesses')
            ->where("name", $params["name"])->count();
        if($count > 0) return false;

        Db::connect('mongodb')->table('luckyblesses')->insert([
            "name" => $params["name"],
            "times" => array_map('intval', $params["times"]),
            "gem" => array_map('intval', $params["gem"]),
            "bless" => array_map('intval', $params["bless"]),
            "orderIndex" => intval($params["orderIndex"])
        ]);
        return true;

    }
}
