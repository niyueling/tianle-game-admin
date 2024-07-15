<?php

namespace app\adminapi\logic\notice;

use app\common\logic\BaseLogic;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use think\facade\Db;

class NoticeLogic extends BaseLogic
{
    /**
     * @notes  公告列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function lists($params)
    {
        $count = Db::connect('mongodb')->table('notices')->count();
        $notesList = Db::connect('mongodb')->table('notices')
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($notesList as $key => $value) {
            $notesList[$key]["_id"] = reset($notesList[$key]["_id"]);
            $notesList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($notesList[$key]["createAt"]) / 1000));
        }

        return [
            "count" => $count,
            "lists" => $notesList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 添加公告
     * @param $params
     * @return bool
     * @author ljj
     * @date 2021/8/23 2:00 下午
     */
    public function add($params)
    {
        Db::connect('mongodb')->table('notices')->insert([
            "message" => $params["message"],
            "createAt" => new UTCDateTime(time() * 1000)
        ]);
        return true;
    }

    /**
     * @notes 编辑公告
     * @param $params
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/8/23 2:41 下午
     */
    public function edit($params)
    {
        Db::connect('mongodb')->table('notices')->where("_id", new ObjectId($params["_id"]))->update([
            "message" => $params["message"],
            "createAt" => new UTCDateTime(time() * 1000)
        ]);
        return true;
    }

    /**
     * @notes 删除公告
     * @param $params
     * @return bool
     * @author ljj
     * @date 2021/8/23 3:04 下午
     */
    public function del($params)
    {
        return Db::connect('mongodb')->table('notices')->where("_id", new ObjectId($params["_id"]))->delete();
    }
}