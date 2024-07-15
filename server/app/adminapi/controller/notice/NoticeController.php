<?php

namespace app\adminapi\controller\notice;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\notice\NoticeLogic;

class NoticeController extends BaseAdminController
{
    /**
     * @notes 公告列表
     * @return \think\response\Json
     * @author ljj
     * @date 2021/8/23 2:16 下午
     */
    public function lists()
    {
        $data = (new NoticeLogic())->lists($this->request->get());
        return $this->success('', $data);
    }

    /**
     * @notes 添加公告
     * @return \think\response\Json
     * @author ljj
     * @date 2021/8/23 2:01 下午
     */
    public function add()
    {
        (new NoticeLogic())->add($this->request->post());
        return $this->success('添加成功',[],1,1);
    }

    /**
     * @notes 编辑公告
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/8/23 2:42 下午
     */
    public function edit()
    {
        (new NoticeLogic())->edit($this->request->post());
        return $this->success('修改成功',[],1,1);
    }

    /**
     * @notes 删除公告
     * @return \think\response\Json
     * @author ljj
     * @date 2021/8/23 3:04 下午
     */
    public function del()
    {
        (new NoticeLogic())->del($this->request->post());
        return $this->success('删除成功',[],1,1);
    }
}