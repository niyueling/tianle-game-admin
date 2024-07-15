<?php

// +----------------------------------------------------------------------
// | 缓存设置
// +----------------------------------------------------------------------

return [
    // 默认缓存驱动
    'default' => env('CACHE.REDISDRIVER', 'file'),

    // 缓存连接方式配置
    'stores'  => [
        'file' => [
            // 驱动方式
            'type'       => 'File',
            // 缓存保存目录
            'path'       => runtime_path() . 'cache',
            // 缓存前缀
            'prefix'     => '',
            // 缓存有效期 0表示永久缓存
            'expire'     => 0,
            // 缓存标签前缀
            'tag_prefix' => 'tag:',
            // 序列化机制 例如 ['serialize', 'unserialize']
            'serialize'  => [],
        ],
        // 更多的缓存连接
        'redis'   =>  [
            'prefix' => 'mahjong-'.env('PROJECT.UNIQUE_IDENTIFICATION'),
            // 驱动方式
            'type' => 'redis',
            // 服务器地址
            'host' => env('CACHE.REDISHOST'),
            // 端口
            'port' => env('CACHE.REDISPORT'),
            // 密码
            'password' => env('CACHE.REDISPASSWORD'),
        ],
    ],
];
