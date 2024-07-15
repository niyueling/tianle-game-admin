<?php

namespace app\adminapi\controller\sms;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\sms\NoticeSettingLists;
use app\adminapi\logic\sms\NoticeLogic;
use app\adminapi\validate\sms\NoticeValidate;

/**
 * 通知控制器
 * Class NoticeController
 * @package app\adminapi\controller\notice
 */
class NoticeController extends BaseAdminController
{
    /**
     * @notes 查看通知设置列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/18 16:00
     */
    public function settingLists()
    {
        return $this->dataLists(new NoticeSettingLists());
    }

    /**
     * @notes 查看通知设置详情
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/18 18:40
     */
    public function detail()
    {
        $params = (new NoticeValidate())->goCheck('detail');
        $result = NoticeLogic::detail($params);
        return $this->data($result);
    }

    /**
     * @notes 通知设置
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/18 18:00
     */
    public function set()
    {
        $params = $this->request->post();
        $result = NoticeLogic::set($params);
        if($result) {
            return $this->success('设置成功');
        }
        return $this->fail(NoticeLogic::getError());
    }
}