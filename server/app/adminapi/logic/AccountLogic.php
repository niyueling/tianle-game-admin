<?php

namespace app\adminapi\logic;


use app\common\logic\BaseLogic;
use app\adminapi\service\AdminTokenService;
use app\common\service\FileService;
use think\facade\Config;
use think\facade\Db;

class AccountLogic extends BaseLogic
{
    /**
     * @notes 管理员账号登录
     * @param $params
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:33
     */
    public function login($params)
    {
        $time = time();
        $admin = Db::connect('mongodb')
            ->table('gms')
            ->where("username", $params["username"])
            ->find();

        if(!empty($params["openid"]) && empty($admin["openid"])) {
            Db::connect('mongodb')
                ->table('gms')
                ->where("username", $params["username"])
                ->update(["openid" => $params["openid"]]);
        }

        //用户表登录信息更新
        Db::connect('mongodb')
            ->table('gms')
            ->where("username", $params["username"])
            ->update(["login_time" => $time, "last_login_ip" => request()->ip()]);

        //设置token
        $adminInfo = AdminTokenService::setToken(reset($admin["_id"]));

        //返回登录信息
        $avatar = Config::get('project.default_image.admin_avatar');
        $avatar = FileService::getFileUrl($avatar);
        return [
            'name' => $adminInfo['username'],
            'avatar' => $avatar,
            'role_name' => $adminInfo['role_name'],
            'token' => $adminInfo['token'],
        ];

    }


    /**
     * @notes 退出登录
     * @param $adminInfo
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 令狐冲
     * @date 2021/7/5 14:34
     */
    public function logout($adminInfo)
    {
        //token不存在，不注销
        if (!isset($adminInfo['token'])) {
            return false;
        }

        //设置token过期
        return AdminTokenService::expireToken($adminInfo['token']);
    }
}
