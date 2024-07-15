<?php

namespace app\adminapi\logic;

use app\common\logic\BaseLogic;
use app\common\service\ConfigService;

class ShopSettingLogic extends BaseLogic
{
    /**
     * @notes 获取政策协议
     * @return array
     * @author Tab
     * @date 2021/7/28 16:08
     */
    public static function getPolicyAgreement()
    {
        $config = [
            'recharge_agreement_name' => ConfigService::get('shop', 'recharge_agreement_name', ''),
            'recharge_agreement_content' => ConfigService::get('shop', 'recharge_agreement_content', ''),
        ];

        return $config;
    }

    /**
     * @notes 设置政策协议
     * @param $params
     * @author Tab
     * @date 2021/7/28 16:13
     */
    public static function setPolicyAgreement($params)
    {
        if(isset($params['recharge_agreement_name']) && isset($params['recharge_agreement_content'])) {
            ConfigService::set('shop', 'recharge_agreement_name', $params['recharge_agreement_name']);
            ConfigService::set('shop', 'recharge_agreement_content', $params['recharge_agreement_content']);
        }
    }
}