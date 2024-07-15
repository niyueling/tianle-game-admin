<?php

namespace app\adminapi\logic\settings\admin;

use app\common\logic\BaseLogic;
use app\common\service\ConfigService;
use app\common\service\FileService;

class AdminSettingLogic extends BaseLogic
{
    /**
     * @notes 获取店铺信息
     * @return array
     * @author Tab
     * @date 2021/7/28 14:19
     */
    public static function getShopInfo()
    {
        $config = [
            'name'                  => ConfigService::get('shop', 'name'),
            'logo'                  => ConfigService::get('shop', 'logo'),
            'admin_login_image'     => ConfigService::get('shop', 'admin_login_image'),
            'login_restrictions'    => ConfigService::get('shop', 'login_restrictions'),
            'password_error_times'  => ConfigService::get('shop', 'password_error_times'),
            'limit_login_time'      => ConfigService::get('shop', 'limit_login_time'),
            'open_mail_notice'     => ConfigService::get('shop', 'open_mail_notice'),
            'mail_1'    => ConfigService::get('shop', 'mail_1'),
            'mail_2'  => ConfigService::get('shop', 'mail_2'),
            'mail_3'      => ConfigService::get('shop', 'mail_3'),
            'open_mnp_recharge'      => ConfigService::getGlobal('number', 'openMnpRecharge'),
            'open_cheer_qian'      => ConfigService::getGlobal('number', 'mnpCheerAndQian'),
            'mnp_recharge_version'      => ConfigService::getGlobal('string', 'mnpRechargeVersion'),
            "ios_sandbox_version" =>ConfigService::getIosSandboxConfig(),
            'web_platform_appid'     => ConfigService::get('web_platform', 'app_id'),
            'web_platform_secret'     => ConfigService::get('web_platform', 'app_secret'),
            'wxgame_offerid'     => ConfigService::get('wxgame', 'offerid'),
            'wxgame_zoneid'     => ConfigService::get('wxgame', 'zoneid'),
            'wxgame_appkey'     => ConfigService::get('wxgame', 'appkey'),
        ];

        $config['logo'] = FileService::getFileUrl($config['logo']);
        $config['admin_login_image'] = FileService::getFileUrl($config['admin_login_image']);

        return $config;
    }

    /**
     * @notes 设置店铺信息
     * @param $params
     * @author Tab
     * @date 2021/7/28 14:47
     */
    public static function setShopInfo($params)
    {
        $params['logo'] = FileService::setFileUrl($params['logo']);
        $params['admin_login_image'] = FileService::setFileUrl($params['admin_login_image']);

        ConfigService::set('shop','name', $params['name']);
        ConfigService::set('shop','logo', $params['logo']);
        ConfigService::set('shop','admin_login_image', $params['admin_login_image']);
        ConfigService::set('shop','login_restrictions', $params['login_restrictions']);
        ConfigService::set('shop','open_mail_notice', $params['open_mail_notice']);

        if($params['login_restrictions']) {
            ConfigService::set('shop','password_error_times', $params['password_error_times']);
            ConfigService::set('shop','limit_login_time', $params['limit_login_time']);
        }

        if($params['open_mail_notice']) {
            ConfigService::set('shop','mail_1', $params['mail_1']);
            ConfigService::set('shop','mail_2', $params['mail_2']);
            ConfigService::set('shop','mail_3', $params['mail_3']);
        }

        if($params['open_mnp_recharge']) ConfigService::setGlobal('number','openMnpRecharge', intval($params['open_mnp_recharge']));
        if($params['open_cheer_qian']) ConfigService::setGlobal('number','mnpCheerAndQian', intval($params['open_cheer_qian']));
        if($params['mnp_recharge_version']) ConfigService::setGlobal('string','mnpRechargeVersion', $params['mnp_recharge_version']);
        if($params['ios_sandbox_version']) ConfigService::setIosSandboxConfig($params['ios_sandbox_version']);
        if($params['web_platform_appid']) ConfigService::set('web_platform','app_id', $params['web_platform_appid']);
        if($params['web_platform_secret']) ConfigService::set('web_platform','app_secret', $params['web_platform_secret']);
        if($params['wxgame_offerid']) ConfigService::set('wxgame','offerid', $params['wxgame_offerid']);
        if($params['wxgame_zoneid']) ConfigService::set('wxgame','zoneid', $params['wxgame_zoneid']);
        if($params['wxgame_appkey']) ConfigService::set('wxgame','appkey', $params['wxgame_appkey']);
    }

