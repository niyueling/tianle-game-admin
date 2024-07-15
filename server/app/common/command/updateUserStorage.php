<?php


namespace app\common\command;

use app\common\service\ConfigService;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Db;

/**
 * 计算用户
 * Class OrderClose
 * @package app\common\command
 */
class updateUserStorage extends Command
{

    protected function configure()
    {
        $this->setName('player_set_storage')
            ->setDescription('上报排行榜');
    }

    protected function execute(Input $input, Output $output)
    {
        $record = Db::connect('mongodb')->table('players')
            ->where("miniOpenid", "<>", null)
            ->where("sessionKey", "<>", null)
            ->select();

        foreach ($record as $player) {
            $accessToken = ConfigService::get('wechat', 'MnpAccessToken');

            $postBody = [
                "kv_list" => [
                    [
                        "key" => "tianle",
                        "value" => json_encode(["wxgame" => ["score" => $player["ruby"], "update_time" => time()]])
                    ]
                ]
            ];

            // 3. 生成登录态签名
            $signature =  hash_hmac('sha256', json_encode($postBody), $player["sessionKey"]);

            $balanceUrl = "https://api.weixin.qq.com/wxa/set_user_storage" .
                "?access_token={$accessToken}&signature={$signature}&sig_method=hmac_sha256&openid={$player["miniOpenid"]}";
            $res = curl_post($balanceUrl, $postBody);

            var_dump($postBody);

            if ($res["errcode"] == 0) var_dump($res);
        }

        return [];
    }

}
