<?php

namespace app\adminapi\logic\club;
use app\common\enum\DefaultEnum;
use app\common\logic\BaseLogic;
use app\common\service\TimeService;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Db;

/**
 * 俱乐部逻辑层
 * Class UserLogic
 * @package app\adminapi\logic\user
 */
class ClubLogic extends BaseLogic
{
    /**
     * @notes  俱乐部列表
     * @param $params
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public static function lists($params): array
    {
        $where = [];
        $playerIds = [];
        $isSearch = false;

        if(!empty($params["realName"])) {
            $isSearch = true;
            $accounts = Db::connect('mongodb')->table('accounts')
                ->where("realName", "like", fuzzy_query($params["clubName"]))->select();
            if(!empty($accounts)) {
                foreach($accounts as $item) $playerIds[] = $item["player"];
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
            $player = Db::connect('mongodb')->table('players')
                ->where("phone", "like", fuzzy_query($params["clubName"]))->find();
            if(!empty($player) && !in_array($player["_id"], $playerIds)) array_push($playerIds, $player["_id"]);
        }

        if(!empty($params["playerShortId"])){
            $isSearch = true;
            $player = Db::connect('mongodb')->table('players')
                ->where("shortId", intval($params["playerShortId"]))->find();
            if(!empty($player) && !in_array($player["_id"], $playerIds)) array_push($playerIds, $player["_id"]);
        }

        if(empty($playerIds) && $isSearch) return nullResultReturn();

        if(!empty($params["clubShortId"])) $where[] = ["shortId", "=", intval($params["clubShortId"])];
        if(!empty($params["club_ids"])) {
            foreach ($params["club_ids"] as $key => $value) $params["club_ids"][$key] = intval($params["club_ids"][$key]);
            $where[] = ["shortId", "in", $params["club_ids"]];
        }
        if(!empty($playerIds)) $where[] = ["owner", "in", $playerIds];
        if(!empty($params["clubName"])) $where[] = ["name", "like", fuzzy_query($params["clubName"])];
        if(!empty($params["state"])) $where[] = ["state", "=", $params["state"]];

        $count = Db::connect('mongodb')->table('clubs')->where($where)->count();
        $clubsList = Db::connect('mongodb')->table('clubs')->where($where)->order("createAt", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($clubsList as $key => $value) {
            $clubsList[$key]["player"] = Db::connect('mongodb')->table('players')->where("_id", $clubsList[$key]["owner"])->find();
            $clubsList[$key]["account"] = Db::connect('mongodb')->table('accounts')->where("player", $clubsList[$key]["owner"])->find();
            if(!empty($clubsList[$key]["gameList"]) && is_array($clubsList[$key]["gameList"])) {
                $games = [];
                foreach ($clubsList[$key]["gameList"] as $k => $v)  {
                    array_push($games, DefaultEnum::GAMElIST[$k]);
                }
                $clubsList[$key]["gameList"] = implode(" | ", $games);
            }
            if(empty($clubsList[$key]["gameList"])) $clubsList[$key]["gameList"] = DefaultEnum::ISNOTRESULT;
            $clubsList[$key]["player"]["realName"] = !empty($clubsList[$key]["account"]) ?
                $clubsList[$key]["account"]["realName"] : DefaultEnum::ISNOTRESULT;
            $clubsList[$key]["_id"] = reset($clubsList[$key]["_id"]);
            if(!empty($clubsList[$key]["createAt"])) $clubsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($clubsList[$key]["createAt"]) / 1000));
        }

        return [
            "count" => $count,
            "lists" => $clubsList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 俱乐部详情
     * @param int $userId
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author cjhao
     * @date 2021/8/18 15:52
     */
    public function detail($params)
    {
        $where = [];
        if(!empty($params["shortId"])) $where[] = ["shortId", "=", intval($params["shortId"])];
        if(!empty($params["club_id"])) $where[] = ["_id", "=", new ObjectId($params["club_id"])];
        $club = Db::connect('mongodb')->table('clubs')->where($where)->find();
        if(empty($club)) return [
            "club" => [],
            "player" => [],
            "account" => []
        ];

        $today = TimeService::today();
        $club["clubGold"] = Db::connect('mongodb')->table('clubmembers')->where("club", new ObjectId($params["club_id"]))->sum("clubGold");
        $club["mainAddAmount"] = Db::connect('mongodb')->table('clubgoldrecords')
            ->where("club", new ObjectId($params["club_id"]))
            ->where("info", "圈主增加")
            ->where("createAt", "between", [
                new UTCDateTime($today[0] * 1000),
                new UTCDateTime($today[1] * 1000)
            ])
            ->sum("goldChange");
        $club["mainReduceAmount"] = Db::connect('mongodb')->table('clubgoldrecords')
            ->where("club", new ObjectId($params["club_id"]))
            ->where("info", 'in', ["圈主清零", "圈主减少"])
            ->where("createAt", "between", [
                new UTCDateTime($today[0] * 1000),
                new UTCDateTime($today[1] * 1000)
            ])->sum("goldChange");
        $club["comsumeAmount"] = Db::connect('mongodb')->table('clubgoldrecords')
            ->where("createAt", "between", [
                new UTCDateTime($today[0] * 1000),
                new UTCDateTime($today[1] * 1000)
            ])
            ->where("club", new ObjectId($params["club_id"]))
            ->where("info", 'like', "游戏消耗")
            ->sum("goldChange");
        $club["winFailAmount"] = Db::connect('mongodb')->table('clubgoldrecords')
            ->where("createAt", "between", [
                new UTCDateTime($today[0] * 1000),
                new UTCDateTime($today[1] * 1000)
            ])
            ->where("goldChange", '>', 0)
            ->where("club", new ObjectId($params["club_id"]))
            ->where("info", 'like', "游戏输赢")
            ->sum("goldChange");

        $yestoday = TimeService::yesterday();
        $club["mainAddYestodayAmount"] = Db::connect('mongodb')->table('clubgoldrecords')
            ->where("club", new ObjectId($params["club_id"]))
            ->where("info", "圈主增加")
            ->where("createAt", "between", [
                new UTCDateTime($yestoday[0] * 1000),
                new UTCDateTime($yestoday[1] * 1000)
            ])
            ->sum("goldChange");
        $club["mainReduceYestodayAmount"] = Db::connect('mongodb')->table('clubgoldrecords')
            ->where("club", new ObjectId($params["club_id"]))
            ->where("info", 'in', ["圈主清零", "圈主减少"])
            ->where("createAt", "between", [
                new UTCDateTime($yestoday[0] * 1000),
                new UTCDateTime($yestoday[1] * 1000)
            ])->sum("goldChange");
        $club["comsumeYestodayAmount"] = Db::connect('mongodb')->table('clubgoldrecords')
            ->where("createAt", "between", [
                new UTCDateTime($yestoday[0] * 1000),
                new UTCDateTime($yestoday[1] * 1000)
            ])
            ->where("club", new ObjectId($params["club_id"]))
            ->where("info", 'like', "游戏消耗")
            ->sum("goldChange");
        $club["winFailYestodayAmount"] = Db::connect('mongodb')->table('clubgoldrecords')
            ->where("createAt", "between", [
                new UTCDateTime($yestoday[0] * 1000),
                new UTCDateTime($yestoday[1] * 1000)
            ])
            ->where("club", new ObjectId($params["club_id"]))
            ->where("info", 'like', "游戏输赢")
            ->where("goldChange", '>', 0)
            ->sum("goldChange");

        $player = [];
        $account = [];

        if(!empty($club["owner"])) {
            $player = Db::connect('mongodb')->table('players')->where("_id", $club["owner"])->find();
            $account = Db::connect('mongodb')->table('accounts')->where("player", $club["owner"])->find();
        }

        $club["_id"] = reset($club["_id"]);
        $club["createAt"] = date('Y-m-d H:i:s',intval(reset($club["createAt"]) / 1000));
        if(!empty($club["gameList"]) && is_array($club["gameList"])) $club["gameList"] = implode(" | ", array_keys($club["gameList"]));
        $club["members"] = Db::connect('mongodb')->table('clubmembers')->where("club", new ObjectId($club["_id"]))->count();
        $club["applys"] = Db::connect('mongodb')->table('clubrequests')->where("clubShortId", $club["shortId"])->count();

        return [
            "club" => $club ?: [],
            "player" => $player ?: [],
            "account" => $account ?: []
        ];
    }

