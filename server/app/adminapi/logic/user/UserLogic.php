<?php

namespace app\adminapi\logic\user;
use app\adminapi\logic\wechat\WechatLogic;
use app\common\{enum\DefaultEnum,
    logic\BaseLogic,
    service\ConfigService,
    service\FileService,
    service\pay\WeChatPayService,
    service\storage\Driver};
use Exception;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Db;
use think\facade\Request;


/**
 * 用户逻辑层
 * Class UserLogic
 * @package app\adminapi\logic\user
 */
class UserLogic extends BaseLogic
{
    /**
     * @notes 用户概况页面
     * @return array
     * @author cjhao
     * @date 2021/8/17 14:58
     */
    public function index():array
    {
        $today_start_time = new UTCDateTime(strtotime(date("Y-m-d") . " 00:00:00") * 1000);
        //用户数
        $userCount = Db::connect('mongodb')->table('players')->count();
        //今日新增用户数
        $userNewCount = Db::connect('mongodb')->table('players')->where("createAt", ">", $today_start_time)->count();
        //实时开房数
        $repetitionCount = Db::connect('mongodb')->table('roominfos')->count();
        //今日开房数
        $purchaseCount = Db::connect('mongodb')->table('roomrecords')->where("createAt", ">", $today_start_time)->where("scores", "<>", [])->count();

        $dayList = day_time(14,true);
        $dayList = array_reverse($dayList);

        $echarts_data = [];
        //图表数据
        foreach ($dayList as $dayVal){
            $today_start_time = new UTCDateTime($dayVal * 1000);
            $today_end_time = new UTCDateTime(($dayVal + 86399) * 1000);
            $newUserCount = Db::connect('mongodb')->table('players')->whereTime('createAt','between',[$today_start_time,$today_end_time])->count();
            $echarts_data[] = [
                'day'               => date('m-d',$dayVal),
                'user_new_count'    => $newUserCount,
            ];
        }

        $data = [
            'user_count'        => $userCount,
            'user_new_count'    => $userNewCount,
            'purchase_count'    => $purchaseCount,
            'echarts_data'      => $echarts_data,
        ];
        return $data;
    }
    /**
     * @notes 用户列表
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author cjhao
     * @date 2021/8/18 15:52
     */
    public function lists($params)
    {
        $where = [];
        $isSearch = false;
        $playerIds = [];

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

        if(!empty($params["playerOpenid"])){
            $isSearch = true;
            $player = Db::connect('mongodb')->table('players')->where("miniOpenid", $params["playerOpenid"])->find();
            if(!empty($player) && !in_array($player["_id"], $playerIds)) array_push($playerIds, $player["_id"]);
        }

        if(empty($playerIds) && $isSearch) return nullResultReturn();
        if(!empty($playerIds)) $where[] = ["_id", "in", $playerIds];
        if(!empty($params["tourist"])) {
            $where[] = ["tourist", "=", $params["tourist"] == "true"];
        }
        if(!empty($params["robot"])) {
            $where[] = ["robot", "=", $params["robot"] == "true"];
        }
        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["createAt", "between",
                [new UTCDateTime($params["start_time"] * 1000),
                    new UTCDateTime($params["end_time"] * 1000)]];
        }

