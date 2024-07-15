<?php


namespace app\common\command;

use app\common\service\ConfigService;
use MongoDB\BSON\UTCDateTime;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Db;

/**
 * 计算用户
 * Class OrderClose
 * @package app\common\command
 */
class updatePlayerLotteryCount extends Command
{

    protected function configure()
    {
        $this->setName('player_lottery_count')
            ->setDescription('每日赠送用户抽奖次数');
    }

    protected function execute(Input $input, Output $output)
    {
        $record = Db::connect('mongodb')->table('players')->select();

        foreach ($record as $value) {
            $lottery = Db::connect('mongodb')->table('playerdailylotteries')
                ->where("shortId", $value["shortId"])->find();

            if(empty($lottery)) {
                Db::connect('mongodb')->table('playerdailylotteries')->insert([
                    "playerId" => $value["_id"],
                    "shortId" => $value["shortId"],
                    "times" => 11,
                    "createAt" => new UTCDateTime(time() * 1000)
                ]);
            } else {
                Db::connect('mongodb')->table('playerdailylotteries')->where("_id", $lottery["_id"])->update([
                    "times" => 10
                ]);
            }

            Db::connect('mongodb')->table('playerdailylotteryrecords')->insert([
                "playerId" => $value["_id"],
                "shortId" => $value["shortId"],
                "times" => 10,
                "createAt" => new UTCDateTime(time() * 1000)
            ]);
        }
    }

}
