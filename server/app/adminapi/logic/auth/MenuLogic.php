<?php

namespace app\adminapi\logic\auth;


use app\common\{
    model\DevAuth,
    logic\BaseLogic,
    enum\DefaultEnum
};

/**
 * 菜单逻辑层
 * Class MenuLogic
 * @package app\adminapi\logic\auth
 */
class MenuLogic extends BaseLogic
{
    /**
     * @notes 添加菜单权限
     * @param $params
     * @return bool
     * @author ljj
     * @date 2021/8/20 9:06 下午
     */
    public function add(array $params)
    {

        $menuId = DevAuth::order('id desc')->value('id');
        $menu = new DevAuth;
        $menu->id       = $menuId+1;
        $menu->name     = $params['name'];
        $menu->uri      = $params['uri'];
        $menu->type     = $params['type'];
        $menu->pid      = $params['pid'];
        $menu->alias    = $params['alias'];
        $menu->icon     = $params['icon'] ?? '';
        $menu->sort     = $params['sort'] ?? DefaultEnum::SORT;
        $menu->disable  = $params['disable'];
        return $menu->save();
    }

    /**
     * @notes 编辑菜单权限
     * @param array $params
     * @return DevAuth
     * @author cjhao
     * @date 2021/8/23 17:21
     */
    public function edit(array $params)
    {
        return DevAuth::update($params,[],['name','uri','type','pid','alias','icon','sort','disable']);

    }

    /**
     * @notes 删除菜单
     * @param int $id
     * @return bool
     * @author cjhao
     * @date 2021/8/23 17:28
     */
    public function del(int $id)
    {
        DevAuth::where(['id'=>$id])->delete();
        return true;
    }
}