<?php

return [

    'middleware' => [
        app\adminapi\http\middleware\InitMiddleware::class,  //初始化
        app\adminapi\http\middleware\LoginMiddleware::class, //登录验证
        app\adminapi\http\middleware\AuthMiddleware::class, //权限认证
        app\adminapi\http\middleware\LoggingMiddleware::class, //日志
    ],

];
