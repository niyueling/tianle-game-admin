<?php

namespace app\adminapi\controller\auth;
use app\adminapi\{
    logic\auth\RoleLogic,
    lists\auth\RoleLists,
    validate\auth\RoleValidate,
    controller\BaseAdminController
};


/**
 * 角色控制器
 * Class RoleController
 * @package app\adminapi\controller\auth
 */
class RoleController extends BaseAdminController
{

    /**
     * @notes 查看角色列表
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/13 10:40
     */
    public function lists()
    {
        return $this->dataLists(new RoleLists());
    }

    /**
     * @notes 添加权限
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/25 16:08
     */
    public function add()
    {
        $params = (new RoleValidate())->post()->goCheck('add');
        $res = (new RoleLogic)->add($params);
        if(true === $res){
            return $this->success('添加成功',[],1,1);
        }
        return $this->fail($res);
    }

    /**
     * @notes 编辑角色
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/13 10:52
     */
    public function edit()
    {
        $params = (new RoleValidate())->post()->goCheck('edit');
        $res = (new RoleLogic)->edit($params);
        if(true === $res){
            return $this->success('编辑成功',[],1,1);
        }
        return $this->fail($res);
    }

    /**
     * @notes 删除角色
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/13 10:53
     */
    public function delete()
    {
        $params = (new RoleValidate())->post()->goCheck('del');
        (new RoleLogic)->delete($params['id']);
        return $this->success('删除成功');
    }

    /**
     * @notes 查看角色详情
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/13 11:10
     */
    public function detail()
    {
        $params = (new RoleValidate())->goCheck('detail');
        $detail = (new RoleLogic)->detail($params['id']);
        return $this->data($detail);
    }


}