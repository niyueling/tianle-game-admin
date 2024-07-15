<?php

namespace app\common\cache;

use app\adminapi\logic\auth\AuthLogic;
use app\common\{model\Admin, model\Role, model\RoleAuthIndex};
use MongoDB\BSON\ObjectId;
use think\facade\Config;
use think\facade\Db;

class AdminAuthCache extends BaseCache
{
    private $prefix = 'admin_auth_';
    private $authConfigList = [];
    private $cacheMd5Key = '';      //权限文件MD5的key
    private $cacheAllKey = '';      //全部权限的key
    private $caheUrlKey = '';       //管理员的url缓存key
    private $cahePageKey = '';      //管理员的page缓存key
    private $authMd5 = '';          //权限文件MD5的值
    private $adminId = '';

    public function __construct($adminId = '')
    {

        parent::__construct();

        $this->adminId = $adminId;
        $this->authConfigList = Config::get('auth');
//        //当前权限配置文件的md5
        $this->authMd5 = md5(json_encode($this->authConfigList));

        $this->cacheMd5Key = $this->prefix.'md5';
        $this->cacheAllKey = $this->prefix.'all';

        $this->caheUrlKey = $this->prefix .'url_'.$this->adminId;
        $this->cahePageKey = $this->prefix .'page_'.$this->adminId;

        $cacheAuthMd5 = $this->get($this->cacheMd5Key);
        $cacheAuth = $this->get($this->cacheAllKey);
        //权限配置文件和缓存的配置文件对比，不一样说明权限配置文件已修改，清理缓存
        if($this->authMd5 !== $cacheAuthMd5 || empty($cacheAuth)){
            $this->deleteTag();
        }

    }


    /**
     * @notes 获取管理权限uri
     * @param $adminId
     * @return array|mixed
     * @author 令狐冲
     * @date 2021/8/19 15:27
     */
    public function getAdminUri()
    {
        //从缓存获取，直接返回
        $urisAuth = $this->get($this->caheUrlKey);

        if ($urisAuth) {
            return $urisAuth;
        }

        //获取角色所有权限id
        $roleAuthKeys = $this->getKeyAuth($this->adminId);
        if (empty($roleAuthKeys)) {
            return [];
        }
        //获取角色权限uri
        $urisAuth = AuthLogic::getAuth($roleAuthKeys);
        $this->set($this->caheUrlKey, $urisAuth, 3600);

        //保存到缓存并读取返回
        return $urisAuth;
    }



    /**
     * @notes 获取全部权限uri
     * @return array|mixed
     * @author cjhao
     * @date 2021/9/13 11:41
     */
    public function getAllUri()
    {
        $cacheAuth = $this->get($this->cacheAllKey);

        if($cacheAuth){
            return $cacheAuth;
        }

        foreach ($this->authConfigList as $authKey => $authConfig){

            foreach ($authConfig as $authValList){
                array_shift($authValList);

                foreach ($authValList as $authVal){
                    $buttonAuth = $authVal['button_auth'] ?? [];
                    $actionAuth = $authVal['action_auth'] ?? [];
                    $authList = [
                        'button_auth'   => array_merge($authList['button_auth'] ?? [],$buttonAuth),
                        'action_auth'   => array_merge($authList['action_auth'] ?? [],$actionAuth),
                    ];
                }
            }
        }
        //保存到缓存并读取返回
        $this->set($this->cacheMd5Key, $this->authMd5, null);
        $this->set($this->cacheAllKey, $authList, null);
        return $authList;
    }


    /**
     * @notes 获取管理员页面权限
     * @param int $adminId
     * @return array
     * @author cjhao
     * @date 2021/10/13 17:43
     */
    public function getAdminPageAuth($role_name):array
    {

        //从缓存获取，直接返回
        $pageAuth = $this->get($this->cahePageKey);
        if($pageAuth){
            return $pageAuth;
        }

        $roleAuthKeys = $this->getKeyAuth($this->adminId);
        if (empty($roleAuthKeys)) {
            return [];
        }

        $authConfigList = [];
        //处理权限数据结构
        foreach ($this->authConfigList as $configKey => $configList){
            foreach ($configList as $authKey => $authVal){
                $pagePathList = array_shift($authVal);
                if(is_string($pagePathList)){
                    $pagePathList = [$pagePathList];
                }
                $pagePath = current($pagePathList);
                $buttonAuth = [];
                foreach ($authVal as $key => $auth ){

                    $authKeys = $configKey.'/'.$authKey.'.'.$key;

                    if(in_array($authKeys,$roleAuthKeys)){
                        $buttonAuth = array_merge($buttonAuth,$auth['button_auth']);
                    }
                }
                //相同的接口分开。
                $authConfigList[$pagePath] = $buttonAuth;
                foreach ($pagePathList as $pagePath){
                    $authConfigList[$pagePath] = $buttonAuth;
                }
            }
        }

        //设置缓存
        $this->set($this->cahePageKey, $authConfigList, 3600);
        //保存到缓存并读取返回
        return $authConfigList;
    }


    /**
     * @notes 获取管理员权限
     * @param $adminId
     * @return array
     * @author cjhao
     * @date 2021/10/13 17:42
     */
    public function getKeyAuth(string $adminId):array
    {
        $admin = Db::connect('mongodb')
            ->table('gms')
            ->where("_id", new ObjectId($adminId))
            ->find();
        $role = Role::where("name", $admin["role"])->find();
        if(empty($role)){
            return [];
        }
        $admin["role_auth_index"] = RoleAuthIndex::where("role_id",$role["id"])->select()->toArray();
        $roleAuthKeys = array_column($admin['role_auth_index'],'auth_key');
        return $roleAuthKeys;

    }

    /**
     * @notes 清理管理员缓存
     * @return bool
     * @author cjhao
     * @date 2021/10/13 18:47
     */
    public function clearAuthCache()
    {
        $this->clear($this->cahePageKey);
        $this->clear($this->caheUrlKey);
        return true;
    }

}