<?php
namespace app\common\command;
use app\common\service\ConfigService;
use app\common\service\WeChatConfigService;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Cache;

/**
 * 获取微信access_token
 * Class OrderClose
 * @package app\common\command
 */
class WechatAccessToken extends Command
{
    protected function configure()
    {
        $this->setName('wechat_access_token')->setDescription('获取微信access_token');
    }

    protected function execute(Input $input, Output $output)
    {
        //获取小程序access_token
        $officialAccountSetting = WeChatConfigService::getWechatConfigByTerminal(1);
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$officialAccountSetting['app_id']}&secret={$officialAccountSetting['secret']}";
        $data = curl_get($url);
        Cache::set("wechatAccessToken", $data["access_token"]);
        ConfigService::set('wechat', 'MnpAccessToken', $data["access_token"]);

        return $data;
    }
}
