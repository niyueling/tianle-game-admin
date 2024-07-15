<?php

namespace app\common\service;

use app\common\enum\PayEnum;
use app\common\enum\UserTerminalEnum;
use app\common\model\PayConfig;

/**
 * 微信配置类
 * Class WeChatConfigService
 * @package app\common\service
 */
class WeChatConfigService
{
    /**
     * 获取微信公众号配置
     * @return array
     */
    public static function getOaConfig()
    {
        $config = [
            'app_id' => ConfigService::get('official_account', 'app_id',''),
            'secret' => ConfigService::get('official_account', 'app_secret',''),
            'mch_id' => ConfigService::get('official_account', 'mch_id'),
            'key' => ConfigService::get('official_account', 'key'),
            'token' => ConfigService::get('official_account', 'token',''),
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => '../runtime/log/wechat.log'
            ],
        ];
        return $config;
    }

    //根据用户客户端不同获取不同的微信配置
    public static function getWechatConfigByTerminal($terminal)
    {
        switch ($terminal) {
            case UserTerminalEnum::WECHAT_MMP:
                $appid = ConfigService::get('mini_program', 'app_id');
                $secret = ConfigService::get('mini_program', 'app_secret');
                $notify_url = (string)url('wechat.wechat/notifyMnp', [], false, true);
                break;
            case UserTerminalEnum::WECHAT_OA:
            case UserTerminalEnum::H5:
                $appid = ConfigService::get('official_account', 'app_id');
                $secret = ConfigService::get('official_account', 'app_secret');
                $notify_url = (string)url('wechat.wechat/notifyOa', [], false, true);
                break;
            case UserTerminalEnum::ANDROID:
            case UserTerminalEnum::IOS:
                $appid = ConfigService::get('open_platform', 'app_id');
                $secret = ConfigService::get('open_platform', 'app_secret');
                $notify_url = (string)url('wechat.wechat/notifyApp', [], false, true);
                break;
            case UserTerminalEnum::WEB:
                $appid = ConfigService::get('web_platform', 'app_id');
                $secret = ConfigService::get('web_platform', 'app_secret');
                $notify_url = (string)url('wechat.wechat/notifyWeb', [], false, true);
                break;
            default:
                $appid = '';
                $secret = '';
        }

        $pay = PayConfig::where(['pay_way' => PayEnum::WECHAT_PAY])->findOrEmpty()->toArray();
        //判断是否已经存在证书文件夹，不存在则新建
        if (!file_exists(app()->getRootPath().'runtime/certificate')) {
            mkdir(app()->getRootPath().'runtime/certificate', 0775, true);
        }
        //写入文件
        $apiclient_cert = $pay['config']['apiclient_cert'] ?? '';
        $apiclient_key = $pay['config']['apiclient_key'] ?? '';
        $cert_path = app()->getRootPath().'runtime/certificate/'.md5($apiclient_cert).'.pem';
        $key_path = app()->getRootPath().'runtime/certificate/'.md5($apiclient_key).'.pem';
        $log_path = app()->getRootPath().'runtime/wechat.log';
        if (!file_exists($cert_path)) {
            $fopen_cert_path = fopen($cert_path, 'w');
            fwrite($fopen_cert_path, $apiclient_cert);
            fclose($fopen_cert_path);
        }
        if (!file_exists($key_path)) {
            $fopen_key_path = fopen($key_path, 'w');
            fwrite($fopen_key_path, $apiclient_key);
            fclose($fopen_key_path);
        }
        if (!file_exists($log_path)) {
            $fopen_log_path = fopen($log_path, 'w');
            fwrite($fopen_log_path, "");
            fclose($fopen_log_path);
        }

        $config = [
            'app_id' => $appid,
            'secret' => $secret,
            'mch_id' => $pay['config']['mch_id'] ?? '',
            'key' => $pay['config']['pay_sign_key'] ?? '',
            'cert_path' => $cert_path,
            'key_path' => $key_path,
            'response_type' => 'array',
            'http' => [
                'verify' => false
            ],
            'log' => [
                'level' => 'debug',
                'file' => $log_path
            ],
            'notify_url' => $notify_url
        ];

        return $config;
    }
}
