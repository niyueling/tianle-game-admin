<?php

namespace app\adminapi\controller;

use app\adminapi\logic\AccountLogic;
use app\adminapi\validate\AccountValidate;

class AccountController extends BaseAdminController
{
    public array $notNeedLogin = ['login', 'logout'];
    /**
     * @notes 账号登录
     * @date 2023/02/10 13:17
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     */
    public function login()
    {
        $params = (new AccountValidate())->post()->goCheck();
        return $this->data((new AccountLogic())->login($params));
    }

    /**
     * @notes 退出登录
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author zhou
     * @date 2023/02/10 13:17
     */
    public function logout()
    {
        //退出登录情况特殊，只有成功的情况，也不需要token验证
        (new AccountLogic())->logout($this->adminInfo);
        return $this->success();
    }
}