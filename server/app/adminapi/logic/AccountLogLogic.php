<?php

namespace app\adminapi\logic;


use app\common\enum\DefaultEnum;
use app\common\logic\BaseLogic;
use app\common\service\TimeService;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use think\db\Query;
use think\facade\Cache;
use think\facade\Db;
use Exception;

class AccountLogLogic extends BaseLogic
{
    /**
     * @notes 查看用户充值流水
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function userRechargeLists($params)
    {
        $where = [["status", "=", 1]];
        $playerIds = [];
        $isSearch = false;

        if(!empty($params["name"])) {
            $isSearch = true;
            $players = Db::connect('mongodb')->table('players')
                ->where("nickname", 'like', fuzzy_query($params["name"]))->select();
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
        if(!empty($playerIds)) $where[] = ["to", "in", $playerIds];
        if(!empty($params["source"])) $where[] = ["source", "=", $params["source"]];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["created", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];
        }

        // 导出
        if (!empty($params["export"]) && $params["export"] == 1) {
            return ExportLogic::userRechargeExport($where);
        }

        $count = Db::connect('mongodb')->table('userrechargeorders')->where($where)->count();
        $logsList = Db::connect('mongodb')->table('userrechargeorders')->where($where)->order("created", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($logsList as $key => $value) {
            $logsList[$key]["player"] = Db::connect('mongodb')->table('players')->where("_id", new ObjectId($value["playerId"]))->find();
            if(empty($logsList[$key]["player"]["phone"])) $logsList[$key]["player"]["phone"] = DefaultEnum::ISNOTRESULT;
             $logsList[$key]["player"]["realName"] = empty($logsList[$key]["account"]) ? DefaultEnum::ISNOTRESULT : $logsList[$key]["account"]["realName"];
            $logsList[$key]["source_str"] = $logsList[$key]["source"] == "admin" ?
                DefaultEnum::USERRECHARGESOURCE["ADMINRECHARGE"] : DefaultEnum::USERRECHARGESOURCE["WECHATRECHARGE"];
            $logsList[$key]["_id"] = reset($logsList[$key]["_id"]);
            $logsList[$key]["created"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["created"]) / 1000));
        }

        return [
            "count" => $count,
            "lists" => $logsList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 查看用户钻石流水
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function userGemLists($params)
    {
        $where = [["gem", ">", 0]];
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
        if(!empty($playerIds)) $where[] = ["player", "in", $playerIds];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];
        }

        // 导出
        if (!empty($params["export"]) && $params["export"] == 1) {
            return ExportLogic::userGemExport($where);
        }

        $count = Db::connect('mongodb')->table('consumerecords')->where($where)->count();
        $logsList = Db::connect('mongodb')->table('consumerecords')->where($where)->order("createAt", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($logsList as $key => $value) {
            $logsList[$key]["playerInfo"] = Db::connect('mongodb')->table('players')->where("_id", $logsList[$key]["player"])->find();
            $logsList[$key]["account"] = Db::connect('mongodb')->table('accounts')
                ->where("player", $logsList[$key]["player"])->find();
            if(empty($logsList[$key]["playerInfo"]["phone"])) $logsList[$key]["playerInfo"]["phone"] = DefaultEnum::ISNOTRESULT;
            $logsList[$key]["playerInfo"]["realName"] = empty($logsList[$key]["account"]) ? DefaultEnum::ISNOTRESULT : $logsList[$key]["account"]["realName"];
            $logsList[$key]["_id"] = reset($logsList[$key]["_id"]);
            $logsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["createAt"]) / 1000));
        }

        return [
            "count" => $count,
            "lists" => $logsList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 查看用户钻石流水
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function userDiamondLists($params)
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
        if(!empty($playerIds)) $where[] = ["player", "in", $playerIds];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];
        }

        // 导出
        if (!empty($params["export"]) && $params["export"] == 1) {
            return ExportLogic::userGemExport($where);
        }

        $count = Db::connect('mongodb')->table('diamondrecords')->where($where)->count();
        $logsList = Db::connect('mongodb')->table('diamondrecords')->where($where)->order("createAt", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($logsList as $key => $value) {
            $logsList[$key]["playerInfo"] = Db::connect('mongodb')->table('players')->where("_id", new ObjectId($value["player"]))->find();
            if(empty($logsList[$key]["playerInfo"]["phone"])) $logsList[$key]["playerInfo"]["phone"] = DefaultEnum::ISNOTRESULT;
            $logsList[$key]["typeStr"] = DefaultEnum::DIAMONDLISTS[$logsList[$key]["type"]];
            if($logsList[$key]["type"] === 18) $logsList[$key]["note"] = DefaultEnum::GAMElIST[$logsList[$key]["note"]];
            $logsList[$key]["_id"] = reset($logsList[$key]["_id"]);
            $logsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["createAt"]) / 1000));
        }

        return [
            "count" => $count,
            "lists" => $logsList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 查看用户钻石流水
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function userGoldLists($params)
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

        if(!empty($params["name"])) {
            $isSearch = true;
            $players = Db::connect('mongodb')->table('players')
                ->where("name", 'like', fuzzy_query($params["name"]))->select();
            if(!empty($players)) {
                foreach($players as $item)
                    if(!empty($item) && !in_array(reset($item["_id"]), $playerIds))
                        array_push($playerIds, reset($item["_id"]));
            }
        }

        if(!empty($params["playerShortId"])){
            $isSearch = true;
            $player = Db::connect('mongodb')->table('players')->where("shortId", intval($params["playerShortId"]))->find();
            if(!empty($player) && !in_array($player["_id"], $playerIds)) array_push($playerIds, reset($player["_id"]));
        }

        if(empty($playerIds) && $isSearch) return nullResultReturn();
        if(!empty($playerIds)) $where[] = ["player", "in", $playerIds];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];
        }

        // 导出
        if (!empty($params["export"]) && $params["export"] == 1) {
            return ExportLogic::userGemExport($where);
        }

        $count = Db::connect('mongodb')->table('goldrecords')->where($where)->count();
        $logsList = Db::connect('mongodb')->table('goldrecords')->where($where)->order("createAt", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($logsList as $key => $value) {
            $logsList[$key]["playerInfo"] = Db::connect('mongodb')->table('players')->where("_id", new ObjectId($value["player"]))->find();
            if(empty($logsList[$key]["playerInfo"]["phone"])) $logsList[$key]["playerInfo"]["phone"] = DefaultEnum::ISNOTRESULT;
            $logsList[$key]["typeStr"] = DefaultEnum::DIAMONDLISTS[$logsList[$key]["type"]];
            $logsList[$key]["_id"] = reset($logsList[$key]["_id"]);
            $logsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["createAt"]) / 1000));
        }

        return [
            "count" => $count,
            "lists" => $logsList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes  房间记录
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function roomLists($params)
    {
        $where = [["scores", "<>", []]];
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
        if(!empty($params["clubShortId"])){
            $club = Db::connect('mongodb')->table('clubs')->where("shortId", intval($params["clubShortId"]))->find();
            if(!empty($club)) $where[] = ["club", "=", $club["_id"]];
        }
        if(!empty($params["juShu"])) $where[] = ["rule.juShu", "=", intval($params["juShu"])];
        if(!empty($params["playerCount"])) $where[] = ["rule.playerCount", "=", intval($params["playerCount"])];
        if(!empty($params["winnerPay"])) $where[] = ["rule.winnerPay", "=", $params["winnerPay"] == 1];
        if(!empty($params["share"])) $where[] = ["rule.share", "=", $params["share"] == 1];
        if(!empty($params["clubOwnerPay"])) $where[] = ["rule.clubOwnerPay", "=", $params["clubOwnerPay"] == 1];
        if(!empty($params["useClubGold"])) $where[] = ["rule.useClubGold", "=", $params["useClubGold"] == 1];
        if(!empty($playerIds)) $where[] = ["players", "in", $playerIds];
        if(!empty($params["roomId"])) $where[] = ["roomNum", "=", $params["roomId"]];
        if(!empty($params["roomState"])) $where[] = ["roomState", "=", $params["roomState"]];
        if(!empty($params["category"])) $where[] = ["category", "=", $params["category"]];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];
        }

        // 导出
        if (!empty($params["export"]) && $params["export"] == 1) {
            return ExportLogic::userRoomExport($where);
        }

        $count = Db::connect('mongodb')->table('roomrecords')->where($where)->count();
        $roomList = Db::connect('mongodb')->table('roomrecords')
            ->where($where)->order("createAt", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        try {
            foreach ($roomList as $key => $value) {
                if(!empty($value["rule"]["ruleId"])) {
                    $clubRule = Db::connect('mongodb')->table('clubrules')
                        ->where("_id", new ObjectId($value["rule"]["ruleId"]))->find();
                }
                if(!empty($clubRule) || !empty($value["rule"]["ruleType"]))
                    $roomList[$key]["roomType"] = !empty($clubRule) ? DefaultEnum::ROOMTYPE[$clubRule["ruleType"]]
                        : DefaultEnum::ROOMTYPE[$value["rule"]["ruleType"]];
                if(empty($roomList[$key]["roomType"])) $roomList[$key]["roomType"] = DefaultEnum::ISNOTRESULT;

                if(!empty($value["creatorId"])) $roomList[$key]["player"] = Db::connect('mongodb')->table('players')
                    ->where("shortId", intval($value["creatorId"]))->find();
                if(!empty($value["club"])) $roomList[$key]["club"] = Db::connect('mongodb')->table('clubs')
                    ->where("_id", $roomList[$key]["club"])->find();
                $roomList[$key]["clubId"] = empty($roomList[$key]["club"]) ? DefaultEnum::ISNOTRESULT : $roomList[$key]["club"]["shortId"];
                $roomList[$key]["clubName"] = empty($roomList[$key]["club"]) ? DefaultEnum::ISNOTRESULT : $roomList[$key]["club"]["name"];
                $roomList[$key]["_id"] = reset($roomList[$key]["_id"]);
                if(!empty($roomList[$key]["createAt"])) $roomList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($roomList[$key]["createAt"]) / 1000));
            }
        } catch(Exception $e) {
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
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function roomDetail($params)
    {
        $room = Db::connect('mongodb')->table('roomrecords')->where("_id", new ObjectId($params["room_id"]))->find();
        $room["player"] = Db::connect('mongodb')->table('players')->where("shortId", $room["creatorId"])->find();
        if(!empty($room["club"])) $room["club"] = Db::connect('mongodb')->table('clubs')->where("_id", $room["club"])->find();
        $room["clubName"] = !empty($room["club"]) ? $room["club"]["name"] : DefaultEnum::ISNOTRESULT;
        $room["clubId"] = !empty($room["club"]) ? $room["club"]["shortId"] : DefaultEnum::ISNOTRESULT;
        $room["games"] = Db::connect('mongodb')->table('gamerecords')->where("room", $room["room"])->where("type", $room["category"])->select();
        $room["gameTimes"] = [];

        foreach ($room["games"] as $k => $item) {
            $timer = $room["games"][$k]["time"];
            $gameId = $room["games"][$k]["_id"];
            $room["gameTimes"][] = ["createAt" => date('Y-m-d H:i:s',intval(reset($timer) / 1000)),
                "gameId" => reset($gameId)];
        }

        //禁止删除，解决games中有NAN值报错
        if(!empty($room["games"])) $room["games"] = json_decode(json_encode($room["games"], JSON_NUMERIC_CHECK | JSON_PARTIAL_OUTPUT_ON_ERROR), true);

        foreach ($room["games"] as $index => $game) {
            $records = [];
            foreach ($game["record"] as $k => $v) {
                if(empty($game["record"][$k])) continue;
                $room["games"][$index]["record"][$k]["_id"] = reset($game["_id"]);
                $room["games"][$index]["record"][$k]["shortId"] = $game["playersInfo"][$k]["model"]["shortId"];
                array_push($records, $room["games"][$index]["record"][$k]);
            }

            $room["games"][$index]["record"] = $records;

            $sort = array_column($room["games"][$index]["record"], 'shortId');
            array_multisort($sort, SORT_ASC, $room["games"][$index]["record"]);
        }

        $room["_id"] = reset($room["_id"]);
        $room["createAt"] = date('Y-m-d H:i:s',intval(reset($room["createAt"]) / 1000));
        $scores = [];

        try {
            foreach ($room["scores"] as $key => $item) {
                if(empty($item)) continue;
                $player = Db::connect('mongodb')->table('players')->where("shortId", intval($item["shortId"]))->find();
                $room["scores"][$key]["gem"] = $player["diamond"];
                $room["scores"][$key]["gold"] = $player["gold"];
                array_push($scores, $room["scores"][$key]);
            }

            $room["scores"] = $scores;
        } catch(Exception $e) {
            var_dump($e);
        }

        return $room;
    }

    /**
     * @notes 查看代理充值流水
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function agencyRechargeLists($params)
    {
        $where = [];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["created", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];
        }

        // 导出
        if (!empty($params["export"]) && $params["export"] == 1) {
            return ExportLogic::agencyRechargeExport($where);
        }

        $count = Db::connect('mongodb')->table('records')->where($where)->count();
        $logsList = Db::connect('mongodb')->table('records')->where($where)->order("created", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($logsList as $key => $value) {
            $logsList[$key]["agency"] = Db::connect('mongodb')->table('gms')
                ->where("_id", $logsList[$key]["to"])->find();
            $logsList[$key]["_id"] = reset($logsList[$key]["_id"]);
            $logsList[$key]["to"] = reset($logsList[$key]["to"]);
            $logsList[$key]["created"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["created"]) / 1000));
        }

        return [
            "count" => $count,
            "lists" => $logsList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 查看代理自助充值流水
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function agencyAutoRechargeLists($params)
    {
        $where = [];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];
        }
        if(!empty($params["status"])) $where[] = ["status", "=", $params["status"]];

        // 导出
        if (!empty($params["export"]) && $params["export"] == 1) {
            return ExportLogic::agencyAutoRechargeExport($where);
        }

        $count = Db::connect('mongodb')->table('gmextrecords')->where($where)->count();
        $logsList = Db::connect('mongodb')->table('gmextrecords')->where($where)->order("createAt", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($logsList as $key => $value) {
            $logsList[$key]["agency"] = Db::connect('mongodb')->table('gms')
                ->where("_id", $value["gm"])->find();
            $logsList[$key]["_id"] = reset($value["_id"]);
            $logsList[$key]["gm"] = reset($value["gm"]);
            $logsList[$key]["status"] = DefaultEnum::RECHARGEORDERSTATUS[$logsList[$key]["status"]];
            $logsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["createAt"]) / 1000));
        }

        return [
            "count" => $count,
            "lists" => $logsList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 查看代理钻石流水
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function agencyGemLists($params)
    {
        $where = [];

        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];
        }

        // 导出
        if (!empty($params["export"]) && $params["export"] == 1) {
            return ExportLogic::agencyGemExport($where);
        }

        $count = Db::connect('mongodb')->table('gmnotes')->where($where)->count();
        $logsList = Db::connect('mongodb')->table('gmnotes')->where($where)->order("createAt", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($logsList as $key => $value) {
            $logsList[$key]["gmInfo"] = Db::connect('mongodb')->table('gms')->where("_id", $logsList[$key]["gm"])->find();
            $logsList[$key]["_id"] = reset($logsList[$key]["_id"]);
            $logsList[$key]["gm"] = reset($logsList[$key]["gm"]);
            $logsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["createAt"]) / 1000));
        }

        return [
            "count" => $count,
            "lists" => $logsList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 查看俱乐部流水
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function clubAccountLists($params)
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

        if(!empty($params["clubShortId"])){
            $club = Db::connect('mongodb')->table('clubs')->where("shortId", intval($params["clubShortId"]))->find();
            if(!empty($club)) $where[] = ["club", "=", $club["_id"]];
        }

        if(empty($playerIds) && $isSearch) return nullResultReturn();
        if(!empty($playerIds)) $where[] = ["member", "in", $playerIds];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];
        }

        // 导出
        if (!empty($params["export"]) && $params["export"] == 1) {
            return ExportLogic::clubAccountExport($where);
        }

        $count = Db::connect('mongodb')->table('clubgoldrecords')->where($where)->count();
        $logsList = Db::connect('mongodb')->table('clubgoldrecords')->where($where)->order("createAt", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($logsList as $key => $value) {
            $logsList[$key]["player"] = Db::connect('mongodb')->table('players')->where("_id", $logsList[$key]["member"])->find();
            $logsList[$key]["club"] = Db::connect('mongodb')->table('clubs')
                ->where("_id", $logsList[$key]["club"])->find();
            $logsList[$key]["account"] = Db::connect('mongodb')->table('accounts')
                ->where("player", $logsList[$key]["member"])->find();
            if(empty($logsList[$key]["player"]["phone"])) $logsList[$key]["player"]["phone"] = DefaultEnum::ISNOTRESULT;
            $logsList[$key]["player"]["realName"] = empty($logsList[$key]["account"]) ? DefaultEnum::ISNOTRESULT : $logsList[$key]["account"]["realName"];

            if($logsList[$key]["from"] != "pay") {
                $player = Db::connect('mongodb')->table('players')->where("_id", $logsList[$key]["from"])->find();
                $logsList[$key]["from"] = $player["name"] . "(" . $player["shortId"] . ")";
            }
            $info = explode("：", $logsList[$key]["info"]);
            if(count($info) >= 2) $logsList[$key]["room"] = explode("：", $logsList[$key]["info"])[1];
            $logsList[$key]["_id"] = reset($logsList[$key]["_id"]);
            $logsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["createAt"]) / 1000));
        }

        return [
            "count" => $count,
            "lists" => $logsList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes  房间记录
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function clubRoomLists($params)
    {
        $where = [["club", "<>", null],["scores", "<>", []]];
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
        if(!empty($params["clubShortId"])){
            $club = Db::connect('mongodb')->table('clubs')->where("shortId", intval($params["clubShortId"]))->find();
            if(!empty($club)) $where[] = ["club", "=", $club["_id"]];
        }
        if(!empty($params["juShu"])) $where[] = ["rule.juShu", "=", intval($params["juShu"])];
        if(!empty($params["playerCount"])) $where[] = ["rule.playerCount", "=", intval($params["playerCount"])];
        if(!empty($params["guanPai"])) $where[] = ["rule.guanPai", "=", $params["guanPai"] == 1];
        if(!empty($params["showCardNum"])) $where[] = ["rule.showCardNum", "=", $params["showCardNum"] == 1];
        if(!empty($params["yaPai"])) $where[] = ["rule.yaPai", "=", $params["yaPai"] == 1];
        if(!empty($params["winnerPay"])) $where[] = ["rule.winnerPay", "=", $params["winnerPay"] == 1];
        if(!empty($params["useClubGold"])) $where[] = ["rule.useClubGold", "=", $params["useClubGold"] == 1];
        if(!empty($params["longTou"])) $where[] = ["rule.longTou", "=", $params["longTou"] == 1];
        if(!empty($params["isPublic"])) $where[] = ["rule.isPublic", "=", $params["isPublic"] == 1];
        if(!empty($playerIds)) $where[] = ["players", "in", $playerIds];
        if(!empty($params["roomNum"])) $where[] = ["roomNum", "=", $params["roomNum"]];
        if(!empty($params["roomState"])) $where[] = ["roomState", "=", $params["roomState"]];
        if(!empty($params["category"])) $where[] = ["category", "=", $params["category"]];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];
        }

        // 导出
        if (!empty($params["export"]) && $params["export"] == 1) {
            return ExportLogic::userRoomExport($where);
        }

        $count = Db::connect('mongodb')->table('roomrecords')->where($where)->count();
        $roomList = Db::connect('mongodb')->table('roomrecords')
            ->where($where)->order("createAt", "desc")->page($params["page_no"], $params["page_size"])->select()->all();

        try {
            foreach ($roomList as $key => $value) {
                if(!empty($value["rule"]["ruleId"])) {
                    $clubRule = Db::connect('mongodb')->table('clubrules')
                        ->where("_id", new ObjectId($value["rule"]["ruleId"]))->find();
                }
                $roomList[$key]["roomType"] = !empty($clubRule) ? DefaultEnum::ROOMTYPE[$clubRule["ruleType"]]
                    : DefaultEnum::ROOMTYPE[$value["rule"]["ruleType"]];
                $roomList[$key]["player"] = Db::connect('mongodb')->table('players')->where("shortId", $roomList[$key]["creatorId"])->find();
                $roomList[$key]["club"] =  Db::connect('mongodb')->table('clubs')->where("_id", $roomList[$key]["club"])->find();
                if(empty($roomList[$key]["club"])) {
                    $roomList[$key]["club"]["shortId"] = DefaultEnum::ISNOTRESULT;
                    $roomList[$key]["club"]["name"] = DefaultEnum::ISNOTRESULT;
                }
                $roomList[$key]["_id"] = reset($roomList[$key]["_id"]);
                if(!empty($roomList[$key]["createAt"])) $roomList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($roomList[$key]["createAt"]) / 1000));
            }
        } catch(Exception $e) {
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
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function clubRoomDetail($params)
    {
        $room = Db::connect('mongodb')->table('roomrecords')->where("_id", new ObjectId($params["room_id"]))->find();
        $room["player"] = Db::connect('mongodb')->table('players')->where("shortId", $room["creatorId"])->find();
        $room["club"] = Db::connect('mongodb')->table('clubs')->where("_id", $room["club"])->find();
        $room["games"] = Db::connect('mongodb')->table('gamerecords')->where("room", $room["room"])->select();
        if(!empty($room["rule"]["ruleId"])) {
            $clubRule = Db::connect('mongodb')->table('clubrules')
                ->where("_id", new ObjectId($room["rule"]["ruleId"]))->find();
        }
        $room["roomType"] = !empty($clubRule) ? DefaultEnum::ROOMTYPE[$clubRule["ruleType"]]
            : DefaultEnum::ROOMTYPE[$room["rule"]["ruleType"]];
        $room["gameTimes"] = [];

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
        } catch(Exception $e) {
            var_dump($e);
        }

        return $room;
    }

    /**
     * @notes  俱乐部房间排行榜
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function clubRoomRanking($params)
    {
        $whereClub = [];
        $where = [];

        if(!empty($params["clubShortId"])){
            $club = Db::connect('mongodb')->table('clubs')->where("shortId", intval($params["clubShortId"]))->find();
            if(!empty($club)) $whereClub[] = ["_id", "=", $club["_id"]];
        }

        if(!empty($params["category"])) $where[] = ["category", "=", $params["category"]];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];
        }

        //查询所有俱乐部信息
        $clubList = Db::connect('mongodb')->table('clubs')
            ->where($whereClub)->order("createAt", "desc")->select();
        $newArrClub = [];

        try {
            foreach ($clubList as $key => $value) {
                //分别查询每个俱乐部的房间列表，得到每个俱乐部的开房数
                $value["rooms"] = Db::connect('mongodb')->table('roomrecords')
                    ->where("club", $clubList[$key]["_id"])->where($where)->select();
                $value["roomsCount"] = empty($value["rooms"]) ? 0 : count($value["rooms"]);
                $value["player"] = Db::connect('mongodb')->table('players')
                    ->where("_id", $value["owner"])->find();
                if(!empty($value["gameList"]) && is_array($value["gameList"])) {
                    $games = [];
                    foreach ($value["gameList"] as $k => $v)  {
                        array_push($games, DefaultEnum::GAMElIST[$k]);
                    }
                    $value["gameList"] = implode(" | ", $games);
                }
                $value["normal_last"] = 0;
                $value["normal"] = 0;
                $value["zero_ju"] = 0;
                $value["dissolve"] = 0;

                foreach ($value["rooms"] as $k => $v)  {
                    if($v["roomState"] == DefaultEnum::ROOMSTATE["NORMALLAST"]) ++$value["normal_last"];
                    if($v["roomState"] == DefaultEnum::ROOMSTATE["NORMAL"]) ++$value["normal"];
                    if($v["roomState"] == DefaultEnum::ROOMSTATE["ZEROJU"]) ++$value["zero_ju"];
                    if($v["roomState"] == DefaultEnum::ROOMSTATE["DISSOLVE"]) ++$value["dissolve"];
                }

                $value["_id"] = reset($value["_id"]);
                $value["createAt"] = date('Y-m-d H:i:s',intval(reset($value["createAt"]) / 1000));
                if($value["roomsCount"] > 0) array_push($newArrClub, $value);
            }

            $sort = array_column($newArrClub, 'roomsCount');
            // 按照roomsCount字段升序  其中SORT_ASC表示升序 SORT_DESC表示降序
            array_multisort($sort, SORT_DESC, $newArrClub);
        } catch(Exception $e) {
            var_dump($e);
        }

        return [
            "count" => count($newArrClub),
            "lists" => $newArrClub,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes  俱乐部房间排行榜
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function oldClubRoomRanking($params)
    {
        $date = [];
        $params['s_time'] = date("Y-m-d H:i:s", strtotime("-30 day"));
        $params['e_time'] = date("Y-m-d H:i:s", time());
        $params['cle'] = strtotime($params['e_time']) - strtotime($params['s_time']);
        $d = floor($params["cle"] / 3600 / 24);

        for($i=0; $i <= $d; $i++) {
            $date[] = date("Y-m-d", strtotime($params["e_time"]) - $i*24*60*60);
        }

        $roomRecordRank = [];
        $command = [
            'aggregate' => 'roomrecords',
            'pipeline' => [
                ['$match' => ["createAt" => ['$gte' => new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    '$lte' => new UTCDateTime(strtotime($params["end_time"]) * 1000)], "club" => ['$ne' => null]]],
                ['$group' => ['_id' => ['club' => '$club', 'category' => '$category'], 'sum' => ['$sum' => 1]]],
                ['$project' => [
                    '_id' => '$_id.club',
                    'category' => '$_id.category',
                    'sum' => 1
                ]],
                ['$sort' => ["sum" => -1]],
            ],
            'cursor' => new \stdClass()
        ];

        $result = Db::connect('mongodb')->cmd(new Query(Db::connect('mongodb')), $command);

        foreach ($result as $key => $value) {
            $result[$key]["_id"] = reset($result[$key]["_id"]);

            $club = Db::connect('mongodb')->table('clubs')->where("_id", new ObjectId($result[$key]["_id"]))->find();
            if(empty($club)) continue;
            $player = Db::connect('mongodb')->table('players')->where("_id", $club["owner"])->find();
            $clubRecord = Db::connect('mongodb')->table('clubroomrecords')
                ->where("club", $club["_id"])
                ->where("gameType", $value["category"])
                ->where("receivedAt", "between", [
                    new UTCDateTime(TimeService::dayByDate($params["start_time"], 1)[0] * 1000),
                    new UTCDateTime(TimeService::dayByDate($params["start_time"], 1)[1] * 1000)
                ])->find();

            $roomRecordRank[] = [
                "it" => $result[$key]["_id"],
                "sum" => $result[$key]["sum"],
                "clubId" => $club["shortId"],
                "clubName" => $club["name"],
                "ownerId" => $player["shortId"],
                'day' => date('md', strtotime($params["start_time"])),
                "ownerName" => $player["name"],
                "createAt" => date('Y-m-d H:i:s',intval(reset($club["createAt"]) / 1000)),
                "getGem" => $clubRecord ? (!empty($clubRecord["getGem"]) ? $clubRecord["getGem"] : 0) : 0,
                "category" => $result[$key]["category"],
                "roomInfo" => !empty($clubRecord["roomInfo"]) ? $clubRecord["roomInfo"] : null
            ];
        }

        return ["records"=> $roomRecordRank, "date" => $date];
    }

    /**
     * @notes 查看用户战队金币流水
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function userClubGoldLists($params)
    {
        $where = [];
        $playerIds = [];
        $fromIds = [];
        $whereClub = [];
        $clubIds = [];
        $isSearch = false;
        $isSearchFrom = false;

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

        if(!empty($params["fromRealName"])) {
            $isSearchFrom = true;
            $accounts = Db::connect('mongodb')->table('accounts')
                ->where("realName", 'like', fuzzy_query($params["fromRealName"]))->select();
            if(!empty($accounts)) {
                foreach($accounts as $item) array_push($fromIds, $item["player"]);
            }
        }

        if(!empty($params["fromName"])) {
            $isSearchFrom = true;
            $players = Db::connect('mongodb')->table('players')
                ->where("name", 'like', fuzzy_query($params["fromName"]))->select();
            if(!empty($players)) {
                foreach($players as $item)
                    if(!empty($item) && !in_array($item["_id"], $fromIds))
                        array_push($fromIds, $item["_id"]);
            }
        }

        if(!empty($params["fromPhone"])) {
            $isSearchFrom = true;
            $player = Db::connect('mongodb')->table('players')
                ->where("phone", 'like', fuzzy_query($params["fromPhone"]))->find();
            if(!empty($player) && !in_array($player["_id"], $fromIds)) array_push($fromIds, $player["_id"]);
        }

        if(!empty($params["fromShortId"])){
            $isSearchFrom = true;
            $player = Db::connect('mongodb')->table('players')->where("shortId", intval($params["fromShortId"]))->find();
            if(!empty($player) && !in_array($player["_id"], $fromIds)) array_push($fromIds, $player["_id"]);
        }

        if(!empty($params["clubName"])) $whereClub[] = ["name", "like", fuzzy_query($params["clubName"])];
        if(!empty($params["clubShortId"])) $whereClub[] = ["shortId", "=", intval($params["clubShortId"])];
        if(!empty($whereClub)){
            $clubs = Db::connect('mongodb')->table('clubs')
                ->where($whereClub)->select();
            foreach ($clubs as $item) array_push($clubIds, $item["_id"]);
            $where[] = ["club", "in", $clubIds];
        }

        if((empty($playerIds) && $isSearch) || (empty($fromIds) && $isSearchFrom) || (empty($clubIds) && !empty($whereClub)))
            return nullResultReturn();
        if(!empty($playerIds)) $where[] = ["member", "in", $playerIds];
        if(!empty($fromIds)) $where[] = ["from", "in", $fromIds];
        if(!empty($params["action"])) $where[] = ["goldChange", $params["action"] == 1 ? ">" : "<", 0];
        if(!empty($params["keyword"])) $where[] = ["info", "like", $params["keyword"]];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];
        }

        // 导出
        if (!empty($params["export"]) && $params["export"] == 1) {
            return ExportLogic::userClubGoldExport($where);
        }

        $count = Db::connect('mongodb')->table('clubgoldrecords')->where($where)->count();
        $logsList = Db::connect('mongodb')->table('clubgoldrecords')->where($where)->order("createAt", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($logsList as $key => $value) {
            $logsList[$key]["memberInfo"] = Db::connect('mongodb')->table('players')
                ->where("_id", $logsList[$key]["member"])->find();
            $logsList[$key]["fromInfo"] = Db::connect('mongodb')->table('players')
                ->where("_id", $logsList[$key]["from"])->find();
            $logsList[$key]["clubInfo"] = Db::connect('mongodb')->table('clubs')
                ->where("_id", $logsList[$key]["club"])->find();
            if(empty($logsList[$key]["memberInfo"]["phone"])) $logsList[$key]["memberInfo"]["phone"] = DefaultEnum::ISNOTRESULT;
            if(!empty($logsList[$key]["fromInfo"])) $logsList[$key]["from"] = ($logsList[$key]["fromInfo"]["shortId"] . '(' . $logsList[$key]["fromInfo"]["name"] . ')');
            if(empty($logsList[$key]["fromInfo"]["name"])) $logsList[$key]["fromInfo"]["name"] = DefaultEnum::ISNOTRESULT;
            if(empty($logsList[$key]["fromInfo"]["shortId"])) $logsList[$key]["fromInfo"]["shortId"] = DefaultEnum::ISNOTRESULT;
            $logsList[$key]["_id"] = reset($logsList[$key]["_id"]);
            $logsList[$key]["gameType"] = !empty($logsList[$key]["gameType"]) ?
                $logsList[$key]["gameType"] : DefaultEnum::ISNOTRESULT;
            $info = explode("：", $logsList[$key]["info"]);
            if(count($info) >= 2) $logsList[$key]["room"] = explode("：", $logsList[$key]["info"])[1];
            $logsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["createAt"]) / 1000));
        }

        return [
            "count" => $count,
            "lists" => $logsList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 查看用户战队金币流水
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function clubCreatorGoldLists($params)
    {
        $where = [["info", 'in', ["圈主增加", "圈主清零", "圈主减少"]]];
        $playerIds = [];
        $fromIds = [];
        $whereClub = [];
        $clubIds = [];
        $isSearch = false;
        $isSearchFrom = false;

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

        if(!empty($params["fromRealName"])) {
            $isSearchFrom = true;
            $accounts = Db::connect('mongodb')->table('accounts')
                ->where("realName", 'like', fuzzy_query($params["fromRealName"]))->select();
            if(!empty($accounts)) {
                foreach($accounts as $item) array_push($fromIds, $item["player"]);
            }
        }

        if(!empty($params["fromName"])) {
            $isSearchFrom = true;
            $players = Db::connect('mongodb')->table('players')
                ->where("name", 'like', fuzzy_query($params["fromName"]))->select();
            if(!empty($players)) {
                foreach($players as $item)
                    if(!empty($item) && !in_array($item["_id"], $fromIds))
                        array_push($fromIds, $item["_id"]);
            }
        }

        if(!empty($params["fromPhone"])) {
            $isSearchFrom = true;
            $player = Db::connect('mongodb')->table('players')
                ->where("phone", 'like', fuzzy_query($params["fromPhone"]))->find();
            if(!empty($player) && !in_array($player["_id"], $fromIds)) array_push($fromIds, $player["_id"]);
        }

        if(!empty($params["fromShortId"])){
            $isSearchFrom = true;
            $player = Db::connect('mongodb')->table('players')->where("shortId", intval($params["fromShortId"]))->find();
            if(!empty($player) && !in_array($player["_id"], $fromIds)) array_push($fromIds, $player["_id"]);
        }

        if(!empty($params["clubName"])) $whereClub[] = ["name", "like", fuzzy_query($params["clubName"])];
        if(!empty($params["clubShortId"])) $whereClub[] = ["shortId", "=", intval($params["clubShortId"])];
        if(!empty($whereClub)){
            $clubs = Db::connect('mongodb')->table('clubs')
                ->where($whereClub)->select();
            foreach ($clubs as $item) array_push($clubIds, $item["_id"]);
            $where[] = ["club", "in", $clubIds];
        }

        if((empty($playerIds) && $isSearch) || (empty($fromIds) && $isSearchFrom) || (empty($clubIds) && !empty($whereClub)))
            return nullResultReturn();
        if(!empty($playerIds)) $where[] = ["member", "in", $playerIds];
        if(!empty($fromIds)) $where[] = ["from", "in", $fromIds];
        if(!empty($params["action"])) $where[] = ["goldChange", $params["action"] == 1 ? ">" : "<", 0];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];
        }

        // 导出
        if (!empty($params["export"]) && $params["export"] == 1) {
            return ExportLogic::userClubGoldExport($where);
        }

        $count = Db::connect('mongodb')->table('clubgoldrecords')->where($where)->count();
        $logsList = Db::connect('mongodb')->table('clubgoldrecords')->where($where)->order("createAt", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($logsList as $key => $value) {
            $logsList[$key]["memberInfo"] = Db::connect('mongodb')->table('players')
                ->where("_id", $logsList[$key]["member"])->find();
            $logsList[$key]["fromInfo"] = Db::connect('mongodb')->table('players')
                ->where("_id", $logsList[$key]["from"])->find();
            $logsList[$key]["clubInfo"] = Db::connect('mongodb')->table('clubs')
                ->where("_id", $logsList[$key]["club"])->find();
            if(empty($logsList[$key]["clubInfo"])) {
                $logsList[$key]["clubInfo"]["name"] = DefaultEnum::ISNOTRESULT;
                $logsList[$key]["clubInfo"]["shortId"] = DefaultEnum::ISNOTRESULT;
            }
            if(empty($logsList[$key]["memberInfo"]["phone"])) $logsList[$key]["memberInfo"]["phone"] = DefaultEnum::ISNOTRESULT;
            if(!empty($logsList[$key]["fromInfo"])) $logsList[$key]["from"] = ($logsList[$key]["fromInfo"]["shortId"] . '(' . $logsList[$key]["fromInfo"]["name"] . ')');
            if(empty($logsList[$key]["fromInfo"]["name"])) $logsList[$key]["fromInfo"]["name"] = DefaultEnum::ISNOTRESULT;
            if(empty($logsList[$key]["fromInfo"]["shortId"])) $logsList[$key]["fromInfo"]["shortId"] = DefaultEnum::ISNOTRESULT;
            $logsList[$key]["_id"] = reset($logsList[$key]["_id"]);
            $logsList[$key]["gameType"] = !empty($logsList[$key]["gameType"]) ?
                $logsList[$key]["gameType"] : DefaultEnum::ISNOTRESULT;
            $logsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["createAt"]) / 1000));
        }

        return [
            "count" => $count,
            "lists" => $logsList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 查看用户红包领取流水
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function userRedPocketLists($params)
    {
        $where = [["amountInFen", ">", 0]];
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
        if(!empty($playerIds)) $where[] = ["player", "in", $playerIds];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];
        }

        // 导出
        if (!empty($params["export"]) && $params["export"] == 1) {
            return ExportLogic::userRedPocketExport($where);
        }

        $count = Db::connect('mongodb')->table('redpocketrecords')->where($where)->count();
        $logsList = Db::connect('mongodb')->table('redpocketrecords')->where($where)->order("createAt", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($logsList as $key => $value) {
            $logsList[$key]["playerInfo"] = Db::connect('mongodb')->table('players')->where("_id", $logsList[$key]["player"])->find();
            $logsList[$key]["account"] = Db::connect('mongodb')->table('accounts')
                ->where("player", $logsList[$key]["player"])->find();
            $logsList[$key]["playerInfo"]["realName"] = !empty($logsList[$key]["account"]) ? $logsList[$key]["account"]["realName"] : DefaultEnum::ISNOTRESULT;
            if(empty($logsList[$key]["playerInfo"]["phone"])) $logsList[$key]["playerInfo"]["phone"] = DefaultEnum::ISNOTRESULT;
            $logsList[$key]["amountInFen"] = round($logsList[$key]["amountInFen"] / 100, 2);
            $logsList[$key]["roomId"] = !empty(explode(":", $logsList[$key]["from"])) ? explode(":", $logsList[$key]["from"])[1] : "";
            $roomRecord = Db::connect('mongodb')->table('roomrecords')->where("roomNum", $logsList[$key]["roomId"])->find();
            if(!empty($roomRecord)) {
                $logsList[$key]["gameName"] = !empty($roomRecord) ? DefaultEnum::GAMElIST[$roomRecord["category"]] : DefaultEnum::ISNOTRESULT;
                $logsList[$key]["roomState"] = !empty($roomRecord) ? DefaultEnum::ROOMSTATETEXT[$roomRecord["roomState"]] : DefaultEnum::ISNOTRESULT;
                $logsList[$key]["playerCount"] = !empty($roomRecord) ? $roomRecord["rule"]["playerCount"] : DefaultEnum::ISNOTRESULT;
                $logsList[$key]["juShu"] = !empty($roomRecord) ? $roomRecord["rule"]["juShu"] : DefaultEnum::ISNOTRESULT;
                if(!empty($roomRecord["rule"]["ruleId"])) {
                    $clubRule = Db::connect('mongodb')->table('clubrules')
                        ->where("_id", new ObjectId($roomRecord["rule"]["ruleId"]))->find();
                }
                $logsList[$key]["roomType"] = !empty($clubRule) ? DefaultEnum::ROOMTYPE[$clubRule["ruleType"]] : DefaultEnum::ROOMTYPE[3];
            }
            $logsList[$key]["_id"] = reset($logsList[$key]["_id"]);
            $logsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["createAt"]) / 1000));
        }

        return [
            "count" => $count,
            "lists" => $logsList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 查看用户红包提现流水
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function userRedPocketWithdrawLists($params)
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
        if(!empty($playerIds)) $where[] = ["player", "in", $playerIds];
        if(!empty($params["state"])) $where[] = ["state", "=", $params["state"]];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];
        }

        // 导出
        if (!empty($params["export"]) && $params["export"] == 1) {
            return ExportLogic::userRedPocketWithdrawExport($where);
        }

        $count = Db::connect('mongodb')->table('redpocketwithdrawrecords')->where($where)->count();
        $logsList = Db::connect('mongodb')->table('redpocketwithdrawrecords')->where($where)->order("createAt", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($logsList as $key => $value) {
            $logsList[$key]["playerInfo"] = Db::connect('mongodb')->table('players')->where("_id", $logsList[$key]["player"])->find();
            $logsList[$key]["account"] = Db::connect('mongodb')->table('accounts')
                ->where("player", $logsList[$key]["player"])->find();
            $logsList[$key]["playerInfo"]["realName"] = !empty($logsList[$key]["account"]) ? $logsList[$key]["account"]["realName"] : DefaultEnum::ISNOTRESULT;
            if(empty($logsList[$key]["playerInfo"]["phone"])) $logsList[$key]["playerInfo"]["phone"] = DefaultEnum::ISNOTRESULT;
            $logsList[$key]["amountInFen"] = round($logsList[$key]["amountInFen"] / 100, 2);
            $logsList[$key]["_id"] = reset($logsList[$key]["_id"]);
            $logsList[$key]["info"] = DefaultEnum::WITHDRAWSTATE[$logsList[$key]["state"]];
            $logsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["createAt"]) / 1000));
        }

        return [
            "count" => $count,
            "lists" => $logsList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 查看商品购买订单
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function payGoodsOrderLists($params)
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
        if(!empty($playerIds)) $where[] = ["playerId", "in", $playerIds];
        if(!empty($params["amount"])) $where[] = ["goodsDetail.amount", "=", $params["amount"]];
        if(!empty($params["status"])) $where[] = ["status", "=", $params["status"]];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];
        }

        $count = Db::connect('mongodb')->table('goodsorders')->where($where)->count();
        $logsList = Db::connect('mongodb')->table('goodsorders')->where($where)->order("createdAt", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($logsList as $key => $value) {
            $logsList[$key]["playerInfo"] = Db::connect('mongodb')->table('players')->where("_id", $logsList[$key]["playerId"])->find();
            $logsList[$key]["account"] = Db::connect('mongodb')->table('accounts')
                ->where("player", $logsList[$key]["playerId"])->find();
            $logsList[$key]["playerInfo"]["realName"] = !empty($logsList[$key]["account"]) ? $logsList[$key]["account"]["realName"] : DefaultEnum::ISNOTRESULT;
            if(empty($logsList[$key]["playerInfo"]["phone"])) $logsList[$key]["playerInfo"]["phone"] = DefaultEnum::ISNOTRESULT;
            if(!empty($logsList[$key]["status"] == 1)) {
                $goodsPayOrders = Db::connect('mongodb')->table('goodspayorders')->where("orderId", $logsList[$key]["_id"])->find();
            }
            $logsList[$key]["goodsPayOrders"] = !empty($goodsPayOrders) ? $goodsPayOrders["thirdOrderNo"] : DefaultEnum::ISNOTRESULT;

            $logsList[$key]["goodsDetail"]["androidPrice"] = round($logsList[$key]["goodsDetail"]["androidPrice"] / 100, 2);
            $logsList[$key]["goodsDetail"]["iosPrice"] = DefaultEnum::IOSPRICE[$logsList[$key]["goodsDetail"]["applePriceId"]];
            $logsList[$key]["goodsDetail"]["goodsType"] = DefaultEnum::GOODSTYPE[$logsList[$key]["goodsDetail"]["goodsType"]];

            $logsList[$key]["payPrice"] = round($logsList[$key]["payPrice"] / 100, 2);
            $logsList[$key]["status"] = DefaultEnum::GOODSTATUS[$logsList[$key]["status"]];
            $logsList[$key]["_id"] = reset($logsList[$key]["_id"]);
            $logsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["createdAt"]) / 1000));
        }

        return [
            "count" => $count,
            "lists" => $logsList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 查看用户充值统计
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function userRechargeStatisticsLists($params)
    {
        if (empty($params['start_time']) || empty($params['end_time'])) {
            // 未传月份，默认获取当前月份数据
            $params['start_time'] = date("Y-m-d H:i:s", strtotime("-14 day"));
            $params['end_time'] = date("Y-m-d H:i:s", time());
        }

        $params['cle'] = strtotime($params['end_time']) - strtotime($params['start_time']);
        $params['start_time'] = new UTCDateTime(strtotime($params['start_time']) * 1000);
        $params['end_time'] = new UTCDateTime(strtotime($params['end_time']) * 1000);
        $data = [];
        $d = floor($params["cle"] / 3600 / 24);
        for($i = 0; $i <= $d; $i++) {
            $day = date("Ymd", intval((reset($params["start_time"]) / 1000) + $i * 24 * 60 * 60));
            $start_time = new UTCDateTime(strtotime($day . ' 00:00:00') * 1000);
            $end_time = new UTCDateTime(strtotime($day . ' 23:59:59') * 1000);
            $wechatGem = Db::connect('mongodb')->table('userrechargeorders')->where("source", "wechat")
                ->where("created", "between", [$start_time, $end_time])->sum("amount");
            $adminGem = Db::connect('mongodb')->table('userrecords')->where("source", "admin")
                ->where("created", "between", [$start_time, $end_time])->sum("amount");
            $wechatGold = Db::connect('mongodb')->table('userrecords')->where("source", "wechat")
                ->where("created", "between", [$start_time, $end_time])->sum("amount");
            $adminGold = Db::connect('mongodb')->table('userrecords')->where("source", "admin")
                ->where("created", "between", [$start_time, $end_time])->sum("amount");

            if($wechatGem > 0 || $adminGem > 0 || $wechatGold > 0 || $adminGold > 0) {
                $data[] = [
                    "dayId" => $day,
                    "wechatGem" => $wechatGem,
                    "adminGem" => $adminGem,
                    "wechatGold" => $wechatGold,
                    "adminGold" => $adminGold,
                ];
            }
        }

        $sort = array_column($data, 'dayId');
        array_multisort($sort, SORT_DESC, $data);

        return [
            "count" => count($data),
            "lists" => $data,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 查看用户充值统计
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function userRedPocketRankingLists($params)
    {
        $count = Db::connect('mongodb')->table('players')->where("redPocket", ">", 0)->order("redPocket", "desc")->count();
        $logsList = Db::connect('mongodb')->table('players')->where("redPocket", ">", 0)->order("redPocket", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($logsList as $key => $value) {
            $logsList[$key]["account"] = Db::connect('mongodb')->table('accounts')->where("player", $logsList[$key]["_id"])->find();
            if(empty($logsList[$key]["phone"])) $logsList[$key]["phone"] = DefaultEnum::ISNOTRESULT;
            $logsList[$key]["redPocket"] = round($logsList[$key]["redPocket"] / 100, 2);
            $logsList[$key]["realName"] = empty($logsList[$key]["account"]) ? DefaultEnum::ISNOTRESULT : $logsList[$key]["account"]["realName"];
            $logsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["createAt"]) / 1000));
        }

        return [
            "count" => $count,
            "lists" => $logsList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 查看宝箱领取日志
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function userGameRateLists($params)
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

        if(empty($playerIds) && $isSearch) return nullResultReturn();
        if(!empty($playerIds)) $where[] = ["player", 'in', $playerIds];
        if(!empty($params["game"])) $where[] = ["game", 'in', $params["game"]];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) $where[] = ["createAt", "between",
            [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                new UTCDateTime(strtotime($params["end_time"]) * 1000)]];

        $count = Db::connect('mongodb')->table('treasureboxrecords')->where($where)->order("createAt", "desc")->count();
        $lists = Db::connect('mongodb')->table('treasureboxrecords')->where($where)->order("createAt", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($lists as $key => $value) {
            $player = Db::connect('mongodb')->table('players')->where("_id", $value["player"])->find();
            $lists[$key]["username"] = $player["name"];

            $treasureBox = Db::connect('mongodb')->table('treasureboxes')->where("level", $value["level"])->find();
            $lists[$key]["boxName"] = $treasureBox["name"];
            $lists[$key]["type"] = DefaultEnum::HELPTYPELISTS[$lists[$key]["type"]];
            $lists[$key]["gameStr"] = !empty($lists[$key]["game"]) ? DefaultEnum::GAMElIST[$lists[$key]["game"]] : DefaultEnum::ISNOTRESULT;

            $lists[$key]["_id"] = reset($lists[$key]["_id"]);
            $lists[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($lists[$key]["createAt"]) / 1000));
            $lists[$key]["helpStr"] = DefaultEnum::ISNOTRESULT;
            if(!empty($lists[$key]["mahjong"])) {
                $cards = [];
                foreach ($lists[$key]["mahjong"]["cardlists"] as $k => $v)  {
                    array_push($cards, "初始牌：{$v["cardName"]}");
                }
                $lists[$key]["helpStr"] = implode(" + ", $cards);
                $lists[$key]["helpStr"] .= ",辅助摸牌次数：{$lists[$key]["mahjong"]["moCount"]}次";
            }

            if(!empty($lists[$key]["pdk"])) {
                $cards = [];
                foreach ($lists[$key]["pdk"] as $k => $v)  {
                    array_push($cards, "初始牌：{$v["cardName"]}");
                }
                $lists[$key]["helpStr"] = implode(" + ", $cards);
            }

            if(!empty($lists[$key]["sss"])) {
                $cards = [];
                foreach ($lists[$key]["sss"] as $k => $v)  {
                    array_push($cards, "初始牌：{$v["cardName"]}");
                }
                $lists[$key]["helpStr"] = implode(" + ", $cards);
            }

            if(!empty($lists[$key]["bf"])) {
                $cards = [];
                foreach ($lists[$key]["bf"] as $k => $v)  {
                    array_push($cards, "初始牌：{$v["cardName"]}");
                }
                $lists[$key]["helpStr"] = implode(" + ", $cards);
            }

            if(!empty($lists[$key]["game"]) && $lists[$key]["game"] === "zhadan") $lists[$key]["helpStr"] = $lists[$key]["star"] . '星';
        }

        return [
            "count" => $count,
            "lists" => $lists,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 查看用户游戏胜率
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function userGameRateRankLists($params)
    {
        $where = [];
        $limit = 10;
        $wherePlayer = [];
        $whereDefault = [];
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

        if(empty($playerIds) && $isSearch) return nullResultReturn();
        if(!empty($playerIds)) $wherePlayer[] = ["_id", 'in', $playerIds];
        if(!empty($params["juShu"])) $limit = $params["juShu"];
        if(!empty($params["game"])) $where[] = ["category", "=", $params["game"]];
        if(empty($params["start_time"]) || empty($params["end_time"])) {
            $today = TimeService::today();

            if(empty($params["juShu"]) && empty($params["game"])) {
                if(!empty($playerIds)) $whereDefault[] = ["player", 'in', $playerIds];

                $count = Db::connect('mongodb')->table('playerhelps')->where($whereDefault)
                    ->order($params["sort_key"], $params["value"])->order("estimateLevel", "desc")->count();
                $playersList = Db::connect('mongodb')->table('playerhelps')->where($whereDefault)
                    ->order($params["sort_key"], $params["value"])->order("estimateLevel", "desc")
                    ->page($params["page_no"], $params["page_size"])->select()->all();

                foreach ($playersList as $key => $value) {
                    $player = Db::connect('mongodb')->table('players')->where("_id", $value["player"])->find();
                    $playersList[$key]["name"] = $player["name"];
                    $playersList[$key]["shortId"] = $player["shortId"];
                    $playersList[$key]["_id"] = $player["_id"];
                    $playersList[$key]["isHelp"] = DefaultEnum::HELPLIST[$playersList[$key]["isHelp"]];
                    $playersList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($playersList[$key]["createAt"]) / 1000));
                }

                return [
                    "count" => $count,
                    "lists" => $playersList,
                    "page_no" => $params["page_no"],
                    "page_size" => $params["page_size"]
                ];
            }

            $params["start_time"] = date("Y-m-d H:i:s", $today[0]);
            $params["end_time"] = date("Y-m-d H:i:s", $today[1]);
        }
        $where[] = ["createAt", "between",
            [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                new UTCDateTime(strtotime($params["end_time"]) * 1000)]];

        $count = Db::connect('mongodb')->table('players')->where($wherePlayer)->order("createAt", "desc")->count();
        $playersList = Db::connect('mongodb')->table('players')->where($wherePlayer)->order("createAt", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($playersList as $key => $value) {
            if(empty($playersList[$key]["phone"])) $playersList[$key]["phone"] = DefaultEnum::ISNOTRESULT;
            $where[] = ["players", "in", $value["_id"]];
            $roomRecords = Db::connect('mongodb')->table('roomrecords')->where($where)->order("createAt", "desc")->limit($limit)->select();
            $playersList[$key]["juRate"] = 0;
            $playersList[$key]["juScore"] = 0;
            $playersList[$key]["totalJuScore"] = 0;
            $playersList[$key]["totalJu"] = count($roomRecords);
            $playersList[$key]["totalMinJu"] = 0;
            $playersList[$key]["minJuRate"] = 0;
            $playersList[$key]["minJuScore"] = 0;
            $playersList[$key]["winJu"] = 0;
            $playersList[$key]["failJu"] = 0;
            $playersList[$key]["winJuScore"] = 0;
            $playersList[$key]["failJuScore"] = 0;
            $playersList[$key]["winMinJu"] = 0;
            $playersList[$key]["failMinJu"] = 0;
            $playersList[$key]["winMinJuScore"] = 0;
            $playersList[$key]["failMinJuScore"] = 0;
            $playersList[$key]["totalMinJuScore"] = 0;
            $playersList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($playersList[$key]["createAt"]) / 1000));

            foreach ($roomRecords as $k => $v) {
                foreach ($v["scores"] as $k1 => $v1) {
                    if(!empty($v1) && $v1["shortId"] == $value["shortId"]) {
                        $v1["score"] > 0 ? ++$playersList[$key]["winJu"] : ++$playersList[$key]["failJu"];
                        $v1["score"] > 0 ? $playersList[$key]["winJuScore"] += $v1["score"] : $playersList[$key]["failJuScore"] += $v1["score"];
                        $playersList[$key]["totalJuScore"] += $v1["score"];
                    }
                }
                try {
                    $games = Db::connect('mongodb')->table('gamerecords')->field("record, playersInfo")->where("room", $v["room"])->select();
                    foreach ($games as $k2 => $v2) {
                        foreach ($v2["record"] as $k3 => $v3) {
                            if(empty($v3) || empty($v2["playersInfo"][$k3]["model"]["shortId"]) || $v2["playersInfo"][$k3]["model"]["shortId"] != $value["shortId"]) continue;
                            $v3["score"] > 0 ? ++$playersList[$key]["winMinJu"] : ++$playersList[$key]["failMinJu"];
                            $v3["score"] > 0 ? $playersList[$key]["winMinJuScore"] += $v3["score"] : $playersList[$key]["failMinJuScore"] += $v3["score"];
                            $playersList[$key]["totalMinJuScore"] += $v3["score"];
                            $playersList[$key]["totalMinJu"]++;
                        }
                    }
                } catch(Exception $e) {
                    var_dump($e);
                }
            }

            if(count($roomRecords) > 0) {
                $playersList[$key]["juRate"] = round($playersList[$key]["winJu"] / count($roomRecords) * 100, 1) . '%';
                $playersList[$key]["juScore"] = round($playersList[$key]["totalJuScore"] / count($roomRecords), 2);
            }

            if($playersList[$key]["totalMinJu"] > 0) {
                $playersList[$key]["minJuRate"] = round($playersList[$key]["winMinJu"] / $playersList[$key]["totalMinJu"] * 100, 1) . '%';
                $playersList[$key]["minJuScore"] = round($playersList[$key]["totalMinJuScore"] / $playersList[$key]["totalMinJu"], 2);
            }
        }

        $sort = array_column($playersList, 'juRate');
        array_multisort($sort, SORT_DESC, $playersList);

        return [
            "count" => $count,
            "lists" => $playersList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }


    /**
     * @notes 查看用户待救助
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function userGameHelpRecord($params)
    {
        $where = [["count", ">", 0]];
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

        if(empty($playerIds) && $isSearch) return nullResultReturn();
        if(!empty($playerIds)) $where[] = ["player", 'in', $playerIds];
        if(!empty($params["game"])) $where[] = ["game", '=', $params["game"]];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) $where[] = ["createAt", "between",
            [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                new UTCDateTime(strtotime($params["end_time"]) * 1000)]];

        $count = Db::connect('mongodb')->table('playerhelpdetails')->where($where)->count();
        $playersList = Db::connect('mongodb')->table('playerhelpdetails')->where($where)
            ->order("game", "asc")->order("shortId", "asc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($playersList as $key => $value) {
            $player = Db::connect('mongodb')->table('players')->where("_id", $value["player"])->find();
            $playersList[$key]["name"] = $player["name"];
            $playersList[$key]["gameStr"] = !empty($playersList[$key]["game"]) ? DefaultEnum::GAMElIST[$playersList[$key]["game"]] : DefaultEnum::ISNOTRESULT;
            $playersList[$key]["isHelp"] = DefaultEnum::HELPSTATELISTS[$playersList[$key]["isHelp"]];
            $playersList[$key]["typeStr"] = DefaultEnum::HELPTYPELISTS[$playersList[$key]["type"]];
            $playersList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($playersList[$key]["createAt"]) / 1000));
            if(empty($playersList[$key]["mahjong"]["cardlists"])) {
                $playersList[$key]["mahjongStr"] = DefaultEnum::ISNOTRESULT;
            } else {
                $cards = [];
                foreach ($playersList[$key]["mahjong"]["cardlists"] as $k => $v)  {
                    array_push($cards, "初始牌：{$v["cardName"]}");
                }
                $playersList[$key]["mahjongStr"] = implode(" + ", $cards);
                $playersList[$key]["mahjongStr"] .= ",辅助摸牌次数：{$playersList[$key]["mahjong"]["moCount"]}次";
            }

            if(empty($playersList[$key]["pdk"])) {
                $playersList[$key]["pdkStr"] = DefaultEnum::ISNOTRESULT;
            } else {
                $cards = [];
                foreach ($playersList[$key]["pdk"] as $k => $v)  {
                    array_push($cards, "初始牌：{$v["cardName"]}");
                }
                $playersList[$key]["pdkStr"] = implode(" + ", $cards);
            }

            if(empty($playersList[$key]["sss"])) {
                $playersList[$key]["sssStr"] = DefaultEnum::ISNOTRESULT;
            } else {
                $cards = [];
                foreach ($playersList[$key]["sss"] as $k => $v)  {
                    array_push($cards, "初始牌：{$v["cardName"]}");
                }
                $playersList[$key]["sssStr"] = implode(" + ", $cards);
            }

            if(empty($playersList[$key]["bf"])) {
                $playersList[$key]["bfStr"] = DefaultEnum::ISNOTRESULT;
            } else {
                $cards = [];
                foreach ($playersList[$key]["bf"] as $k => $v)  {
                    array_push($cards, "初始牌：{$v["cardName"]}");
                }
                $playersList[$key]["bfStr"] = implode(" + ", $cards);
            }
        }

        return [
            "count" => $count,
            "lists" => $playersList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 查看用户是否可以触发规则
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function userCanRateRuleLists($params)
    {
        $player = Db::connect('mongodb')->table('players')->where("_id", $params["_id"])->find();
        $rateRule = Db::connect('mongodb')->table('ratetemplate')->select()->toArray();

        if(empty($player["phone"])) $player["phone"] = DefaultEnum::ISNOTRESULT;

        foreach($rateRule as $key => $value) {
            $where = [];
            $limit = $value["juCount"];
            $where[] = ["category", "=", $value["game"]];

            $params['start_time'] = date("Y-m-d H:i:s", strtotime("-" . $value["day"] . " day"));
            $params['end_time'] = date("Y-m-d H:i:s", time());
            $params['cle'] = strtotime($params['end_time']) - strtotime($params['start_time']);
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];

            $where[] = ["players", "in", $player["_id"]];
            $rateRule[$key]["totalJu"] = Db::connect('mongodb')->table('roomrecords')->where($where)->count();
            $roomRecords = Db::connect('mongodb')->table('roomrecords')->where($where)->order("createAt", "desc")->limit($limit)->select();
            $rateRule[$key]["username"] = $player["name"];
            $rateRule[$key]["phone"] = $player["phone"];
            $rateRule[$key]["shortId"] = $player["shortId"];
            $rateRule[$key]["juRate"] = 0;
            $rateRule[$key]["winJu"] = 0;
            $rateRule[$key]["failJu"] = 0;
            $rateRule[$key]["winJuScore"] = 0;
            $rateRule[$key]["failJuScore"] = 0;
            $rateRule[$key]["totalJuScore"] = 0;
            $rateRule[$key]["isHelp"] = 0;
            $rateRule[$key]["estimate"] = 0;

            foreach ($roomRecords as $k => $v) {
                foreach ($v["scores"] as $k1 => $v1) {
                    if(!empty($v1) && $v1["shortId"] == $player["shortId"]) {
                        $v1["score"] > 0 ? ++$rateRule[$key]["winJu"] : ++$rateRule[$key]["failJu"];
                        $v1["score"] > 0 ? $rateRule[$key]["winJuScore"] += $v1["score"] : $rateRule[$key]["failJuScore"] += $v1["score"];
                        $rateRule[$key]["totalJuScore"] += $v1["score"];
                    }
                }
            }

            if(count($roomRecords) > 0) {
                $rateRule[$key]["juRate"] = round($rateRule[$key]["winJu"] / count($roomRecords), 2);
            }

            //判断局数,胜率,输局积分是否满足条件
            if($rateRule[$key]["totalJu"] >= intval($rateRule[$key]["juCount"]) && $rateRule[$key]["juRate"] <= $rateRule[$key]["juRank"]
            && abs($rateRule[$key]["totalJuScore"]) >= intval($rateRule[$key]["juMinScore"])) $rateRule[$key]["isHelp"] = 1;

            $rateRule[$key]["rateLevel"] = Db::connect('mongodb')->table('ratelevels')
                ->where("level", ">=", $rateRule[$key]["minLevel"])->where("level", "<=", $rateRule[$key]["maxLevel"])
                ->order("createAt", "desc")->limit($limit)->select();
            $rateRule[$key]["scorestr"] = $rateRule[$key]["juMinScore"] . '~' . $rateRule[$key]["juMaxScore"];
            $rateRule[$key]["levelstr"] = $rateRule[$key]["minLevel"] . '~' . $rateRule[$key]["maxLevel"];
            $rateRule[$key]["estimateLevel"] = 0;

            if($rateRule[$key]["isHelp"] == 1) {
                //计算预估救助等级
                $level = $rateRule[$key]["maxLevel"] - $rateRule[$key]["minLevel"];
                $score = round(($rateRule[$key]["juMaxScore"] - $rateRule[$key]["juMinScore"]) / $level, 2);
                $userScore = round(abs($rateRule[$key]["failJuScore"]) - $rateRule[$key]["juMinScore"], 2);

                if($userScore < $score) $rateRule[$key]["estimateLevel"] = $rateRule[$key]["minLevel"];
                else {
                    $calcLevel = $rateRule[$key]["minLevel"] += floor(($userScore / $score));
                    $rateRule[$key]["estimateLevel"] = $calcLevel >= $rateRule[$key]["maxLevel"] ? $rateRule[$key]["maxLevel"] : $calcLevel;
                }
            }

            $rateRule[$key]["isHelp"] = DefaultEnum::HELPLIST[$rateRule[$key]["isHelp"]];
        }

        return [
            "count" => count($rateRule),
            "lists" => $rateRule,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 查看4王游戏列表
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function gameHaveFourLists($params)
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

        if(!empty($params["name"])) {
            $isSearch = true;
            $players = Db::connect('mongodb')->table('players')
                ->where("name", 'like', fuzzy_query($params["name"]))->select();
            if(!empty($players)) {
                foreach($players as $item) array_push($playerIds, $item["shortId"]);
            }
        }

        if(!empty($params["playerPhone"])) {
            $isSearch = true;
            $player = Db::connect('mongodb')->table('players')
                ->where("phone", 'like', fuzzy_query($params["playerPhone"]))->find();
            if(!empty($player) && !in_array($player["_id"], $playerIds)) array_push($playerIds, $player["shortId"]);
        }

        if(!empty($params["playerShortId"])){
            $isSearch = true;
            $player = Db::connect('mongodb')->table('players')->where("shortId", intval($params["playerShortId"]))->find();
            if(!empty($player) && !in_array($player["_id"], $playerIds)) array_push($playerIds, $player["shortId"]);
        }

        if(empty($playerIds) && $isSearch) return nullResultReturn();
        if(!empty($playerIds)) $where[] = ["shortId", 'in', $playerIds];
        if(!empty($params["juShu"])) $where[] = ["juCount", '=', intval($params["juShu"])];
        if(!empty($params["jokerNum"])) $where[] = ["jokerNum", '=', intval($params["jokerNum"])];
        if(!empty($params["dayId"])) $where[] = ["dayId", '=', $params["dayId"]];
        if(!empty($params["roomId"])) $where[] = ["roomId", '=', intval($params["roomId"])];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["gameTime", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];
        }

        // 导出
        if (!empty($params["export"]) && $params["export"] == 1) {
            return ExportLogic::gameHaveFourJokerExport($where);
        }

        $count = Db::connect('mongodb')->table('gamefourjokerrecords')->where($where)->count();
        $recordList = Db::connect('mongodb')->table('gamefourjokerrecords')->where($where)
            ->order("dayId", "desc")->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($recordList as $key => $value) {
            $player = Db::connect('mongodb')->table('players')->where("shortId", $value["shortId"])->find();
            if(!empty($player)) $recordList[$key]["name"] = $player["name"];
            $recordList[$key]["gameTime"] = date('Y-m-d H:i:s',intval(reset($recordList[$key]["gameTime"]) / 1000));
            $recordList[$key]["type"] = DefaultEnum::GAMElIST[$recordList[$key]["type"]];
            $recordList[$key]["juShu"] = '第' . $recordList[$key]["juShu"] . '局';
            $recordList[$key]["_id"] = reset($recordList[$key]["_id"]);
        }

        return [
            "count" => $count,
            "lists" => $recordList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 查看4王游戏列表
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function gameHaveFourDetail($params)
    {
        $record = Db::connect('mongodb')->table('gamefourjokerrecords')->where("_id", new ObjectId($params["_id"]))->find();

        foreach ($record["cards"] as $key => $value)  {
            $record["cards"][$key]["type_arr"] = DefaultEnum::CARDTYPE[$value["type"]];
            $record["cards"][$key]["point"] = DefaultEnum::POINTTYPE[$value["point"]] ?? $value["point"];
        }

        $sort = array_column($record["cards"], 'type');
        array_multisort($sort, SORT_ASC, $record["cards"]);

        return $record["cards"];
    }

    /**
     * @notes  对局详情
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public function roomRuleDetail($params)
    {
        $game = Db::connect('mongodb')->table('gamerecords')->where("_id", new ObjectId($params["game_id"]))->find();
        $game["_id"] = reset($game["_id"]);
        $game["time"] = date('Y-m-d H:i:s', intval(reset($game["time"]) / 1000));

        //禁止删除，解决games中有NAN值报错
        $game = json_decode(json_encode($game, JSON_NUMERIC_CHECK | JSON_PARTIAL_OUTPUT_ON_ERROR), true);

        foreach ($game["events"] as $k1 => $v1) {
            if(in_array($game["type"], ["zhadan", "biaofen", "paodekuai"])) {
                $sort = array_column($game["events"][$k1]["info"]["cards"], 'value');
                array_multisort($sort, SORT_DESC, $game["events"][$k1]["info"]["cards"]);

                if(!empty($game["events"][$k1]["info"]["actionCards"])) {
                    $sort = array_column($game["events"][$k1]["info"]["actionCards"], 'value');
                    array_multisort($sort, SORT_DESC, $game["events"][$k1]["info"]["actionCards"]);
                }
            }

            if(in_array($game["type"], ["majiang"])) {
                rsort($game["events"][$k1]["info"]["cards"]);
            }
        }

        return $game;
    }

    /**
     * @notes  解散房间
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/10 16:58
     */
    public static function userRoomDissolve($params)
    {
        $room = Db::connect('mongodb')->table('roomrecords')->where("roomNum", $params["roomId"])->find();
        if(empty($room) && empty($params["game"])) return ["code" => false, "msg" => "房间不存在"];

        try {
            $redis = Cache::store('redis')->handler();

            // 在需要的地方发布消息到redis频道
            $message = [
                'payload' => [
                    'roomId' => intval($params["roomId"]),
                    'gameType' => !empty($room["category"]) ? $room["category"] : $params["game"]
                ],
                'cmd' => 'dissolveRoom'
            ];

            $redis->publish('adminChannelToGame', json_encode($message));
        } catch (\RedisException $e) {
            return ["code" => false, "msg" => $e->getMessage()];
        }

        return ["code" => true, "msg" => "操作成功"];
    }
}
