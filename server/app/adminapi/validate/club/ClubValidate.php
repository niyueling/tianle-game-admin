<?php

namespace app\adminapi\validate\club;

use app\common\{service\sms\SmsDriver, validate\BaseValidate};
use think\facade\Db;
use \MongoDB\BSON\ObjectId;

class ClubValidate extends BaseValidate
{
    protected $rule = [
        'club_id'   => 'require|checkUser',
        'value'     => 'require',
        'type'  => 'require'
    ];

    protected $message = [
        'club_id.require'   => '请选择用户',
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

    //获取用户信息
    public function sceneFans()
    {
        return $this->only(['user_id']);
    }

    public function sceneInfo()
    {
        return $this->only(['user_id']);
    }

    public function sceneUserInviterLists()
    {
        return $this->only(['user_id']);
    }

    public function sceneAdjustFirstLeaderInfo()
    {
        return $this->only(['user_id']);
    }

    public function sceneAdjustFirstLeader()
    {
        return $this->only(['user_id', 'type']);
    }



    //用户验证
    public function checkUser($value,$rule,$data)
    {
        $userIds = is_array($value) ? $value : [$value];

        foreach ($userIds as $item) {
            $club = Db::connect('mongodb')->table('clubs')->where("_id", new ObjectId($item))->find();
            if(empty($club)) {
                return '俱乐部不存在！';
            }
        }
        return true;
    }

    //验证是否可更新信息
    public function checkField($value, $rule, $data)
    {
        $allowField = ['name', 'disable'];
        if (!in_array($value, $allowField)) {
            return '俱乐部信息不允许更新';
        }

        if($value == "club_id") {
            $club = Db::connect('mongodb')->table('clubs')->where("shortId", intval($data["value"]))->find();
            if(empty($club)) return '俱乐部不存在';
        }

        return true;
    }
}