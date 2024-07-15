<?php

namespace app\adminapi\logic\auth;
use app\common\{cache\AdminAuthCache, model\RoleAuthIndex, model\Role, logic\BaseLogic};
use think\facade\Config;
use think\facade\Db;

/**
 * 角色逻辑层
 * Class RoleLogic
 * @package app\adminapi\logic\auth
 */
class RoleLogic extends BaseLogic
{

    /**
     * @notes 添加角色
     * @param $params
     * @author cjhao
     * @date 2021/8/25 16:08
     */
    public function add(array $params)
    {
        Db::startTrans();
        try{

            $authKeys = $params['auth_keys'];
            //处理规格值
            array_walk($authKeys, function (&$auth){
                $auth = ['auth_key'=>$auth];
            });

            $role = new Role();
            $role->name = $params['name'];
            $role->desc = $params['desc'];
            $role->save();
            $role->roleAuthIndex()->saveAll($authKeys);

            Db::commit();
            return true;

        } catch (\Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }

    }

    /**
     * @notes 编辑角色
     * @param array $params
     * @return bool|string
     * @author cjhao
     * @date 2021/8/25 20:47
     */
    public function edit(array $params)
    {
        Db::startTrans();
        try{

            $authKeys = $params['auth_keys'];
            //处理规格值
            array_walk($authKeys, function (&$auth){
                $auth = ['auth_key'=>$auth];
            });

            $role = Role::find($params['id']);
            RoleAuthIndex::where(['role_id'=>$params['id']])->delete();

            $role->name = $params['name'];
            $role->desc = $params['desc'];
            $role->save();
            $role->roleAuthIndex()->saveAll($authKeys);
            (new AdminAuthCache())->deleteTag();
            Db::commit();
            return true;

        } catch (\Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }

    }

    /**
     * @notes 删除角色
     * @param int $id
     * @return bool
     * @author cjhao
     * @date 2021/8/25 20:47
     */
    public static function delete(int $id)
    {
        Role::destroy(['id'=>$id]);
        (new AdminAuthCache())->deleteTag();
        return true;
    }


    /**
     * @notes 角色详情
     * @param int $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/25 17:58
     */
    public function detail(int $id):array
    {
        $detail = Role::field('id,name,desc')
            ->find($id);
        $authList = $detail->roleAuthIndex()->select()->toArray();
        $authKeys = array_column($authList,'auth_key');
        $detail->auth_keys = $authKeys;
        return $detail->toArray();
    }


}