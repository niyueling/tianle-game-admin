<?php

namespace app\adminapi\validate\club;
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
class adjustClubWallet extends BaseValidate
{
    protected $rule = [
        '_id'   => 'require',
        'action'    => 'require|in:0,1',
        'num'       => 'require|gt:0|checkNum',
        'remark'    => 'max:128',
    ];
    protected $message = [
        'id.require'        => '请选择用户',
        'action.require'    => '请选择调整类型',
        'action.in'         => '调整类型错误',
        'num.require'       => '请输入调整数量',
        'num.gt'            => '调整余额必须大于零',
        'remark'            => '备注不可超过128个符号',
    ];


    protected function checkNum($vaule,$rule,$data){

        $member = Db::connect('mongodb')->table('clubmembers')->where("_id", new ObjectId($data['_id']))->find();

        if(empty($member)){
            return '俱乐部成员不存在';
        }

        if(1 == $data['action']){
            return true;
        }
        $surplusMoeny = $member["clubGold"] - $vaule;
        if($surplusMoeny < 0){
            return '成员战队金币仅剩'.$member["clubGold"];
        }

        return true;
    }


}