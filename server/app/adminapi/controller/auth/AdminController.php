<?php

namespace app\adminapi\controller\auth;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\validate\auth\AdminValidate;
use app\adminapi\logic\auth\AdminLogic;
use app\adminapi\lists\auth\AdminLists;
use app\adminapi\validate\ResetPasswordValidate;

class AdminController extends BaseAdminController
{
    /**
     * @notes 查看管理员列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/13 11:32
     */
    public function lists()
    {
        return $this->dataLists(new AdminLists());
    }

    /**
     * @notes 查看管理员详情
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/13 11:43
     */
    public function detail()
    {
        $params = (new AdminValidate())->goCheck('detail');
        $result = AdminLogic::detail($params);
        return $this->data($result);
    }

    /**
     * @notes 获取管理员的基本信息
     * @return \think\response\Json
     * @author zhou
     * @date 2023/02/10 16:38
     */
    public function getAdminInfo()
    {
        $result = AdminLogic::getAdminInfo($this->adminId);
        return $this->data($result);
    }

    /**
     * @notes 修改管理员密码
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/4/21 15:16
     *
     */
    public function resetPassword(){
        $params = (new ResetPasswordValidate())->post()->goCheck(null,['admin_id'=>$this->adminId]);
        $result = AdminLogic::resetPassword($params,$this->adminId);
        if(true === $result){
            return $this->success('密码修改成功',[],1,1);
        }
        return $this->fail($result);
    }
}