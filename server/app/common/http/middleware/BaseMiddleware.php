<?php

declare (strict_types=1);

namespace app\common\http\middleware;


use app\common\service\JsonService;

/**
 * 基础中间件
 * Class LikeShopMiddleware
 * @package app\common\http\middleware
 */
class BaseMiddleware
{

    public function handle($request, \Closure $next)
    {

        //过滤前后空格
        $request->filter(['trim']);

        return $next($request);
    }
}