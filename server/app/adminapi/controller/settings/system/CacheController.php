<?php

namespace app\adminapi\controller\settings\system;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\settings\system\CacheLogic;

/**
 * 系统缓存
 * Class CacheController
 * @package app\adminapi\controller\settings\system
 */
class CacheController extends BaseAdminController
{
    /**
     * @notes 清除系统缓存
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/13 15:35
     */
    public function clear()
    {
         CacheLogic::clear();
         return $this->success('清除成功');
    }
}