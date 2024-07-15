<?php

declare(strict_types=1);

namespace app\common\controller;


use app\BaseController;
use app\common\lists\BaseDataLists;
use app\common\service\JsonService;

class BaseLikeShopController extends BaseController
{

    public array $notNeedLogin = [];

    /**
     * @notes 操作成功
     * @param string $msg
     * @param array $data
     * @param int $code
     * @param int $show
     * @return \think\response\Json
     * @author 令狐冲
     * @date 2021/7/8 00:39
     */
    protected function success(string $msg = 'success', array $data = [], int $code = 1, int $show = 0)
    {
        return JsonService::success($msg, $data, $code, $show);
    }

    /**
     * @notes 数据返回
     * @param $data
     * @return \think\response\Json
     * @author 令狐冲
     * @date 2021/7/8 11:13
     */
    protected function data($data)
    {
        return JsonService::data($data);
    }


    /**
     * @notes 列表数据返回
     * @param \app\common\lists\BaseDataLists|null $lists
     * @return \think\response\Json
     * @author 令狐冲
     * @date 2021/7/8 00:40
     */
    protected function dataLists(BaseDataLists $lists = null)
    {

        return JsonService::dataLists($lists);
    }

    /**
     * @notes 操作失败
     * @param string $msg
     * @param array $data
     * @param int $code
     * @param int $show
     * @return \think\response\Json
     * @author 令狐冲
     * @date 2021/7/8 00:39
     */
    protected function fail(string $msg = 'fail', array $data = [], int $code = 0, int $show = 1)
    {
        return JsonService::fail($msg, $data, $code, $show);
    }


    /**
     * @notes 是否免登录验证
     * @return bool
     * @author 令狐冲
     * @date 2021/8/3 14:36
     */
    public function isNotNeedLogin()
    {

        $notNeedLogin = $this->notNeedLogin;
        if (empty($notNeedLogin)) {
            return false;
        }
        $action = $this->request->action();

        if (!in_array(trim($action), $notNeedLogin)) {
            return false;
        }
        return true;
    }


}