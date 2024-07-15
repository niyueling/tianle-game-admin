<?php

namespace app\adminapi\controller\club;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\club\ClubLogic;
use app\adminapi\validate\club\adjustClubWallet;
use app\adminapi\validate\club\ClubValidate;

/**
 * 俱乐部控制器
 * Class UserController
 * @package app\adminapi\controller\user
 */
class ClubController extends BaseAdminController
{

    /**
     * @notes 获取俱乐部基本信息
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/17 14:59
     */
    public function detail()
    {
        $data = (new ClubLogic())->detail($this->request->get());
        return $this->success('获取成功', $data);

    }

    /**
     * @notes 俱乐部列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/10 16:49
     */
    public function lists()
    {
        $data = (new ClubLogic())->lists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 设置俱乐部信息
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/18 18:07
     */
    public function setInfo()
    {
        $params = (new ClubValidate())->post()->goCheck('setInfo');
        (new ClubLogic())->setClubInfo($params);
        return $this->success('更新成功', [], 1, 1);
    }

    /**
     * @notes 解散俱乐部
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/18 18:07
     */
    public function deleteClub()
    {
        (new ClubLogic())->deleteClub($this->request->post());
        return $this->success('更新成功', [], 1, 1);
    }

    /**
     * @notes 成员审核列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/10 16:49
     */
    public function requestLists()
    {
        $data = (new ClubLogic())->requestLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 成员审核
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/18 18:07
     */
    public function setRequestInfo()
    {

        $res = (new ClubLogic())->setRequestInfo($this->request->post());

        if("success" == $res){
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail($res);
    }

    /**
     * @notes 添加俱乐部
     * @return \think\response\Json
     * @author Tab
     * @date 2021/9/13 19:32
     */
    public function addClub()
    {
        $res = ClubLogic::addClub($this->request->post());

        if("success" == $res){
            return $this->success('新增成功', [], 1, 1);
        }
        return $this->fail($res);
    }

    /**
     * @notes 成员管理
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/10 16:49
     */
    public function memberLists()
    {
        $data = (new ClubLogic())->memberLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 调整用户钱包
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/9/10 18:15
     */
    public function adjustUserWallet()
    {
        $params = (new adjustClubWallet())->post()->goCheck();
        $res = (new ClubLogic())->adjustUserWallet($params, $this->adminId);
        if(true === $res){
            return $this->success('调整成功', [], 1, 1);
        }
        return $this->fail($res);
    }

    /**
     * @notes 踢出成员
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/9/10 18:15
     */
    public function kickMember()
    {
        $res = (new ClubLogic())->kickMember($this->request->post());
        if(true === $res){
            return $this->success('踢出成功', [], 1, 1);
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
        $data = (new ClubLogic())->roomLists($this->request->get());
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
        $data = (new ClubLogic())->roomDetail($this->request->get());
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
        $data = (new ClubLogic())->onLineRoomLists($this->request->get());
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
        $data = (new ClubLogic())->onlineRoomDetail($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 设置俱乐部成员信息
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/18 18:07
     */
    public function setMemberInfo()
    {
        (new ClubLogic())->setMemberInfo($this->request->post());
        return $this->success('更新成功', [], 1, 1);
    }

    /**
     * @notes 黑名单管理
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/10 16:49
     */
    public function blackLists()
    {
        $data = (new ClubLogic())->blackLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 设置黑名单
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/18 18:07
     */
    public function setBlackInfo()
    {
        $res = (new ClubLogic())->setBlackInfo($this->request->post());
        if(true == $res){
            return $this->success('更新成功', [], 1, 1);
        }
        return $this->fail("不能对俱乐部创建者做操作");
    }
}
