<?php

namespace app\adminapi\logic\auth;
use think\facade\Config;

/**
 * 权限功能类
 * Class AuthLogic
 * @package app\adminapi\logic\auth
 */
class AuthLogic
{
    /**
     * @notes 获取菜单
     * @return array
     * @author cjhao
     * @date 2021/8/25 17:37
     */
    public static function getMenu():array
    {
        $menu = Config::get('menu');
        return $menu;
    }

    /**
     * @notes 获取权限
     * @param array $authKeys array-返回指定权限
     * @return array
     * @author cjhao
     * @date 2021/8/26 11:09
     */
    public static function getAuth(array $authKeys = []):array
    {
        $authConfigList = Config::get('auth');
        //获取指定权限
        if(!empty($authKeys)){
            $authList = [];
            foreach ($authKeys as $keys){
                $keyList = explode('/',$keys);
                $authConfig = $authConfigList[$keyList[0]] ?? [];
                if(empty($authConfig)){
                    continue;
                }
                $keyList = explode('.',$keyList[1]);

                $buttonAuth = $authConfig[$keyList[0]][$keyList[1]]['button_auth'] ?? [];
                $actionAuth = $authConfig[$keyList[0]][$keyList[1]]['action_auth'] ?? [];

                $authList = [
                    'button_auth'   => array_merge($authList['button_auth'] ?? [],$buttonAuth),
                    'action_auth'   => array_merge($authList['action_auth'] ?? [],$actionAuth),
                ];
            }
            return $authList;
        }
        return $authConfigList;

    }
}