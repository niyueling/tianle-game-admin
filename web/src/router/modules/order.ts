import Main from '@/layout/main.vue'
const routes = [
    {
        path: '/order',
        name: 'order',
        meta: { title: '复审' },
        redirect: '/order/order',
        component: Main,
        children: [
            {
                path: '/order/order',
                name: 'order_order',
                meta: {
                    title: '订单管理',
                    parentPath: '/order',
                    icon: 'icon_order_guanli',
                    permission: ['view']
                },
                component: () => import('@/views/order/order.vue')
            },
            {
                path: '/order/order_detail',
                name: 'order_detail',
                meta: {
                    hidden: true,
                    title: '订单详情',
                    parentPath: '/order',
                    prevPath: '/order/order'
                },
                component: () => import('@/views/order/order_detail.vue')
            },
            {
                path: '/order/after_sales',
                name: 'after_sales',
                meta: {
                    title: '售后订单',
                    parentPath: '/order',
                    icon: 'icon_order_shouhou',
                    permission: ['view']
                },
                component: () => import('@/views/order/after_sales.vue')
            },
            {
                path: '/order/after_sales_detail',
                name: 'after_sales_detail',
                meta: {
                    hidden: true,
                    title: '售后订单详情',
                    parentPath: '/order'
                },
                component: () => import('@/views/order/after_sales_detail.vue')
            }
        ]
    }
]

export default routes
