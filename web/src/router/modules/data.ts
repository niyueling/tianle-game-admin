import Main from '@/layout/main.vue'
import Blank from '@/layout/blank.vue'
const routes = [
    {
        path: '/data',
        name: 'data',
        meta: { title: '数据' },
        redirect: '/data/room',
        component: Main,
        children: [
            {
                path: '/data/room',
                name: 'room_analysis',
                meta: {
                    title: '房间分析',
                    parentPath: '/data',
                    icon: 'icon_shuju_liuliang',
                    permission: ['view']
                },
                component: () => import('@/views/data/room_analysis.vue')
            },
            {
                path: '/data/user',
                name: 'user',
                meta: {
                    title: '用户分析',
                    parentPath: '/data',
                    icon: 'icon_shuju',
                    permission: ['view']
                },
                component: () => import('@/views/data/user.vue')
            },
            {
                path: '/data/recharge',
                name: 'recharge',
                meta: {
                    title: '充值分析',
                    parentPath: '/data',
                    icon: 'icon_dianpu_shoppingCar',
                    permission: ['view']
                },
                component: () => import('@/views/data/recharge.vue')
            },
            // {
            //     path: '/data/consume',
            //     name: 'consume',
            //     meta: {
            //         title: '消耗分析',
            //         parentPath: '/data',
            //         icon: 'icon_dianpu_shoppingCar',
            //         permission: ['view']
            //     },
            //     component: () => import('@/views/data/consume.vue')
            // },
            {
                path: '/data/visitor',
                name: 'visitor',
                meta: {
                    title: '访问分析',
                    parentPath: '/data',
                    icon: 'icon_dianpu_shoppingCar',
                    permission: ['view']
                },
                component: () => import('@/views/data/visitor_analysis.vue')
            }
        ]
    }
]

export default routes
