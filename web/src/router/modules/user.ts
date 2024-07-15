import Main from '@/layout/main.vue'

const routes = [
    {
        path: '/user',
        name: 'user',
        meta: { title: '用户' },
        redirect: '/user/profile',
        component: Main,
        children: [
            {
                path: '/user/profile',
                name: 'user_profile',
                meta: {
                    title: '基本概括',
                    parentPath: '/user',
                    icon: 'icon_user',
                    permission: ['view']
                },
                component: () => import('@/views/user/profile.vue')
            },
            {
                path: '/user/lists',
                name: 'user_list',
                meta: {
                    title: '用户管理',
                    parentPath: '/user',
                    icon: 'icon_user_guanli',
                    permission: ['view']
                },
                component: () => import('@/views/user/lists.vue')
            },
            {
                path: '/user/user_details',
                name: 'user_details',
                meta: {
                    title: '用户详情',
                    parentPath: '/user',
                    hidden: true,
                    prevPath: '/user/lists'
                },
                component: () => import('@/views/user/user_details.vue')
            },
            {
                path: '/user/recharge',
                name: 'user_recharge',
                meta: {
                    title: '充值记录',
                    parentPath: '/user',
                    permission: ['view'],
                    icon: 'icon_kdzs_fjrmb'
                },
                component: () => import('@/views/finance/user/account_log.vue')
            },
            {
                path: '/user/room',
                name: 'user_room',
                meta: {
                    title: '房间记录',
                    parentPath: '/user',
                    permission: ['view'],
                    icon: 'icon_pcshop'
                },
                component: () => import('@/views/finance/user/user_rooms.vue')
            },
            {
                path: '/user/gold',
                name: 'user_gold',
                meta: {
                    title: '金豆记录',
                    parentPath: '/user',
                    permission: ['view'],
                    icon: 'icon_xpdy_mbgl'
                },
                component: () => import('@/views/finance/user/gold_log.vue')
            },
            // {
            //     path: '/user/statistics_recharge',
            //     name: 'user_statistics_recharge',
            //     meta: {
            //         title: '充值统计',
            //         parentPath: '/user',
            //         permission: ['view'],
            //         icon: 'icon_xycj_cj'
            //     },
            //     component: () => import('@/views/finance/user/recharge_statistics.vue')
            // },
            // {
            //     path: '/user/gem',
            //     name: 'user_gem',
            //     meta: {
            //         title: '旧钻石记录',
            //         parentPath: '/user',
            //         permission: ['view'],
            //         icon: 'icon_kdzs_mdsz'
            //     },
            //     component: () => import('@/views/finance/user/gem_log.vue')
            // },
            {
                path: '/user/diamond',
                name: 'user_diamond',
                meta: {
                    title: '钻石记录',
                    parentPath: '/user',
                    permission: ['view'],
                    icon: 'icon_kdzs_mdsz'
                },
                component: () => import('@/views/finance/user/diamond_log.vue')
            },
            // {
            //     path: '/user/red_pocket',
            //     name: 'user_red_pocket',
            //     meta: {
            //         title: '红包领取',
            //         parentPath: '/user',
            //         permission: ['view'],
            //         icon: 'icon_xcxzb_zb'
            //     },
            //     component: () => import('@/views/finance/user/red_pocket_log.vue')
            // },
            // {
            //     path: '/user/red_pocket_withdraw',
            //     name: 'user_red_pocket_withdraw',
            //     meta: {
            //         title: '红包提现',
            //         parentPath: '/user',
            //         permission: ['view'],
            //         icon: 'operation'
            //     },
            //     component: () => import('@/views/finance/user/red_pocket_withdraw_log.vue')
            // },
            {
                path: '/user/room/detail',
                name: 'user_room_detail',
                meta: {
                    hidden: true,
                    title: '房间详情',
                    parentPath: '/user',
                    permission: ['view']
                },
                component: () => import('@/views/finance/user/user_rooms_details.vue')
            },
            {
                path: '/user/room/rule',
                name: 'user_room_rule',
                meta: {
                    hidden: true,
                    title: '对局详情',
                    parentPath: '/user',
                    permission: ['view']
                },
                component: () => import('@/views/finance/user/user_rooms_rule.vue')
            },
            {
                path: '/user/room/json',
                name: 'user_room_json',
                meta: {
                    hidden: true,
                    title: '规则详情',
                    parentPath: '/user',
                    permission: ['view']
                },
                component: () => import('@/views/finance/user/user_rooms_json.vue')
            },
            // {
            //     path: '/user/fourJokerGame',
            //     name: 'user_four_joker_game',
            //     meta: {
            //         title: '4王局',
            //         parentPath: '/user',
            //         permission: ['view'],
            //         icon: 'icon_user_guanli'
            //     },
            //     component: () => import('@/views/finance/user/four_joker_game.vue')
            // },
            // {
            //     path: '/user/fourJokerGame/detail',
            //     name: 'user_four_joker_game_detail',
            //     meta: {
            //         hidden: true,
            //         title: '4王局',
            //         parentPath: '/user',
            //         permission: ['view'],
            //         icon: 'icon_xpdy_dyjgl'
            //     },
            //     component: () => import('@/views/finance/user/four_joker_game_detail.vue')
            // },
            // {
            //     path: '/user/redpocket_ranking',
            //     name: 'user_redpocket_ranking',
            //     meta: {
            //         title: '红包排行榜',
            //         parentPath: '/user',
            //         permission: ['view'],
            //         icon: 'icon_kdzs_pldy'
            //     },
            //     component: () => import('@/views/finance/user/red_pocket_ranking.vue')
            // }
        ]
    }
]

export default routes
