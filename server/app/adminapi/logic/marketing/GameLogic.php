<?php

namespace app\adminapi\logic\marketing;

use app\common\enum\DefaultEnum;
use app\common\logic\BaseLogic;
use MongoDB\BSON\ObjectId;
use think\facade\Db;

/**
 * 县区管理
 * Class CenterLogic
 * @package app\adminapi\logic
 */
class GameLogic extends BaseLogic
{
    /**
     * @notes 游戏区域管理
     * @return array
     * @author Tab
     * @date 2021/9/27 10:31
     */
    public static function gameLists($params)
    {
        $where = [];
        $whereRegion = [];
        $regionIds = [];

        if (!empty($params['gameName'])) $where[] = ["gameName", "=", $params['gameName']];
        if (!empty($params['city'])) $whereRegion[] = ["city", "=", $params['city']];
        if (!empty($params['county'])) $whereRegion[] = ["county", "=", $params['county']];

        if(!empty($whereRegion)) {
            $regionList = Db::connect('mongodb')->table('regions')->where($whereRegion)->select();
            foreach ($regionList as $region) array_push($regionIds, $region["_id"]);
            if(!empty($regionIds)) $where[] = ["region", "in", $regionIds];
        }

        $count = Db::connect('mongodb')->table('regiongames')->where($where)->count();
        $regionList = Db::connect('mongodb')->table('regiongames')->where($where)
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($regionList as $key => $value) {
            $region = Db::connect('mongodb')->table('regions')->where("_id", $value["region"])->find();
            $regionList[$key]["city"] = $region["city"];
            $regionList[$key]["county"] = $region["county"];
            $regionList[$key]["_id"] = reset($regionList[$key]["_id"]);
            $regionList[$key]["gameNameStr"] = DefaultEnum::GAMElIST[$regionList[$key]["gameName"]];
        }

        return [
            "count" => $count,
            "lists" => $regionList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 游戏区域搜索条件
     * @return array
     * @author Tab
     * @date 2021/9/27 10:31
     */
    public static function searchLists()
    {
        $regionList = Db::connect('mongodb')->table('regions')->select();
        $countyList = [];
        $cityList = [];

        foreach ($regionList as $key => $value) {
            if(!in_array($value["city"], $cityList)) array_push($cityList, $value["city"]);
            if(empty($countyList[$value["city"]])) $countyList[$value["city"]] = [$value["county"]];
            else $countyList[$value["city"]][] = $value["county"];
        }
        $gameList = DefaultEnum::GAMElIST;

        return [
            "city" => $cityList,
            "county" => $countyList,
            "game" => $gameList
        ];
    }

    /**
     * @notes 删除游戏区域
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function delGame(array $params):bool
    {
        Db::connect('mongodb')->table('regiongames')->where(['_id' => new ObjectId($params['_id'])])->delete();
        return true;
    }

    /**
     * @notes 编辑游戏区域
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function setGameInfo(array $params):string
    {
        $region = Db::connect('mongodb')->table('regions')
            ->where(['city' => $params['city'], 'county' => $params['county']])->find();
        if(empty($region)) return "游戏区域不存在";

        $game = Db::connect('mongodb')->table('regiongames')
            ->where(['region' => $region['_id'], 'gameName' => $params['gameName']])->find();
        if(!empty($game)) return "该区域游戏已经存在";

        Db::connect('mongodb')->table('regiongames')->where(['_id' => new ObjectId($params['_id'])])->update([
            "region" => $region["_id"],
            "gameName" => $params["gameName"]
        ]);
        return "success";

    }

    /**
     * @notes 新增游戏区域
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function addGame(array $params):string
    {
        $region = Db::connect('mongodb')->table('regions')
            ->where(['city' => $params['city'], 'county' => $params['county']])->find();
        if(empty($region)) return "游戏区域不存在";

        $game = Db::connect('mongodb')->table('regiongames')
            ->where(['region' => $region['_id'], 'gameName' => $params['gameName']])->find();
        if(!empty($game)) return "该区域游戏已经存在";

        Db::connect('mongodb')->table('regiongames')->insert([
            "region" => $region["_id"],
            "gameName" => $params["gameName"]
        ]);
        return "success";

    }
}