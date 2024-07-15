<?php

namespace app\adminapi\validate\auth;
use app\adminapi\logic\auth\AuthLogic;
use app\common\{
    model\Role,
    validate\BaseValidate
};

/**
 * 角色验证器
 * Class RoleValidate
 * @package app\adminapi\validate\auth
 */
class RoleValidate extends BaseValidate
{
    protected $rule = [
        'id'        => 'require|checkRole',
        'name'      => 'require|max:64|unique:'.Role::class.',name',
        'auth_keys' => 'array|checkAuth',
    ];

    protected $message = [
        'id.require'        => '请选择角色',
        'name.require'      => '请输入角色名称',
        'name.max'          => '角色名称最长为16个字符',
        'name.unique'       => '角色名称已存在',
        'auth_keys.array'   => '权限格式错误'
    ];

    public function sceneAdd()
    {
        return $this->only(['name', 'auth_keys']);
    }

    public function sceneDetail()
    {
        return $this->only(['id']);
    }

    public function sceneDel()
    {
        return $this->only(['id'])
            ->append('id', 'checkAdmin');
    }

    //验证角色是否存在
    public function checkRole($value,$rule,$data)
    {
        if (!Role::find($value)) {
            return '角色不存在';
        }
        return true;
    }

    //验证角色是否被使用
    public function checkAdmin($value,$rule,$data)
    {
        return true;
    }

    //验证权限数据是否完整
    public function checkAuth($value,$rule,$data)
    {
        $configAuth = AuthLogic::getAuth();

        foreach ($value as $postAuth){
            $keyList = explode('/',$postAuth);
            if(empty($keyList)){
                print_r($keyList);
                return '权限数据错误1';
            }
            $keys = explode('.',$keyList[1] ?? '');
            if(count($keys) < 2){
                print_r($keys);
                return'权限数据错误2';
            }

            $auth = $configAuth[$keyList[0]][$keys[0]][$keys[1]] ?? [];
            if(empty($auth)){
                var_dump($keyList[0], $keys[0], $keys[1]);
                print_r($auth);
                return '权限数据错误3';
            }
        }
        return true;

    }
}
