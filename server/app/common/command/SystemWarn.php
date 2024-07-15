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
class SystemWarn extends Command
{

    protected function configure()
    {
        $this->setName('system_warn')
            ->setDescription('系统预警');
    }

    protected function execute(Input $input, Output $output)
    {
        $mailslists = [];
        $open_mail_notice = ConfigService::get('shop', 'open_mail_notice');
        $mail_1 = ConfigService::get('shop', 'mail_1');
        $mail_2 = ConfigService::get('shop', 'mail_2');
        $mail_3 = ConfigService::get('shop', 'mail_3');

        if(empty($open_mail_notice)) return "未开放报警功能";
        if(!empty($mail_1)) array_push($mailslists, $mail_1);
        if(!empty($mail_2)) array_push($mailslists, $mail_2);
        if(!empty($mail_3)) array_push($mailslists, $mail_3);

        //判断是否有提现异常记录
        $res = self::checkWithdrawRecord(15);
        if(!$res["flag"]) {
            self::addSystemWarmLogs($res["flag"], $res["logLists"], "无需报警", false, "", "");
            return "无需报警";
        }

        self::sendMail($mailslists, $res);
    }

    public static function sendMail($mailslists, $res) {
        // 实例化 PHPMailer
        $mail = new PHPMailer(true);
        try {
            // 邮件服务器设置
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = config('mail.host');
            $mail->SMTPAuth = true;
            $mail->Username = config('mail.username');
            $mail->Password = config('mail.password');
            $mail->SMTPSecure = config('mail.secure');
            $mail->CharSet = 'UTF-8';
            $mail->Port = config('mail.port');
            $mail->setFrom(config('mail.username'),config('mail.from_name'));
            $mail->Subject = '提现记录警报';
            $mail->Body    = '';
            foreach ($res["logLists"] as $log)
                if($log["flag"]) $mail->Body .= $log["name"] . "(" . $log["shortId"] . ")" .
                    ",报错原因：" . $log["state"] . "提现金额:" . intval($log["amountInFen"] / 100);

            foreach ($mailslists as $address) {
                $mail->addAddress($address);
                $mail->send();
                self::addSystemWarmLogs($res["flag"], $res["logLists"], "邮件报警成功", true, $mail->Subject, $mail->Body);
            }

            return "邮件报警成功";
        } catch (Exception $e) {
            self::addSystemWarmLogs($res["flag"], $res["logLists"], '邮件发送失败：' . $mail->ErrorInfo, false, $mail->Subject, $mail->Body);
            return ['邮件发送失败：' . $mail->ErrorInfo];
        }
    }

    public static function addSystemWarmLogs($flag, $logLists, $info, $state, $subject, $body) {
        Db::connect('mongodb')->table('systemwarns')->insert([
            "flag" => $flag,
            "subject" => $subject,
            "body" => $body,
            "recordlists" => $logLists,
            "createAt" => new UTCDateTime(time() * 1000),
            "state" => $state,
            "info" => $info
        ]);
    }

    public static function checkWithdrawRecord($minutes) {
        //获取上次报警时间
        $warn = Db::connect('mongodb')->table('systemwarns')->order("createAt", "desc")->limit(1)->select()->toArray();
        $flag = false;
        $start_time = !empty($warn) ? intval(reset($warn[0]["createAt"]) / 1000) : time() - 60 * 60 * 24;
        $end_time = time();

        $logsList = Db::connect('mongodb')->table('redpocketwithdrawrecords')
            ->where("state","403")->where("createAt", "between",
                [new UTCDateTime($start_time * 1000),
                    new UTCDateTime($end_time * 1000)])->select()->toArray();

        foreach ($logsList as $key => $value) {
            $player = Db::connect('mongodb')->table('players')->where("_id", $value["player"])->find();
            $logsList[$key]["shortId"] = $player["shortId"];
            $logsList[$key]["name"] = $player["name"];
            if(empty($player) || (!empty($player["warnTime"]) && time() < $player["warnTime"])) {
                $logsList[$key]["flag"] = false;
                continue;
            }

            $logsList[$key]["flag"] = true;
            $flag = true;
            Db::connect('mongodb')->table('players')->where("_id", $value["player"])->update(["warnTime" => time() + 60 * $minutes]);
        }

        return ["flag" => $flag, "logLists" => $logsList];
    }
}
