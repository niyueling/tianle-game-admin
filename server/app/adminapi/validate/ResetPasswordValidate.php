<?php

namespace app\adminapi\validate;
use app\common\validate\BaseValidate;
use MongoDB\BSON\ObjectId;
use think\facade\Config;
use think\facade\Db;

class ResetPasswordValidate extends BaseValidate
{
    protected $rule = [
        'password'              => 'require|confirm',
        'origin_password'       => 'require|checkPassword',
    ];

    protected $message = [
        'origin_password.require'       => '请输入当前密码',
        'password.require'              => '请输入新密码',
        'password.min'                  => '新密码至少六位数',
        'password.confirm'              => '两次密码输入不一致',
        'password_confirm.require'      => '请输入确认密码',
    ];


    public function checkPassword($value,$rule,$data){
        if($value == $data['password']){
            return '新密码和当前密码一样，请重新输入密码';
        }
        $passwordSalt = Config::get('project.unique_identification');
        $adminInfo = Db::connect('mongodb')
            ->table('gms')
            ->where("_id", new ObjectId($data['admin_id']))
            ->find();

        if ($adminInfo['pass'] !== create_password($value, $passwordSalt)) {
            return '密码错误';
        }
        return true;
    }



}