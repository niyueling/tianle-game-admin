<?php

declare (strict_types=1);

namespace app\adminapi\http\middleware;
use app\common\{
    cache\AdminAuthCache,
    service\JsonService
};

class AuthMiddleware
{
    /**
     * @notes 权限验证
     * @param $request
     * @param \Closure $next
     * @return mixed
     * @author 令狐冲
     * @date 2021/7/2 19:29
     */
    public function handle($request, \Closure $next)
    {
        //不登录访问，无需权限验证
        if ($request->controllerObject->isNotNeedLogin()) {
            return $next($request);
        };

        //系统默认超级管理员，无需权限验证
        if (1 == $request->adminInfo['root'] ) {
            return $next($request);
        }
        $accessUri = strtolower($request->controller() . '/' . $request->action());//当前访问uri
        $adminAuthCache = new AdminAuthCache($request->adminInfo['admin_id']);
        $allUri = $adminAuthCache->getAllUri()['action_auth'];
        //判断该当前访问的uri是否存在，不存在无需验证
        if (!in_array($accessUri, $allUri)) {
            return $next($request);
        }
        //管理员访问的uri判断
        $AdminUris = $adminAuthCache->getAdminUri()['action_auth'] ?? [];
        if (in_array($accessUri, $AdminUris)) {
            return $next($request);
        }
        return JsonService::fail('权限不足，无法访问或操作');
    }
}