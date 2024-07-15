<?php

namespace app\adminapi\service;

use app\common\cache\AdminTokenCache;
use think\facade\Config;
use think\facade\Db;

class AdminTokenService
{
    /**
     * @notes 设置或更新管理员token
     * @param $adminId 管理员id
     * @param $terminal 多终端名称
     * @param $multipointLogin 是否支持多处登录
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:42
     */
    public static function setToken($adminId)
    {
        $time = time();
        $adminSession = Db::connect('mongodb')->table('session')->where("admin_id", $adminId)->find();

        //获取token延长过期的时间
        $expireTime = $time + Config::get('project.admin_token.expire_duration');
        $adminTokenCache = new AdminTokenCache();
        empty($adminSession) ? $token = create_token($adminId) : $token = $adminSession["token"];

        //token处理
        if (!empty($adminSession)) {
            if ($adminSession["expire_time"] < $time) {
                //清空缓存
                $adminTokenCache->deleteAdminInfo($adminSession["token"]);
                //如果token过期或账号设置不支持多处登录，更新token
                $adminSession["token"] = create_token($adminId);
                $token = $adminSession["token"];
            }
            $adminSession["expire_time"] = $expireTime;
            $adminSession["update_time"] = $time;
            unset($adminSession["_id"]);

            Db::connect('mongodb')->table('session')->where("admin_id", $adminId)->update($adminSession);
        } else {
            //找不到在该终端的token记录，创建token记录
            Db::connect('mongodb')->table('session')->insert([
                'admin_id' => $adminId,
                'token' => $token,
                'expire_time' => $expireTime,
                'update_time' => time()
            ]);
        }

        return $adminTokenCache->setAdminInfo($token);
    }

    /**
     * @notes 延长token过期时间
     * @param $token
     * @return array|false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 令狐冲
     * @date 2021/7/5 14:25
     */
    public static function overtimeToken($token)
    {
        $time = time();
        $adminSession = Db::connect('mongodb')->table('session')->where("token", $token)->find();
        if (empty($adminSession)) {
            return false;
        }
        //延长token过期时间
        $adminSession["expire_time"] = $time + Config::get('project.admin_token.expire_duration');
        $adminSession["update_time"] = $time;
        Db::connect('mongodb')->table('session')->where("token", $token)->update([
            "expire_time" => $adminSession["expire_time"],
            "update_time" => $adminSession["update_time"]
        ]);
        return (new AdminTokenCache())->setAdminInfo($adminSession["token"]);
    }

    /**
     * @notes 设置token为过期
     * @param $token
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 令狐冲
     * @date 2021/7/5 14:31
     */
    public static function expireToken($token)
    {
        $adminSession = Db::connect('mongodb')->table('session')->where("token", $token)->find();
        if (empty($adminSession)) {
            return false;
        }

        $time = time();
        $adminSession["expire_time"] = $time;
        $adminSession["update_time"] = $time;
        Db::connect('mongodb')->table('session')->where("token", $token)->update([
            "expire_time" => $adminSession["expire_time"],
            "update_time" => $adminSession["update_time"]
        ]);

        return (new  AdminTokenCache())->deleteAdminInfo($token);

    }

}