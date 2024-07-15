<?php

namespace app\adminapi\logic\settings\pay;

use app\common\enum\PayEnum;
use app\common\enum\YesNoEnum;
use app\common\logic\BaseLogic;
use app\common\model\PayConfig;
use app\common\model\PayWay;
use app\common\service\FileService;

class PayWayLogic extends BaseLogic
{
    /**
     * @notes 查看支付方式配置
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/7/28 4:02 下午
     */
    public function getPayWay()
    {
        $pay_way = PayWay::select();
        $pay_way = $pay_way->append(['pay_way_name'])->toArray();
        if (empty($pay_way)) {
            return [];
        }

        $lists = [];
        for ($i=1;$i<=max(array_column($pay_way,'scene'));$i++) {
            foreach ($pay_way as $val) {
                if ($val['scene'] == $i) {
                    $val['icon'] = FileService::getFileUrl(PayConfig::where('id',$val['dev_pay_id'])->value('icon'));
                    $lists[$i][] = $val;
                }
            }
        }

        return $lists;
    }

    /**
     * @notes 设置支付方式
     * @param $params
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/7/28 5:23 下午
     */
    public function setPayWay($params)
    {
        $pay_way = new PayWay;
        $data = [];
        foreach ($params as $key=>$value) {
            $is_default = array_column($value,'is_default');
            $is_default_num = array_count_values($is_default);
            $status = array_column($value,'status');
            $scene_name = PayEnum::getPaySceneDesc($key);
            if (!in_array(YesNoEnum::YES,$is_default)) {
                return $scene_name.'支付场景缺少默认支付';
            }
            if ($is_default_num[YesNoEnum::YES] > 1) {
                return $scene_name.'支付场景的默认值只能存在一个';
            }
            if (!in_array(YesNoEnum::YES,$status)) {
                return $scene_name.'支付场景至少开启一个支付状态';
            }

            foreach ($value as $val) {
                $result = PayWay::where('id',$val['id'])->findOrEmpty();
                if ($result->isEmpty()) {
                    continue;
                }
                if ($val['is_default'] == YesNoEnum::YES && $val['status'] == YesNoEnum::NO) {
                    return $scene_name.'支付场景的默认支付未开启支付状态';
                }

                $data[] = [
                    'id' => $val['id'],
                    'is_default' => $val['is_default'],
                    'status' => $val['status'],
                ];
            }
        }
        $pay_way->saveAll($data);
        return true;
    }
}

