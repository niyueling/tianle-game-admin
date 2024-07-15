<?php

use think\facade\Route;

//管理后台
Route::rule('admin/:any', function () {
    return view(app()->getRootPath() . 'public/admin/index.html');
})->pattern(['any' => '\w+']);

//手机端
Route::rule('mobile/:any', function () {
    return view(app()->getRootPath() . 'public/mobile/index.html');
})->pattern(['any' => '\w+']);

//定时任务
Route::rule('crontab', function () {
    \think\facade\Console::call('crontab');
});