    /**
     * @notes 获取备案信息
     * @param $params
     * @return array
     * @author Tab
     * @date 2021/7/28 15:08
     */
    public static function getRecordInfo()
    {
        $config = [
            'copyright' => ConfigService::get('shop', 'copyright', ''),
            'record_number' => ConfigService::get('shop', 'record_number', ''),
            'record_system_link' => ConfigService::get('shop', 'record_system_link', ''),
            'business_license' => ConfigService::get('shop', 'business_license'),
            'other_qualifications' => ConfigService::get('shop', 'other_qualifications',[]),
        ];

        $config['business_license'] = $config['business_license'] ? FileService::getFileUrl($config['business_license']) : '';
        if (!empty($config['other_qualifications'])) {
            foreach ($config['other_qualifications'] as &$val) {
                $val = FileService::getFileUrl($val);
            }
        }

        return $config;
    }

    /**
     * @notes 设置备案信息
     * @param $params
     * @author Tab
     * @date 2021/7/28 15:14
     */
    public static function setRecordInfo($params)
    {
        ConfigService::set('shop', 'copyright', $params['copyright'] ?? '');
        ConfigService::set('shop', 'record_number', $params['record_number'] ?? '');
        ConfigService::set('shop', 'record_system_link', $params['record_system_link'] ?? '');
        ConfigService::set('shop', 'business_license', FileService::setFileUrl($params['business_license'] ?? ''));
        $other_qualifications = [];
        if (!empty($params['other_qualifications'])) {
            foreach ($params['other_qualifications'] as &$val) {
                $val = FileService::setFileUrl($val);
            }
            $other_qualifications = json_encode($params['other_qualifications']);
        }
        ConfigService::set('shop', 'other_qualifications', $other_qualifications);
        return true;
    }

    /**
     * @notes 获取分享设置
     * @return array
     * @author Tab
     * @date 2021/7/28 15:29
     */
    public static function getShareSetting()
    {
        $config = [
            'share_page' => ConfigService::get('shop', 'share_page'),
            'share_title' => ConfigService::get('shop', 'share_title', ''),
            'share_intro' => ConfigService::get('shop', 'share_intro', ''),
            'share_image' => ConfigService::get('shop', 'share_image'),
        ];
        $config['share_image'] = FileService::getFileUrl($config['share_image']);

        return $config;
    }

    /**
     * @notes 分享设置
     * @param $params
     * @author Tab
     * @date 2021/7/28 15:37
     */
    public static function setShareSetting($params)
    {
        ConfigService::set('shop', 'share_page', $params['share_page']);
        ConfigService::set('shop', 'share_title', $params['share_title'] ?? '');
        ConfigService::set('shop', 'share_intro', $params['share_intro'] ?? '');
        ConfigService::set('shop', 'share_image', FileService::setFileUrl($params['share_image']));
    }

    /**
     * @notes 获取政策协议
     * @return array
     * @author Tab
     * @date 2021/7/28 16:08
     */
    public static function getPolicyAgreement()
    {
        $config = [
            'service_agreement_name' => ConfigService::get('shop', 'service_agreement_name', ''),
            'service_agreement_content' => ConfigService::get('shop', 'service_agreement_content', ''),
            'privacy_policy_name' => ConfigService::get('shop', 'privacy_policy_name', ''),
            'privacy_policy_content' => ConfigService::get('shop', 'privacy_policy_content', ''),
        ];

        return $config;
    }

    /**
     * @notes 设置政策协议
     * @param $params
     * @author Tab
     * @date 2021/7/28 16:13
     */
    public static function setPolicyAgreement($params)
    {
        if(isset($params['service_agreement_name']) && isset($params['service_agreement_content'])) {
            ConfigService::set('shop', 'service_agreement_name', $params['service_agreement_name']);
            ConfigService::set('shop', 'service_agreement_content', $params['service_agreement_content']);
        }

        if(isset($params['privacy_policy_name']) && isset($params['privacy_policy_content'])) {
            ConfigService::set('shop', 'privacy_policy_name', $params['privacy_policy_name']);
            ConfigService::set('shop', 'privacy_policy_content', $params['privacy_policy_content']);
        }
    }
}
