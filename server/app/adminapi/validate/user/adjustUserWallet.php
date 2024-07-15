<?php

namespace app\adminapi\validate\user;
use app\common\validate\BaseValidate;
use MongoDB\BSON\ObjectId;
use think\facade\Db;

/**
 * 调整用户钱包验证器
 * Class AdjustUserEarnings
 * @package app\adminapi\validate\user
 */
class adjustUserWallet extends BaseValidate
{
    protected $rule = [
        'user_id'   => 'require',
        'type'      => 'require|in:1,2,3,4,5',
        'action'    => 'require|in:0,1',
        'num'       => 'require|gt:0|checkNum',
        'remark'    => 'max:128',
    ];
    protected $message = [
        'id.require'        => '请选择用户',
        'type.require'      => '请选择变动类型',
        'type.in'           => '变动类型错误',
        'action.require'    => '请选择调整类型',
        'action.in'         => '调整类型错误',
        'num.require'       => '请输入调整数量',
        'num.gt'            => '调整数量必须大于零',
        'remark'            => '备注不可超过128个符号',
    ];


    protected function checkNum($vaule,$rule,$data){

        $user = Db::connect('mongodb')->table('players')->where("_id", new ObjectId($data["user_id"]))->find();

        if(empty($user)){
            return '用户不存在';
        }

        if(1 == $data['action']){
            return true;
        }
        switch ($data['type']){
            case 1:
                $surplusMoeny = $user["diamond"] - $vaule;
                if($surplusMoeny < 0){
                    return '用户可用钻石仅剩'.$user["diamond"];
                }
                break;
            case 2:
                $surplusMoeny = $user["gold"] - $vaule;
                if($surplusMoeny < 0){
                    return '用户可用金币仅剩'.$user["gold"];
                }
                break;
            case 5:
                $surplusMoeny = $user["voucher"] - $vaule;
                if($surplusMoeny < 0){
                    return '用户可用代金券仅剩'.$user["voucher"];
                }
                break;
        }

        return true;
    }


}
