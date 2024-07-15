<?php

namespace app\adminapi\logic\auth;

use app\common\logic\BaseLogic;
use app\common\model\Admin;
use think\facade\Config;
use think\facade\Db;
use \MongoDB\BSON\ObjectId;

class AdminLogic extends BaseLogic
{
    /**
     * @notes 查看管理员详情
     * @param $params
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author Tab
     * @date 2021/7/13 11:52
     */
    public static function detail($params)
    {
        return Db::connect('mongodb')->table('gms')->where("_id", new ObjectId($params['_id']))->find();
    }

    /**
     * @notes 获取管理员基本信息
     * @param $params
     * @return array
     * @author cjhao
     * @date 2022/4/21 15:05
     */
    public static function getAdminInfo($adminIid){
        $adminInfo = Db::connect('mongodb')->table('gms')->where(['_id' => new ObjectId($adminIid)])->find();
        $adminInfo["_id"] = reset($adminInfo["_id"]);
        return $adminInfo;
    }

    /**
     * @notes 修改管理员密码
     * @param $params
     * @param $adminId
     * @return bool|string
     * @author cjhao
     * @date 2022/4/21 15:16
     */
    public static function resetPassword($params,$adminId){
        try{

            $passwordSalt = Config::get('project.unique_identification');
            $password = create_password($params['password'], $passwordSalt);
            Db::connect('mongodb')
                ->table('gms')
                ->where("_id", new ObjectId($adminId))
                ->update(['pass'=>$password]);
            return true;

        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}