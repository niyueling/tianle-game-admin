<?php
// 事件定义文件
return [
    'bind' => [
    ],

    'listen' => [
        'AppInit' => [],
        'HttpRun' => [],
        'HttpEnd' => [],
        'LogLevel' => [],
        'LogWrite' => [],
        // 通知
        'Notice' => ['app\common\listener\NoticeListener'],
    ],

    'subscribe' => [
    ],
];
