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
class RedpocketLogic extends BaseLogic
{
    /**
     * @notes 红包管理
     * @return array
     * @author Tab
     * @date 2021/9/27 10:31
     */
    public static function redPocketLists($params)
    {
        $where = [];
        if (!empty($params['game'])) $where[] = ["game", "=", $params['game']];

        $count = Db::connect('mongodb')->table('rewardconfigs')->where($where)->count();
        $regionList = Db::connect('mongodb')->table('rewardconfigs')->where($where)
            ->page($params["page_no"], $params["page_size"])->select()->all();

        $sort = array_column($regionList, 'redPocket');
        array_multisort($sort, SORT_ASC, $regionList);

        foreach ($regionList as $key => $value) {
            $regionList[$key]["gameStr"] = DefaultEnum::GAMElIST[$value["game"]];
            $regionList[$key]["typeStr"] = DefaultEnum::REDPOCKETTYPE[$value["type"]];
            $regionList[$key]["redPocket"] = round($value["redPocket"] / 100, 2);
            $regionList[$key]["probability"] = round($value["probability"] * 100, 2) . '%';
            $regionList[$key]["probabilitys"] = round($value["probability"] * 100, 1);
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
     * @notes 删除红包
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function delRedPocket(array $params):bool
    {
        Db::connect('mongodb')->table('rewardconfigs')->where(['_id' => new ObjectId($params['_id'])])->delete();
        return true;
    }

    /**
     * @notes 编辑红包
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function setRedPocketInfo(array $params):string
    {
        $game = Db::connect('mongodb')->table('rewardconfigs')
            ->where(['game' => $params['game'], 'type' => $params['type'], 'redPocket' => $params['redPocket']])->find();
        if(!empty($game)) return "该游戏对应数额红包已经创建";

        $totalProbability = Db::connect('mongodb')->table('rewardconfigs')
            ->where(['game' => $params['game'], 'type' => $params['type']])
            ->where("_id", "<>", new ObjectId($params['_id']))->sum("probability");
        if($totalProbability + round($params["probabilitys"] / 100, 3) > 1) return "红包概率累加不能超过1";

        Db::connect('mongodb')->table('rewardconfigs')->where(['_id' => new ObjectId($params['_id'])])->update([
            "game" => $params["game"],
            "type" => $params["type"],
            "redPocket" => intval($params["redPocket"] * 100),
            'probability' => round($params["probabilitys"] / 100, 3)
        ]);
        return "success";

    }

    /**
     * @notes 新增红包
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function addRedPocket(array $params):string
    {
        $game = Db::connect('mongodb')->table('rewardconfigs')
            ->where(['game' => $params['game'], 'type' => $params['type'], 'redPocket' => $params['redPocket']])->find();
        if(!empty($game)) return "该游戏对应数额红包已经创建";

        $totalProbability = Db::connect('mongodb')->table('rewardconfigs')
            ->where(['game' => $params['game'], 'type' => $params['type']])->sum("probability");
        if($totalProbability + round($params["probabilitys"] / 100, 3) > 1) return "红包概率累加不能超过1";

        Db::connect('mongodb')->table('rewardconfigs')->insert([
            "game" => $params["game"],
            "type" => $params["type"],
            "redPocket" => intval($params["redPocket"] * 100),
            "probability" => round($params["probabilitys"] / 100, 3)
        ]);
        return "success";

    }
}
