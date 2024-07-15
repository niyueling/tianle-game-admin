<?php

return [
    // 默认使用的数据库连接配置
    'default'         => env('database.driver', 'mysql'),

    // 自定义时间查询规则
    'time_query_rule' => [],

    // 自动写入时间戳字段
    // true为自动识别类型 false关闭
    // 字符串则明确指定时间字段类型 支持 int timestamp datetime date
    'auto_timestamp'  => true,

    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',

    // 数据库连接配置信息
    'connections'     => [
        'mysql' => [
            // 数据库类型
            'type'            => env('mysql.type', 'mysql'),
            // 服务器地址
            'hostname'        => env('mysql.hostname', 'likeshop-mysql'),
            // 数据库名
            'database'        => env('mysql.database', 'localhost_likeshopb2cv3'),
            // 用户名
            'username'        => env('mysql.username', 'root'),
            // 密码
            'password'        => env('mysql.password', 'root'),
            // 端口
            'hostport'        => env('mysql.hostport', '3306'),
            // 数据库连接参数
            'params'          => [],
            // 数据库编码默认采用utf8
            'charset'         => env('mysql.charset', 'utf8mb4'),
            // 数据库表前缀
            'prefix'          => env('mysql.prefix', 'ls_'),

            // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy'          => 0,
            // 数据库读写是否分离 主从式有效
            'rw_separate'     => false,
            // 读写分离后 主服务器数量
            'master_num'      => 1,
            // 指定从服务器序号
            'slave_no'        => '',
            // 是否严格检查字段是否存在
            'fields_strict'   => true,
            // 是否需要断线重连
            'break_reconnect' => false,
            // 监听SQL
            'trigger_sql'     => env('app_debug', false),
            // 开启字段缓存
            'fields_cache'    => false,
        ],

        'mongodb'=>[
            // 数据库类型
            'type'              => env('mongo.mongotype', 'mongo'),
            // 服务器地址
            'hostname'          => env('mongo.mongohostname', '127.0.0.1'),
            // 数据库名
            'database'          => env('mongo.mongodatabase', 'test'),
            // 用户名
            'username'          => env('mongo.mongousername', ''),
            // 密码
            'password'          => env('mongo.mongopassword', ''),
            // 端口
            'hostport'          => env('mongo.mongohostport', '27017'),
            'pk_type'           => "",
            // 数据库连接参数
            'params'            => [],
            // 数据库调试模式
            'debug'             => env('mongo.mongodebug', false),
            // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy'            => 0,
            // 数据库读写是否分离 主从式有效
            'rw_separate'       => false,
            // 监听SQL
            'trigger_sql'       => false,
            // 读写分离后 主服务器数量
            'master_num'        => 1,
            // 指定从服务器序号
            'slave_no'          => '',
            // 是否严格检查字段是否存在
            'fields_strict'     => true,
            // 是否需要断线重连
            'break_reconnect'   => false,
            // 字段缓存路径
            'schema_cache_path' => app()->getRuntimePath() . 'schema' . DIRECTORY_SEPARATOR,
        ]

        // 更多的数据库配置信息
    ],
];
