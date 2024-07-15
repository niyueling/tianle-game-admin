<?php

/**
 * @todo 权限控制
 */
return [
    //首页
    'index'     => [
        //控制台
        'index' => [
            'page_path'     => '/index/index',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['workbench/index'],
            ],
        ]
    ],
    //代理
    'agency'     => [
        'index'          => [
            'page_path'     => ['/agency/index','/agency/agency_detail'],
            'view'          => [
                'button_auth'   => ['view'],
                'action_auth'   => ['agency.agency/lists'],
            ],
            'detail'          => [
                'button_auth'   => ['view'],
                'action_auth'   => ['agency.agency/detail'],
            ],
            'edit'          => [
                'button_auth'   => ['edit'],
                'action_auth'   => ['agency.agency/setInfo'],
            ],
            'recharge'          => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => ['agency.agency/adjustUserWallet'],
            ],
            'bind'          => [
                'button_auth'   => ['edit'],
                'action_auth'   => ['agency.agency/setInfo'],
            ],
        ],
        //自助充值方案
        'user'    => [
            'page_path'     => ['/agency/user'],
            'view'          => [
                'button_auth'   => ['view'],
                'action_auth'   => ['agency.agency/getPolicyAgreement'],
            ]
        ],
        //自助充值
        'recharge'          => [
            'page_path'     => '/agency/recharge',
            'view'          => [
                'button_auth'   => ['view'],
                'action_auth'   => [],
            ]
        ]
    ],
    //俱乐部
    'clubs'     => [
        //俱乐部
        'lists'         => [
            'page_path' => ['clubs/lists', '/clubs/clubs_detail', '/clubs/members', '/clubs/apply', '/clubs/blacks', '/finance/club/gold'],
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['club.club/lists'],
            ],
            'room'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['club.club/roomLists', 'club.club/roomDetail'],
            ],
            'online'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['club.club/onLineRoomLists','club.club/onLineRoomDetail'],
            ],
            'detail'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['club.club/detail'],
            ],
            'member'      => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => ['club.club/memberLists'],
            ],
            'apply'      => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => ['club.club/requestLists'],
            ],
            'blacklist'      => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => ['club.club/blackLists'],
            ],
            'gold'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['account_log/userClubGoldLists'],
            ],
            'manager'        => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => [
                    'club.club/adjustUserWallet',
                    'club.club/setInfo',
                    'club.club/setRequestInfo',
                    'club.club/addClub',
                    'club.club/kickMember',
                    'club.club/setMemberInfo',
                    'club.club/setBlackInfo',
                ]
            ],
        ]
    ],
    //用户
    'user'      => [
        //用户概述
        'profile'   => [
            'page_path'     => '/user/profile',
            'view'      => [
                'button_auth'  => ['view'],
                'action_auth'  => ['user.user/index'],
            ],
        ],
        //用户管理
        'lists'      => [
            'page_path'     => ['/user/lists', '/user/user_details', '/user/rooms', '/user/rooms/detail', '/user/rooms/online', '/user/rooms/online_detail'],
            'view'      => [
                'button_auth'  => ['view'],
                'action_auth'  => ['user.user/lists','user.user/detail'],
            ],
            'room'      => [
                'button_auth'  => ['view'],
                'action_auth'  => ['user.user/roomLists','user.user/roomDetail'],
            ],
            'online'      => [
                'button_auth'  => ['view'],
                'action_auth'  => ['user.user/onLineRoomLists','user.user/onLineRoomDetail'],
            ],
            'manager'        => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => [
                    'user.user/setInfo',
                    'user.user/adjustUserWallet',
                ]
            ]
        ]
    ],

    //财务
    'finance'   => [
        //用户
        'user'   => [
            'page_path'     => ['/finance/user/recharge', '/finance/user/gem', '/finance/user/gold', '/finance/user/red_pocket', '/finance/user/red_pocket_withdraw',
                '/finance/user/room','/finance/user/statistics_recharge', '/finance/user/redpocket_ranking', '/finance/user/game_rate'],
            'recharge'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['account_log/userRechargeLists'],
            ],
            'statisticsrecharge'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['account_log/userRechargeStatisticsLists'],
            ],
            'gem'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['account_log/userGemLists'],
            ],
            'gold'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['account_log/userClubGoldLists'],
            ],
            'redpocket'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['account_log/userRedPocketLists'],
            ],
            'withdraw'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['account_log/userRedPocketWithdrawLists'],
            ],
            'room'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['account_log/roomLists'],
            ],
            'redpocketranking'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['account_log/userRedPocketRankingLists'],
            ],
            'gamerate'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['account_log/userGameRateLists'],
            ],
        ],
        //代理
        'agency'   => [
            'page_path'     => ['/finance/agency/recharge', '/finance/agency/gem', '/finance/agency/auto_recharge'],
            'recharge'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['account_log/agencyRechargeLists'],
            ],
            'gem'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['account_log/agencyGemLists'],
            ],
            'autorecharge'      => [
                'button_auth'   => ['view'],
                'action_auth'   => [],
            ],
        ],
        //俱乐部
        'club'   => [
            'page_path'     => ['/finance/club/recharge', '/finance/club/gold', '/finance/club/room', '/finance/club/room_ranking',
                '/finance/club/creator_gold'],
            'recharge'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['account_log/clubAccountLists'],
            ],
            'gold'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['account_log/userClubGoldLists'],
            ],
            'ranking'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['account_log/clubRoomRanking'],
            ],
            'creator'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['account_log/clubCreatorGoldLists'],
            ],
            'room'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['account_log/clubRoomLists'],
            ],
        ],
        //商品
        'goods'   => [
            'page_path'     => ['/finance/goods/order'],
            'order'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['account_log/payGoodsOrderLists'],
            ],
        ],
    ],
    //数据
    'data'      => [
        'room'=> [
            'page_path'     => '/data/room',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['data.center/trafficAnalysis'],
            ],
        ],
        'user'    => [
            'page_path'     => '/data/user',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['data.center/userAnalysis'],
            ],
        ],
        'recharge'    => [
            'page_path'     => '/data/recharge',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['data.center/rechargeAnalysis'],
            ],
        ],
        'consume'    => [
            'page_path'     => '/data/consume',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['data.center/consumeAnalysis'],
            ],
        ],
        'visitor'    => [
            'page_path'     => '/data/visitor',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['data.center/visitorAnalysis'],
            ],
        ]
    ],

    //营销
    'marketing'      => [
        'goods'=> [
            'page_path'     => '/marketing/goods',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['marketing.goods/goodLists'],
            ],
            'manager'        => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => [
                    'marketing.goods/delGoods',
                    'marketing.goods/setGoodInfo',
                    'marketing.goods/addGoods'
                ]
            ]
        ],
        'city_street'    => [
            'page_path'     => '/marketing/city_street',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['marketing.region/regionLists'],
            ],
            'manager'        => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => [
                    'marketing.region/delRegion',
                    'marketing.region/setRegionInfo',
                    'marketing.region/addRegion'
                ]
            ]
        ],
        'games'    => [
            'page_path'     => '/marketing/games',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['marketing.game/gameLists', 'marketing.game/searchLists'],
            ],
            'manager'        => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => [
                    'marketing.game/delGame',
                    'marketing.game/setGameInfo',
                    'marketing.game/addGame'
                ]
            ]
        ],
        'redpocket'    => [
            'page_path'     => '/marketing/redpocket',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['marketing.redpocket/redPocketLists'],
            ],
            'manager'        => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => [
                    'marketing.redpocket/delRedPocket',
                    'marketing.redpocket/setRedPocketInfo',
                    'marketing.redpocket/addRedPocket'
                ]
            ]
        ]
    ],

    //内容
    'content'      => [
        'notice'=> [
            'page_path'     => '/content/notice',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['notice.notice/lists'],
            ],
            'manager'        => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => [
                    'notice.notice/lists/add',
                    'notice.notice/lists/edit',
                    'notice.notice/lists/del'
                ]
            ]
        ],
        'email'    => [
            'page_path'     => '/content/email',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['email.email/lists'],
            ],
            'manager'        => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => [
                    'email.email/add',
                    'email.email/del',
                ]
            ]
        ],
        'public_email'    => [
            'page_path'     => ['content/public_email', '/content/email_receive'],
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['email.email/publicLists'],
            ],
            'record'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['email.email/receiveLists'],
            ],
            'manager'        => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => [
                    'email.email/delPublicEmail',
                    'email.email/addPublicEmail'
                ]
            ]
        ],
        'main_email'    => [
            'page_path'     => ['content/main_email', '/content/email_receive'],
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['email.email/publicLists'],
            ],
            'record'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['email.email/receiveLists'],
            ],
            'manager'        => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => [
                    'email.email/delPublicEmail',
                    'email.email/addPublicEmail'
                ]
            ]
        ]
    ],

    //设置
    'setting'   => [
        'admin'      => [
            'page_path'     => '/setting/admin/index',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['settings.admin.admin_setting/getShopInfo'],
            ],
            'save'      => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => ['settings.admin.admin_setting/setShopInfo'],
            ],
        ],
        //备案信息
        'record'      => [
            'page_path'     => '/setting/admin/record',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['settings.admin.admin_setting/getrecordinfo'],
            ],
            'save'      => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => ['settings.admin.admin_setting/setrecordinfo'],
            ],
        ],
        //政策协议
        'protocol'     => [
            'page_path'     => '/setting/admin/protocol',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['settings.admin.admin_setting/getpolicyagreement'],
            ],
            'save'      => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => ['settings.admin.admin_setting/setpolicyagreement'],
            ],
        ],
        //支付方式
        'paymethod'     => [
            'page_path'     => '/setting/payment/pay_method',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['settings.pay.payway/getpayway'],
            ],
            'save'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['settings.pay.payway/setpayway'],
            ],
        ],
        //支付配置
        'payconfig'     => [
            'page_path'     => '/setting/payment/pay_config',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['settings.pay.payconfig/lists'],
            ],
            'edit'      => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => [
                    'settings.pay.payway/getconfig',
                    'settings.pay.payway/setconfig',
                ],
            ],
        ],
        //管理员
        'permissions'   => [
            'page_path'     => '/setting/permissions/admin',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['auth.admin/lists','auth.role/lists'],
            ],
            'manage'      => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => [
                    'auth.admin/add',
                    'auth.admin/edit',
                    'auth.admin/detail',
                    'auth.admin/del',
                ],
            ],

        ],
        //角色
        'role'          => [
            'page_path'     => '/setting/permissions/role',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['auth.role/lists'],
            ],
            'manage'      => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => [
                    'auth.role/add',
                    'auth.role/edit',
                    'auth.role/detail',
                    'auth.role/getMenu',
                    'auth.role/del',
                ],
            ],
        ],
        //储存设置
        'storage'           => [
            'page_path'     => '/setting/storage/index',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['settings.admin.storage/lists'],
            ],
            'manage'      => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => [
                    'settings.order.storage/change',
                    'settings.shop.storage/index',
                    'settings.shop.storage/setup'
                ],
            ],
        ],
        //系统日志
        'systemlog'     => [
            'page_path'     => '/setting/system_maintain/journal',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['settings.system.log/lists'],
            ],
        ],
        //系统缓存
        'systemcache'     => [
            'page_path'     => '/setting/system_maintain/cache',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => [],
            ],
            'clear'     => [
                'button_auth'   => ['auth_all'],
                'action_auth'   => ['settings.system.cache/clear'],
            ],
        ],
        //计划任务
        'task'     => [
            'page_path'     => '/setting/task',
            'view'      => [
                'button_auth'   => ['view'],
                'action_auth'   => ['crontab.crontab/lists'],
            ],
            'manage'    => [
                'button_auth'   => ['view'],
                'action_auth'   => [
                    'crontab.crontab/add',
                    'crontab.crontab/edit',
                    'crontab.crontab/operate',
                    'crontab.crontab/del',
                ],
            ],
        ],
    ],
];
