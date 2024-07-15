import Main from '@/layout/main.vue'
import Blank from '@/layout/blank.vue'
const routes = [
    {
        path: '/marketing',
        name: 'marketing',
        meta: { title: '营销' },
        redirect: '/marketing/redpocket',
        component: Main,
        children: [
            // {
            //     path: '/marketing/city_street',
            //     name: 'marketing_city_street',
            //     meta: {
            //         title: '县区管理',
            //         parentPath: '/marketing',
            //         icon: 'icon_shuju',
            //         permission: ['view']
            //     },
            //     component: () => import('@/views/marketing/region_lists.vue')
            // },
            {
                path: '/marketing/games',
                name: 'marketing_games',
                meta: {
                    title: '游戏管理',
                    parentPath: '/marketing',
                    icon: 'icon_dianpu_shoppingCar',
                    permission: ['view']
                },
                component: () => import('@/views/marketing/game_lists.vue')
            },
            {
                path: '/marketing/gameItems',
                name: 'marketing_game_item',
                meta: {
                    title: '游戏分类管理',
                    parentPath: '/marketing',
                    icon: 'icon_dianpu_shoppingCar',
                    permission: ['view']
                },
                component: () => import('@/views/marketing/game_item_lists.vue')
            },
            {
                path: '/marketing/category',
                name: 'marketing_game_category',
                meta: {
                    title: '场次管理',
                    parentPath: '/marketing',
                    icon: 'icon_dianpu_shoppingCar',
                    permission: ['view']
                },
                component: () => import('@/views/marketing/game_category_lists.vue')
            },
            {
                path: '/marketing/conversion',
                name: 'marketing_conversion',
                meta: {
                    title: '兑换管理',
                    parentPath: '/marketing',
                    icon: 'icon_dianpu_shoppingCar',
                    permission: ['view']
                },
                component: () => import('@/views/marketing/conversion_lists.vue')
            },
            {
                path: '/marketing/cheer',
                name: 'marketing_cheer',
                meta: {
                    title: '助威管理',
                    parentPath: '/marketing',
                    icon: 'icon_dianpu_shoppingCar',
                    permission: ['view']
                },
                component: () => import('@/views/marketing/cheer_lists.vue')
            },
            {
                path: '/marketing/qian',
                name: 'marketing_qian',
                meta: {
                    title: '求签管理',
                    parentPath: '/marketing',
                    icon: 'icon_dianpu_shoppingCar',
                    permission: ['view']
                },
                component: () => import('@/views/marketing/qian_lists.vue')
            },
            {
                path: '/marketing/rate',
                name: 'marketing_rate',
                meta: {
                    title: '救助管理',
                    parentPath: '/marketing',
                    icon: 'icon_dianpu_daohang'
                },
                component: Blank,
                redirect: '/marketing/rate/index',
                children: [
                    {
                        path: '/marketing/rate/index',
                        name: 'marketing_rate_index',
                        meta: {
                            title: '救助概览',
                            parentPath: '/marketing',
                            permission: ['view']
                        },
                        component: () => import('@/views/marketing/rate/survey.vue')
                    },
                    {
                        path: '/marketing/rate/rule',
                        name: 'marketing_rate_rule',
                        meta: {
                            title: '救助规则',
                            parentPath: '/marketing',
                            permission: ['view']
                        },
                        component: () => import('@/views/marketing/rate/rule.vue')
                    },
                    {
                        path: '/marketing/rate/await_help',
                        name: 'marketing_rate_await_help',
                        meta: {
                            title: '等待补助',
                            parentPath: '/marketing',
                            permission: ['view']
                        },
                        component: () => import('@/views/finance/user/game_help_record.vue')
                    },
                    {
                        path: '/marketing/rate/help',
                        name: 'marketing_rate_help',
                        meta: {
                            hidden: true,
                            title: '触发详情',
                            parentPath: '/marketing',
                            permission: ['view']
                        },
                        component: () => import('@/views/marketing/rate/rate_help_list.vue')
                    },
                    {
                        path: '/marketing/rate/game_rate_record',
                        name: 'marketing_game_rate_record',
                        meta: {
                            title: '胜率记录',
                            parentPath: '/marketing',
                            permission: ['view']
                        },
                        component: () => import('@/views/marketing/rate/game_rate.vue')
                    },
                    {
                        path: '/marketing/rate/help_records',
                        name: 'marketing_rate_help_records',
                        meta: {
                            title: '救助记录',
                            parentPath: '/marketing',
                            permission: ['view']
                        },
                        component: () => import('@/views/marketing/rate/help_records.vue')
                    }
                ]
            },
            {
                path: '/marketing/prizes',
                name: 'marketing_prizes',
                meta: {
                    title: '奖品管理',
                    parentPath: '/marketing',
                    icon: 'icon_user'
                },
                component: Blank,
                redirect: '/marketing/prizes/index',
                children: [
                    {
                        path: '/marketing/prizes/index',
                        name: 'marketing_prizes_index',
                        meta: {
                            title: '排行榜列表',
                            parentPath: '/marketing',
                            permission: ['view']
                        },
                        component: () => import('@/views/marketing/prizes/rank_lists.vue')
                    },
                    {
                        path: '/marketing/prizes/rule',
                        name: 'marketing_prizes_rule',
                        meta: {
                            hidden: true,
                            title: '奖品设置',
                            parentPath: '/marketing',
                            permission: ['view']
                        },
                        component: () => import('@/views/marketing/prizes/rule.vue')
                    },
                    {
                        path: '/marketing/prizes/player_rule',
                        name: 'marketing_prizes_player_rule',
                        meta: {
                            title: '对局奖品设置',
                            parentPath: '/marketing',
                            permission: ['view']
                        },
                        component: () => import('@/views/marketing/prizes/playerRule.vue')
                    },
                    {
                        path: '/marketing/prizes/records',
                        name: 'marketing_prizes_records',
                        meta: {
                            title: '抽奖记录',
                            parentPath: '/marketing',
                            permission: ['view']
                        },
                        component: () => import('@/views/marketing/prizes/lottery_records.vue')
                    }
                ]
            },
            {
                path: '/marketing/recharge',
                name: 'marketing_recharge',
                meta: {
                    title: '充值管理',
                    parentPath: '/marketing',
                    icon: 'icon_user'
                },
                component: Blank,
                redirect: '/marketing/recharge/agency',
                children: [
                    {
                        path: '/marketing/recharge/agency',
                        name: 'marketing_recharge_agency',
                        meta: {
                            title: '代理充值管理',
                            parentPath: '/marketing',
                            permission: ['view']
                        },
                        component: () => import('@/views/marketing/recharge/agency.vue')
                    },
                    {
                        path: '/marketing/recharge/player',
                        name: 'marketing_recharge_player',
                        meta: {
                            title: '用户充值管理',
                            parentPath: '/marketing',
                            permission: ['view']
                        },
                        component: () => import('@/views/marketing/recharge/player.vue')
                    }
                ]
            },
            {
                path: '/marketing/redpocket',
                name: 'marketing_redpocket',
                meta: {
                    title: '红包管理',
                    parentPath: '/marketing',
                    icon: 'icon_dianpu_shoppingCar',
                    permission: ['view']
                },
                component: () => import('@/views/marketing/redpocket_lists.vue')
            },
            {
                path: '/marketing/treasureBox',
                name: 'marketing_treasureBox',
                meta: {
                    title: '宝箱管理',
                    parentPath: '/marketing',
                    icon: 'icon_user'
                },
                component: Blank,
                redirect: '/marketing/treasureBox/lists',
                children: [
                    {
                        path: '/marketing/treasureBox/lists',
                        name: 'marketing_treasureBox_lists',
                        meta: {
                            title: '宝箱管理',
                            parentPath: '/marketing',
                            icon: 'icon_dianpu_shoppingCar',
                            permission: ['view']
                        },
                        component: () => import('@/views/marketing/treasure_box_lists.vue')
                    },
                    {
                        path: '/marketing/treasureBox/record',
                        name: 'marketing_treasureBox_record',
                        meta: {
                            title: '领取记录',
                            parentPath: '/marketing',
                            permission: ['view']
                        },
                        component: () => import('@/views/finance/user/game_rate.vue')
                    },
                ]
            },
        ]
    }
]

export default routes