    /**
     * @notes 更新俱乐部信息
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public function setClubInfo(array $params):bool
    {
        Db::connect('mongodb')->table('clubs')->where("_id", new ObjectId($params['club_id']))->update([$params['field']=>$params['value']]);

        return true;
    }

    /**
     * @notes 解散俱乐部
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public function deleteClub(array $params):bool
    {
        $club = Db::connect('mongodb')->table('clubs')->where("_id", new ObjectId($params['club_id']))->find();
        Db::connect('mongodb')->table('clubs')->where("_id", new ObjectId($params['club_id']))->delete();
        Db::connect('mongodb')->table('clubrules')->where("clubId", new ObjectId($params['club_id']))->delete();
        Db::connect('mongodb')->table('clubroomrecords')->where("club", new ObjectId($params['club_id']))->delete();
        Db::connect('mongodb')->table('clubrequests')->where("clubShortId", $club["shortId"])->delete();
        Db::connect('mongodb')->table('clubmembers')->where("club", new ObjectId($params['club_id']))->delete();
        Db::connect('mongodb')->table('clubgoldrecords')->where("club", new ObjectId($params['club_id']))->delete();

        return true;
    }

    /**
     * @notes  成员审核列表
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function requestLists($params)
    {
        $where = [["clubShortId", "=", intval($params["shortId"])]];
        $playerIds = [];
        $isSearch = false;

        if(!empty($params["realName"])) {
            $isSearch = true;
            $accounts = Db::connect('mongodb')->table('accounts')
                ->where("realName", 'like', fuzzy_query($params["realName"]))->select();
            if(!empty($accounts)) {
                foreach($accounts as $item) array_push($playerIds, $item["player"]);
            }
        }

        if(!empty($params["playerName"])) {
            $isSearch = true;
            $players = Db::connect('mongodb')->table('players')
                ->where("name", 'like', fuzzy_query($params["playerName"]))->select();
            if(!empty($players)) {
                foreach($players as $item) array_push($playerIds, $item["_id"]);
            }
        }

        if(!empty($params["playerPhone"])) {
            $isSearch = true;
            $player = Db::connect('mongodb')->table('players')
                ->where("phone", 'like', fuzzy_query($params["playerPhone"]))->find();
            if(!empty($player) && !in_array($player["_id"], $playerIds)) array_push($playerIds, $player["_id"]);
        }

        if(!empty($params["playerShortId"])){
            $isSearch = true;
            $player = Db::connect('mongodb')->table('players')->where("shortId", intval($params["playerShortId"]))->find();
            if(!empty($player) && !in_array($player["_id"], $playerIds)) array_push($playerIds, $player["_id"]);
        }

        if(empty($playerIds) && $isSearch) return [
            "count" => 0,
            "lists" => [],
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];

        if(!empty($playerIds)) $where[] = ["playerId", "in", $playerIds];

        $count = Db::connect('mongodb')->table('clubrequests')->where($where)->count();
        $requestList = Db::connect('mongodb')->table('clubrequests')
            ->where($where)->order("createAt", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        try {
            foreach ($requestList as $key => $value) {
                $player = Db::connect('mongodb')->table('players')->where("shortId", intval($requestList[$key]["playerShortId"]))->find();
                if(!empty($player["phone"])) $requestList[$key]["phone"] =  $player["phone"];
                $requestList[$key]["gold"] =  $player["gold"];
                $requestList[$key]["gem"] =  $player["gem"];
                $requestList[$key]["redPocket"] =  round($player["redPocket"] / 100, 2);
                $requestList[$key]["isTourist"] =  $player["isTourist"];
                $requestList[$key]["_id"] = reset($requestList[$key]["_id"]);
                if(!empty($requestList[$key]["createAt"])) $requestList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($requestList[$key]["createAt"]) / 1000));
            }
        } catch(\Exception $e) {
            var_dump($e);
        }

        return [
            "count" => $count,
            "lists" => $requestList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 成员审核
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public function setRequestInfo(array $params):string
    {
        $request = Db::connect('mongodb')->table('clubrequests')->where("_id", new ObjectId($params["id"]))->find();
        if(empty($request)) return "申请记录不存在";

        if($params["status"] == 1) {
            $club = Db::connect('mongodb')->table('clubs')->where("shortId", intval($request["clubShortId"]))->find();

            if(empty($club)) return "俱乐部不存在";
            if($club["state"] != "on") return "俱乐部已解散";

            $player = Db::connect('mongodb')->table('players')->where("shortId", intval($request["playerShortId"]))->find();
            if(empty($player)) return "成员不存在";

            $clubmerber = Db::connect('mongodb')->table('clubmembers')->where("member", $player["_id"])
                ->where("club", $club["_id"])->find();
            if(!empty($clubmerber)) return "该成员已经加入俱乐部，请勿重复操作";

            Db::connect('mongodb')->table('clubmembers')->insert([
                "club" => $club["_id"],
                "clubGold" => 0,
                "member" => $player["_id"],
                "joinAt" => new UTCDateTime(time() * 1000)
            ]);
        }

        Db::connect('mongodb')->table('clubrequests')->where("_id", new ObjectId($params['id']))->delete();

        return "success";
    }

    /**
     * @notes 添加俱乐部
     * @author Tab
     * @date 2021/9/13 19:33
     */
    public static function addClub($params)
    {
        $admin = Db::connect('mongodb')->table('clubs')->where("name", $params['name'])->find();
        if(!empty($admin)) return "俱乐部已存在，请勿重复新增";

        $global = Db::connect('mongodb')->table('globals')->where("_id", "club")->find();
        $player = Db::connect('mongodb')->table('players')->where("shortId", intval($params['playerShortId']))->find();
        if(empty($player)) return "用户不存在";
        if(empty($player["phone"])) return "用户未绑定手机，请先绑定手机";
        $global["shortIdCounter"] = $global["shortIdCounter"] + 1;

        Db::connect('mongodb')->table('clubs')->insert([
            "state" => "on",
            "name" => $params['name'],
            "shortId" => $global["shortIdCounter"],
            "owner" => $player["_id"],
            "gameList" => [],
            "createAt" => new UTCDateTime(time() * 1000)
        ]);

        $club = Db::connect('mongodb')->table('clubs')->where("name", $params['name'])->find();

        Db::connect('mongodb')->table('clubmembers')->insert([
            "clubGold" => 0,
            "club" => $club["_id"],
            "member" => $player["_id"],
            "joinAt" => new UTCDateTime(time() * 1000)
        ]);

        Db::connect('mongodb')->table('globals')->where("_id", "club")->update(["shortIdCounter" => $global["shortIdCounter"]]);

        return "success";
    }

