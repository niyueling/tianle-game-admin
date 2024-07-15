<?php


namespace app\adminapi\logic\email;

use app\common\enum\DefaultEnum;
use app\common\logic\BaseLogic;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use think\facade\Db;

/**
 * 邮件逻辑层
 * Class NoticeLogic
 * @package app\adminapi\logic\notice
 */
class EmailLogic extends BaseLogic
{
    /**
     * @notes  邮件列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function lists($params)
    {
        $where = [];
        $playerIds = [];
        $isSearch = false;


        if(!empty($params["realName"])) {
            $isSearch = true;
            $accounts = Db::connect('mongodb')->table('accounts')
                ->where("realName", "like", fuzzy_query($params["clubName"]))->select();
            if(!empty($accounts)) {
                foreach($accounts as $item) array_push($playerIds, $item["player"]);
            }
        }

        if(!empty($params["name"])) {
            $isSearch = true;
            $players = Db::connect('mongodb')->table('players')
                ->where("name", "like", fuzzy_query($params["name"]))->select();
            if(!empty($players)) {
                foreach($players as $item)
                    if(!empty($item) && !in_array($item["_id"], $playerIds))
                        array_push($playerIds, $item["_id"]);
            }
        }

        if(!empty($params["playerPhone"])) {
            $isSearch = true;
            $player = Db::connect('mongodb')->table('players')->where("phone", "like", fuzzy_query($params["clubName"]))->find();
            if(!empty($player) && !in_array($player["_id"], $playerIds)) array_push($playerIds, $player["_id"]);
        }

        if(!empty($params["playerShortId"])){
            $isSearch = true;
            $player = Db::connect('mongodb')->table('players')->where("shortId", intval($params["playerShortId"]))->find();
            if(!empty($player) && !in_array($player["_id"], $playerIds)) array_push($playerIds, $player["_id"]);
        }
        if(empty($playerIds) && $isSearch) return nullResultReturn();
        if(!empty($playerIds)) $where[] = ["to", "in", $playerIds];
        if(!empty($params["title"])) $where[] = ["title", "like", fuzzy_query($params["title"])];
        if(!empty($params["state"])) $where[] = ["state", "=", intval($params["state"])];
        if(!empty($params["giftState"])) $where[] = ["giftState", "=", intval($params["giftState"])];
        if(!empty($params["type"])) $where[] = ["type", "=", $params["type"]];
        if(!empty($params["start_time"]) && !empty($params["end_time"]))
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];

        try {
            $count = Db::connect('mongodb')->table('mails')->where($where)->count();
            $emailsList = Db::connect('mongodb')->table('mails')->where($where)->order("createAt", "desc")
                ->page($params["page_no"], $params["page_size"])->select()->all();

            foreach ($emailsList as $key => $value) {
                $emailsList[$key]["isGiftEmail"] = false;
                if($value["gift"]["gem"] || $value["gift"]["ruby"] || $value["gift"]["gold"])
                    $emailsList[$key]["isGiftEmail"] = true;

                $player = Db::connect('mongodb')->table('players')->where("_id", $value["to"])->find();
                $emailsList[$key]["playerShortId"] = $player["shortId"];
                $emailsList[$key]["username"] = $player["name"];
                $emailsList[$key]["phone"] = !empty($player["phone"]) ? $player["phone"] : DefaultEnum::ISNOTRESULT;

                $emailsList[$key]["typeStr"] = DefaultEnum::EMAILTYPE[$emailsList[$key]["type"]];
                $emailsList[$key]["stateStr"] = DefaultEnum::EMAILSTATE[$emailsList[$key]["state"]];
                $emailsList[$key]["giftStateStr"] = $emailsList[$key]["isGiftEmail"] ?
                    DefaultEnum::GIFTSTATE[$emailsList[$key]["giftState"]] : DefaultEnum::ISNOTRESULT;
                $emailsList[$key]["_id"] = reset($emailsList[$key]["_id"]);
                $emailsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($emailsList[$key]["createAt"]) / 1000));
            }

            return [
                "count" => $count,
                "lists" => $emailsList,
                "page_no" => $params["page_no"],
                "page_size" => $params["page_size"]
            ];
        } catch(\Exception $e) {
            var_dump($e);
        }


    }

    /**
     * @notes  用户战队列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function userClubLists($params)
    {
        $player = Db::connect('mongodb')->table('players')->where("shortId", intval($params["shortId"]))->find();
        if(empty($player)) return [];
        $lists = Db::connect('mongodb')->table('clubmembers')->where("member", $player["_id"])->select()->toArray();

        foreach ($lists as $key => $value) {
            $club = Db::connect('mongodb')->table('clubs')->where("_id", $value["club"])->find();
            $lists[$key]["clubName"] = $club["name"];
            $lists[$key]["club"] = reset($lists[$key]["club"]);
            $lists[$key]["joinAt"] = date('Y-m-d H:i:s',intval(reset($lists[$key]["joinAt"]) / 1000));
        }

        return $lists;
    }

    /**
     * @notes 添加邮件
     * @param $params
     * @return bool
     * @author ljj
     * @date 2021/8/23 2:00 下午
     */
    public function add($params)
    {
        $player = Db::connect('mongodb')->table('players')->where("shortId", intval($params["playerShortId"]))->find();
        if(empty($player)) return false;
        Db::connect('mongodb')->table('mails')->insert([
            "type" => $params["type"],
            "state" => 1,
            "giftState" => 1,
            "to" => $player["_id"],
            "title" => $params["title"],
            "content" => $params["content"],
            "gift" => ["gem" => intval($params["gem"]) ?? 0, "ruby" => intval($params["ruby"]) ?? 0, "gold" => intval($params["gold"]), "clubId" => $params["clubId"] ?? '',"source" => "admin"],
            "createAt" => new UTCDateTime(time() * 1000)
        ]);
        return true;
    }

