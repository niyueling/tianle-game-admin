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
class GameCategoryLogic extends BaseLogic
{
    /**
     * @notes 游戏子分类列表
     * @return array
     * @author Tab
     * @date 2021/9/27 10:31
     */
    public static function lists($params)
    {
        $where = [];
        if (!empty($params['name'])) $where[] = ["name", "=", $params['name']];
        if (!empty($params['gameType'])) $where[] = ["gameType", "=", $params['gameType']];

        $count = Db::connect('mongodb')->table('gameitems')->where($where)->count();
        $lists = Db::connect('mongodb')->table('gameitems')->where($where)
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($lists as $key => $value) {
            $lists[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($lists[$key]["createAt"]) / 1000));
            $lists[$key]["_id"] = reset($lists[$key]["_id"]);
            $lists[$key]["game"] = DefaultEnum::GAMElIST[$lists[$key]["gameType"]];
            $lists[$key]["openStr"] = $lists[$key]["isOpen"] ? "正常" : "暂停";
            $lists[$key]["isOpen"] = $lists[$key]["isOpen"] ? 1 : 2;
        }

        return [
            "count" => $count,
            "lists" => $lists,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 删除游戏子分类
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function del(array $params):bool
    {
        Db::connect('mongodb')->table('gameitems')->where(['_id' => new ObjectId($params['_id'])])->delete();
        return true;
    }

    /**
     * @notes 编辑游戏子分类
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function setInfo(array $params):string
    {
        $category = Db::connect('mongodb')->table('gameitems')
            ->where(['gameType' => $params['gameType'], 'name' => $params['name']])->find();
        if(!empty($category)) return "该游戏分类已经存在";

        Db::connect('mongodb')->table('gameitems')->where(['_id' => new ObjectId($params['_id'])])->update([
            "name" => $params["name"],
            "gameType" => $params["gameType"],
            "isOpen" => $params["isOpen"] == 1
        ]);
        return "success";

    }

    /**
     * @notes 新增游戏子分类
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function add(array $params):string
    {
        $category = Db::connect('mongodb')->table('gameitems')
            ->where(['gameType' => $params['gameType'], 'name' => $params['name']])->find();
        if(!empty($category)) return "该游戏分类已经存在";

        Db::connect('mongodb')->table('gameitems')->insert([
            "name" => $params["name"],
            "gameType" => $params["gameType"],
            "isOpen" => $params["isOpen"] == 1,
            "createAt" => new UTCDateTime(time() * 1000)
        ]);
        return "success";

    }
}
