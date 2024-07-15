<?php

namespace app\adminapi\lists\sms;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\enum\YesNoEnum;
use app\common\lists\ListsSearchInterface;
use app\common\model\NoticeSetting;

/**
 * 通知设置
 * Class NoticeSettingLists
 * @package app\adminapi\lists\notice
 */
class NoticeSettingLists extends BaseAdminDataLists implements ListsSearchInterface
{
    public function setSearch(): array
    {
        return [
            '=' => ['recipient', 'type']
        ];
    }

    /**
     * @notes 通知设置列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author Tab
     * @date 2021/8/18 16:35
     */
    public function lists(): array
    {
        $lists = NoticeSetting::field('*,type as type_desc,recipient as recipient_desc')
            ->where($this->searchWhere)
            ->order([
                'recipient' => 'asc',
                'type' => 'asc',
            ])
            ->select()
            ->toArray();

        foreach($lists as &$item) {
            if(empty($item['system_notice'])) {
                $item['system_notice']['status'] = YesNoEnum::NO;
                $item['sms_notice']['status'] = YesNoEnum::NO;
                $item['oa_notice']['status'] = YesNoEnum::NO;
                $item['mnp_notice']['status'] = YesNoEnum::NO;
            }
        }

        return $lists;
    }

    /**
     * @notes 通知设置数量
     * @return int
     * @author Tab
     * @date 2021/8/18 16:44
     */
    public function count(): int
    {
        $count = NoticeSetting::where($this->searchWhere)->count();

        return $count;
    }
}