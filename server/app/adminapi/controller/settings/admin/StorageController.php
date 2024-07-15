<?php

namespace app\adminapi\controller\settings\admin;


use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\settings\admin\AdminStorageLogic;
use app\adminapi\validate\settings\admin\StorageValidate;
use think\response\Json;

/**
 * 存储设置控制器
 * Class StorageController
 * @package app\adminapi\controller\settings\shop
 */
class StorageController extends BaseAdminController
{

    /**
     * @notes 存储引擎列表
     * @return Json
     * @author suny
     * @date 2021/9/9 4:53 下午
     */
    public function lists()
    {

        return $this->success('获取成功', AdminStorageLogic::lists());
    }

    /**
     * @notes 存储配置信息
     * @return Json
     * @author suny
     * @date 2021/9/9 4:02 下午
     */
    public function index(): Json
    {
        $param = (new StorageValidate())->get()->goCheck('index');
        return $this->success('获取成功', AdminStorageLogic::index($param));
    }

    /**
     * @notes 设置存储参数
     * @author 张无忌
     * @date 2021/7/28 10:40
     */
    public function setup(): Json
    {

        $params = (new StorageValidate())->post()->goCheck('setup');
        $result = AdminStorageLogic::setup($params);
        if($result === true){
            return $this->success('配置成功',[],1,1);
        }else{
            return $this->success($result,[],1,1);
        }
    }

    /**
     * @notes 切换存储方式
     * @author suny
     * @date 2021/9/9 11:19 上午
     */
    public function change(): Json
    {

        $params = (new StorageValidate())->post()->goCheck('change');
        AdminStorageLogic::change($params);
        return $this->success('切换成功');
    }
}
