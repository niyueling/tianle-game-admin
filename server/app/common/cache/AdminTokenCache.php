<?php

namespace app\common\cache;

use think\facade\Db;
use \MongoDB\BSON\ObjectId;

class AdminTokenCache extends BaseCache
{

    private $prefix = 'token_admin_';

    /**
     * @notes 通过token获取缓存管理员信息
     * @param $token
     * @return false|mixed
     * @author 令狐冲
     * @date 2021/6/30 16:57
     */
    public function getAdminInfo($token)
    {
        //直接从缓存获取
        $adminInfo = $this->get($this->prefix . $token);
        if ($adminInfo) {
            return $adminInfo;
        }
        //从数据获取信息被设置缓存(可能后台清除缓存）
        $adminInfo = $this->setAdminInfo($token);
        if ($adminInfo) {
            return $adminInfo;
        }

        return false;
    }

    /**
     * @notes 通过有效token设置管理信息缓存
     * @param $token
     * @return array|false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 令狐冲
     * @date 2021/7/5 12:12
     */
    public function setAdminInfo($token)
    {
        $adminSession = Db::connect('mongodb')->table('session')->where([['token', '=', $token], ['expire_time', '>', time()]])->find();
        if (empty($adminSession)) {
            return [];
        }
        $admin = Db::connect('mongodb')->table('gms')->where('_id', '=', new ObjectId($adminSession["admin_id"]))->find();

        $adminInfo = [
            'admin_id' => reset($admin["_id"]),
            'username' => $admin["username"],
            'role_name' => $admin["role"],
            'gold' => $admin["gold"],
            'token' => $token,
            'gem' => $admin["gem"],
            'root' => $admin["role"] == "super",
            'expire_time' => $adminSession["expire_time"],
        ];
        $this->set($this->prefix . $token, $adminInfo, new \DateTime(Date('Y-m-d H:i:s', $adminSession["expire_time"])));
        return $this->getAdminInfo($token);
    }

    /**
     * @notes 删除缓存
     * @param $token
     * @return bool
     * @author 令狐冲
     * @date 2021/7/3 16:57
     */
    public function deleteAdminInfo($token)
    {
        return $this->delete($this->prefix . $token);
    }


}