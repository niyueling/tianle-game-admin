<?php

namespace app\adminapi\controller\settings\pay;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\settings\PayConfigLists;
use app\adminapi\logic\settings\pay\PayConfigLogic;
use app\adminapi\validate\settings\pay\PayConfigValidate;

class PayConfigController extends BaseAdminController
{
    /**
     * @notes 设置支付配置
     * @return \think\response\Json
     * @author ljj
     * @date 2021/7/27 5:29 下午
     */
    public function setConfig()
    {
        $params = (new PayConfigValidate())->post()->goCheck();
        (new PayConfigLogic())->setConfig($params);
        return $this->success('设置成功',[],1,1);
    }

    /**
     * @notes 查看支付配置
     * @return \think\response\Json
     * @author ljj
     * @date 2021/7/27 5:36 下午
     */
    public function getConfig()
    {
        $id = (new PayConfigValidate())->goCheck('get');
        $result = (new PayConfigLogic())->getConfig($id);
        return $this->success('获取成功',$result);
    }

    /**
     * @notes 查看支付配置列表
     * @return \think\response\Json
     * @author ljj
     * @date 2021/7/31 2:23 下午
     */
    public function lists()
    {
        return $this->dataLists(new PayConfigLists());
    }
}