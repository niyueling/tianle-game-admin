<?php

namespace app\adminapi\validate\agency;

use app\common\{service\sms\SmsDriver, validate\BaseValidate};
use think\facade\Db;
use \MongoDB\BSON\ObjectId;

class AgencyValidate extends BaseValidate
{
    protected $rule = [
        'user_id'   => 'require|checkUser',
        'user_ids'  => 'require|array',
        'label_ids' => 'require|array',
        'field'     => 'require|checkField',
        'type'  => 'require'
    ];

    protected $message = [
        'user_id.require'   => '请选择用户',
        'user_ids.require'  => '请选择用户',
        'user_ids.array'    => '用户数据错误',
        'field.require'     => '请选择操作',
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
            $admin = Db::connect('mongodb')->table('gms')->where("_id", new ObjectId($item))->find();
            if(empty($admin)) {
                return '代理不存在！';
            }
        }
        return true;
    }
    //验证是否可更新信息
    public function checkField($value, $rule, $data)
    {
        $allowField = ['username', 'disable', "pass", "club_id", "partner"];
        if (!in_array($value, $allowField)) {
            return '用户信息不允许更新';
        }

        if($value == "club_id") {
            $admin = Db::connect('mongodb')->table('gms')->where("_id", new ObjectId($data["user_id"]))->find();
            if(!empty($admin["club_id"]) && is_array($admin["club_id"]) && in_array($data["value"], $admin["club_id"])) return '俱乐部已绑定，请勿重复操作';
            if(!empty($admin["club_id"]) && count($admin["club_id"]) >= 3) return '每个代理最多可绑定三个俱乐部';

            $club = Db::connect('mongodb')->table('clubs')->where("shortId", intval($data["value"]))->find();
            if(empty($club)) return '俱乐部不存在';
            if($club["state"] == "off") return '俱乐部已解散';

            $smsDriver = new SmsDriver();
            $result = $smsDriver->verify($data['phone'], $data['code']);
            if($result) {
                return true;
            }
            return '验证码错误';
        }

        return true;
    }
}