    /**
     * @notes 删除邮件
     * @param $params
     * @return bool
     * @author ljj
     * @date 2021/8/23 3:04 下午
     */
    public function del($params)
    {
        return Db::connect('mongodb')->table('mails')->where("_id", new ObjectId($params["_id"]))->delete();
    }

    /**
     * @notes  公共邮件列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function publicLists($params)
    {
        $where = [["clubOwnerOnly", "=", $params["clubOwnerOnly"] == 1]];

        if(!empty($params["title"])) $where[] = ["title", "like", fuzzy_query($params["title"])];
        if(!empty($params["type"])) $where[] = ["type", "=", $params["type"]];
        if(!empty($params["start_time"]) && !empty($params["end_time"]))
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];

        $count = Db::connect('mongodb')->table('publicmails')->where($where)->count();
        $emailsList = Db::connect('mongodb')->table('publicmails')->where($where)->order("createAt", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($emailsList as $key => $value) {
            $emailsList[$key]["typeStr"] = DefaultEnum::EMAILTYPE[$emailsList[$key]["type"]];
            $emailsList[$key]["_id"] = reset($emailsList[$key]["_id"]);
            $emailsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($emailsList[$key]["createAt"]) / 1000));
        }

        return [
            "count" => $count,
            "lists" => $emailsList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 添加公共邮件
     * @param $params
     * @return bool
     * @author ljj
     * @date 2021/8/23 2:00 下午
     */
    public function addPublicEmail($params)
    {
        Db::connect('mongodb')->table('publicmails')->insert([
            "type" => $params["type"],
            "clubOwnerOnly" => $params["clubOwnerOnly"] == 1,
            "title" => $params["title"],
            "content" => $params["content"],
            "gift" => ["gem" => intval($params["gem"]) ?? 0, "ruby" => intval($params["ruby"]) ?? 0, "gold" => intval($params["gold"]), "clubId" => $params["clubId"] ?? '',"source" => "admin"],
            "createAt" => new UTCDateTime(time() * 1000)
        ]);
        return true;
    }

    /**
     * @notes 删除公共邮件
     * @param $params
     * @return bool
     * @author ljj
     * @date 2021/8/23 3:04 下午
     */
    public function delPublicEmail($params)
    {
        return Db::connect('mongodb')->table('publicmails')->where("_id", new ObjectId($params["_id"]))->delete();
    }

    /**
     * 邮件逻辑层
     * Class NoticeLogic
     * @package app\adminapi\logic\notice
     */

    /**
     * @notes  公共/圈主邮件领取列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function receiveLists($params)
    {
        $where = [["mail", "=", $params["mail"]]];
        $playerIds = [];
        $isSearch = false;


        if(!empty($params["realName"])) {
            $isSearch = true;
            $accounts = Db::connect('mongodb')->table('accounts')->where("realName", "like", fuzzy_query($params["clubName"]))->select();
            if(!empty($accounts)) {
                foreach($accounts as $item) array_push($playerIds, $item["player"]);
            }
        }

        if(!empty($params["name"])) {
            $isSearch = true;
            $players = Db::connect('mongodb')->table('players')
                ->where("name", "like", fuzzy_query($params["name"]))->select();
            if(!empty($players)) {
                foreach($players as $item)
                    if(!empty($item) && !in_array($item["_id"], $playerIds))
                        array_push($playerIds, $item["_id"]);
            }
        }

        if(!empty($params["playerPhone"])) {
            $isSearch = true;
            $player = Db::connect('mongodb')->table('players')->where("phone", "like", fuzzy_query($params["clubName"]))->find();
            if(!empty($player) && !in_array($player["_id"], $playerIds)) array_push($playerIds, $player["_id"]);
        }

        if(!empty($params["playerShortId"])){
            $isSearch = true;
            $player = Db::connect('mongodb')->table('players')->where("shortId", intval($params["playerShortId"]))->find();
            if(!empty($player) && !in_array($player["_id"], $playerIds)) array_push($playerIds, $player["_id"]);
        }
        if(empty($playerIds) && $isSearch) return nullResultReturn();
        if(!empty($playerIds)) $where[] = ["player", "in", $playerIds];
        if(!empty($params["state"])) $where[] = ["state", "=", intval($params["state"])];
        if(!empty($params["giftState"])) $where[] = ["giftState", "=", intval($params["giftState"])];
        $count = Db::connect('mongodb')->table('publicmailrecords')->where($where)->count();
        $emailsList = Db::connect('mongodb')->table('publicmailrecords')->where($where)
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($emailsList as $key => $value) {
            $player = Db::connect('mongodb')->table('players')->where("_id", $value["player"])->find();
            $emailsList[$key]["playerShortId"] = $player["shortId"];
            $emailsList[$key]["username"] = $player["name"];
            $emailsList[$key]["phone"] = !empty($player["phone"]) ? $player["phone"] : DefaultEnum::ISNOTRESULT;

            $emailsList[$key]["stateStr"] = DefaultEnum::EMAILSTATE[$emailsList[$key]["state"]];
            $email = Db::connect('mongodb')->table('publicmails')
                ->where("_id", new ObjectId($emailsList[$key]["mail"]))->find();
            $emailsList[$key]["title"] = $email["title"];
            $emailsList[$key]["content"] = $email["content"];
            $emailsList[$key]["giftStateStr"] = $email["type"] == "noticeGift" ?
                DefaultEnum::GIFTSTATE[$emailsList[$key]["giftState"]] : DefaultEnum::ISNOTRESULT;
            $emailsList[$key]["_id"] = reset($emailsList[$key]["_id"]);
        }

        return [
            "count" => $count,
            "lists" => $emailsList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }
}
