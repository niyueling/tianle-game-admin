<?php
// 全局中间件定义文件
return [
    //跨域中间件
    app\common\http\middleware\LikeshopAllowMiddleware::class,
    //基础中间件
    app\common\http\middleware\BaseMiddleware::class,
];
