<?php

namespace app\adminapi\controller\agency;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\agency\AgencyLogic;
use app\adminapi\logic\ShopSettingLogic;
use app\adminapi\validate\agency\adjustAgencyWallet;
use app\adminapi\validate\agency\AgencyValidate;
use app\common\enum\NoticeEnum;
use think\facade\Cache;

/**
 * 代理控制器
 * Class UserController
 * @package app\adminapi\controller\user
 */
class AgencyController extends BaseAdminController
{
    /**
     * @notes 代理列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/10 16:49
     */
    public function lists()
    {
        $data = (new AgencyLogic())->lists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 成员审核列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/10 16:49
     */
    public function requestLists()
    {
        $data = (new AgencyLogic())->requestLists($this->request->get());
        return $this->success('', $data);
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
        $params = (new AgencyValidate())->goCheck('detail');
        $detail = (new AgencyLogic())->detail($params['user_id']);
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

        $params = (new AgencyValidate())->post()->goCheck('setInfo');
        (new AgencyLogic())->setUserInfo($params);
        return $this->success('更新成功', [], 1, 1);
    }

    /**
     * @notes 成员审核
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/18 18:07
     */
    public function setRequestInfo()
    {

        $res = (new AgencyLogic())->setRequestInfo($this->request->post());

        if("success" == $res){
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail($res);
    }

    /**
     * @notes 调整用户钱包
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/9/10 18:15
     */
    public function adjustUserWallet()
    {
        $params = (new adjustAgencyWallet())->post()->goCheck();
        $res = (new AgencyLogic())->adjustUserWallet($params, $this->adminId);
        if(true === $res){
            return $this->success('调整成功', [], 1, 1);
        }
        return $this->fail($res);
    }

    /**
     * @notes 添加代理
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/13 19:32
     */
    public function addAgency()
    {
        $res = AgencyLogic::addAgency($this->request->post());

        if("success" == $res){
            return $this->success('新增成功', [], 1, 1);
        }
        return $this->fail($res);
    }

    /**
     * @notes 获取政策协议
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/28 16:06
     */
    public function getPolicyAgreement()
    {
        $result = ShopSettingLogic::getPolicyAgreement();
        return $this->data($result);
    }

    /**
     * @notes 设置政策协议
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/28 16:11
     */
    public function setPolicyAgreement()
    {
        $params = $this->request->post();
        ShopSettingLogic::setPolicyAgreement($params);
        return $this->success('设置成功');
    }

    /**
     * @notes 发送验证码-绑定俱乐部
     * @author Tab
     * @date 2021/8/25 15:47
     */
    public function captcha()
    {
        $get = $this->request->get();
        $code = mt_rand(1000, 9999);
        $result = event('Notice',  [
            'scene_id' => NoticeEnum::BIND_MOBILE_CAPTCHA,
            'params' => [
                'mobile' => $get['phone'],
                'code' => $code
            ]
        ]);

        if ($result[0] === true) {
            $redis = Cache::store('redis')->handler();
            $redis->setex($get['phone'], 60, $code);
            return $this->success('发送成功');
        }

        return $this->fail($result[0]);
    }

    /**
     * @notes 获取充值模板
     * @return \think\response\Json
     * @author Tab
     * @date 2021/7/28 16:06
     */
    public function getRechargeTemplate()
    {
        $result = AgencyLogic::getRechargeTemplate($this->adminId);
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
        $params['admin_id'] = $this->adminId;
        $result = AgencyLogic::recharge($params);
        if($result) {
            return $this->data($result);
        }
        return $this->fail(AgencyLogic::getError());
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
        $order = AgencyLogic::getPayOrderInfo($this->request->post());
        if (false === $order) {
            return $this->fail(AgencyLogic::getError(), $this->request->post());
        }
        //支付流程
        $result = AgencyLogic::pay($this->request->post("from"), $order);
        if (false === $result) {
            return $this->fail(AgencyLogic::getError(), $this->request->post());
        }
        return $this->success('', $result);
    }

    public function payStatus()
    {
        $result = AgencyLogic::getPayStatus($this->request->get());
        if ($result === false) {
            return $this->fail(AgencyLogic::getError());
        }
        return $this->data($result);
    }
}
