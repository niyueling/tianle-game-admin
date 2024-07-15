<?php

namespace app\adminapi\lists;


use app\common\enum\FileEnum;
use app\common\lists\ListsSearchInterface;
use app\common\model\File;
use app\common\service\FileService;

class FileLists extends BaseAdminDataLists implements ListsSearchInterface
{
    /**
     * @notes 文件搜索条件
     * @return array
     * @author 张无忌
     * @date 2021/7/29 18:11
     */
    public function setSearch(): array
    {
        return [
            '=' => ['type', 'cid'],
            '%like%' => ['name']
        ];
    }

    /**
     * @notes 获取文件列表
     * @return array
     * @throws @\think\db\exception\DataNotFoundException
     * @throws @\think\db\exception\DbException
     * @throws @\think\db\exception\ModelNotFoundException
     * @author 张无忌
     * @date 2021/7/29 18:11
     */
    public function lists(): array
    {
        $lists = (new File())->field(['id,cid,type,name,uri'])
            ->order('id', 'desc')
            ->where($this->searchWhere)
            ->where(['source'=>FileEnum::SOURCE_BACKSTAGE])
            ->limit($this->limitOffset, $this->limitLength)
            ->select()
            ->toArray();

        foreach ($lists as &$item) {
            $item['url'] = $item['uri'];
            $item['uri'] = FileService::getFileUrl($item['uri']);
        }

        return $lists;
    }

    /**
     * @notes 获取文件数量
     * @return int
     * @author 张无忌
     * @date 2021/7/29 18:11
     */
    public function count(): int
    {
        return (new File())->where($this->searchWhere)->where(['source'=>FileEnum::SOURCE_BACKSTAGE])->count();
    }
}