        $count = Db::connect('mongodb')->table('players')->where($where)->count();
        $playersList = Db::connect('mongodb')->table('players')->where($where)->order("createAt", "desc")
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($playersList as $key => $value) {
            $playersList[$key]["phone"] = !empty($playersList[$key]["phone"]) ? $playersList[$key]["phone"] : DefaultEnum::ISNOTRESULT;
            $playersList[$key]["isAuth"] = Db::connect('mongodb')->table('accounts')
                ->where("player", $playersList[$key]["_id"])->count();
            $playersList[$key]["redPocket"] = round($playersList[$key]["redPocket"] / 100, 2);
            $playersList[$key]["isCreator"] = Db::connect('mongodb')->table('clubs')
                ->where("owner", $playersList[$key]["_id"])->count();
            $playersList[$key]["joinClubCount"] = Db::connect('mongodb')->table('clubmembers')
                ->where("member", $playersList[$key]["_id"])->count();
            $playersList[$key]["club"] = Db::connect('mongodb')->table('clubs')
                ->where("owner", $playersList[$key]["_id"])->find();
            if(!empty($playersList[$key]["club"]["_id"])) $playersList[$key]["club"]["_id"] = reset($playersList[$key]["club"]["_id"]);
            $playersList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($playersList[$key]["createAt"]) / 1000));
            $playersList[$key]["_id"] = reset($playersList[$key]["_id"]);
            if(empty($playersList[$key]["headImgUrl"])) $playersList[$key]["headImgUrl"] = ConfigService::get('default_image', 'user_avatar');
        }

        return [
            "count" => $count,
            "lists" => $playersList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 用户详情
     * @param int $userId
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author cjhao
     * @date 2021/8/18 15:52
     */
    public function detail(string $userId)
    {
        $user = Db::connect('mongodb')->table('players')->where("_id", new ObjectId($userId))->find();
        //实名认证信息
        $user["account"] = Db::connect('mongodb')->table('accounts')
            ->where("player", $user["_id"])->find();
        $user["isAuth"] = empty($user["account"]) ? false : true;
        //创建俱乐部信息
        $user["createClub"] = Db::connect('mongodb')->table('clubs')
            ->where("owner", $user["_id"])->find();
        $user["isCreator"] =empty($user["createClub"]) ? false : true;
        $user["inviteInfo"] = Db::connect('mongodb')->table('playerinvitepeoples')
            ->where("inviteeShortId", intval($user["shortId"]))->find();
        if(!empty($user["inviteInfo"])) {
            $invitePlayer = Db::connect('mongodb')->table('players')
                ->where("shortId", intval($user["inviteInfo"]["shortId"]))->find();
            if(!empty($invitePlayer)) $user["inviteInfo"]["inviteName"] = $invitePlayer["name"];
        }

        $user["playerinvitecount"] = Db::connect('mongodb')->table('playerinvitepeoples')
            ->where("shortId", intval($user["shortId"]))->count();
        $user["joinClubs"] = Db::connect('mongodb')->table('clubmembers')->where("member", $user["_id"])->select();
        $user["joinClubCount"] =empty($user["joinClubs"]) ? 0 : count($user["joinClubs"]);
        if(!empty($user["joinClubs"])) $user["joinClubs"] = $user["joinClubs"]->toArray();
        foreach ($user["joinClubs"] as $key => $value) {
            $user["joinClubs"][$key]["club"] = Db::connect('mongodb')->table('clubs')
                ->where("_id", $user["joinClubs"][$key]["club"])->find();
            $user["joinClubs"][$key]["joinClubMembers"] = Db::connect('mongodb')->table('clubmems')
                ->where("club", $user["joinClubs"][$key]["club"]["_id"])->count();
            $player = Db::connect('mongodb')->table('players')
                ->where("_id", $user["joinClubs"][$key]["club"]["owner"])->find();
            $user["joinClubs"][$key]["club"]["playerShortId"] = $player["shortId"];
            $user["joinClubs"][$key]["club"]["playerUserName"] = $player["name"];
            if(!empty($user["createClub"]) && reset($value["club"]) == reset($user["createClub"]["_id"])) $user["joinClubs"][$key]["isCreator"] = true;
            else $user["joinClubs"][$key]["isCreator"] = false;
        }

        $user["lotteryCount"] = Db::connect('mongodb')->table('playerdailylotteries')
            ->where("shortId", intval($user["shortId"]))->sum("times");

        $user["redPocket"] = round($user["redPocket"] / 100, 2);
        $user["createAt"] = date('Y-m-d H:i:s',intval(reset($user["createAt"]) / 1000));
        if(empty($user["headImgUrl"])) $user["headImgUrl"] = ConfigService::get('default_image', 'user_avatar');

        return $user;
    }

    /**
     * @notes 更新用户信息
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public function setUserInfo(array $params):bool
    {
        Db::connect('mongodb')->table('players')->where(['_id'=> new ObjectId($params['user_id'])])->update([$params['field'] => $params['value']]);
        return true;

    }

    /**
     * @notes 删除用户
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public function deleteUserInfo(array $params):bool
    {
        Db::connect('mongodb')->table('players')->where(['_id' => new ObjectId($params['user_id'])])->delete();
        return true;

    }

    /**
     * @notes 充值
     * @param array $params
     * @return string
     * @author cjhao
     * @date 2021/9/10 18:15
     */
    public function adjustUserWallet(array $params)
    {
        Db::startTrans();

        try {
            $user = Db::connect('mongodb')->table('players')->where("_id", new ObjectId($params['user_id']))->find();
            if($params['type'] == DefaultEnum::REDPOCKET) $params['num'] *= DefaultEnum::REDPOCKETRANK;
            switch ($params['type']){
                case 1:
                    if(1 == $params['action']){
                        //调整钻石
                        Db::connect('mongodb')->table('players')->where("_id", new ObjectId($params['user_id']))
                            ->update(["diamond" => $user["diamond"] + $params['num']]);
                    }else{
                        Db::connect('mongodb')->table('players')->where("_id", new ObjectId($params['user_id']))
                            ->update(["diamond" => $user["diamond"] - $params['num']]);
                    }

                    $player = Db::connect('mongodb')->table('players')->where("_id", new ObjectId($params['user_id']))->find();

                    Db::connect('mongodb')->table('diamondrecords')->insert([
                        "player" => $params['user_id'],
                        "amount" => $params['action'] == 1 ? intval($params['num']) : -$params['num'],
                        "residue" => $player["diamond"],
                        "type" => 6,
                        "note" => "后台充值",
                        "createAt" => new UTCDateTime(time() * 1000)
                    ]);

                    break;
                case 2:
                    //增加
                    if(1 == $params['action']){
                        Db::connect('mongodb')->table('players')->where("_id", new ObjectId($params['user_id']))
                            ->update(["gold" => $user["gold"] + $params['num']]);
                    }else{
                        Db::connect('mongodb')->table('players')->where("_id", new ObjectId($params['user_id']))
                            ->update(["gold" => $user["gold"] - $params['num']]);
                    }

                    $player = Db::connect('mongodb')->table('players')->where("_id", new ObjectId($params['user_id']))->find();

                    Db::connect('mongodb')->table('goldrecords')->insert([
                        "player" => $params['user_id'],
                        "amount" => $params['action'] == 1 ? intval($params['num']) : -$params['num'],
                        "residue" => $player["gold"],
                        "type" => 40,
                        "note" => "后台充值",
                        "createAt" => new UTCDateTime(time() * 1000)
                    ]);

                    break;

                case 3:
                    //增加
                    if(1 == $params['action']){
                        Db::connect('mongodb')->table('players')->where("_id", new ObjectId($params['user_id']))
                            ->update(["redPocket" => $user["redPocket"] + $params['num']]);
                    }else{
                        Db::connect('mongodb')->table('players')->where("_id", new ObjectId($params['user_id']))
                            ->update(["redPocket" => $user["redPocket"] - $params['num']]);
                    }

                    break;

                case 4:
                    if(1 == $params['action']){
                        Db::connect('mongodb')->table('players')->where("_id", new ObjectId($params['user_id']))
                            ->update(["turntableTimes" => $user["turntableTimes"] + $params['num']]);
                    }else{
                        Db::connect('mongodb')->table('players')->where("_id", new ObjectId($params['user_id']))
                            ->update(["turntableTimes" => $user["turntableTimes"] - $params['num']]);
                    }

                    break;

                case 5:
                    //增加
                    if(1 == $params['action']){
                        Db::connect('mongodb')->table('players')->where("_id", new ObjectId($params['user_id']))
                            ->update(["voucher" => $user["voucher"] + $params['num']]);
                    }else{
                        Db::connect('mongodb')->table('players')->where("_id", new ObjectId($params['user_id']))
                            ->update(["voucher" => $user["voucher"] - $params['num']]);
                    }

                    break;
            }

            Db::commit();
            return "success";

        } catch (Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }

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
        $where = [["players", "in", $params["_id"]]];
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
                        array_push($playerIds, $item["player"]);
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
        if(!empty($params["roomId"])) $where[] = ["roomNum", "=", $params["roomId"]];
        if(!empty($params["category"])) $where[] = ["category", "=", $params["category"]];
        if(!empty($params["start_time"]) && !empty($params["end_time"]))
            $where[] = ["createAt", "between",
                [new UTCDateTime(strtotime($params["start_time"]) * 1000),
                    new UTCDateTime(strtotime($params["end_time"]) * 1000)]];

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
                $roomList[$key]["roomType"] = !empty($clubRule) ? DefaultEnum::ROOMTYPE[$clubRule["ruleType"]]
                    : DefaultEnum::ROOMTYPE[$value["rule"]["ruleType"]];
                $roomList[$key]["player"] = Db::connect('mongodb')->table('players')->where("shortId", $roomList[$key]["creatorId"])->find();
                $roomList[$key]["club"] =  Db::connect('mongodb')->table('clubs')->where("_id", $roomList[$key]["club"])->find();
                $roomList[$key]["clubName"] = !empty($roomList[$key]["club"]) ? $roomList[$key]["club"]["name"] : DefaultEnum::ISNOTRESULT;
                $roomList[$key]["clubShortId"] = !empty($roomList[$key]["club"]) ? $roomList[$key]["club"]["shortId"] : DefaultEnum::ISNOTRESULT;
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
        if(!empty($room["games"])) $room["games"] = json_encode($room["games"], JSON_NUMERIC_CHECK | JSON_PARTIAL_OUTPUT_ON_ERROR);
        $room["_id"] = reset($room["_id"]);
        $room["createAt"] = date('Y-m-d H:i:s',intval(reset($room["createAt"]) / 1000));
        $scores = [];

        try {
            foreach ($room["scores"] as $key => $item) {
                if(empty($item)) continue;
                $player = Db::connect('mongodb')->table('players')->where("shortId", intval($item["shortId"]))->find();
                $room["scores"][$key]["gem"] = $player["gem"];
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
        //无法直接查询当前房间用户是否在，所以需要查询所有实时房间组装成lists
        $roomIds = [];
        $joinRoomIds = [];
        $roominfos = Db::connect('mongodb')->table('roominfos')->select();
        foreach($roominfos as $item) array_push($roomIds, $item["roomId"]);

        //查询这些房间哪些用户有加入游戏，组装成新的roomIds
        $roomjoins = Db::connect('mongodb')->table('roomjoins')
            ->where("roomId", "in", $roomIds)->where("joinId", "=", $params["_id"])->select();
        foreach($roomjoins as $item) array_push($joinRoomIds, $item["roomId"]);

        $where = [["roomId", "in", $joinRoomIds]];
        if(!empty($params["roomId"])) $where[] = ["roomId", "=", intval($params["roomId"])];
        if(!empty($params["gameType"])) $where[] = ["gameType", "=", $params["gameType"]];
        if(!empty($params["clubShortId"])) {
            $club = Db::connect('mongodb')->table('clubs')->where("shortId", intval($params["clubShortId"]))->find();
            if(empty($club)) return nullResultReturn();
            $where[] = ["clubId", "=", reset($club["_id"])];
        }



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
            if(!empty($roomList[$key]["detail"]["detail"]["clubId"]))$roomList[$key]["club"] =  Db::connect('mongodb')->table('clubs')
                ->where("_id", new ObjectId($roomList[$key]["detail"]["detail"]["clubId"]))->find();
            if(!empty($roomList[$key]["detail"]["detail"]["gameRule"]["ruleId"])) {
                $clubRule = Db::connect('mongodb')->table('clubrules')
                    ->where("_id", new ObjectId($roomList[$key]["detail"]["detail"]["gameRule"]["ruleId"]))->find();
            }
            $roomList[$key]["roomType"] = !empty($clubRule) ? DefaultEnum::ROOMTYPE[$clubRule["ruleType"]]
                : DefaultEnum::ROOMTYPE[$roomList[$key]["detail"]["detail"]["gameRule"]["ruleType"]];
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
        if(!empty($room["detail"]["detail"]["clubId"])) $room["club"] =  Db::connect('mongodb')->table('clubs')
            ->where("_id", new ObjectId($room["detail"]["detail"]["clubId"]))->find();
        $room["games"] = Db::connect('mongodb')->table('gamerecords')->where("room", strval($room["room"]))->select();
        if(!empty($room["detail"]["detail"]["gameRule"]["ruleId"])) {
            $clubRule = Db::connect('mongodb')->table('clubrules')
                ->where("_id", new ObjectId($room["detail"]["detail"]["gameRule"]["ruleId"]))->find();
        }
        $room["roomType"] = !empty($clubRule) ? DefaultEnum::ROOMTYPE[$clubRule["ruleType"]]
            : DefaultEnum::ROOMTYPE[$room["detail"]["detail"]["gameRule"]["ruleType"]];
        $room["gameTimes"] = [];
        $players = [];

        foreach ($room["detail"]["detail"]["players"] as $k => $item) {
            if(empty($room["detail"]["detail"]["players"][$k])) continue;
            $room["detail"]["detail"]["players"][$k] = Db::connect('mongodb')->table('players')
                ->where("_id", $room["detail"]["detail"]["players"][$k])->find();
            if(!empty($room["clubId"])) {
                $clubmember = Db::connect('mongodb')->table('clubmembers')
                    ->where("member", $room["detail"]["detail"]["players"][$k]["_id"])->where("club", new ObjectId($room["clubId"]))->find();
                $room["detail"]["detail"]["players"][$k]["clubGold"] = $clubmember["clubGold"];
            }
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

    public function inviteLists($params)
    {
        $where = [];
        $isSearch = false;
        $playerIds = [];

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
        if(!empty($playerIds)) $where[] = ["inviteePlayerId", "in", $playerIds];
        if(!empty($params["user_id"])) $where[] = ["playerId", "=", $params["user_id"]];
        if(!empty($params["start_time"]) && !empty($params["end_time"])) {
            $where[] = ["createAt", "between",
                [new UTCDateTime($params["start_time"] * 1000),
                    new UTCDateTime($params["end_time"] * 1000)]];
        }

        $count = Db::connect('mongodb')->table('playerinvitepeoples')->where($where)->count();
        $playersList = Db::connect('mongodb')->table('playerinvitepeoples')->where($where)
            ->order("createAt", "desc")->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($playersList as $key => $value) {
            $playersList[$key]["player"] = Db::connect('mongodb')->table('players')->where("_id", $value["inviteePlayerId"])->find();
            if(empty($playersList[$key]["player"]["phone"])) $playersList[$key]["player"]["phone"] = DefaultEnum::ISNOTRESULT;
            $playersList[$key]["player"]["redPocket"] = round($playersList[$key]["player"]["redPocket"] / 100, 2);
            $playersList[$key]["isCreator"] = Db::connect('mongodb')->table('clubs')
                ->where("owner", $playersList[$key]["inviteePlayerId"])->count();
            $playersList[$key]["joinClubCount"] = Db::connect('mongodb')->table('clubmembers')
                ->where("member", $playersList[$key]["inviteePlayerId"])->count();
            $playersList[$key]["club"] = Db::connect('mongodb')->table('clubs')
                ->where("owner", $playersList[$key]["inviteePlayerId"])->find();
            if(!empty($playersList[$key]["club"]["_id"])) $playersList[$key]["club"]["_id"] = reset($playersList[$key]["club"]["_id"]);
            $playersList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($playersList[$key]["createAt"]) / 1000));
            if(empty($playersList[$key]["player"]["headImgUrl"])) $playersList[$key]["player"]["headImgUrl"] = ConfigService::get('default_image', 'user_avatar');
        }

        return [
            "count" => $count,
            "lists" => $playersList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    public static function bindOrGetUserInfo($params)
    {
        $player = Db::connect('mongodb')->table('players')->where("_id", $params["unionid"])->find();

        if(empty($player)) return ["code" => false, "msg" => "此微信暂未绑定游戏账号，请先绑定"];
        if(empty($player["openid"])) {
            Db::connect('mongodb')->table('players')->where("_id", $params["unionid"])->
                update(["openid" => $params["openid"], "headImgUrl" => $params["headimgurl"], "name" => $params["nickname"]]);

            $player = Db::connect('mongodb')->table('players')->where("_id", $params["unionid"])->find();
        }

        $player["createAt"] = date('Y-m-d H:i:s',intval(reset($player["createAt"]) / 1000));

        return ["code" => true, "data" => $player];
    }

    /**
     * @notes 用户充值模板
     * @param int $userId
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author cjhao
     * @date 2021/8/18 15:52
     */
    public static function getRechargeTemplate($params)
    {
        $player = Db::connect('mongodb')->table('players')->where("unionid", $params["unionid"])->find();
        $player["_id"] = reset($player["_id"]);
        $templates = Db::connect('mongodb')->table('goods')->where(["isOnline" => true, "goodsType" => 1])
            ->order("amount", "asc")->select()->toArray();
        foreach ($templates as $key => $value) {
            $templates[$key]["_id"] = reset($value["_id"]);
            $templates[$key]["androidPrice"] = round($value["price"], 2);
            $templates[$key]["iosPrice"] = round($value["price"], 2);

            $orderCount = Db::connect('mongodb')->table('userrechargeorders')
                ->where("to", $player["_id"])
                ->where("status", 1)
                ->where("goodsId", $templates[$key]["_id"])->count();

            if($orderCount > 0) $templates[$key]["firstTimeAmount"] = 0;
        }

        return ["player" => $player, "templates" => $templates];
    }

    /**
     * @notes 金豆模板
     * @param int $userId
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author cjhao
     * @date 2021/8/18 15:52
     */
    public static function getRubyTemplate()
    {
        $templates = Db::connect('mongodb')->table('goodsexchangerubies')
            ->order("gemCount", "asc")->select()->toArray();
        foreach ($templates as $key => $value) {
            $templates[$key]["_id"] = reset($templates[$key]["_id"]);
        }

        return $templates;
    }

    /**
     * @notes 钻石兑换金豆
     * @param int $userId
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author cjhao
     * @date 2021/8/18 15:52
     */
    public static function gem2ruby($params)
    {
        $template = Db::connect('mongodb')->table('goodsexchangerubies')->where("_id", new ObjectId($params["_id"]))->find();
        $player = Db::connect('mongodb')->table('players')->where("_id", $params["user_id"])->find();

        if($template["gemCount"] > 0 && $player["gem"] < $template["gemCount"]) return ["code" => false, "msg" => "钻石不足，兑换失败"];
        Db::connect('mongodb')->table('players')->where("_id", $params["user_id"])->update([
            "gem" => $player["gem"] - $template["gemCount"],
            "ruby" => $player["ruby"] + $template["rubyAmount"]
        ]);

        Db::connect('mongodb')->table('diamondrecords')->insert([
            "player" => $params["user_id"],
            "amount" => -$template["gemCount"],
            "residue" => $player["gem"] - $template["gemCount"],
            "type" => 22,
            "note" => "{$template["gemCount"]}钻石兑换{$template["rubyAmount"]}金豆",
            "createAt" => new UTCDateTime(time() * 1000)
        ]);


        return ["code" => true, "msg" => "兑换成功"];
    }

    /**
     * @notes 获取充值模板
     * @param Object $params
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author cjhao
     * @date 2021/8/18 15:52
     */
    public static function getTemplates($params)
    {
        $templates = Db::connect('mongodb')->table('goods')->where(["isOnline" => true, "goodsType" => 1])->order("amount", "asc")->select()->toArray();
        foreach ($templates as $key => $value) {
            $templates[$key]["_id"] = reset($value["_id"]);
            $templates[$key]["androidPrice"] = $value["androidPrice"];
            $templates[$key]["iosPrice"] = DefaultEnum::IOSPRICE[$templates[$key]["applePriceId"]];
            unset($templates[$key]["applePriceId"]);
            unset($templates[$key]["originPrice"]);

            if(!empty($params["user_id"])) {
                $orderCount = Db::connect('mongodb')->table('userrechargeorders')
                    ->where("to", $params["user_id"])
                    ->where("status", "finish")
                    ->where("goodsId", $templates[$key]["_id"])->count();

                if($orderCount > 0) $templates[$key]["firstTimeAmount"] = 0;
            }
        }

        return $templates;
    }

    public static function recharge($params)
    {
        try {
            return self::rechargeByTemplate($params);
        } catch(Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 选择模板充值
     * @param $params
     * @throws \think\Exception
     * @author Tab
     * @date 2021/8/11 11:25
     */
    public static function rechargeByTemplate($params)
    {
        $template = Db::connect('mongodb')->table('goods')
            ->where(["_id" => new ObjectId($params["_id"])])->find();
        if(empty($template)) {
            return ["code" => false, "msg" => "充值模板不存在"];
        }

        $orderCount = Db::connect('mongodb')->table('userrechargeorders')
            ->where("to", $params["user_id"])
            ->where("status", 1)
            ->where("goodsId", $params["_id"])->count();

        $params['money'] = $template["amount"];
        $params['award'] = $orderCount > 0 ? 0 : $template["firstTimeAmount"];
        $params['androidPrice'] = $template["price"];
        return self::addRechargeOrder($params);
    }

    /**
     * @notes 添加充值订单
     * @param $params
     * @author Tab
     * @date 2021/8/11 11:23
     */
    public static function addRechargeOrder($params)
    {
        try {
            $player = Db::connect('mongodb')->table('players')->where(["_id" => new ObjectId($params["user_id"])])->find();
            $data = [
                "diamond"       => $params["money"] + $params["award"],
                "status"        => 0,
                'playerId'      => $params["user_id"],
                'shortId'      => intval($player["shortId"]),
                'price'        => round($params["androidPrice"], 2),
                "goodsId"      => $params["_id"],
                'source'        => "wechat",
                'sn'      => generate_sn("userrechargeorders", "sn"),
                'created'       => new UTCDateTime(time() * 1000)
            ];

            $data["id"] = Db::connect('mongodb')->table('userrechargeorders')->insertGetId($data);
            return ["code" => true, "data" => [
                "order_id" => $data["id"],
                'order_sn' => $data["sn"],
                "price" => $params["androidPrice"],
                'from' => 'playerRecharge'
            ]];
        } catch (\Exception $e) {
            var_dump($e);
        }

    }

    /**
     * @notes 获取准备预支付订单信息
     * @param $params
     * @return array|false|\think\Model
     * @author 段誉
     * @date 2021/8/3 11:57
     */
    public static function getPayOrderInfo($params)
    {
        try {
            Db::connect('mongodb')->table('userrechargeorders')->where("_id", new ObjectId($params['order_id']))
                ->update(["openid" => $params["openid"]]);

            $order = Db::connect('mongodb')->table('userrechargeorders')->where("_id", new ObjectId($params['order_id']))->find();
            if(empty($order)) {
                throw new Exception('充值订单不存在');
            }

            if ($order['status'] == 1) {
                throw new Exception('订单已支付');
            }
            $order["_id"] = reset($order["_id"]);
            return $order;
        } catch (Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }

    /**
     * @notes 支付
     * @param $payWay // 支付方式
     * @param $from //订单来源(商品订单?充值订单?其他订单?)
     * @param $order //订单信息
     * @param $terminal //终端
     * @return array|bool|string|void
     * @throws Exception
     * @author 段誉
     * @date 2021/7/29 14:49
     */
    public static function pay($from, $order, $terminal = 2)
    {
        try {
            $payService = (new WeChatPayService(2, $from, $order['playerId'], $terminal));
            $result = $payService->pay($from, $order, $terminal);

            //支付成功, 执行支付回调
            if (false === $result && !self::hasError()) {
                self::setError($payService->getError());
            }
            return $result;
        } catch(\Exception $e) {
            var_dump($e);
        }

    }

    /**
     * @notes 查看订单支付状态
     * @param $params
     * @return array|false
     * @author Tab
     * @date 2021/11/30 15:54
     */
    public static function getPayStatus($params)
    {
        try {
            $order = Db::connect('mongodb')->table('userrechargeorders')->where("_id", new ObjectId($params['order_id']))->find();
            if (empty($order)) {
                throw new Exception('订单不存在');
            }
            $order["_id"] = reset($order["_id"]);
            $order["from"] = reset($order["from"]);
            $order["created"] = date('Y-m-d H:i:s',intval(reset($order["created"]) / 1000));
            unset($order["relation"]);
            unset($order["kickback"]);
            unset($order["kickback2"]);
            return [
                'pay_status' => $order["status"],
                'order' => $order
            ];
        } catch (Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 获取最近一条未支付订单
     * @param $params
     * @return array|false
     * @author Tab
     * @date 2021/11/30 15:54
     */
    public static function getLastPayOrder($params)
    {
        $order = Db::connect('mongodb')->table('userrechargeorders')->where("created", "between",
            [new UTCDateTime((time() - 60 * 60) * 1000), new UTCDateTime(time() * 1000)])
            ->where("orderSn", $params["order_id"])
            ->where("status", "pedding")
            ->find();
        if (empty($order)) return ["empty" => true, "data" => []];

        $order["_id"] = reset($order["_id"]);
        $order["from"] = reset($order["from"]);
        $order["created"] = date('Y-m-d H:i:s',intval(reset($order["created"]) / 1000));
        unset($order["relation"]);
        unset($order["kickback"]);
        unset($order["kickback2"]);
        return ["empty" => false, "data" => $order];
    }

    /**
     * @notes 注册回调
     * @param $params
     * @return array|bool
     * @author Tab
     * @date 2021/11/30 15:54
     */
    public static function registNotify($params)
    {
        Db::connect('mongodb')->table('userregistnotifys')->insert([
            "player" => $params["unionid"],
            "shortId" => $params["shortId"],
            "isSuccess" => $params["isSuccess"] == 1,
            "createAt" => new UTCDateTime(time() * 1000)
        ]);

        return true;
    }

    /**
     * @notes 新用户注册
     * @param $params
     * @return array|bool
     * @author Tab
     * @date 2021/11/30 15:54
     */
    public static function findOrCreate($params)
    {
        $player = Db::connect('mongodb')->table('players')->where([
            "_id" => $params["unionid"]
        ])->find();

        if (empty($player)) return ["msg" => "用户尚未注册", "data" => ["operate" => 1]];
        if (empty($player["phone"])) return ["msg" => "用户尚未绑定手机号", "data" => ["operate" => 2]];

        return ["msg" => "登录成功", "data" => $player];
    }

    /**
     * @notes 新用户注册
     * @param $params
     * @return array|bool
     * @author Tab
     * @date 2021/11/30 15:54
     */
    public static function create($params)
    {
        $redis = new \Redis();
        $redis->connect(env('CACHE.REDISHOST'), env('CACHE.REDISPORT'));
        $redis->select(env('CACHE.REDISSELECT'));

        $isPhoneBind = Db::connect('mongodb')->table('players')->where([
            "phone" => $params["phone"]
        ])->count();

        $code = $redis->get($params["phone"]);
        if(empty($code) || $code !== $params["code"]) return ["code" => false, "msg" => "验证码错误"];

        if ($isPhoneBind > 0) return ["code" => false, "msg" => "手机号已经被其他用户绑定"];

        if ($params["operate"] == 1) {
            $player = Db::connect('mongodb')->table('players')->where([
                "_id" => $params["unionid"]
            ])->find();

            if (!empty($player)) return ["code" => false, "msg" => "用户已经注册"];
            // 在需要的地方发布消息到redis频道
            $message = [
                'payload' => [
                    "unionid" => $params["unionid"],
                    "headImgUrl" => $params["headImgUrl"],
                    "name" => $params["name"],
                    "phone" => $params["phone"]
                ],
                'cmd' => 'createNewPlayer'
            ];

            $redis->publish('adminChannelToDating', json_encode($message));
        }

        if ($params["operate"] == 2) {
            Db::connect('mongodb')->table('players')->where([
                "_id" => $params["unionid"]
            ])->update(["phone" => $params["phone"]]);
        }

        return ["code" => true, "msg" => "绑定成功", "data" => []];
    }

    /**
     * @notes 选择模板充值
     * @param $params
     * @throws \think\Exception
     * @author Tab
     * @date 2021/8/11 11:25
     */
    public static function wxGameRecharge($params)
    {
        Db::connect('mongodb')->table('wxgameparams')->insert([
            "userId" => $params["userId"],
            "templateId"=> $params["_id"],
            "terminal"=> intval($params["terminal"]),
            "env"=> intval($params["env"])
        ]);
        $template = Db::connect('mongodb')->table('goods')
            ->where(["_id" => new ObjectId($params["_id"])])->find();

        if(empty($template)) return ["code" => false, "msg" => "充值模板不存在"];

        $orderCount = Db::connect('mongodb')->table('userrechargeorders')
            ->where("to", $params["userId"])
            ->where("status", "finish")
            ->where("goodsId", $params["_id"])->count();

        $params['award'] = $orderCount > 0 ? 0 : $template["firstTimeAmount"];
        $params['androidPrice'] = !empty($params["plat"]) && $params["plat"] === "ios" ?
            DefaultEnum::IOSPRICE[$template["applePriceId"]] : $template["androidPrice"];

        $admin = Db::connect('mongodb')->table('gms')->where(["role" => "super"])->find();
        $player = Db::connect('mongodb')->table('players')->where(["_id" => $params["userId"]])->find();
        if(empty($player["miniOpenid"]) && !empty($params["terminal"]) && $params["terminal"] == 1) return ["code" => false, "msg" => "未获取到用户openid,请先绑定微信"];
        $data = [
            'price'        => round($params["androidPrice"] / 100, 2),
            "gem"          => $template["amount"] + $params["award"],
            "goodsId"      => $params["_id"],
            'relation'      => [],
            'source'        => "wechat",
            'currency'      => "cash",
            "status"        => "pedding",
            'kickback'      => 0,
            'kickback2'     => 0,
            'from'          => $admin["_id"],
            'to'            => $params["userId"],
            'orderSn'      => generate_sn("userrechargeorders", "orderSn"),
            'boxId'         => $template["boxId"] ?? '',
            'boxCount'         => intval($template["boxCount"] ?? 0),
            'created'       => new UTCDateTime(time() * 1000)
        ];

        $data["id"] = Db::connect('mongodb')->table('userrechargeorders')->insertGetId($data);

        // 2. 获取用户openid
        $player = Db::connect('mongodb')->table('players')->where("_id", $data["to"])->find();
        if(empty($player) || empty($player["miniOpenid"]) || empty($player["sessionKey"])) return ["code" => false, "msg" => "用户不存在，请先小游戏授权", "data" => $player];

        $accessToken = ConfigService::get('wechat', 'MnpAccessToken');
        $appKey = ConfigService::get('wxgame', 'appkey');

        $postBody = [
            "openid" => $player["miniOpenid"],
            "offer_id" => ConfigService::get('wxgame', 'offerid') . '',
            "ts" => time(),
            "zone_id" => ConfigService::get('wxgame', 'zoneid') . '',
            "env" => intval($params["env"]),
            "user_ip" => Request::ip()
        ];

        // 3. 生成登录态签名和支付请求签名
        $signature =  hash_hmac('sha256', json_encode($postBody), $player["sessionKey"]);
        $needSignMsg = "/wxa/game/getbalance&" . json_encode($postBody);
        $paySign = hash_hmac('sha256', $needSignMsg, $appKey);

        // 4. 查询用户游戏币余额
        $balanceUrl = "https://api.weixin.qq.com/wxa/game/getbalance?access_token={$accessToken}&signature={$signature}&sig_method=hmac_sha256&pay_sig={$paySign}";
        $res = curl_post($balanceUrl, $postBody);

        if ($res["errcode"] != 0) return ["code" => false, "msg" => $res["errmsg"], "data" => $res];
        if ($res["balance"] < $data["price"] * 10) {
            return ["code" => true, "data" => [
                "orderId" => $data["id"],
                'orderSn' => $data["orderSn"],
                "env" => intval($params['env']),
                "offerId" => ConfigService::get('wxgame','offerid'),
                'zoneId' => ConfigService::get('wxgame','zoneid'),
                "currencyType" => "CNY",
                "buyQuantity" => round($params["androidPrice"] / 10, 2),
                "operate" => 1
            ]];
        }

        // 5. 扣除游戏币
        $payBody = [
            "openid" => $player["miniOpenid"],
            "offer_id" => ConfigService::get('wxgame', 'offerid') . '',
            "ts" => time(),
            "zone_id" => ConfigService::get('wxgame', 'zoneid') . '',
            "env" => intval($params["env"]),
            "user_ip" => Request::ip(),
            "amount" => $data["price"] * 10,
            "bill_no" => $data["id"]
        ];

        $sign =  hash_hmac('sha256', json_encode($payBody), $player["sessionKey"]);
        $needSign = "/wxa/game/pay&" . json_encode($payBody);
        $paySig = hash_hmac('sha256', $needSign, $appKey);

        $payUrl = "https://api.weixin.qq.com/wxa/game/pay?access_token={$accessToken}&signature={$sign}&sig_method=hmac_sha256&pay_sig={$paySig}";
        $response = curl_post($payUrl, $payBody);

        if ($response["errcode"] != 0) return ["code" => false, "msg" => $response["errmsg"]];

        $data = WechatLogic::playerRecharge($data["id"], $response["bill_no"]);
        if(!$data["code"]) return $data;

        //通知游戏
        WechatLogic::noticeGame(["playerId" => $params["userId"]]);
        $response["operate"] = 2;

        return ["code" => true, "data"=> $response];
    }

    /**
     * @notes 生成分销海报
     * @author Tab
     * @date 2021/8/6 15:09
     */
    public static function posterGenerate($params)
    {
        // 获取分享海报背景图
        $bgImg = public_path() . "uploads/images/wxgame_share_bg.jpg";
        $addImg = "https://phpadmin.tianle.fanmengonline.com/uploads/images/wxgame_share_add.png";
        $reduceImg = "https://phpadmin.tianle.fanmengonline.com/uploads/images/wxgame_share_reduce.png";
        $avatarImg = "https://phpadmin.tianle.fanmengonline.com/uploads/images/wxgame_share_avatar_background.png";

        // 二维码保存路径
        $saveDir = 'uploads/images/wxgame/qr_code/';
        if(!file_exists($saveDir)) {
            mkdir($saveDir, 0777, true);
        }

        $saveKey = 'uid'. $params['roomId'] . $params["juIndex"] . 'qrcode';
        $qrCodeName = md5($saveKey) . '.png';
        $qrCodeUrl = public_path() . $saveDir . $qrCodeName;

        // 删除旧的二维码
        @unlink($qrCodeUrl);

        // 获取海报配置
        $posterConfig = self::posterConfig();

        // 使用分享背景图创建一个图像
        $bgResource = imagecreatefromstring(file_get_contents($bgImg));

        foreach ($params["records"] as $key => $value) {
            // 用户头像判断
            $userAvatar = FileService::setFileUrl($value['avatar']);
            if (strpos($userAvatar, 'http://') === 0) $userAvatar = str_replace('http://', 'https://', $userAvatar);

            // 合成头像框
            self::writeImg($bgResource, $avatarImg, $posterConfig["border_{$key}"]);

            // 合成头像
            self::writeImg($bgResource, $userAvatar, $posterConfig["head_pic_{$key}"]);

            // 合成昵称
            $nickname = filterEmoji($value['name']);
            if (mb_strlen($nickname, 'utf-8') > 6) $nickname = substr($nickname, 0, 15) . '...';
            self::writeText($bgResource, $nickname, $posterConfig["nickname_{$key}"]);

            // 合成ID
            self::writeText($bgResource, "ID:" . $value['shortId'], $posterConfig["id_{$key}"]);

            // 合成积分
            self::writeImg($bgResource, $value['operate'] == 1 ? $addImg : $reduceImg, $posterConfig["score_{$key}"]);
        }

        imagepng($bgResource, $qrCodeUrl);

        return ["url" => FileService::getFileUrl($saveDir . $qrCodeName) ];
    }

    /**
     * @notes 海报配置
     * @return array
     * @author Tab
     * @date 2021/8/6 11:46
     */
    public static function posterConfig()
    {
        return [
            'head_pic_0' => [
                'w' => 110, 'h' => 110, 'x' => 250, 'y' => 65,
            ],
            'nickname_0' => [
                'color' => '#2f190c', 'font_face' => public_path().'resource/font/SourceHanSansCN-Regular.otf', 'font_size' => 40, 'x' => 380, 'y' => 110,
            ],
            'id_0' => [
                'color' => '#2f190c', 'font_face' => public_path().'resource/font/SourceHanSansCN-Regular.otf', 'font_size' => 32, 'x' => 380, 'y' => 170,
            ],
            'score_0' => [
                'w' => 200, 'h' => 120, 'x' => 800, 'y' => 65,
            ],
            'border_0' => [
                'w' => 140, 'h' => 140, 'x' => 230, 'y' => 55,
            ],
            'head_pic_1' => [
                'w' => 100, 'h' => 100, 'x' => 255, 'y' => 250,
            ],
            'nickname_1' => [
                'color' => '#2f190c', 'font_face' => public_path().'resource/font/SourceHanSansCN-Regular.otf', 'font_size' => 40, 'x' => 380, 'y' => 290,
            ],
            'id_1' => [
                'color' => '#2f190c', 'font_face' => public_path().'resource/font/SourceHanSansCN-Regular.otf', 'font_size' => 32, 'x' => 380, 'y' => 350,
            ],
            'score_1' => [
                'w' => 180, 'h' => 100, 'x' => 800, 'y' => 250,
            ],
            'border_1' => [
                'w' => 130, 'h' => 130, 'x' => 240, 'y' => 235,
            ],
            'head_pic_2' => [
                'w' => 100, 'h' => 100, 'x' => 255, 'y' => 410,
            ],
            'nickname_2' => [
                'color' => '#2f190c', 'font_face' => public_path().'resource/font/SourceHanSansCN-Regular.otf', 'font_size' => 40, 'x' => 380, 'y' => 450,
            ],
            'id_2' => [
                'color' => '#2f190c', 'font_face' => public_path().'resource/font/SourceHanSansCN-Regular.otf', 'font_size' => 32, 'x' => 380, 'y' => 510,
            ],
            'score_2' => [
                'w' => 180, 'h' => 100, 'x' => 800, 'y' => 410,
            ],
            'border_2' => [
                'w' => 130, 'h' => 130, 'x' => 240, 'y' => 400,
            ],
            'head_pic_3' => [
                'w' => 100, 'h' => 100, 'x' => 255, 'y' => 570,
            ],
            'nickname_3' => [
                'color' => '#2f190c', 'font_face' => public_path().'resource/font/SourceHanSansCN-Regular.otf', 'font_size' => 40, 'x' => 380, 'y' => 610,
            ],
            'id_3' => [
                'color' => '#2f190c', 'font_face' => public_path().'resource/font/SourceHanSansCN-Regular.otf', 'font_size' => 32, 'x' => 380, 'y' => 670,
            ],
            'score_3' => [
                'w' => 180, 'h' => 100, 'x' => 800, 'y' => 570,
            ],
            'border_3' => [
                'w' => 130, 'h' => 130, 'x' => 240, 'y' => 560,
            ],
        ];
    }

    /**
     * @notes 写入图像
     * @param $bgResource
     * @param $img
     * @param $config
     * @param false $isRounded
     * @return mixed
     * @author Tab
     * @date 2021/8/6 14:40
     */
    public static function writeImg($bgResource, $img, $config){
        $picImg = imagecreatefromstring(file_get_contents($img));
        $picW = imagesx($picImg);
        $picH = imagesy($picImg);

        // 圆形头像大图合并到海报
        imagecopyresampled($bgResource, $picImg,
            $config['x'],
            $config['y'],
            0, 0,
            $config['w'],
            $config['h'],
            $picW,
            $picH
        );

        return $bgResource;
    }

    /**
     * @notes 写入文本
     * @param $bgResource
     * @param $text
     * @param $config
     * @return mixed
     * @author Tab
     * @date 2021/8/6 14:42
     */
    public static function writeText($bgResource, $text, $config){
        $fontUri = $config['font_face'];
        $fontSize = $config['font_size'];
        $color = substr($config['color'],1);
        //颜色转换
        $color= str_split($color, 2);
        $color = array_map('hexdec', $color);
        if (empty($color[3]) || $color[3] > 127) {
            $color[3] = 0;
        }
        $fontColor = imagecolorallocatealpha($bgResource, $color[0], $color[1], $color[2], $color[3]);
        imagettftext($bgResource, $fontSize,0, $config['x'], $config['y'], $fontColor, $fontUri, $text);
        return $bgResource;
    }
}
