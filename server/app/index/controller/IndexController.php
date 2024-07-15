<?php


namespace app\index\controller;

class IndexController
{
    /**
     * @notes 域名访问默认控制器
     * @return string|\think\response\View
     * @author 令狐冲
     * @date 2021/7/23 11:01
     */
    public function index()
    {
        $template = app()->getRootPath() . 'public/admin/index.html';
        if (is_mobile()) {
            $template = app()->getRootPath() . 'public/mobile/index.html';
        }
        if (file_exists($template)) {
            return view($template);
        }
        return '敬请期待';
    }
}