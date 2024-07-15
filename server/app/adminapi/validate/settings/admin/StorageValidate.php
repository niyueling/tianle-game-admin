<?php

namespace app\adminapi\validate\settings\admin;


use app\common\validate\BaseValidate;

class StorageValidate extends BaseValidate
{
    protected $rule = [
        'engine' => 'require',
        'status' => 'require',
    ];

    /**
     * @notes 设置存储参数
     * @return StorageValidate
     * @author suny
     * @date 2021/9/9 3:02 下午
     */
    public function sceneSetup()
    {

        return $this->only(['engine', 'status']);
    }

    /**
     * @notes 获取配置参数信息
     * @return StorageValidate
     * @author suny
     * @date 2021/9/9 5:48 下午
     */
    public function sceneIndex()
    {

        return $this->only(['engine']);
    }

    /**
     * @notes 切换默认存储引擎
     * @return StorageValidate
     * @author suny
     * @date 2021/9/9 3:00 下午
     */
    public function sceneChange()
    {

        return $this->only(['engine']);
    }
}