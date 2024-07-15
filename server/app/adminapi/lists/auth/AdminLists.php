<?php

namespace app\adminapi\lists\auth;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\lists\ListsExcelInterface;
use app\common\lists\ListsExtendInterface;
use app\common\lists\ListsSearchInterface;
use app\common\lists\ListsSortInterface;
use think\facade\Db;

class AdminLists extends BaseAdminDataLists implements ListsExtendInterface, ListsSearchInterface, ListsSortInterface, ListsExcelInterface
{
    /**
     * @notes 设置导出字段
     * @return string[]
     * @author 令狐冲
     * @date 2021/7/21 16:04
     */
    public function setExcelFields(): array
    {
        return [
            'account' => '账号',
            'name' => '名称',
            'role_name' => '角色',
            'create_time' => '创建时间',
            'login_time' => '最后登录时间',
            'login_ip' => '最后登录IP',
            'disable_desc' => '状态',
        ];
    }

    /**
     * @notes 设置导出文件名
     * @return string
     * @author 令狐冲
     * @date 2021/7/26 17:49
     */
    public function setFileName(): string
    {
        return '管理员列表';
    }

    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author 令狐冲
     * @date 2021/7/13 00:52
     */
    public function setSearch(): array
    {
        return [
            '%like%' => ['name', 'account'],
            '=' => ['role_id']
        ];
    }

    /**
     * @notes 设置支持排序字段
     * @return string[]
     * @author 令狐冲
     * @date 2021/7/15 23:40
     */
    public function setSortFields(): array
    {
        // 格式: ['前端传过来的字段名' => '数据库中的字段名'];
        return ['create_time' => 'createAt', 'id' => '_id'];
    }

    /**
     * @notes 设置默认排序
     * @return string[]
     * @author 令狐冲
     * @date 2021/7/16 00:04
     */
    public function setDefaultOrder(): array
    {
        return ['id' => 'desc'];
    }


    /**
     * @notes 获取管理列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 令狐冲
     * @date 2021/7/13 00:52
     */
    public function lists(): array
    {
        $adminLists = Db::connect('mongodb')->table('gms')->where($this->searchWhere)
            ->limit($this->limitOffset, $this->limitLength)->select()->all();

        //管理员列表增加角色名称
        foreach ($adminLists as $k => $v) {
            if(!empty($v['root']) && $v['root']) $adminLists[$k]['role'] = '超级管理员';
            $adminLists[$k]["_id"] = reset($v["_id"]);
            if(!empty($v['login_time'])) $adminLists[$k]["login_time"] = date('Y-m-d H:i:s', $v['login_time']);
            $adminLists[$k]["is_bind_club"] = !empty($adminLists[$k]["club_id"]) && count($adminLists[$k]["club_id"]) > 0 ? 1 : 0;
        }

        return $adminLists;
    }

    /**
     * @notes 获取数量
     * @return int
     * @author 令狐冲
     * @date 2021/7/13 00:52
     */
    public function count(): int
    {
        return Db::connect('mongodb')->table('gms')->where($this->searchWhere)->count();
    }

    public function extend()
    {
        return [];
    }
}
