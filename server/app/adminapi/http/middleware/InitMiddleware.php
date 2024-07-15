<?php

declare (strict_types=1);

namespace app\adminapi\http\middleware;

use app\adminapi\controller\BaseAdminController;
use app\common\exception\ControllerExtendException;
use app\common\service\JsonService;
use think\exception\ClassNotFoundException;
use think\exception\HttpException;

class InitMiddleware
{
    /**
     * @notes 初始化
     * @param $request
     * @param \Closure $next
     * @return mixed
     * @author 令狐冲
     * @date 2021/7/2 19:29
     */
    public function handle($request, \Closure $next)
    {
        //接口版本判断
        $version = $request->header('version');
        if (empty($version) && !$this->nocheck($request)) {
            return JsonService::fail('请求参数缺少接口版本号', ["action" => $request->controller() . '/'. $request->action()], 0, 0);
        }


        //获取控制器
        try {
            $controller = str_replace('.', '\\', $request->controller());
            $controller = '\\app\\adminapi\\controller\\' . $controller . 'Controller';
            $controllerClass = invoke($controller);
            if (($controllerClass instanceof BaseAdminController) === false) {
                throw new ControllerExtendException($controller, '404');
            }
        } catch (ClassNotFoundException $e) {
            throw new HttpException(404, 'controller not exists:' . $e->getClass());
        }
        //创建控制器对象
        $request->controllerObject = invoke($controller);

        return $next($request);
    }

    public function nocheck($request)
    {
        //特殊方法不验证版本号参数
        $noCheck = [
            'wechat.Wechat/notifyOa',
            'wechat.Wechat/notifyMnp',
            'wechat.Wechat/index',
            'alipay.Alipay/aliNotify',
            'user.User/registNotify',
            'marketing.Qian/upload',
        ];
        $requestAction = $request->controller() . '/'. $request->action();
        return in_array($requestAction, $noCheck);
    }
}
