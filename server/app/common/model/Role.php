<?php


namespace app\common\model;
use think\model\concern\SoftDelete;

/**
 * 角色模型
 * Class Role
 * @package app\common\model
 */
class Role extends BaseModel
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

    public function roleAuthIndex()
    {
        return $this->hasMany(RoleAuthIndex::class,'role_id');

    }
}