<?php

namespace app\adminapi\controller\settings\pay;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\settings\pay\PayWayLogic;

class PayWayController extends BaseAdminController
{
    /**
     * @notes 查看支付方式
     * @return \think\response\Json
     * @author ljj
     * @date 2021/7/28 3:11 下午
     */
    public function getPayWay()
    {
        $result = (new PayWayLogic())->getPayWay();
        return $this->success('获取成功',$result);
    }

    /**
     * @notes 设置支付方式
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/7/28 5:39 下午
     */
    public function setPayWay()
    {
        $params = $this->request->post();
        $result = (new PayWayLogic())->setPayWay($params);
        if (true !== $result) {
            return $this->fail($result);
        }
        return $this->success('设置成功',[],1, 1);
    }
}