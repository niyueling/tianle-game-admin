<?php

namespace app\adminapi\controller\settings\admin;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\settings\admin\AdminSettingLogic;
use app\adminapi\validate\settings\admin\AdminSettingsValidate;

/**
 * 后台设置控制器
 * Class ShopSettingController
 * @package app\adminapi\controller\settings\shop
 */
class AdminSettingController extends BaseAdminController
{
    public array $notNeedLogin = ['getPolicyAgreement'];
    /**
     * @notes 获取后台基本信息
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/28 14:17
     */
    public function getShopInfo()
    {
        $result = AdminSettingLogic::getShopInfo();
        return $this->data($result);
    }

    /**
     * @notes 设置后台基本信息
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/28 14:42
     */
    public function setShopInfo()
    {
        $params = (new AdminSettingsValidate())->post()->goCheck('setShopInfo');
        AdminSettingLogic::setShopInfo($params);
        return $this->success('设置成功');
    }

    /**
     * @notes 获取备案信息
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/28 15:01
     */
    public function getRecordInfo()
    {
        $result = AdminSettingLogic::getRecordInfo();
        return $this->data($result);
    }

    /**
     * @notes 设置备案信息
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/28 15:22
     */
    public function setRecordInfo()
    {
        $params = $this->request->post();
        AdminSettingLogic::setRecordInfo($params);
        return $this->success('设置成功');
    }

    /**
     * @notes 获取政策协议
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/28 16:06
     */
    public function getPolicyAgreement()
    {
        $result = AdminSettingLogic::getPolicyAgreement();
        return $this->data($result);
    }

    /**
     * @notes 设置政策协议
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/28 16:11
     */
    public function setPolicyAgreement()
    {
        $params = $this->request->post();
        AdminSettingLogic::setPolicyAgreement($params);
        return $this->success('设置成功');
    }
}
