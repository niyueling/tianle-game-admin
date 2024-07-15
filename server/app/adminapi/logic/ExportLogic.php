<?php

namespace app\adminapi\logic;

use app\common\enum\DefaultEnum;
use app\common\logic\BaseLogic;
use app\common\service\ExportExcelServer;
use MongoDB\BSON\ObjectId;
use think\facade\Db;

class ExportLogic extends BaseLogic {
    public static function userRechargeExport($where)
    {
        try {
            $logsList = Db::connect('mongodb')->table('userrecords')->where($where)->order("created", "desc")->select()->toArray();

            foreach ($logsList as $key => $value) {
                $player = Db::connect('mongodb')->table('players')->where("_id", $value["to"])->find();
                $logsList[$key]["username"] =$player["name"];
                $logsList[$key]["shortId"] =$player["shortId"];
                $logsList[$key]["source_str"] = $logsList[$key]["source"] == "admin" ?
                    DefaultEnum::USERRECHARGESOURCE["ADMINRECHARGE"] : DefaultEnum::USERRECHARGESOURCE["WECHATRECHARGE"];
                $logsList[$key]["currency_str"] = DefaultEnum::USERRECHARGECURRENCY[$logsList[$key]["currency"]];
                $agency = Db::connect('mongodb')->table('gms')
                    ->where("_id", $logsList[$key]["from"])->find();
                $logsList[$key]["admin_name"] =$agency["username"];
                $logsList[$key]["_id"] = reset($logsList[$key]["_id"]);
                $logsList[$key]["created"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["created"]) / 1000));
            }

            $excelFields = [
                '_id' => '订单编号',
                'username' => '用户昵称',
                'shortId' => '用户Id',
                'amount' => '变动数量',
                'source_str' => '变动类型',
                'currency_str' => '币种',
                'admin_name' => '操作者',
                'created' => '时间',
            ];

            $export = new ExportExcelServer();
            $export->setFileName('充值明细');
            $result = $export->createExcel($excelFields, $logsList);

