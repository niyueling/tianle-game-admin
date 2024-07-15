<?php

namespace app\adminapi\controller\user;

use app\common\enum\NoticeEnum;
use app\adminapi\{
    logic\user\UserLogic,
    validate\user\UserValidate,
    validate\user\adjustUserWallet,
    controller\BaseAdminController};
use think\facade\Cache;

/**
 * 用户控制器
 * Class UserController
 * @package app\adminapi\controller\user
 */
class UserController extends BaseAdminController
{
    public array $notNeedLogin = ['bindOrGetUserInfo', 'getRechargeTemplate', 'prepay', 'payStatus', 'recharge', 'getTemplates', 'getPayStatus',
        'getLastPayOrder', 'gem2ruby', 'findOrCreate', 'captcha', 'create', 'registNotify', 'wxGameRecharge', 'posterGenerate'];

    /**
     * @notes 用户概况
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/17 14:59
     */
    public function index()
    {
        $data = (new UserLogic)->index();
        return $this->success('', $data);

    }

    /**
     * @notes 用户列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/10 16:49
     */
    public function lists()
    {
        $userList = (new UserLogic)->lists($this->request->get());
        return $this->success("获取成功", $userList);
    }

    /**
     * @notes 用户详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/18 16:27
     */
    public function detail()
    {
        $params = (new UserValidate())->goCheck('detail');
        $detail = (new UserLogic)->detail($params['user_id']);
        return $this->success('', $detail);
    }

    /**
     * @notes 设置用户信息
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/18 18:07
     */
    public function setInfo()
    {

        $params = (new UserValidate())->post()->goCheck('setInfo');
        (new UserLogic)->setUserInfo($params);
        return $this->success('更新成功', [], 1, 1);
    }

    /**
     * @notes 删除用户
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/18 18:07
     */
    public function delete()
    {
        (new UserLogic)->deleteUserInfo($this->request->post());
        return $this->success('删除成功', []);
    }

    /**
     * @notes 充值
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/9/10 18:15
     */
    public function adjustUserWallet()
    {
        $params = (new adjustUserWallet())->post()->goCheck();
        $params["admin_id"] = $this->adminId;
        $res = (new UserLogic)->adjustUserWallet($params);
        if($res == "success"){
            return $this->success('调整成功', [], 1, 1);
        }
        return $this->fail($res);
    }

    /**
     * @notes 房间记录
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/10 16:49
     */
    public function roomLists()
    {
        $data = (new UserLogic())->roomLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 房间详情
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/10 16:49
     */
    public function roomDetail()
    {
        $data = (new UserLogic())->roomDetail($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 实时房间
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/10 16:49
     */
    public function onLineRoomLists()
    {
        $data = (new UserLogic())->onLineRoomLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 实时房间详情
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/10 16:49
     */
    public function onlineRoomDetail()
    {
        $data = (new UserLogic())->onlineRoomDetail($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 我的粉丝
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/10 16:49
     */
    public function inviteLists()
    {
        $data = (new UserLogic())->inviteLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 绑定账户
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/18 18:07
     */
    public function bindOrGetUserInfo()
    {
        $res = UserLogic::bindOrGetUserInfo($this->request->post());
        if($res["code"]) return $this->success('更新成功', $res["data"], 1, 1);
        return $this->fail($res["msg"]);

    }

    /**
     * @notes 获取充值模板
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/28 16:06
     */
    public function getRechargeTemplate()
    {
        $result = UserLogic::getRechargeTemplate($this->request->get());
        return $this->data($result);
    }

    /**
     * @notes 充值
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/11 10:44
     */
    public function recharge()
    {
        $params = $this->request->post();
        $result = UserLogic::recharge($params);
        if($result["code"]) {
            return $this->data($result["data"]);
        }
        return $this->fail($result["msg"]);
    }

    /**
     * @notes 预支付
     * @return \think\response\Json
     * @throws \Exception
     * @author 段誉
     * @date 2021/8/3 14:03
     */
    public function prepay()
    {
        //订单信息
        $order = UserLogic::getPayOrderInfo($this->request->post());
        if (false === $order) {
            return $this->fail(UserLogic::getError(), $this->request->post());
        }
        //支付流程
        $result = UserLogic::pay($this->request->post("from"), $order, $this->request->post("terminal") ?? 2);
        if (false === $result) {
            return $this->fail(UserLogic::getError(), $this->request->post());
        }
        return $this->success('', $result);
    }

    /**
     * @notes 获取充值模板
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/28 16:06
     */
    public function getTemplates()
    {
        if(!empty($this->request->get("currency")) && $this->request->get("currency") === "ruby") {
            $result = UserLogic::getRubyTemplate();
        } else {
            $result = UserLogic::getTemplates($this->request->get());
        }

        return $this->data($result);
    }

    /**
     * @notes 钻石兑换金豆
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/28 16:06
     */
    public function gem2ruby()
    {
        $result = UserLogic::gem2ruby($this->request->post());
        if($result["code"]) {
            return $this->data([]);
        }
        return $this->fail($result["msg"]);
    }

    /**
     * @notes 获取订单状态
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/28 16:06
     */
    public function getPayStatus()
    {
        $result = UserLogic::getPayStatus($this->request->get());
        if ($result === false) {
            return $this->fail(UserLogic::getError());
        }
        return $this->data($result);
    }

    /**
     * @notes 获取最近一条未支付订单
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/28 16:06
     */
    public function getLastPayOrder()
    {
        $result = UserLogic::getLastPayOrder($this->request->get());

        return $this->data($result);
    }

    /**
     * @notes 注册回调
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/28 16:06
     */
    public function registNotify()
    {
        UserLogic::registNotify($this->request->post());

        return $this->success("执行成功");
    }

    /**
     * @notes 发送验证码-绑定俱乐部
     * @author Tab
     * @date 2021/8/25 15:47
     */
    public function captcha()
    {
        $get = $this->request->get();
        $code = mt_rand(100000, 999999);
        $result = event('Notice',  [
            'scene_id' => NoticeEnum::BIND_MOBILE_CAPTCHA,
            'params' => [
                'mobile' => $get['phone'],
                'code' => $code
            ]
        ]);

        if ($result[0] === true) {
            $redis = Cache::store('redis')->handler();
            $redis->setex($get['phone'], 300, $code);
            return $this->success('发送成功', ["code" => $code]);
        }

        return $this->fail($result[0]);
    }

    /**
     * @notes 新用户注册
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/28 16:06
     */
    public function findOrCreate()
    {
        $result = UserLogic::findOrCreate($this->request->get());

        return $this->success($result["msg"], $result["data"]);
    }

    /**
     * @notes 新用户注册
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/28 16:06
     */
    public function create()
    {
        $result = UserLogic::create($this->request->post());

        if($result["code"]) return $this->success($result["msg"], $result["data"]);

        return $this->fail($result["msg"]);
    }

    /**
     * @notes 充值
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/11 10:44
     */
    public function wxGameRecharge()
    {
        $params = $this->request->post();
        $result = UserLogic::wxGameRecharge($params);
        if($result["code"]) {
            return $this->success("创建成功", $result["data"]);
        }
        return $this->fail($result["msg"], $result["data"] ?? []);
    }

    /**
     * @notes 生成分享url
     * @return \think\response\Json
     * @author Tab
     * @date 2021/8/11 10:44
     */
    public function posterGenerate()
    {
        $params = $this->request->post();
        $result = UserLogic::posterGenerate($params);
        return $this->success("创建成功", $result);
    }
}
