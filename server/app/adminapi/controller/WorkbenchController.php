<?php

namespace app\adminapi\controller;

use app\adminapi\logic\WorkbenchLogic;

/**
 * 工作台
 * Class WorkbenchCotroller
 * @package app\adminapi\controller
 */
class WorkbenchController extends BaseAdminController
{
    /**
     * @notes 工作台
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/10 14:02
     */
    public function index()
    {

        $result = WorkbenchLogic::index($this->adminInfo, $this->request->get());
        return $this->data($result);
    }
}
