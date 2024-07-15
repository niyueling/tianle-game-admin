<?php

namespace app\adminapi\validate\settings\admin;
use app\common\validate\BaseValidate;

/**
 * 店铺设置验证器
 * Class ShopSettingsValidate
 * @package app\adminapi\validate\settings\shop
 */
class AdminSettingsValidate extends BaseValidate
{
    protected $rule = [
        'name' => 'require',
        'logo' => 'require',
        'admin_login_image' => 'require',
        'login_restrictions' => 'require|in:0,1',
        'password_error_times' => 'requireIf:login_restrictions,1|integer|gt:0',
        'limit_login_time' => 'requireIf:login_restrictions,1|integer|gt:0',
    ];

    protected $message = [
        'name.require' => '请输入名称',
        'logo.require' => '请上传logo',
        'admin_login_image.require' => '请上传管理后台登录页图片',
        'login_restrictions.require' => '请选择管理后台登录限制',
        'login_restrictions.in' => '管理后台登录限制状态值有误',
        'password_error_times.requireIf' => '请输入密码错误次数',
        'password_error_times.integer' => '密码错误次数须为整型',
        'password_error_times.gt' => '密码错误次数须大于0',
        'limit_login_time.requireIf' => '请输入限制登录分钟数',
        'limit_login_time.integer' => '限制登录分钟数须为整型',
        'limit_login_time.gt' => '限制登录分钟数须大于0',
    ];

    /**
     * @notes 设置店铺信息场景
     * @return AdminSettingsValidate
     * @author Tab
     * @date 2021/7/28 14:50
     */
    public function sceneSetShopInfo()
    {
        return $this->only(['name', 'logo', 'admin_login_image', 'login_restrictions', 'password_error_times', 'limit_login_time']);
    }
}