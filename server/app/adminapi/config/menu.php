<?php
/**
 * todo
 * name:菜单、权限名称
 * type：类型：1-菜单；2-权限
 * sons:子级菜单
 * ----auth_key：权限key(必须唯一)
 */
return [
    //首页
    [
        'name'  => '首页',
        'type'  => 1,
        'sons'  => [
            [
                'name'    => '首页',
                'type'    => 1,
                'sons'    => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'index/index.view'
                    ]
                ],
            ]
        ]
    ],
    //代理
    [
        'name'  => '代理',
        'type'  => 1,
        'sons'=>[
            [
                'name'  => '代理列表',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '列表',
                        'type'      => 2,
                        'auth_key'  => 'agency/index.view'
                    ],
                    [
                        'name'      => '详情',
                        'type'      => 2,
                        'auth_key'  => 'agency/index.detail'
                    ],
                    [
                        'name'      => '编辑',
                        'type'      => 2,
                        'auth_key'  => 'agency/index.edit'
                    ],
                    [
                        'name'      => '充值',
                        'type'      => 2,
                        'auth_key'  => 'agency/index.recharge'
                    ],
                    [
                        'name'      => '绑定俱乐部',
                        'type'      => 2,
                        'auth_key'  => 'agency/index.bind'
                    ],
                ],
            ],
            [
                'name'  => '自助充值方案',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'agency/user.view'
                    ]
                ],
            ],
            [
                'name'  => '自助充值',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'agency/recharge.view'
                    ]
                ],
            ]
        ]
    ],
    //俱乐部
    [
        'name'  => '俱乐部',
        'type'  => 1,
        'sons'  => [
            [
                'name'    => '俱乐部管理',
                'type'    => 1,
                'sons'    => [
                    [
                        'name'      => '列表',
                        'type'      => 2,
                        'auth_key'  => 'clubs/lists.view'
                    ],
                    [
                        'name'      => '详情',
                        'type'      => 2,
                        'auth_key'  => 'clubs/lists.detail'
                    ],
                    [
                        'name'      => '历史房间',
                        'type'      => 2,
                        'auth_key'  => 'clubs/lists.room'
                    ],
                    [
                        'name'      => '实时房间',
                        'type'      => 2,
                        'auth_key'  => 'clubs/lists.online'
                    ],
                    [
                        'name'      => '成员管理',
                        'type'      => 2,
                        'sons'    => [
                            [
                                'name'      => '列表',
                                'type'      => 3,
                                'auth_key'  => 'clubs/lists.member'
                            ],
                            [
                                'name'      => '管理',
                                'type'      => 3,
                                'auth_key'  => 'clubs/lists.manager'
                            ],
                            [
                                'name'      => '成员审核',
                                'type'      => 3,
                                'auth_key'  => 'clubs/lists.apply'
                            ],
                            [
                                'name'      => '黑名单管理',
                                'type'      => 3,
                                'auth_key'  => 'clubs/lists.blacklist'
                            ],
                            [
                                'name'      => '金币记录',
                                'type'      => 3,
                                'auth_key'  => 'clubs/lists.gold'
                            ]
                        ]
                    ],
                ],
            ]
        ],
    ],
    //用户
    [
        'name'  => '用户',
        'type'  => 1,
        'sons'=>[
            [
                'name'  => '基本概括',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'user/profile.view'
                    ]
                ],
            ],
            [
                'name'  => '用户管理',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '列表',
                        'type'      => 2,
                        'auth_key'  => 'user/lists.view'
                    ],
                    [
                        'name'      => '俱乐部管理',
                        'type'      => 2,
                        'auth_key'  => 'clubs/lists.view',
                    ],
                    [
                        'name'      => '历史房间',
                        'type'      => 2,
                        'auth_key'  => 'user/lists.room',
                    ],
                    [
                        'name'      => '实时房间',
                        'type'      => 2,
                        'auth_key'  => 'user/lists.online',
                    ],
                    [
                        'name'      => '金币记录',
                        'type'      => 2,
                        'auth_key'  => 'finance/user.gold',
                    ],
                    [
                        'name'      => '钻石记录',
                        'type'      => 2,
                        'auth_key'  => 'finance/user.gem'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'user/lists.manager'
                    ]

                ],
            ],
        ]
    ],
    //财务
    [
        'name'  => '财务',
        'type'  => 1,
        'sons'=>[
            [
                'name'  => '用户',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '充值记录',
                        'type'      => 2,
                        'auth_key'  => 'finance/user.recharge'
                    ],
                    [
                        'name'      => '充值统计',
                        'type'      => 2,
                        'auth_key'  => 'finance/user.statisticsrecharge'
                    ],
                    [
                        'name'      => '钻石记录',
                        'type'      => 2,
                        'auth_key'  => 'finance/user.gem'
                    ],
                    [
                        'name'      => '金币记录',
                        'type'      => 2,
                        'auth_key'  => 'finance/user.gold'
                    ],
                    [
                        'name'      => '红包领取',
                        'type'      => 2,
                        'auth_key'  => 'finance/user.redpocket'
                    ],
                    [
                        'name'      => '红包提现',
                        'type'      => 2,
                        'auth_key'  => 'finance/user.withdraw'
                    ],
                    [
                        'name'      => '房间记录',
                        'type'      => 2,
                        'auth_key'  => 'finance/user.room'
                    ],
                    [
                        'name'      => '红包排行榜',
                        'type'      => 2,
                        'auth_key'  => 'finance/user.redpocketranking'
                    ],
                    [
                        'name'      => '游戏胜率',
                        'type'      => 2,
                        'auth_key'  => 'finance/user.gamerate'
                    ],
                ],
            ],
            [
                'name'  => '代理',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '充值记录',
                        'type'      => 2,
                        'auth_key'  => 'finance/agency.recharge'
                    ],
                    [
                        'name'      => '钻石记录',
                        'type'      => 2,
                        'auth_key'  => 'finance/agency.gem'
                    ],
                    [
                        'name'      => '自助充值记录',
                        'type'      => 2,
                        'auth_key'  => 'finance/agency.autorecharge'
                    ]

                ],
            ],
            [
                'name'  => '俱乐部',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '流水记录',
                        'type'      => 2,
                        'auth_key'  => 'finance/club.recharge'
                    ],
                    [
                        'name'      => '金币记录',
                        'type'      => 2,
                        'auth_key'  => 'finance/club.gold'
                    ],
                    [
                        'name'      => '房间记录',
                        'type'      => 2,
                        'auth_key'  => 'finance/club.room'
                    ],
                    [
                        'name'      => '房间排行榜',
                        'type'      => 2,
                        'auth_key'  => 'finance/club.ranking'
                    ],
                    [
                        'name'      => '金币管理记录',
                        'type'      => 2,
                        'auth_key'  => 'finance/club.creator'
                    ]
                ],
            ],
            [
                'name'  => '商品',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '订单记录',
                        'type'      => 2,
                        'auth_key'  => 'finance/goods.order'
                    ]
                ],
            ],
        ]
    ],
    //营销
    [
        'name'  => '营销',
        'type'  => 1,
        'sons'=>[
            [
                'name'  => '商品管理',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '列表',
                        'type'      => 2,
                        'auth_key'  => 'marketing/goods.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'marketing/goods.manager'
                    ]
                ],
            ],
            [
                'name'  => '县区管理',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '列表',
                        'type'      => 2,
                        'auth_key'  => 'marketing/city_street.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'marketing/city_street.manager'
                    ]
                ],
            ],
            [
                'name'  => '游戏管理',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '列表',
                        'type'      => 2,
                        'auth_key'  => 'marketing/games.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'marketing/games.manager'
                    ]
                ],
            ],
            [
                'name'  => '红包管理',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '列表',
                        'type'      => 2,
                        'auth_key'  => 'marketing/redpocket.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'marketing/redpocket.manager'
                    ]
                ],
            ],
        ]
    ],
    //数据
    [
        'name'  => '数据',
        'type'  => 1,
        'sons'=>[
            [
                'name'  => '房间分析',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'data/room.view'
                    ],
                ],
            ],
            [
                'name'  => '用户分析',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'data/user.view'
                    ],
                ],
            ],
            [
                'name'  => '充值分析',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'data/recharge.view'
                    ],
                ],
            ],
            [
                'name'  => '消耗分析',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'data/consume.view'
                    ],
                ],
            ],
            [
                'name'  => '访问分析',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'data/visitor.view'
                    ],
                ],
            ]
        ]
    ],
    //内容
    [
        'name'  => '内容',
        'type'  => 1,
        'sons'=>[
            [
                'name'  => '公告管理',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'content/notice.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'content/notice.manager'
                    ],
                ],
            ],
            [
                'name'  => '私信邮件',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'content/email.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'content/email.manager'
                    ],
                ],
            ],
            [
                'name'  => '公共邮件',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'content/public_email.view'
                    ],
                    [
                        'name'      => '领取记录',
                        'type'      => 2,
                        'auth_key'  => 'content/public_email.record'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'content/public_email.manager'
                    ],
                ],
            ],
            [
                'name'  => '圈主邮件',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'content/main_email.view'
                    ],
                    [
                        'name'      => '领取记录',
                        'type'      => 2,
                        'auth_key'  => 'content/public_email.record'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'content/main_email.manager'
                    ],
                ],
            ]
        ]
    ],
    //设置
    [
        'name'  => '设置',
        'type'  => 1,
        'sons'=>[
            [
                'name'  => '店铺信息',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/admin.view'
                    ],
                    [
                        'name'      => '保存',
                        'type'      => 2,
                        'auth_key'  => 'setting/admin.save'
                    ],
                ],
            ],
            [
                'name'  => '版权资质',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/record.view'
                    ],
                    [
                        'name'      => '保存',
                        'type'      => 2,
                        'auth_key'  => 'setting/record.save'
                    ],
                ],
            ],
            [
                'name'  => '政策协议',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/protocol.view'
                    ],
                    [
                        'name'      => '保存',
                        'type'      => 2,
                        'auth_key'  => 'setting/protocol.save'
                    ],
                ],
            ],
            [
                'name'  => '支付方式',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/paymethod.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'setting/paymethod.save'
                    ],
                ],
            ],
            [
                'name'  => '支付配置',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/payconfig.view'
                    ],
                    [
                        'name'      => '编辑',
                        'type'      => 2,
                        'auth_key'  => 'setting/payconfig.edit'
                    ],
                ],
            ],
            [
                'name'  => '管理员',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/permissions.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'setting/permissions.manage'
                    ],
                ],
            ],
            [
                'name'  => '角色',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/role.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'setting/role.manage'
                    ],
                ]
            ],
            [
                'name'  => '存储设置',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/storage.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'setting/storage.manage'
                    ],
                ],
            ],
            [
                'name'  => '系统日志',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/systemlog.view'
                    ],
                ],
            ],
            [
                'name'  => '系统缓存',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/systemcache.view'
                    ],
                    [
                        'name'      => '清除系统缓存',
                        'type'      => 2,
                        'auth_key'  => 'setting/systemcache.clear'
                    ],
                ],
            ],
            [
                'name'  => '定时任务',
                'type'  => 1,
                'sons'  => [
                    [
                        'name'      => '查看',
                        'type'      => 2,
                        'auth_key'  => 'setting/task.view'
                    ],
                    [
                        'name'      => '管理',
                        'type'      => 2,
                        'auth_key'  => 'setting/task.manage'
                    ],
                ],
            ],
        ]
    ]
];