    /**
     * @notes  成员列表
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function memberLists($params)
    {
        $where = [];
        $club = Db::connect('mongodb')->table('clubs')->where("shortId", intval($params["shortId"]))->find();
        $where[] = ["club", "=", $club["_id"]];
        $playerIds = [];
        $isSearch = false;

        if(!empty($params["realName"])) {
            $isSearch = true;
            $accounts = Db::connect('mongodb')->table('accounts')
                ->where("realName", 'like', fuzzy_query($params["realName"]))->select();
            if(!empty($accounts)) {
                foreach($accounts as $item) array_push($playerIds, $item["player"]);
            }
        }

        if(!empty($params["playerName"])) {
            $isSearch = true;
            $players = Db::connect('mongodb')->table('players')
                ->where("name", 'like', fuzzy_query($params["playerName"]))->select();
            if(!empty($players)) {
                foreach($players as $item) array_push($playerIds, $item["_id"]);
            }
        }

        if(!empty($params["playerPhone"])) {
            $isSearch = true;
            $player = Db::connect('mongodb')->table('players')
                ->where("phone", 'like', fuzzy_query($params["playerPhone"]))->find();
            if(!empty($player) && !in_array($player["_id"], $playerIds)) array_push($playerIds, $player["_id"]);
        }

        if(!empty($params["playerShortId"])){
            $isSearch = true;
            $player = Db::connect('mongodb')->table('players')->where("shortId", intval($params["playerShortId"]))->find();
            if(!empty($player) && !in_array($player["_id"], $playerIds)) array_push($playerIds, $player["_id"]);
        }

        if(empty($playerIds) && $isSearch) return [
            "count" => 0,
            "lists" => [],
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];

        if(!empty($playerIds)) $where[] = ["member", "in", $playerIds];

        $count = Db::connect('mongodb')->table('clubmembers')->where($where)->count();
        $memberList = Db::connect('mongodb')->table('clubmembers')
            ->where($where)->order("joinAt", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        try {
            foreach ($memberList as $key => $value) {
                $player = Db::connect('mongodb')->table('players')->where("_id", $memberList[$key]["member"])->find();
                $memberList[$key]["isBlack"] = Db::connect('mongodb')->table('clubextras')
                    ->where("blacklist", "in", [$memberList[$key]["member"]])
                    ->where("clubId", reset($club["_id"]))->count();
                if(!empty($player["phone"])) $memberList[$key]["phone"] =  $player["phone"];
                $memberList[$key]["gold"] =  $player["gold"];
                $memberList[$key]["gem"] =  $player["gem"];
                $memberList[$key]["clubId"] =  $club["shortId"];
                $memberList[$key]["redPocket"] =  round($player["redPocket"] / 100, 2);
                $memberList[$key]["isTourist"] =  $player["isTourist"];
                $memberList[$key]["username"] =  $player["name"];
                $memberList[$key]["shortId"] =  $player["shortId"];
                $memberList[$key]["_id"] = reset($memberList[$key]["_id"]);
                $memberList[$key]["club"] = reset($memberList[$key]["club"]);
                if(!empty($memberList[$key]["joinAt"])) $memberList[$key]["joinAt"] = date('Y-m-d H:i:s',intval(reset($memberList[$key]["joinAt"]) / 1000));
            }
        } catch(\Exception $e) {
            var_dump($e);
        }

        return [
            "count" => $count,
            "lists" => $memberList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 代理充值
     * @param array $params
     * @return string
     * @author cjhao
     * @date 2021/9/10 18:15
     */
    public function adjustUserWallet(array $params, $adminId)
    {
        $member = Db::connect('mongodb')->table('clubmembers')->where("_id", new ObjectId($params['_id']))->find();

        if(1 == $params['action']){
            //调整钻石
            Db::connect('mongodb')->table('clubmembers')->where("_id", new ObjectId($params['_id']))->update(["clubGold" => $member["clubGold"] + $params['num']]);
        }else{
            Db::connect('mongodb')->table('clubmembers')->where("_id", new ObjectId($params['_id']))->update(["clubGold" => $member["clubGold"] - $params['num']]);
        }

        $newMember = Db::connect('mongodb')->table('clubmembers')->where("_id", new ObjectId($params['_id']))->find();
        $club = Db::connect('mongodb')->table('clubs')->where("_id", $member['club'])->find();

        Db::connect('mongodb')->table('clubgoldrecords')->insert([
            "club" => $member['club'],
            "member" => $member['member'],
            "gameType" => "" ,
            "goldChange" => $params['action'] == 1 ? intval($params['num']) : -$params['num'],
            "allClubGold" => $newMember["clubGold"],
            "info" => "圈主" . DefaultEnum::CLUBACTIONTYPE[$params['action']],
            "createAt" => new UTCDateTime(time() * 1000),
            "from" => $club["owner"]
        ]);

        return true;

    }

