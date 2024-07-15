import Main from '@/layout/main.vue'
const routes = [
    {
        path: '/agency',
        name: 'agency',
        meta: { title: '代理' },
        redirect: '/agency/index',
        component: Main,
        children: [
            {
                path: '/agency/index',
                name: 'agency_index',
                meta: {
                    title: '代理列表',
                    parentPath: '/agency',
                    icon: 'icon_dianpu_shoppingCar'
                },
                component: () => import('@/views/agency/lists.vue')
            },
            {
                path: '/agency/agency_detail',
                name: 'agency_detail',
                meta: {
                    hidden: true,
                    title: '代理详情',
                    parentPath: '/agency',
                    icon: 'icon_dianpu_xiangqing'
                },
                component: () => import('@/views/agency/agency_details.vue')
            },
            {
                path: '/agency/recharge',
                name: 'agency_recharge',
                meta: {
                    title: '充值记录',
                    parentPath: '/agency',
                    permission: ['view'],
                    icon: 'icon_kdzs_mdsz'
                },
                component: () => import('@/views/finance/agency/recharge_log.vue')
            },
            {
                path: '/agency/gem',
                name: 'agency_gem',
                meta: {
                    title: '钻石记录',
                    parentPath: '/agency',
                    permission: ['view'],
                    icon: 'icon_xpdy_mbgl'
                },
                component: () => import('@/views/finance/agency/gem_log.vue')
            },
            {
                path: '/agency/auto_recharge',
                name: 'agency_auto_recharge',
                meta: {
                    title: '自助充值记录',
                    parentPath: '/agency',
                    permission: ['view'],
                    icon: 'icon_xcxzb_zb'
                },
                component: () => import('@/views/finance/agency/auto_recharge.vue')
            },
            {
                path: '/agency/apply',
                name: 'agency_apply',
                meta: {
                    hidden: true,
                    title: '成员审核',
                    parentPath: '/agency',
                    icon: 'icon_dianpu_sucai',
                    permission: ['view']
                },
                component: () => import('@/views/agency/apply_lists.vue')
            }
        ]
    }
]

export default routes
