<?php

namespace app\adminapi\controller\sms;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\sms\SmsConfigLogic;
use app\adminapi\validate\sms\SmsConfigValidate;

/**
 * 短信配置控制器
 * Class SmsConfigController
 * @package app\adminapi\controller\notice
 */
class SmsConfigController extends BaseAdminController
{
    /**
     * @notes 获取短信配置
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/19 11:40
     */
    public function getConfig()
    {
        $result = SmsConfigLogic::getConfig();
        return $this->data($result);
    }

    /**
     * @notes 短信配置
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/24 18:50
     */
    public function setConfig()
    {
        $params = (new SmsConfigValidate())->post()->goCheck('setConfig');
        $result = SmsConfigLogic::setConfig($params);
        return $this->success('设置成功');
    }

    /**
     * @notes 查看短信配置详情
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/19 11:57
     */
    public function detail()
    {
        $params = (new SmsConfigValidate())->goCheck('detail');
        $result = SmsConfigLogic::detail($params);
        return $this->data($result);
    }

}