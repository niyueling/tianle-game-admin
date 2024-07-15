<?php

namespace app\adminapi\controller;

use app\adminapi\logic\auth\AuthLogic;
use app\adminapi\logic\ConfigLogic;

/**
 * 配置控制器
 * Class ConfigController
 * @package app\adminapi\controller
 */
class ConfigController extends BaseAdminController
{
    public array $notNeedLogin = ['getConfig'];

    /**
     * @notes 获取配置
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/19 16:29
     */
    public function getConfig()
    {
        $data = ConfigLogic::getConfig();
        return $this->data($data);
    }

    /**
     * @notes 获取菜单
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/25 17:46
     */
    public function getMenu()
    {
        $auth = AuthLogic::getMenu();
        return $this->data($auth);
    }

    /**
     * @notes 获取权限
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/25 19:32
     */
    public function getAuth()
    {
        $data = ConfigLogic::getAuth($this->adminInfo);
        return $this->data($data);
    }

    /**
     * @notes 获取营销模块接口
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/9/8 17:19
     */
    public function getMarketingModule()
    {
        $data = ConfigLogic::getMarketingModule();
        return $this->data($data);
    }

    /**
     * @notes 获取应用中心模块接口
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/9/24 16:58
     */
    public function getAppModule()
    {
        $data = ConfigLogic::getAppModule();
        return $this->data($data);
    }


}