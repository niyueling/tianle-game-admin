<?php

namespace app\adminapi\logic\marketing;

use app\common\enum\DefaultEnum;
use app\common\logic\BaseLogic;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use think\facade\Db;

/**
 * 县区管理
 * Class CenterLogic
 * @package app\adminapi\logic
 */
class CategoryLogic extends BaseLogic
{
    /**
     * @notes 场次列表
     * @return array
     * @author Tab
     * @date 2021/9/27 10:31
     */
    public static function lists($params)
    {
        $where = [];

        if (!empty($params['title'])) $where[] = ["title", "=", $params['title']];
        if (!empty($params['level'])) $where[] = ["level", "=", $params['level']];
        if (!empty($params['category'])) $where[] = ["category", "=", $params['category']];

        $count = Db::connect('mongodb')->table('gamecategories')->where($where)->count();
        $lists = Db::connect('mongodb')->table('gamecategories')->where($where)
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($lists as $key => $value) {
            $lists[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($lists[$key]["createAt"]) / 1000));
            $lists[$key]["_id"] = reset($lists[$key]["_id"]);
            $lists[$key]["minAmountStr"] = $lists[$key]["minAmount"] != 0 ? formatNumber($lists[$key]["minAmount"]) : "无限制";
            $lists[$key]["maxAmountStr"] = $lists[$key]["maxAmount"] != 0 ? formatNumber($lists[$key]["maxAmount"]) : "无限制";
            $lists[$key]["openStr"] = $lists[$key]["isOpen"] ? "正常" : "暂停";
            $lists[$key]["isOpen"] = $lists[$key]["isOpen"] ? 1 : 2;
            $lists[$key]["minScoreStr"] = formatNumber($lists[$key]["minScore"]);
            $lists[$key]["categoryStr"] = DefaultEnum::GAMECATEGORYlIST[$lists[$key]["category"]];
            if(!empty($lists[$key]["game"]) && !empty($lists[$key]["gameCategory"])) {
                $lists[$key]["gameStr"] = DefaultEnum::GAMElIST[$lists[$key]["game"]];
                $item = Db::connect('mongodb')->table('gameitems')->where(['_id' => new ObjectId($lists[$key]['gameCategory'])])->find();
                $lists[$key]["gameCategoryStr"] = $item["name"];
                $lists[$key]["isOpenDoubleStr"] = DefaultEnum::openDoubleLists[$lists[$key]["isOpenDouble"] ?? false];
            }
        }

        return [
            "count" => $count,
            "lists" => $lists,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 删除场次
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function del(array $params):bool
    {
        Db::connect('mongodb')->table('gamecategories')->where(['_id' => new ObjectId($params['_id'])])->delete();
        return true;
    }

    /**
     * @notes 编辑场次
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function setInfo(array $params):string
    {
        $category = Db::connect('mongodb')->table('gamecategories')
            ->where(['category' => $params['category'], 'level' => $params['level']])->find();
        if(!empty($category)) return "该场次已经存在";

        Db::connect('mongodb')->table('gamecategories')->where(['_id' => new ObjectId($params['_id'])])->update([
            "title" => $params["title"],
            "game" => $params["game"],
            "gameCategory" => $params["gameCategory"],
            "level" => intval($params["level"]),
            "category" => $params["category"],
            "minScore" => intval($params["minScore"]),
            "minAmount" => intval($params["minAmount"]) ?? 0,
            "maxAmount" => intval($params["maxAmount"]) ?? 0,
            "roomRate" => intval($params["roomRate"]) ?? 0,
            "playerCount" => intval($params["playerCount"]) ?? 0,
            "isOpen" => $params["isOpen"] == 1,
            "isOpenDouble" => $params["isOpenDouble"] == 1,
        ]);
        return "success";

    }

    /**
     * @notes 新增场次
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function add(array $params):string
    {
        $category = Db::connect('mongodb')->table('gamecategories')
            ->where(['category' => $params['category'], 'level' => $params['level']])->find();
        if(!empty($category)) return "该场次已经存在";

        Db::connect('mongodb')->table('gamecategories')->insert([
            "title" => $params["title"],
            "game" => $params["game"],
            "gameCategory" => $params["gameCategory"],
            "level" => intval($params["level"]),
            "category" => $params["category"],
            "minScore" => intval($params["minScore"]),
            "minAmount" => intval($params["minAmount"]) ?? 0,
            "maxAmount" => intval($params["maxAmount"]) ?? 0,
            "roomRate" => intval($params["roomRate"]) ?? 0,
            "playerCount" => intval($params["playerCount"]) ?? 0,
            "isOpen" => $params["isOpen"] == 1,
            "isOpenDouble" => $params["isOpenDouble"] == 1,
            "createAt" => new UTCDateTime(time() * 1000)
        ]);

        return "success";
    }

    /**
     * @notes 游戏子分类列表
     * @return array
     * @author Tab
     * @date 2021/9/27 10:31
     */
    public static function gameItemList()
    {
        $lists = Db::connect('mongodb')->table('gameitems')->select()->toArray();

        foreach ($lists as $key => $value) {
            $lists[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($lists[$key]["createAt"]) / 1000));
            $lists[$key]["_id"] = reset($lists[$key]["_id"]);
        }

        return $lists;
    }
}
