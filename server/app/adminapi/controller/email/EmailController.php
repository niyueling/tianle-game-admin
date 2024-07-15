<?php


namespace app\adminapi\controller\email;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\email\EmailLogic;

/**
 * 邮件控制器
 * Class NoticeController
 * @package app\adminapi\controller\notice
 */
class EmailController extends BaseAdminController
{
    /**
     * @notes 邮件列表
     * @return \think\response\Json
     * @author ljj
     * @date 2021/8/23 2:16 下午
     */
    public function lists()
    {
        $data = (new EmailLogic())->lists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 获取用户绑定战队
     * @return \think\response\Json
     * @author ljj
     * @date 2021/8/23 2:16 下午
     */
    public function userClubLists()
    {
        $data = (new EmailLogic())->userClubLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 添加邮件
     * @return \think\response\Json
     * @author ljj
     * @date 2021/8/23 2:01 下午
     */
    public function add()
    {
        $res = (new EmailLogic())->add($this->request->post());
        if($res) return $this->success('添加成功',[],1,1);
        return $this->fail('用户不存在');
    }

    /**
     * @notes 删除邮件
     * @return \think\response\Json
     * @author ljj
     * @date 2021/8/23 3:04 下午
     */
    public function del()
    {
        (new EmailLogic())->del($this->request->post());
        return $this->success('删除成功',[],1,1);
    }

    /**
     * @notes 公共邮件列表
     * @return \think\response\Json
     * @author ljj
     * @date 2021/8/23 2:16 下午
     */
    public function publicLists()
    {
        $data = (new EmailLogic())->publicLists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 添加公共邮件
     * @return \think\response\Json
     * @author ljj
     * @date 2021/8/23 2:01 下午
     */
    public function addPublicEmail()
    {
        (new EmailLogic())->addPublicEmail($this->request->post());
        return $this->success('添加成功',[],1,1);
    }

    /**
     * @notes 删除公共邮件
     * @return \think\response\Json
     * @author ljj
     * @date 2021/8/23 3:04 下午
     */
    public function delPublicEmail()
    {
        (new EmailLogic())->delPublicEmail($this->request->post());
        return $this->success('删除成功',[],1,1);
    }

    /**
     * @notes 公共/圈主邮件领取列表
     * @return \think\response\Json
     * @author ljj
     * @date 2021/8/23 2:16 下午
     */
    public function receiveLists()
    {
        $data = (new EmailLogic())->receiveLists($this->request->get());
        return $this->success('', $data);
    }
}
