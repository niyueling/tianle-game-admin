<?php

namespace app\adminapi\controller\marketing;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\marketing\RateLogic;

/**
 * 救助管理
 * Class CenterController
 * @package app\adminapi\controller\data
 */
class RateController extends BaseAdminController
{
    /**
     * @notes 救助概览
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/27 10:28
     */
    public function index()
    {
        $params = $this->request->get();
        $result = RateLogic::index($params);
        return $this->data($result);
    }

    /**
     * @notes  获取救助设置
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/10 17:00
     */
    public function rule()
    {
        $result = RateLogic::rule();
        return $this->data($result);
    }

    /**
     * @notes 救助设置
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/10 18:13
     */
    public function setRule()
    {
        $params = $this->request->post();
        $result = RateLogic::setRule($params);
        if($result) {
            return $this->success('设置成功');
        }
        return $this->fail(RateLogic::getError());
    }

    /**
     * @notes  救助记录
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/10 17:00
     */
    public function record()
    {
        $result = RateLogic::record($this->request->get());
        return $this->data($result);
    }
}
