<?php

return [
    'marketing'     => [//营销
        [
            'name'      => '营销玩法',
            'introduce' => '吸粉、老客带新客，提高下单转化率',
            'list'      => [
                [
                    'name'      => '优惠券',
                    'introduce' => '发放优惠券',
                    'tips'      => '',
                    'page_path' => '/coupon/lists',
                    'image'     => '/resource/image/adminapi/default/coupon.png',
                    'is_open' => true
                ],
                [
                    'name'      => '限时秒杀',
                    'introduce' => '超级好货 限时抢',
                    'tips'      => '',
                    'page_path' => '/seckill/lists',
                    'image'     => '/resource/image/adminapi/default/miaosha.png',
                    'is_open' => true
                ],
                [
                    'name'      => '拼团活动',
                    'introduce' => '邀请好友拼团 共享优惠',
                    'tips'      => '',
                    'page_path' => '/combination/lists',
                    'image'     => '/resource/image/adminapi/default/pintuan.png',
                    'is_open' => true
                ],
                [
                    'name'      => '砍价活动',
                    'introduce' => '邀请好友砍价 裂变快速传播',
                    'tips'      => '',
                    'page_path' => '/bargain/lists',
                    'image'     => '/resource/image/adminapi/default/kanjia.png',
                    'is_open' => true
                ],
                [
                    'name'      => '会员折扣',
                    'introduce' => '单独设置商品会员价',
                    'tips'      => '',
                    'page_path' => '/member_price/index',
                    'image'     => '/resource/image/adminapi/default/level_discount.png',
                    'is_open' => true
                ]
            ],
        ],
        [
            'name'      => '营销互动',
            'introduce' => '增强互动，留存复购',
            'list'      => [
                [
                    'name'      => '积分签到',
                    'introduce' => '用户每日签到领取各种奖励 增加用户黏性',
                    'tips'      => '',
                    'page_path' => '/calendar/survey',
                    'image'     => '/resource/image/adminapi/default/sign.png',
                    'is_open' => true
                ],
                [
                    'name'      => '幸运抽奖',
                    'introduce' => '积分抽奖 趣味互动 提升积分价值',
                    'tips'      => '',
                    'page_path' => '/lucky_draw/index',
                    'image'     => '/resource/image/adminapi/default/zhuanpan.png',
                    'is_open' => true
                ],
                [
                    'name'      => '消费奖励',
                    'introduce' => '用户消费赠送奖励',
                    'tips'      => '',
                    'page_path' => '/consumption_reward/setting',
                    'image'     => '/resource/image/adminapi/default/pay_reward.png',
                    'is_open' => true
                ],
                [
                    'name'      => '包邮活动',
                    'introduce' => '满足活动条件可享受包邮优惠',
                    'tips'      => '',
                    'page_path' => '/free_shipping/index',
                    'image'     => '/resource/image/adminapi/default/free_shipping.png',
                    'is_open' => true
                ]
            ],
        ],
    ],
    'apply'     => [//应用中心
        [
            'name'      => '分销推广',
            'introduce' => '',
            'list'      => [
                [
                    'name'      => '分销应用',
                    'introduce' => '裂变分销 智能锁粉 用户主动推广卖货',
                    'tips'      => '',
                    'page_path' => '/distribution/survey',
                    'image'     => '/resource/image/adminapi/default/distribution.png',
                    'is_open' => true
                ]
            ],
        ],
        [
            'name'      => '经营应用',
            'introduce' => '',
            'list'      => [
                [
                    'name'      => '用户储值',
                    'introduce' => '多充多送 增加复购',
                    'tips'      => '',
                    'page_path' => '/recharge/survey',
                    'image'     => '/resource/image/adminapi/default/recharge.png',
                    'is_open' => true
                ],
                [
                    'name'      => '商城公告',
                    'introduce' => '商城公告',
                    'tips'      => '',
                    'page_path' => '/notice/lists',
                    'image'     => '/resource/image/adminapi/default/shop_notice.png',
                    'is_open' => true
                ],
                [
                    'name'      => '门店自提',
                    'introduce' => '门店自提点 核销订单',
                    'tips'      => '',
                    'page_path' => '/selffetch/selffetch_order',
                    'image'     => '/resource/image/adminapi/default/store.png',
                    'is_open' => true
                ],
                [
                    'name'      => '小程序直播',
                    'introduce' => '直播卖货 快速推广',
                    'tips'      => '',
                    'page_path' => '/live_broadcast/lists',
                    'image'     => '/resource/image/adminapi/default/zhibo.png',
                    'is_open' => true
                ],
                [
                    'name'      => '商城资讯',
                    'introduce' => '商城动态 最新通知',
                    'tips'      => '',
                    'page_path' => '/article/lists',
                    'image'     => '/resource/image/adminapi/default/article.png',
                    'is_open' => true
                ],
                [
                    'name'      => '积分商城',
                    'introduce' => '积分兑换实物',
                    'tips'      => '',
                    'page_path' => '/integral_mall/integral_goods',
                    'image'     => '/resource/image/adminapi/default/integral_store.png',
                    'is_open' => true
                ],
                [
                    'name'      => '足迹汽泡',
                    'introduce' => '营造粉丝气氛 提升用户购买欲',
                    'tips'      => '',
                    'page_path' => '/footprint/index',
                    'image'     => '/resource/image/adminapi/default/footprint.png',
                    'is_open' => true
                ],
                [
                    'name'      => '自定义海报',
                    'introduce' => '自定义分享海报',
                    'tips'      => '',
                    'page_path' => '/custom_poster/goods',
                    'image'     => '/resource/image/adminapi/default/custom_poster.png',
                    'is_open' => true
                ],
            ],
        ],
        [
            'name'      => '配套工具',
            'introduce' => '',
            'list'      => [
                [
                    'name'      => '消息通知',
                    'introduce' => '面向买家/卖家推送短信、微信消息通知',
                    'tips'      => '',
                    'page_path' => '/sms/buyers/buyers',
                    'image'     => '/resource/image/adminapi/default/message.png',
                    'is_open' => true
                ],
                [
                    'name'      => '在线客服',
                    'introduce' => '支持小程序人工在线客服',
                    'tips'      => '',
                    'page_path' => '/service',
                    'image'     => '/resource/image/adminapi/default/service.png',
                    'is_open' => true
                ],
                [
                    'name'      => '快递助手',
                    'introduce' => '批量打印 快速高效打印快递面单',
                    'tips'      => '',
                    'page_path' => '/express/batch',
                    'image'     => '/resource/image/adminapi/default/electronic_face_sheet.png',
                    'is_open' => true
                ],
                [
                    'name'      => '小票打印',
                    'introduce' => '快速高效打印订单',
                    'tips'      => '',
                    'page_path' => '/print/list',
                    'image'     => '/resource/image/adminapi/default/ticket_printing.png',
                    'is_open' => true
                ],
                [
                    'name'      => '评价助手',
                    'introduce' => '快速高效评价',
                    'tips'      => '',
                    'page_path' => '/evaluation/index',
                    'image'     => '/resource/image/adminapi/default/comment.png',
                    'is_open' => true
                ],
            ],
        ]
    ]
];
