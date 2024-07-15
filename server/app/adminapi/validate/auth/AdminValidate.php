<?php

namespace app\adminapi\validate\auth;

use app\common\validate\BaseValidate;
use MongoDB\BSON\ObjectId;
use think\facade\Db;

class AdminValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|checkAdmin',
        'account' => 'require|length:1,32|checkUsed',
        'password' => 'require|length:6,32|edit',
        'password_confirm' => 'requireWith:password|confirm',
        'name' => 'require|length:1,16',
        'role_id' => 'require',
        'disable' => 'require|in:0,1',
        'multipoint_login' => 'require|in:0,1',
    ];

    protected $message = [
        'id.require' => '管理员id不能为空',
        'account.require' => '账号不能为空',
        'account.length' => '账号长度须在1-32位字符',
        'password.require' => '密码不能为空',
        'password.length' => '密码长度须在6-32位字符',
        'password_confirm.requireWith' => '确认密码不能为空',
        'password_confirm.confirm' => '两次输入的密码不一致',
        'name.require' => '名称不能为空',
        'name.length' => '名称须在1-16位字符',
        'role_id.require' => '请选择角色',
        'disable.require' => '请选择状态',
        'disable.in' => '状态值错误',
        'multipoint_login.require' => '请选择是否支持多处登录',
        'multipoint_login.in' => '多处登录状态值为误',
    ];

    public function sceneDetail()
    {
        return $this->only(['id']);
    }

    /**
     * @notes 检查账号是否已被使用
     * @param $value
     * @return bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author Tab
     * @date 2021/7/13 11:38
     */
    public function checkUsed($value, $rule, $data)
    {
        $where = [
            ['username', '=', $value]
        ];
        // 编辑的情况，要排除自身ID
        if (isset($data['_id'])) {
            $where[] = ['_id', '<>', new ObjectId($data['_id'])];
        }
        $admins = Db::connect('mongodb')->table('gms')->where($where)->select()->toArray();
        if ($admins) {
            return '账号已被占用';
        }
        return true;
    }

    /**
     * @notes 检查指定管理员是否存在
     * @param $value
     * @return bool|string
     * @author Tab
     * @date 2021/7/13 11:39
     */
    public function checkAdmin($value)
    {
        $admin = Db::connect('mongodb')->table('gms')->where("_id", new ObjectId($value));
        if (empty($admin)) {
            return '管理员不存在';
        }
        return true;
    }
}