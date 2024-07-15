<?php

namespace app\adminapi\validate\agency;
use app\common\{
    validate\BaseValidate
};
use think\facade\Db;
use \MongoDB\BSON\ObjectId;

/**
 * 调整用户钱包验证器
 * Class AdjustUserEarnings
 * @package app\adminapi\validate\user
 */
class adjustAgencyWallet extends BaseValidate
{
    protected $rule = [
        'user_id'   => 'require',
        'type'      => 'require|in:1,2,3',
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
        'num.gt'            => '调整余额必须大于零',
        'remark'            => '备注不可超过128个符号',
    ];


    protected function checkNum($vaule,$rule,$data){

        $admin = Db::connect('mongodb')->table('gms')->where("_id", new ObjectId($data['user_id']))->find();

        if(empty($admin)){
            return '用户不存在';
        }

        if(1 == $data['action']){
            return true;
        }
        switch ($data['type']){
            case 1:
                $surplusMoeny = $admin["gem"] - $vaule;
                if($surplusMoeny < 0){
                    return '代理钻石仅剩'.$admin["gem"];
                }
                break;
            case 2:
                $surplusMoeny = $admin["gold"] - $vaule;
                if($surplusMoeny < 0){
                    return '用户金币仅剩'.$admin["gold"];
                }
                break;
        }

        return true;
    }


}
