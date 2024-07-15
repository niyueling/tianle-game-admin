<?php

namespace app\adminapi\validate\user;

use app\common\validate\BaseValidate;
use MongoDB\BSON\ObjectId;
use think\facade\Db;

class UserValidate extends BaseValidate
{
    protected $rule = [
        'user_id'   => 'require|checkUser',
        'field'     => 'require|checkField',
        'type'  => 'require'
    ];

    protected $message = [
        'user_id.require'   => '请选择用户',
        'field.require'     => '请选择操作',
        'value.require'     => '请输入内容',
        'type.require'     => '请选择调整方式',
    ];

    public function sceneDetail()
    {
        return $this->only(['user_id']);
    }

    //设置黑名单
    public function sceneSetInfo()
    {
        return $this->only(['user_id', 'field', 'value']);
    }

    public function sceneInfo()
    {
        return $this->only(['user_id']);
    }

    //用户验证
    public function checkUser($value,$rule,$data)
    {
        $userIds = is_array($value) ? $value : [$value];

        foreach ($userIds as $item) {
            $user = Db::connect('mongodb')->table('players')->where("_id", new ObjectId($item))->find();
            if(empty($user)) {
                return '用户不存在！';
            }
        }
        return true;
    }
    //验证是否可更新信息
    public function checkField($value, $rule, $data)
    {
        $allowField = ['nickname', 'sex', 'phone'];

        if (!in_array($value, $allowField)) {
            return '用户信息不允许更新';
        }

        return true;
    }
}
