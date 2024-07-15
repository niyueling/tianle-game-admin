<?php

namespace app\adminapi\validate\email;

use app\common\validate\BaseValidate;

class EmailValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require',
    ];

    protected $message = [
        'id.require' => '参数缺失'
    ];

    /**
     * @notes 查看通知设置详情
     * @return NoticeValidate
     * @author Tab
     * @date 2021/8/18 18:39
     */
    protected function sceneDetail()
    {
        return $this->only(['id']);
    }
}