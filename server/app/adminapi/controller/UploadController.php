<?php

namespace app\adminapi\controller;

use app\common\service\UploadService;
use app\common\validate\UploadValidate;
use Exception;
use think\response\Json;

class UploadController extends BaseAdminController
{
    /**
     * @notes 上传图片
     * @return Json
     * @author 张无忌
     * @date 2021/7/28 16:57
     */
    public function image()
    {
        (new UploadValidate())->goCheck(null,['file'=>$this->request->file()]);
        try {
            $cid = $this->request->post('cid', 0);
            $result = UploadService::image($cid,$this->adminId);
            return $this->success('上传成功', $result);
        } catch (Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * @notes 上传视频
     * @return Json
     * @author 张无忌
     * @date 2021/7/29 14:01
     */
    public function video()
    {
        (new UploadValidate())->goCheck(null,['file'=>$this->request->file()]);
        try {
            $cid = $this->request->post('cid', 0);
            $result = UploadService::video($cid,$this->adminId);
            return $this->success('上传成功', $result);
        } catch (Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * @notes 上传素材
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/11/22 17:51
     */
    public function wechatMaterial()
    {
        $file = $this->request->file('file');
        $result = UploadService::wechatMaterial($file);
        if (is_array($result)) {
            return $this->success('', $result);
        }
        return $this->fail($result);

    }

}