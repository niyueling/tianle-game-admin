<?php

namespace app\adminapi\logic\marketing;

use app\common\logic\BaseLogic;
use MongoDB\BSON\ObjectId;
use think\facade\Db;

/**
 * 县区管理
 * Class CenterLogic
 * @package app\adminapi\logic
 */
class RegionLogic extends BaseLogic
{
    /**
     * @notes 县区管理
     * @return array
     * @author Tab
     * @date 2021/9/27 10:31
     */
    public static function regionLists($params)
    {
        $where = [];
        if (!empty($params['city'])) $where[] = ["city", "=", $params['city']];
        if (!empty($params['county'])) $where[] = ["county", "=", $params['county']];

        $count = Db::connect('mongodb')->table('regions')->where($where)->count();
        $regionList = Db::connect('mongodb')->table('regions')->where($where)
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($regionList as $key => $value) {
            $regionList[$key]["_id"] = reset($regionList[$key]["_id"]);
        }

        return [
            "count" => $count,
            "lists" => $regionList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 删除县区
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function delRegion(array $params):bool
    {
        Db::connect('mongodb')->table('regions')->where(['_id' => new ObjectId($params['_id'])])->delete();
        return true;

    }

    /**
     * @notes 编辑县区
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function setRegionInfo(array $params):bool
    {
        Db::connect('mongodb')->table('regions')->where(['_id' => new ObjectId($params['_id'])])->update([
            "city" => $params["city"],
            "county" => $params["county"]
        ]);
        return true;

    }

    /**
     * @notes 新增县区
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function addRegion(array $params):bool
    {
        $count = Db::connect('mongodb')->table('regions')->where("city", $params["city"])
            ->where("county", $params["county"])->count();
        if($count > 0) return false;

        Db::connect('mongodb')->table('regions')->insert([
            "city" => $params["city"],
            "county" => $params["county"]
        ]);
        return true;

    }
}