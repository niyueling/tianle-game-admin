<?php

namespace app\adminapi\lists\settings;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\model\PayConfig;

class PayConfigLists extends BaseAdminDataLists
{
    /**
     * @notes 查看支付配置列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/7/31 2:18 下午
     */
    public function lists(): array
    {
        $lists = PayConfig::field('id,name,pay_way,icon,sort')
            ->append(['pay_way_name'])
            ->order('sort','asc')
            ->select()
            ->toArray();

        return $lists;
    }

    /**
     * @notes 查看支付配置总数
     * @return int
     * @author ljj
     * @date 2021/7/31 2:18 下午
     */
    public function count(): int
    {
        return PayConfig::count();
    }
}