<?php

namespace app\adminapi\logic;
use app\adminapi\logic\auth\AuthLogic;
use app\common\{cache\AdminAuthCache, model\Role, service\ConfigService, service\FileService};
use think\facade\Config;

/**
 * 配置类逻辑层
 * Class ConfigLogic
 * @package app\adminapi\logic
 */
class ConfigLogic
{
    /**
     * @notes 获取配置
     * @return array
     * @author cjhao
     * @date 2021/8/19 16:28
     */
    public static function getConfig():array
    {
        $data = [
            'oss_domain'                => FileService::getFileUrl(),
            'copyright'                 => ConfigService::get('shop', 'copyright', ''),
            'record_number'             => ConfigService::get('shop', 'record_number', ''),
            'record_system_link'        => ConfigService::get('shop', 'record_system_link', ''),
            'name'                      => ConfigService::get('shop', 'name'),
            'logo'                      => FileService::getFileUrl(ConfigService::get('shop', 'logo')),
            'admin_login_image'         => FileService::getFileUrl(ConfigService::get('shop', 'admin_login_image')),
            'favicon'                   => FileService::getFileUrl(ConfigService::get('shop','favicon'))

        ];

        return $data;

    }

    /**
     * @notes 获取菜单权限
     * @param array $adminInfo
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/25 20:33
     */
    public static function getAuth(array $adminInfo):array
    {
        $data = [
            'root'  => $adminInfo['root'],
            'auth'  => [],
        ];
        if(1 == $adminInfo['root']){
            return $data;
        }
        $adminAuthCache = new AdminAuthCache($adminInfo['admin_id']);
        $pageAuth = $adminAuthCache->getAdminPageAuth($adminInfo['role_name']);
        $data['auth'] = $pageAuth;

        return $data;
    }

    /**
     * @notes 获取营销中心模块
     * @return array
     * @author cjhao
     * @date 2021/9/11 11:43
     */
    public static function getMarketingModule():array
    {
        $configModule = Config::get('module');
        return $configModule['marketing'];
    }

    /**
     * @notes 获取应用中心模块
     * @return array
     * @author cjhao
     * @date 2021/9/24 16:55
     */
    public static function getAppModule():array
    {
        $configModule = Config::get('module');
        return $configModule['apply'];
    }

}