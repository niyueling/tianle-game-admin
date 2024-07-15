import Main from '@/layout/main.vue'
import Blank from '@/layout/blank.vue'
const routes = [
    {
        path: '/clubs',
        name: 'clubs',
        meta: { title: '俱乐部' },
        redirect: '/clubs/lists',
        component: Main,
        children: [
            {
                path: '/clubs/lists',
                name: 'clubs_lists',
                meta: {
                    title: '俱乐部管理',
                    parentPath: '/clubs',
                    icon: 'icon_goods',
                    permission: ['view']
                },
                component: () => import('@/views/clubs/lists.vue')
            },
            {
                path: '/clubs/clubs_detail',
                name: 'clubs_detail',
                meta: {
                    hidden: true,
                    title: '俱乐部详情',
                    parentPath: '/clubs',
                    icon: 'icon_dianpu_xiangqing'
                },
                component: () => import('@/views/clubs/clubs_details.vue')
            },
            {
                path: '/clubs/apply',
                name: 'clubs_apply',
                meta: {
                    hidden: true,
                    title: '成员审核',
                    parentPath: '/clubs',
                    icon: 'icon_dianpu_sucai',
                    permission: ['view']
                },
                component: () => import('@/views/clubs/apply_lists.vue')
            },
            {
                path: '/clubs/members',
                name: 'clubs_members',
                meta: {
                    hidden: true,
                    title: '成员管理',
                    parentPath: '/clubs',
                    icon: 'icon_dianpu_sucai',
                    permission: ['view']
                },
                component: () => import('@/views/clubs/club_members.vue')
            },
            {
                path: '/clubs/blacks',
                name: 'clubs_blacks',
                meta: {
                    hidden: true,
                    title: '黑名单管理',
                    parentPath: '/clubs',
                    icon: 'icon_dianpu_sucai',
                    permission: ['view']
                },
                component: () => import('@/views/clubs/club_blacks.vue')
            },
            {
                path: '/clubs/recharge',
                name: 'clubs_recharge',
                meta: {
                    title: '流水记录',
                    parentPath: '/clubs',
                    permission: ['view'],
                    icon: 'icon_kdzs_fjrmb'
                },
                component: () => import('@/views/finance/club/account_log.vue')
            },
            {
                path: '/clubs/gold',
                name: 'clubs_gold',
                meta: {
                    title: '金币记录',
                    parentPath: '/clubs',
                    permission: ['view'],
                    icon: 'icon_xycj_cj'
                },
                component: () => import('@/views/finance/user/gold_log.vue')
            },
            {
                path: '/clubs/room',
                name: 'clubs_room',
                meta: {
                    title: '房间记录',
                    parentPath: '/clubs',
                    permission: ['view'],
                    icon: 'icon_kdzs_mdsz'
                },
                component: () => import('@/views/finance/club/club_rooms.vue')
            },
            {
                path: '/clubs/room/detail',
                name: 'clubs_room_detail',
                meta: {
                    hidden: true,
                    title: '房间详情',
                    parentPath: '/clubs',
                    permission: ['view'],
                    icon: 'icon_xycj_cj'
                },
                component: () => import('@/views/finance/club/club_rooms_details.vue')
            },
            {
                path: '/clubs/room_ranking',
                name: 'clubs_room_ranking',
                meta: {
                    title: '俱乐部排行榜',
                    parentPath: '/clubs',
                    permission: ['view'],
                    icon: 'icon_xpdy_mbgl'
                },
                component: () => import('@/views/finance/club/room_ranking.vue')
            },
            {
                path: '/clubs/creator_gold',
                name: 'clubs_creator_gold',
                meta: {
                    title: '金币管理记录',
                    parentPath: '/clubs',
                    permission: ['view'],
                    icon: 'icon_xcxzb_zb'
                },
                component: () => import('@/views/finance/user/club_creator_gold_log.vue')
            }
        ]
    }
]

export default routes
