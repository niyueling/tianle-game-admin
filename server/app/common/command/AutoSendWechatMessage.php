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
class AutoSendWechatMessage extends Command
{

    protected function configure()
    {
        $this->setName('wechat_send_message')
            ->setDescription('推送充值卡片');
    }

    protected function execute(Input $input, Output $output)
    {
        $record = Db::connect('mongodb')->table('wechatmessages')->where("isSend", false)
            ->where("time", "<", time() - 5)->select();

        foreach ($record as $value) {
            //发送客服消息给用户
            $accessToken = ConfigService::get('wechat', 'MnpAccessToken');
            $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$accessToken}";
            $data = [
                "touser" => $value['openid'],
                "msgtype" => "link",
                "link" => [
                    "title" => "天乐麻将充值",
                    "description" => "欢迎来到天乐麻将，点击进行充值",
                    "url" => "https://phpadmin.tianle.fanmengonline.com/mobile/#/bundle/pages/user_recharge/user_recharge",
                    "thumb_url" => "https://phpadmin.tianle.fanmengonline.com/uploads/images/tianle.png"
                ]
            ];

            curl_post($url, $data);

            Db::connect('mongodb')->table('wechatmessages')->where("_id", $value["_id"])->update([
                "isSend" => true,
                "execTime" => new UTCDateTime(time() * 1000)
            ]);
        }
    }

}
