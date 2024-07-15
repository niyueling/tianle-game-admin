<?php

namespace app\adminapi\lists;

use app\common\lists\BaseDataLists;

abstract class BaseAdminDataLists extends BaseDataLists
{
    protected array $adminInfo;
    protected string $adminId;


    public function __construct()
    {
        parent::__construct();


        $this->adminInfo = $this->request->adminInfo;
        $this->adminId = $this->request->adminId;

    }


}