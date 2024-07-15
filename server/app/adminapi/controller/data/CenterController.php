<?php

namespace app\adminapi\controller\data;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\data\CenterLogic;

/**
 * 数据中心
 * Class CenterController
 * @package app\adminapi\controller\data
 */
class CenterController extends BaseAdminController
{
    /**
     * @notes 房间分析
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function trafficAnalysis()
    {
        $params = $this->request->get();
        $result = CenterLogic::trafficAnalysis($params);
        return $this->data($result);
    }

    /**
     * @notes 用户分析
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 11:18
     */
    public function userAnalysis()
    {
        $params = $this->request->get();
        $result = CenterLogic::userAnalysis($params);
        return $this->data($result);
    }

    /**
     * @notes 充值分析
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 15:00
     */
    public function rechargeAnalysis()
    {
        $params = $this->request->get();
        $result = CenterLogic::rechargeAnalysis($params);
        return $this->data($result);
    }

    /**
     * @notes 消耗分析
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 15:00
     */
    public function consumeAnalysis()
    {
        $params = $this->request->get();
        $result = CenterLogic::consumeAnalysis($params);
        return $this->data($result);
    }

    /**
     * @notes 访客分析
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 15:00
     */
    public function visitorAnalysis()
    {
        $params = $this->request->get();
        $result = CenterLogic::visitorAnalysis($params);
        return $this->data($result);
    }
}