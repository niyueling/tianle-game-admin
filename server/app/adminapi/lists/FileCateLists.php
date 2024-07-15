<?php

namespace app\adminapi\lists;


use app\common\lists\ListsSearchInterface;
use app\common\model\FileCate;

class FileCateLists extends BaseAdminDataLists implements ListsSearchInterface
{
    /**
     * @notes 文件分类搜素条件
     * @return array
     * @author 张无忌
     * @date 2021/7/29 18:10
     */
    public function setSearch(): array
    {
        return [
            '=' => ['type']
        ];
    }

    /**
     * @notes 获取文件分类列表
     * @return array
     * @throws @\think\db\exception\DataNotFoundException
     * @throws @\think\db\exception\DbException
     * @throws @\think\db\exception\ModelNotFoundException
     * @author 张无忌
     * @date 2021/7/29 18:10
     */
    public function lists(): array
    {
        $lists = (new FileCate())->field(['id,pid,type,name'])
            ->where($this->searchWhere)
            ->select()->toArray();

        return linear_to_tree($lists, 'children');
    }

    /**
     * @notes 获取文件分类数量
     * @return int
     * @author 张无忌
     * @date 2021/7/29 18:11
     */
    public function count(): int
    {
        return (new FileCate())->where($this->searchWhere)->count();
    }
}