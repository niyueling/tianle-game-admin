<?php

namespace app\adminapi\lists\auth;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\lists\ListsExcelInterface;
use app\common\model\Admin;
use app\common\model\Role;

/**
 * 角色列表
 * Class RoleLists
 * @package app\adminapi\lists\auth
 */
class RoleLists extends BaseAdminDataLists implements ListsExcelInterface
{
    /**
     * @notes 导出字段
     * @return string[]
     * @author Tab
     * @date 2021/9/22 18:52
     */
    public function setExcelFields(): array
    {
        return [
            'name' => '角色名称',
            'desc' => '备注',
            'create_time' => '创建时间'
        ];
    }

    /**
     * @notes 导出表名
     * @return string
     * @author Tab
     * @date 2021/9/22 18:52
     */
    public function setFileName(): string
    {
        return '角色表';
    }

    /**
     * @notes 角色列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/25 18:00
     */
    public function lists(): array
    {
        $lists = Role::field('id,name,desc,create_time')
            ->limit($this->limitOffset, $this->limitLength)
            ->order('id', 'desc')
            ->select()
            ->toArray();

        return $lists;
    }

    /**
     * @notes 总记录数
     * @return int
     * @author Tab
     * @date 2021/7/13 11:26
     */
    public function count(): int
    {
        return Role::count();
    }
}