            return ['url' => $result];

        } catch (\Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public static function userGemExport($where)
    {
        try {
            $logsList = Db::connect('mongodb')->table('consumerecords')->where($where)->order("createAt", "desc")->select()->toArray();

            foreach ($logsList as $key => $value) {
                $player = Db::connect('mongodb')->table('players')->where("_id", $value["player"])->find();
                $logsList[$key]["username"] =$player["name"];
                $logsList[$key]["shortId"] =$player["shortId"];
                $logsList[$key]["_id"] = reset($logsList[$key]["_id"]);
                $logsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["createAt"]) / 1000));
                $logsList[$key]["note"] = str_replace('=>', '~~~', $logsList[$key]["note"]);
            }


            $excelFields = [
                '_id' => '订单编号',
                'username' => '用户昵称',
                'shortId' => '用户Id',
                'gem' => '变动数量',
                'note' => '备注',
                'createAt' => '时间',
            ];

            $export = new ExportExcelServer();
            $export->setFileName('钻石明细');
            $result = $export->createExcel($excelFields, $logsList);

            return ['url' => $result];

        } catch (\Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public static function userClubGoldExport($where)
    {
        try {
            $logsList = Db::connect('mongodb')->table('clubgoldrecords')->where($where)->order("createAt", "desc")->select()->toArray();

            foreach ($logsList as $key => $value) {
                $player = Db::connect('mongodb')->table('players')->where("_id", $logsList[$key]["member"])->find();
                $logsList[$key]["username"] =$player["name"];
                $logsList[$key]["shortId"] =$player["shortId"];

                $clubInfo = Db::connect('mongodb')->table('clubs')->where("_id", $logsList[$key]["club"])->find();
                $logsList[$key]["clubName"] = $clubInfo["name"] ?? "/";
                $logsList[$key]["clubShortId"] =$clubInfo["shortId"] ?? "/";

                $logsList[$key]["_id"] = reset($logsList[$key]["_id"]);
                $logsList[$key]["gameType"] = !empty($logsList[$key]["gameType"]) ? $logsList[$key]["gameType"] : DefaultEnum::ISNOTRESULT;
                $info = explode("：", $logsList[$key]["info"]);
                $logsList[$key]["room"] = count($info) >= 2 ? $info[1] : "/";
                $logsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["createAt"]) / 1000));
            }


            $excelFields = [
                '_id' => '订单编号',
                'username' => '用户昵称',
                'shortId' => '用户Id',
                'clubName' => '战队昵称',
                'clubShortId' => '战队Id',
                'gameType' => '游戏类型',
                'goldChange' => '变动数量',
                'allClubGold' => '剩余金币',
                'room' => '房间',
                'info' => '备注',
                'createAt' => '时间',
            ];

            $export = new ExportExcelServer();
            $export->setFileName('金币明细');
            $result = $export->createExcel($excelFields, $logsList);

            return ['url' => $result];

        } catch (\Exception $e) {
            var_dump($e);
            return false;
        }
    }

    public static function userRedPocketExport($where)
    {
        try {
            $logsList = Db::connect('mongodb')->table('redpocketrecords')->where($where)->order("createAt", "desc")->select()->toArray();

            foreach ($logsList as $key => $value) {
                $player = Db::connect('mongodb')->table('players')->where("_id", $logsList[$key]["player"])->find();
                $logsList[$key]["username"] =$player["name"];
                $logsList[$key]["shortId"] =$player["shortId"];
                $logsList[$key]["amountInFen"] = round($logsList[$key]["amountInFen"] / 100, 2);
                $logsList[$key]["roomId"] = !empty(explode(":", $logsList[$key]["from"])) ? explode(":", $logsList[$key]["from"])[1] : "";
                $roomRecord = Db::connect('mongodb')->table('roomrecords')->where("roomNum", $logsList[$key]["roomId"])->find();
                if(!empty($roomRecord)) {
                    $logsList[$key]["gameName"] = !empty($roomRecord) ? DefaultEnum::GAMElIST[$roomRecord["category"]] : DefaultEnum::ISNOTRESULT;
                    $logsList[$key]["roomState"] = !empty($roomRecord) ? DefaultEnum::ROOMSTATETEXT[$roomRecord["roomState"]] : DefaultEnum::ISNOTRESULT;
                    $logsList[$key]["playerCount"] = !empty($roomRecord) ? $roomRecord["rule"]["playerCount"] : DefaultEnum::ISNOTRESULT;
                    $logsList[$key]["juShu"] = !empty($roomRecord) ? $roomRecord["rule"]["juShu"] : DefaultEnum::ISNOTRESULT;
                }
                $logsList[$key]["_id"] = reset($logsList[$key]["_id"]);
                $logsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["createAt"]) / 1000));
            }

            $excelFields = [
                '_id' => '订单编号',
                'username' => '用户昵称',
                'shortId' => '用户Id',
                'amountInFen' => '红包金额',
                'roomId' => '房间号',
                'gameName' => '游戏',
                'juShu' => '局数',
                'playerCount' => '人数',
                'roomState' => '房间状态',
                'createAt' => '时间',
            ];

            $export = new ExportExcelServer();
            $export->setFileName('红包领取明细');
            $result = $export->createExcel($excelFields, $logsList);

            return ['url' => $result];

        } catch (\Exception $e) {
            var_dump($e);
            return false;
        }
    }

    public static function userRedPocketWithdrawExport($where)
    {
        try {
            $logsList = Db::connect('mongodb')->table('redpocketwithdrawrecords')->where($where)->order("createAt", "desc")->select()->toArray();

            foreach ($logsList as $key => $value) {
                $player = Db::connect('mongodb')->table('players')->where("_id", $logsList[$key]["player"])->find();
                $logsList[$key]["username"] =$player["name"];
                $logsList[$key]["shortId"] =$player["shortId"];
                $logsList[$key]["amountInFen"] = round($logsList[$key]["amountInFen"] / 100, 2);
                $logsList[$key]["_id"] = reset($logsList[$key]["_id"]);
                $logsList[$key]["info"] = DefaultEnum::WITHDRAWSTATE[$logsList[$key]["state"]];
                $logsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["createAt"]) / 1000));
            }

            $excelFields = [
                '_id' => '订单编号',
                'username' => '用户昵称',
                'shortId' => '用户Id',
                'amountInFen' => '金额',
                'info' => '状态',
                'createAt' => '时间',
            ];

            $export = new ExportExcelServer();
            $export->setFileName('红包提现明细');
            $result = $export->createExcel($excelFields, $logsList);

            return ['url' => $result];

        } catch (\Exception $e) {
            return false;
        }
    }

    public static function userRoomExport($where)
    {
        try {
            $roomList = Db::connect('mongodb')->table('roomrecords')
                ->where($where)->order("createAt", "desc")->select()->toArray();

            foreach ($roomList as $key => $value) {
                $roomList[$key]["isClubRoom"] = empty($value["club"]) ? "是" : "否";
                $roomList[$key]["gameType"] = DefaultEnum::GAMElIST[$roomList[$key]["category"]];
                if(!empty($value["rule"]["ruleId"])) $clubRule = Db::connect('mongodb')->table('clubrules')->where("_id", new ObjectId($value["rule"]["ruleId"]))->find();
                if(!empty($clubRule) || !empty($value["rule"]["ruleType"]))
                    $roomList[$key]["roomType"] = !empty($clubRule) ? DefaultEnum::ROOMTYPE[$clubRule["ruleType"]] : DefaultEnum::ROOMTYPE[$value["rule"]["ruleType"]];
                if(empty($roomList[$key]["roomType"])) $roomList[$key]["roomType"] = DefaultEnum::ISNOTRESULT;
                $player = Db::connect('mongodb')->table('players')->where("shortId", intval($value["creatorId"]))->find();
                $roomList[$key]["username"] =$player["name"];
                $roomList[$key]["shortId"] =$player["shortId"];
                $roomList[$key]["juShu"] =$value["rule"]["juShu"];
                $roomList[$key]["playerCount"] =$value["rule"]["playerCount"];
                if(!empty($value["club"])) $roomList[$key]["club"] = Db::connect('mongodb')->table('clubs')
                    ->where("_id", $roomList[$key]["club"])->find();
                $roomList[$key]["clubId"] = empty($roomList[$key]["club"]) ? DefaultEnum::ISNOTRESULT : $roomList[$key]["club"]["shortId"];
                $roomList[$key]["clubName"] = empty($roomList[$key]["club"]) ? DefaultEnum::ISNOTRESULT : $roomList[$key]["club"]["name"];
                $roomList[$key]["payType"] = formatPayType($value["rule"], $roomList[$key]["clubName"]);
                $roomList[$key]["_id"] = reset($roomList[$key]["_id"]);
                $roomList[$key]["roomState"] = DefaultEnum::ROOMSTATETEXT[$roomList[$key]["roomState"]];
                if(!empty($roomList[$key]["createAt"])) $roomList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($roomList[$key]["createAt"]) / 1000));
            }

            $excelFields = [
                '_id' => '订单编号',
                'username' => '用户昵称',
                'shortId' => '用户Id',
                'isClubRoom' => '战队房',
                'clubName' => '俱乐部名称',
                'clubId' => '俱乐部id',
                'roomType' => '房间类型',
                'gameType' => '游戏类型',
                'juShu' => '局数',
                'playerCount' => '人数',
                'payType' => '钻石',
                'roomState' => '状态',
                'createAt' => '时间',
            ];

            $export = new ExportExcelServer();
            $export->setFileName('房间明细');
            $result = $export->createExcel($excelFields, $roomList);

            return ['url' => $result];

        } catch (\Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public static function gameHaveFourJokerExport($where)
    {
        try {
            $recordList = Db::connect('mongodb')->table('gamefourjokerrecords')->where($where)
                ->order("dayId", "desc")->select()->toArray();

            foreach ($recordList as $key => $value) {
                $player = Db::connect('mongodb')->table('players')->where("shortId", $value["shortId"])->find();
                $recordList[$key]["username"] =$player["name"];
                $recordList[$key]["shortId"] =$player["shortId"];
                $recordList[$key]["gameTime"] = date('Y-m-d H:i:s',intval(reset($recordList[$key]["gameTime"]) / 1000));
                $recordList[$key]["type"] = DefaultEnum::GAMElIST[$recordList[$key]["type"]];
                $recordList[$key]["juShu"] = '第' . $recordList[$key]["juShu"] . '局';
                $recordList[$key]["_id"] = reset($recordList[$key]["_id"]);
            }

            $excelFields = [
                '_id' => '订单编号',
                'username' => '用户昵称',
                'shortId' => '用户Id',
                'roomId' => '房间号',
                'type' => '游戏',
                'juShu' => '小局',
                'juCount' => '局数',
                'jokerNum' => '王数',
                'jokerCount' => '王总数',
                'todayJuCount' => '今日局数',
                'dayId' => '日期',
            ];

            $export = new ExportExcelServer();
            $export->setFileName('4王局明细');
            $result = $export->createExcel($excelFields, $recordList);

            return ['url' => $result];

        } catch (\Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public static function agencyRechargeExport($where)
    {
        try {
            $logsList = Db::connect('mongodb')->table('records')->where($where)->order("created", "desc")->select()->toArray();

            foreach ($logsList as $key => $value) {
                $agency = Db::connect('mongodb')->table('gms')->where("_id", $logsList[$key]["to"])->find();
                $logsList[$key]["username"] =$agency["username"];
                $logsList[$key]["role"] =$agency["role"];
                $logsList[$key]["gem"] =$agency["gem"];
                $logsList[$key]["_id"] = reset($logsList[$key]["_id"]);
                $logsList[$key]["created"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["created"]) / 1000));
            }

            $excelFields = [
                '_id' => '订单编号',
                'username' => '昵称',
                'role' => '角色',
                'amount' => '金额',
                'gem' => '持有钻石',
                'created' => '时间',
            ];

            $export = new ExportExcelServer();
            $export->setFileName('充值明细');
            $result = $export->createExcel($excelFields, $logsList);

            return ['url' => $result];

        } catch (\Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public static function agencyAutoRechargeExport($where)
    {
        try {
            $logsList = Db::connect('mongodb')->table('gmextrecords')->where($where)->order("createAt", "desc")->select()->toArray();

            foreach ($logsList as $key => $value) {
                $agency = Db::connect('mongodb')->table('gms')->where("_id", $value["gm"])->find();
                $logsList[$key]["username"] =$agency["username"];
                $logsList[$key]["role"] =$agency["role"];
                $logsList[$key]["gmGem"] =$agency["gem"];
                $logsList[$key]["_id"] = reset($value["_id"]);
                $logsList[$key]["status"] = DefaultEnum::RECHARGEORDERSTATUS[$logsList[$key]["status"]];
                $logsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["createAt"]) / 1000));
            }


            $excelFields = [
                '_id' => '订单编号',
                'username' => '昵称',
                'role' => '角色',
                'price' => '充值金额',
                'gem' => '充值钻石',
                'gmGem' => '持有钻石',
                'status' => '状态',
                'createAt' => '时间',
            ];

            $export = new ExportExcelServer();
            $export->setFileName('自助充值明细');
            $result = $export->createExcel($excelFields, $logsList);

            return ['url' => $result];

        } catch (\Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public static function agencyGemExport($where)
    {
        try {
            $logsList = Db::connect('mongodb')->table('gmnotes')->where($where)->order("createAt", "desc")->select()->toArray();

            foreach ($logsList as $key => $value) {
                $gmInfo = Db::connect('mongodb')->table('gms')->where("_id", $logsList[$key]["gm"])->find();
                $logsList[$key]["username"] =$gmInfo["username"];
                $logsList[$key]["role"] =$gmInfo["role"];
                $logsList[$key]["gem"] =$gmInfo["gem"];
                $logsList[$key]["_id"] = reset($logsList[$key]["_id"]);
                $logsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["createAt"]) / 1000));
            }

            $excelFields = [
                '_id' => '订单编号',
                'username' => '昵称',
                'role' => '角色',
                'gem' => '持有钻石',
                'note' => '备注',
                'createAt' => '时间',
            ];

            $export = new ExportExcelServer();
            $export->setFileName('钻石明细');
            $result = $export->createExcel($excelFields, $logsList);

            return ['url' => $result];

        } catch (\Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public static function clubAccountExport($where)
    {
        try {
            $logsList = Db::connect('mongodb')->table('clubgoldrecords')->where($where)->order("createAt", "desc")->select()->toArray();

            foreach ($logsList as $key => $value) {
                $player = Db::connect('mongodb')->table('players')->where("_id", $logsList[$key]["member"])->find();
                $club = Db::connect('mongodb')->table('clubs')->where("_id", $logsList[$key]["club"])->find();
                $logsList[$key]["username"] = $player["name"];
                $logsList[$key]["shortId"] = $player["shortId"];
                $logsList[$key]["clubName"] = $club["name"];
                $logsList[$key]["clubId"] = $club["shortId"];

                if($logsList[$key]["from"] != "pay") {
                    $player = Db::connect('mongodb')->table('players')->where("_id", $logsList[$key]["from"])->find();
                    $logsList[$key]["from"] = $player["name"] . "(" . $player["shortId"] . ")";
                }
                $info = explode("：", $logsList[$key]["info"]);
                count($info) >= 2 ? $logsList[$key]["room"] = explode("：", $logsList[$key]["info"])[1] : $logsList[$key]["room"] = DefaultEnum::ISNOTRESULT;
                $logsList[$key]["_id"] = reset($logsList[$key]["_id"]);
                $logsList[$key]["createAt"] = date('Y-m-d H:i:s',intval(reset($logsList[$key]["createAt"]) / 1000));
            }

            $excelFields = [
                '_id' => '订单编号',
                'username' => '用户昵称',
                'shortId' => '用户ID',
                'clubName' => '俱乐部名称',
                'clubId' => '俱乐部ID',
                'goldChange' => '变动数量',
                'allClubGold' => '持有金币',
                'room' => '房间号',
                'info' => '备注',
                'from' => '操作者',
                'createAt' => '时间',
            ];

            $export = new ExportExcelServer();
            $export->setFileName('流水明细');
            $result = $export->createExcel($excelFields, $logsList);

            return ['url' => $result];

        } catch (\Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
    }
}