    /**
     * @notes 踢出成员
     * @param array $params
     * @return string
     * @author cjhao
     * @date 2021/9/10 18:15
     */
    public function kickMember(array $params)
    {
        $member = Db::connect('mongodb')->table('clubmembers')->where("_id", new ObjectId($params['_id']))->find();
        Db::connect('mongodb')->table('clubmembers')->where("_id", new ObjectId($params['_id']))->delete();

        Db::connect('mongodb')->table('clublogs')->insert([
            "clubId" => $member['club'],
            "op" => 3,
            "operator" => $member["member"],
            "detail" => [],
            "createAt" => new UTCDateTime(time() * 1000)
        ]);

        return true;
    }

    /**
     * @notes  房间记录
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function roomLists($params)
    {
        $where = [["club", "=", new ObjectId($params["_id"])]];
        $playerIds = [];
        $isSearch = false;

        if(!empty($params["realName"])) {
            $isSearch = true;
            $accounts = Db::connect('mongodb')->table('accounts')
                ->where("realName", 'like', fuzzy_query($params["realName"]))->select();
            if(!empty($accounts)) {
                foreach($accounts as $item) array_push($playerIds, $item["player"]);
            }
        }

        if(!empty($params["name"])) {
            $isSearch = true;
            $players = Db::connect('mongodb')->table('players')
                ->where("name", 'like', fuzzy_query($params["name"]))->select();
            if(!empty($players)) {
                foreach($players as $item)
                    if(!empty($item) && !in_array($item["_id"], $playerIds))
                        array_push($playerIds, $item["_id"]);
            }
        }

        if(!empty($params["playerPhone"])) {
            $isSearch = true;
            $player = Db::connect('mongodb')->table('players')
                ->where("phone", 'like', fuzzy_query($params["playerPhone"]))->find();
            if(!empty($player) && !in_array($player["_id"], $playerIds)) array_push($playerIds, $player["_id"]);
        }

        if(!empty($params["playerShortId"])){
            $isSearch = true;
            $player = Db::connect('mongodb')->table('players')->where("shortId", intval($params["playerShortId"]))->find();
            if(!empty($player) && !in_array($player["_id"], $playerIds)) array_push($playerIds, $player["_id"]);
        }

        if(empty($playerIds) && $isSearch) return nullResultReturn();
        if(!empty($playerIds)) $where[] = ["players", "in", $playerIds];
        if(!empty($params["roomId"])) $where[] = ["roomNum", "=", $params["roomId"]];
        if(!empty($params["category"])) $where[] = ["category", "=", $params["category"]];
        if(!empty($params["start_time"]) && !empty($params["end_time"]))
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];

        $count = Db::connect('mongodb')->table('roomrecords')->where($where)->count();
        $roomList = Db::connect('mongodb')->table('roomrecords')
            ->where($where)
            ->page($params["page_no"], $params["page_size"])->select()->all();

        try {
            foreach ($roomList as $key => $value) {
                $roomList[$key]["player"] = Db::connect('mongodb')->table('players')
                    ->where("shortId", $roomList[$key]["creatorId"])->find();
                $roomList[$key]["club"] =  Db::connect('mongodb')->table('clubs')
                    ->where("_id", $roomList[$key]["club"])->find();
                $roomList[$key]["_id"] = reset($roomList[$key]["_id"]);
                if(!empty($roomList[$key]["createAt"])) $roomList[$key]["createAt"] =
                    date('Y-m-d H:i:s',intval(reset($roomList[$key]["createAt"]) / 1000));
                $clubRule = Db::connect('mongodb')->table('clubrules')
                    ->where("_id", new ObjectId($value["rule"]["ruleId"]))->find();
                $roomList[$key]["roomType"] = !empty($clubRule) ? DefaultEnum::ROOMTYPE[$clubRule["ruleType"]] : DefaultEnum::ROOMTYPE[3];
            }
        } catch(\Exception $e) {
            var_dump($e);
        }

        return [
            "count" => $count,
            "lists" => $roomList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes  房间详情
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function roomDetail($params)
    {
        $room = Db::connect('mongodb')->table('roomrecords')->where("_id", new ObjectId($params["room_id"]))->find();
        $room["player"] = Db::connect('mongodb')->table('players')->where("shortId", $room["creatorId"])->find();
        $room["club"] = Db::connect('mongodb')->table('clubs')->where("_id", $room["club"])->find();
        $room["games"] = Db::connect('mongodb')->table('gamerecords')->where("room", $room["room"])->select();
        $room["gameTimes"] = [];
        $clubRule = Db::connect('mongodb')->table('clubrules')
            ->where("_id", new ObjectId($room["rule"]["ruleId"]))->find();
        $room["roomType"] = !empty($clubRule) ? DefaultEnum::ROOMTYPE[$clubRule["ruleType"]] : DefaultEnum::ROOMTYPE[3];

        foreach ($room["games"] as $k => $item) {
            $timer = $room["games"][$k]["time"];
            $room["gameTimes"][] = ["createAt" => date('Y-m-d H:i:s',intval(reset($timer) / 1000))];
        }

        //禁止删除，解决games中有NAN值报错
        $room["games"] = json_encode($room["games"], JSON_NUMERIC_CHECK | JSON_PARTIAL_OUTPUT_ON_ERROR);
        $room["_id"] = reset($room["_id"]);
        $room["createAt"] = date('Y-m-d H:i:s',intval(reset($room["createAt"]) / 1000));

        $scores = [];
        try {
            foreach ($room["scores"] as $key => $item) {
                if(empty($item)) continue;
                $player = Db::connect('mongodb')->table('players')->where("shortId", intval($item["shortId"]))->find();
                $room["scores"][$key]["gem"] = $player["gem"];
                $clubber = Db::connect('mongodb')->table('clubmembers')->where("member", $player["_id"])->where("club", $room["club"]["_id"])->find();
                $room["scores"][$key]["clubGold"] = $clubber["clubGold"];
                array_push($scores, $room["scores"][$key]);
            }

            $room["scores"] = $scores;
        } catch(\Exception $e) {
            var_dump($e);
        }

        return $room;
    }

    /**
     * @notes  实时房间
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function onLineRoomLists($params)
    {
        $where = [["clubId", "=", $params["_id"]]];

        if(!empty($params["roomId"])) $where[] = ["roomId", "=", intval($params["roomId"])];
        if(!empty($params["gameType"])) $where[] = ["gameType", "=", $params["gameType"]];

        $count = Db::connect('mongodb')->table('roominfos')->where($where)->count();
        $roomList = Db::connect('mongodb')->table('roominfos')
            ->where($where)
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($roomList as $key => $value) {
            $roomList[$key]["detail"] = Db::connect('mongodb')->table('roomdetails')
                ->where("roomId", $roomList[$key]["roomId"])->find();
            $roomList[$key]["detail"]["detail"] = json_decode($roomList[$key]["detail"]["detail"], true);
            $roomList[$key]["_id"] = reset($roomList[$key]["_id"]);
            $roomList[$key]["player"] = Db::connect('mongodb')->table('players')
                ->where("_id", $roomList[$key]["detail"]["detail"]["creator"])->find();
            $roomList[$key]["club"] =  Db::connect('mongodb')->table('clubs')
                ->where("_id", new ObjectId($roomList[$key]["detail"]["detail"]["clubId"]))->find();
            $clubRule = Db::connect('mongodb')->table('clubrules')
                ->where("_id", new ObjectId($roomList[$key]["detail"]["detail"]["gameRule"]["ruleId"]))->find();
            $roomList[$key]["roomType"] = !empty($clubRule) ? DefaultEnum::ROOMTYPE[$clubRule["ruleType"]] : DefaultEnum::ROOMTYPE[3];
        }

        return [
            "count" => $count,
            "lists" => $roomList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes  房间详情
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function onlineRoomDetail($params)
    {
        $room = Db::connect('mongodb')->table('roominfos')->where("_id", new ObjectId($params["room_id"]))->find();
        $room["detail"] = Db::connect('mongodb')->table('roomdetails')
            ->where("roomId", $room["roomId"])->find();
        $room["detail"]["detail"] = json_decode($room["detail"]["detail"], true);
       $room["player"] = Db::connect('mongodb')->table('players')
            ->where("_id", $room["detail"]["detail"]["creator"])->find();
        $room["club"] =  Db::connect('mongodb')->table('clubs')
            ->where("_id", new ObjectId($room["detail"]["detail"]["clubId"]))->find();
        $clubRule = Db::connect('mongodb')->table('clubrules')
            ->where("_id", new ObjectId($room["detail"]["detail"]["gameRule"]["ruleId"]))->find();
        $room["roomType"] = !empty($clubRule) ? DefaultEnum::ROOMTYPE[$clubRule["ruleType"]] : DefaultEnum::ROOMTYPE[3];
        $room["games"] = Db::connect('mongodb')->table('gamerecords')->where("room", strval($room["room"]))->select();
        $room["gameTimes"] = [];
        $players = [];

        foreach ($room["detail"]["detail"]["players"] as $k => $item) {
            if(empty($room["detail"]["detail"]["players"][$k])) continue;
            $room["detail"]["detail"]["players"][$k] = Db::connect('mongodb')->table('players')
                ->where("_id", $room["detail"]["detail"]["players"][$k])->find();
            $clubmember = Db::connect('mongodb')->table('clubmembers')
                ->where("member", $room["detail"]["detail"]["players"][$k]["_id"])->where("club", new ObjectId($room["clubId"]))->find();
            $room["detail"]["detail"]["players"][$k]["clubGold"] = $clubmember["clubGold"];
            $players[] = $room["detail"]["detail"]["players"][$k];
        }

        $room["detail"]["detail"]["players"] = $players;

        foreach ($room["games"] as $k => $item) {
            $timer = $room["games"][$k]["time"];
            $room["gameTimes"][] = ["createAt" => date('Y-m-d H:i:s',intval(reset($timer) / 1000))];
        }

        //禁止删除，解决games中有NAN值报错
        if(!empty($room["games"])) $room["games"] = json_encode($room["games"], JSON_NUMERIC_CHECK | JSON_PARTIAL_OUTPUT_ON_ERROR);
        $room["_id"] = reset($room["_id"]);

        return $room;
    }

    /**
     * @notes 更新俱乐部成员信息
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public function setMemberInfo(array $params):bool
    {
        Db::connect('mongodb')->table('clubmembers')
            ->where(['_id'=>new ObjectId($params["_id"])])->update(["role"=>$params['role']]);
        return true;
    }

    /**
     * @notes  黑名单列表
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function blackLists($params)
    {
        $where = [];
        $playerIds = [];
        $isSearch = false;

        if(!empty($params["realName"])) {
            $isSearch = true;
            $accounts = Db::connect('mongodb')->table('accounts')
                ->where("realName", 'like', fuzzy_query($params["realName"]))->select();
            if(!empty($accounts)) {
                foreach($accounts as $item) array_push($playerIds, $item["player"]);
            }
        }

        if(!empty($params["playerName"])) {
            $isSearch = true;
            $players = Db::connect('mongodb')->table('players')
                ->where("name", 'like', fuzzy_query($params["playerName"]))->select();
            if(!empty($players)) {
                foreach($players as $item)
                    if(!empty($item) && !in_array($item["_id"], $playerIds))
                        array_push($playerIds, $item["_id"]);
            }
        }

        if(!empty($params["playerPhone"])) {
            $isSearch = true;
            $player = Db::connect('mongodb')->table('players')
                ->where("phone", 'like', fuzzy_query($params["playerPhone"]))->find();
            if(!empty($player) && !in_array($player["_id"], $playerIds)) array_push($playerIds, $player["_id"]);
        }

        if(!empty($params["playerShortId"])){
            $isSearch = true;
            $player = Db::connect('mongodb')->table('players')->where("shortId", intval($params["playerShortId"]))->find();
            if(!empty($player) && !in_array($player["_id"], $playerIds)) array_push($playerIds, $player["_id"]);
        }

        if(empty($playerIds) && $isSearch) return [
            "count" => 0,
            "lists" => [],
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];

        if(!empty($playerIds)) $where[] = ["blacklist", "in", $playerIds];
        $club = Db::connect('mongodb')->table('clubs')->where("shortId", intval($params["shortId"]))->find();
        $where[] = ["clubId", '=', reset($club["_id"])];
        $black = Db::connect('mongodb')->table('clubextras')->where($where)->find();
        if(empty($black)) return [
            "count" => 0,
            "lists" => [],
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];

        $club["_id"] = reset($club["_id"]);
        foreach ($black["blacklist"] as $k => $value) {
            $player = Db::connect('mongodb')->table('players')->where("_id", $black["blacklist"][$k])->find();
            $player["redPocket"] = round($player["redPocket"] / 100, 2);
            $black["blacklist"][$k] = $player;
            $black["blacklist"][$k]["club"] = $club;
        }
        return [
            "count" => !empty($black["blacklist"]) ? count($black["blacklist"]) : 0,
            "lists" => $black["blacklist"],
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 更新黑名单
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public function setBlackInfo(array $params):bool
    {
        if($params["action"] == 1) {
            $black = Db::connect('mongodb')->table('clubextras')->where("clubId", $params["club"])->find();
            $blacklist = [];
            if(in_array($params["_id"], $black["blacklist"])) return true;
            if(!empty($black) && !empty($black["blacklist"])) $blacklist = $black["blacklist"];
            array_push($blacklist, $params["_id"]);

            $club = Db::connect('mongodb')->table('clubs')->where("_id", new ObjectId($params["club"]))->find();
            if($params["_id"] == $club["owner"]) return false;

            if(!empty($black)) {
                Db::connect('mongodb')->table('clubextras')
                    ->where("_id", $black["_id"])->update(["blacklist" => $blacklist]);
            } else {
                Db::connect('mongodb')->table('clubextras')->insert([
                    "blacklist" => $blacklist,
                    "clubId" => $params["club"],
                    "createAt" => new UTCDateTime(time() * 1000),
                    "renameList" => []
                ]);
            }
        }

        if($params["action"] == 2) {
            $black = Db::connect('mongodb')->table('clubextras')
                ->where("blacklist", "in", [$params["_id"]])->where("clubId", $params["club"])->find();

            if(empty($black)) return true;
            $blacklist = [];
            foreach ($black["blacklist"] as $info) {
                if($info != $params["_id"]) array_push($blacklist, $info);
            }

            Db::connect('mongodb')->table('clubextras')
                ->where("_id", $black["_id"])->update(["blacklist" => $blacklist ?: []]);
        }
        return true;
    }
}
