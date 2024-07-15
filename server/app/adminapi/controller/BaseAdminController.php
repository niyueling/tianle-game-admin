<?php

declare (strict_types=1);

namespace app\adminapi\controller;

use app\common\controller\BaseLikeShopController;
use think\App;

class BaseAdminController extends BaseLikeShopController
{
    protected string $adminId = "";
    protected array $adminInfo = [];


    public function initialize()
    {

        if (isset($this->request->adminInfo) && $this->request->adminInfo) {
            $this->adminInfo = $this->request->adminInfo;
            $this->adminId = $this->request->adminInfo['admin_id'];
        }
    }


}