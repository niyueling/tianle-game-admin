<?php
namespace app\common\command;
use app\common\service\TimeService;
use MongoDB\BSON\UTCDateTime;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Db;

/**
 * 计算用户
 * Class OrderClose
 * @package app\common\command
 */
class GameFourJoker extends Command
{
    protected function configure()
    {
        $this->setName('game_four_joker')->setDescription('统计4王局');
    }

    protected function execute(Input $input, Output $output)
    {
        $yestoday_arr = TimeService::yesterday();
        $where = [["type", "=", "zhadan"]];

        if(!empty($yestoday_arr[0]) && !empty($yestoday_arr[1])) {
            $where[] = ["time", "between", [
                new UTCDateTime($yestoday_arr[0] * 1000),
                new UTCDateTime($yestoday_arr[1] * 1000)
            ]];
        }
        Db::connect('mongodb')->table('gamefourjokerrecords')->where("dayId", "=", date("Ymd", $yestoday_arr[0]))->delete();

        // 每批次处理的记录数
        $pagesize = 50;
        // 获取符合条件的记录总数
        $total = Db::connect('mongodb')->table('gamerecords')->where($where)->count();
        // 计算需要分成多少批次处理
        $pagecount = ceil($total / $pagesize);

        for ($page = 1; $page <= $pagecount; $page++) {
            $gamelists = Db::connect('mongodb')->table('gamerecords')->where($where)->page($page, $pagesize)->select();

            foreach ($gamelists as $key => $value) {
                $isHaveFourJoker = false;
                $shortId = '';
                $joker = 0;
                $cards = [];
                foreach ($value["events"] as $key1 => $value1) {
                    if ($value1["type"] == "shuffle") {
                        $jokerCount = 0;
                        foreach ($value1["info"]["cards"] as $card) {
                            if (in_array($card["value"], [16, 17])) $jokerCount++;
                        }
                        if ($jokerCount >= 4) {
                            $isHaveFourJoker = true;
                            $shortId = $value["states"][$value1["index"]]["model"]["shortId"];
                            $joker = $jokerCount;
                            $cards = $value1["info"]["cards"];
                            break;
                        }
                    }
                }
                if (!$isHaveFourJoker) {
                    continue;
                }
                $insertData = [
                    "shortId" => $shortId,
                    "room" => $value["room"],
                    "type" => $value["type"],
                    "juShu" => $value["juShu"],
                    "juCount" => $value["game"]["rule"]["juShu"],
                    "jokerNum" => $joker,
                    "jokerCount" => $value["game"]["rule"]["jokerCount"],
                    "todayJuCount" => $total,
                    "dayId" => date('Ymd', intval(reset($value["time"]) / 1000)),
                    "cards" => $cards,
                    "roomId" => $value["game"]["roomId"],
                    "gameId" => reset($value["_id"]),
                    "gameTime" => new UTCDateTime(reset($value["time"])),
                    "createAt" => new UTCDateTime(time() * 1000)
                ];
                $isExist = Db::connect('mongodb')->table('gamefourjokerrecords')->where("gameId", reset($value["_id"]))->count();
                if(!$isExist) {
                    Db::connect('mongodb')->table('gamefourjokerrecords')->insertGetId($insertData);
                }
            }
        }

        return [];
    }
}
