<?php

namespace app\adminapi\logic\sms;

use app\common\logic\BaseLogic;
use app\common\service\ConfigService;

/**
 * 短信配置逻辑层
 * Class SmsConfigLogic
 * @package app\adminapi\logic\notice
 */
class SmsConfigLogic extends BaseLogic
{
    /**
     * @notes 获取短信配置
     * @return array
     * @author Tab
     * @date 2021/8/19 11:54
     */
    public static function getConfig()
    {
        $config = [
            'ali' => ConfigService::get('sms', 'ali'),
            'tencent' => ConfigService::get('sms', 'tencent'),
        ];
        return $config;
    }

    /**
     * @notes 短信配置
     * @param $params
     * @return bool
     * @author Tab
     * @date 2021/8/19 14:31
     */
    public static function setConfig($params)
    {
        $type = $params['type'];
        unset($params['type']);
        $params['name'] = self::getNameDesc(strtoupper($type));
        ConfigService::set('sms', $type, $params);
        $default = ConfigService::get('sms', 'engine', false);
        if($params['status'] == 1 && $default === false) {
            // 启用当前短信配置 并 设置当前短信配置为默认
            ConfigService::set('sms', 'engine', strtoupper($type));
            return true;
        }
        if($params['status'] == 1 && $default != strtoupper($type)) {
            // 找到默认短信配置
            $defaultConfig = ConfigService::get('sms', strtolower($default));
            // 状态置为禁用 并 更新
            $defaultConfig['status'] = 0;
            ConfigService::set('sms', strtolower($default), $defaultConfig);
            // 设置当前短信配置为默认
            ConfigService::set('sms', 'engine', strtoupper($type));
            return true;
        }
    }

    /**
     * @notes 查看短信配置详情
     * @param $params
     * @return array|int|mixed|string
     * @author Tab
     * @date 2021/8/19 13:59
     */
    public static function detail($params)
    {
        return ConfigService::get('sms', $params['type']);
    }

    /**
     * @notes 获取短信平台名称
     * @param $value
     * @return string
     * @author Tab
     * @date 2021/8/19 14:19
     */
    public  static function getNameDesc($value)
    {
        $desc = [
            'ALI' => '阿里云短信',
            'TENCENT' => '腾讯云短信',
        ];
        return $desc[$value] ?? '';
    }
}