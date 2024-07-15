<?php
return [
    //唯一标识，密码盐、路径加密等
    'unique_identification' => env('project.unique_identification', 'likeshop'),

    //系统版本号
    'version' => '2.0.3',

    //后台管理员token（登录令牌）配置
    'admin_token' => [
        'expire_duration' => 3600 * 24,//管理后台token过期时长(单位秒）
        'be_expire_duration' => 3600,//管理后台token临时过期前时长，自动续期
    ],

    //商城用户token（登录令牌）配置
    'user_token' => [
        'expire_duration' => 3600 * 8,//用户token过期时长(单位秒）
        'be_expire_duration' => 3600,//用户token临时过期前时长，自动续期
    ],

    //客服token（登录令牌）配置
    'kefu_token' => [
        'expire_duration' => 3600 * 8,//客服token过期时长(单位秒）
        'be_expire_duration' => 3600,//客服token临时过期前时长，自动续期
    ],

    //列表页
    'lists' => [
        'page_size_max' => 25000,//列表页查询数量限制（列表页每页数量、导出每页数量）
        'page_size' => 25, //默认每页数量
    ],

    //各种默认图片
    'default_image' => [
        'admin_avatar' => 'resource/image/adminapi/default/avatar.png',
        'user_avatar' => 'http://fanmeng.com/resource/image/shopapi/default/avatar.png',
        'distribution_share_bg' => 'resource/image/shopapi/default/distribution_share_bg.png',
        'system_notice_icon' => 'resource/image/shopapi/default/system_notice.png',
        'earnings_notice_icon' => 'resource/image/shopapi/default/earning_notice.png',
    ],

    // 用户设置
    'default_user' => [
        // 开启邀请下级 0-关闭 1-开启
        'invite_open' => 1,
        // 邀请下级资格  1-全部用户可邀请 2-指定用户
        'invite_ways' => '2',
        // 指定用户
        'invite_appoint_user' => [],
        // 成为下级条件 1-绑定邀请码
        'invite_condition' => 1
    ],
    //登录设置
    'login'     => [
        //注册方式：1-手机号注册
        'register_way'              => ['1'],
        //登录方式：1-账号密码登录；2-手机短信验证码登录
        'login_way'                 => ['1','2'],
        //手机号码注册需验证码
        'is_mobile_register_code'   => 1,
        //注册强制绑定手机
        'coerce_mobile'             => 0,
        //公众号微信授权登录
        'h5_wechat_auth'            => 1,
        //公众号自动微信授权登录
        'h5_auto_wechat_auth'       => 1,
        //小程序微信授权登录
        'mnp_wechat_auth'           => 1,
        //小程序自动微信授权登录
        'mnp_auto_wechat_auth'      => 1,
        //APP微信授权登录
        'app_wechat_auth'           => 1,
        //字节小程序授权登录
        'toutiao_auth'           => 1,
        //字节小程序自动授权登录
        'toutiao_auto_auth'      => 1,
    ],
    'config'  => [
        //提现方式：1-钱包余额；2-微信零钱；3-银行卡；4-微信收款码；5-支付宝收款码
        'withdraw_way'              => ['1'],
        //最低提现金额
        'withdraw_min_money'        => 10,
        //最高提现金额
        'withdraw_max_money'        => 100,
        //提现手续费
        'withdraw_service_charge'  => 1,

    ],

    // 交易设置
    'transaction' => [
        // 系统取消待付款订单 0-关闭系统自动取消待付款订单 1-订单提交{cancel_unpaid_orders_times}分钟内未付款，系统自动取消
        'cancel_unpaid_orders' => 0,
        // 默认取消未付款订单时间,单位：分钟
        'cancel_unpaid_orders_times' => 30,
        // 买家取消待发货订单 0-关闭买家取消待发货订单 1-待发货订单{cancel_unshipped_orders_times}分钟内允许买家取消
        'cancel_unshipped_orders' => 0,
        // 默认取消待发货订单时间,单位：分钟
        'cancel_unshipped_orders_times' => 30,
        // 系统自动确认收货 0-关闭系统自动确认收货 1-订单发货后{automatically_confirm_receipt_days}天内，系统 自动确认收货
        'automatically_confirm_receipt' => 0,
        // 默认系统自动收货时间,单位：天
        'automatically_confirm_receipt_days' => 7,
        // 买家售后维权时效 0-关闭售后维权 1-订单确认收货{after_sales_days}天内，可申请售后维权
        'after_sales' => 0,
        // 默认售后维权时间，单位：天
        'after_sales_days' => 15,
        // 库存占用时机 1-订单提交占用库存
        'inventory_occupancy' => 1,
        // 取消未付款/未发货的订单退回库存 0-无需退回库存 1-需要退回库存
        'return_inventory' => 1,
        // 取消未付款/未发货订单退回优惠券券 0-无需退还优惠券 1-需要退还优惠券
        'return_coupon' => 1
    ],

    // 店铺信息
    'shop' => [
        /****** 店铺信息 ******/
        // 商城名称
        'name' => '凡盟后台',
        // 商城logo
        'logo' => 'resource/image/adminapi/default/logo.png',
        // 管理后台登录页图片
        'admin_login_image' => 'resource/image/adminapi/default/login.png',
        // 管理后台登录限制 0-不限制 1-需要限制
        'login_restrictions' => 0,
        // 限制密码错误次数
        'password_error_times' => 3,
        // 限制禁止多少分钟不能登录
        'limit_login_time' => 30,
        // 商城状态 0-关闭 1-开启
        'status' => 1,
        // 商城logo示例图
        'logo_example_image' => 'resource/image/adminapi/default/logo_example.png',
        //站点图标
        'favicon' => 'resource/image/adminapi/default/favicon.ico',
        // 商城关闭示例图
        'close_example_image' => 'resource/image/adminapi/default/close_example.png',
        // 登录封面示例图
        'login_example_image' => 'resource/image/adminapi/default/login_example.png',

        /****** 分享设置 ******/
        // 分享页面 1-当前页面
        'share_page' => 1,
        // 分享图片
        'share_image' => 'resource/image/adminapi/default/share.png',

    ],

    // 微信公众号设置
    'official_account' => [
        // 消息加密方式 1-明文模式 2-兼容模式 3-安全模式
        'encryption_type' => 1,
        // token
        'token' => 'LikeShopV3'
    ],

    // 微信小程序设置
    'mini_program' => [
        // 消息加密方式 1-明文模式 2-兼容模式 3-安全模式
        'encryption_type' => 1,
        // 数据格式 1-JSON 2-XML
        'data_format' => 2,
        // token
        'token' => 'LikeShopV3'
    ],

    // h5设置
    'h5' => [
        // 状态 0-关闭 1-开启
        'status' => 1
    ],

    // 充值设置
    'recharge' => [
        // 充值开关
        'open' => 0,
        // 最低充值金额
        'min_amount' => 0
    ],
    // 签到
    'sign' => [
        'remark' => '1.每天签到可以获得每天签到奖励；2.每天最多可签到1次，断签则会重新计算连签天数，达到连续天数后即可获得连续奖励；3.超过最大连续签到奖励天数后，重新计算连续签到天数；4.活动以及奖励最终解释权归商家所有。'
    ],
    // 默认短信配置
    'sms' => [
        'ali' => [
            'name' => '阿里云短信',
            'sign' => '',
            'app_key' => '',
            'secret_key' => '',
            'status' => false
        ],
        'tencent' => [
            'name' => '腾讯云短信',
            'sign' => '',
            'app_id' => '',
            'secret_id' => '',
            'secret_key' => '',
            'status' => false
        ],
    ],
    // PC商城默认设置
    'pc' => [
        'ico' => 'resource/image/adminapi/default/favicon.ico'
    ],
    // 在线客服前缀
    'websocket_prefix' => 'plus_socket_',
];
