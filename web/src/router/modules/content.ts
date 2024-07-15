import Main from '@/layout/main.vue'
const routes = [
    {
        path: '/content',
        name: 'content',
        meta: { title: '内容' },
        redirect: '/content/notice',
        component: Main,
        children: [
            {
                path: '/content/notice',
                name: 'content_notice',
                meta: {
                    title: '公告管理',
                    parentPath: '/content',
                    icon: 'icon_yingyongcenter',
                    permission: ['view']
                },
                component: () => import('@/views/content/notice/lists.vue')
            },
            {
                path: '/content/email',
                name: 'content_email',
                meta: {
                    title: '私信邮件',
                    parentPath: '/content',
                    icon: 'icon_yingyongcenter',
                    permission: ['view']
                },
                component: () => import('@/views/content/email/lists.vue')
            },
            {
                path: '/content/public_email',
                name: 'content_public_email',
                meta: {
                    title: '公共邮件',
                    parentPath: '/content',
                    icon: 'icon_yingyongcenter',
                    permission: ['view']
                },
                component: () => import('@/views/content/email/public_lists.vue')
            },
            {
                path: '/content/main_email',
                name: 'content_main_email',
                meta: {
                    title: '圈主邮件',
                    parentPath: '/content',
                    icon: 'icon_yingyongcenter',
                    permission: ['view']
                },
                component: () => import('@/views/content/email/main_lists.vue')
            },
            {
                path: '/content/email_receive',
                name: 'content_email_receive',
                meta: {
                    hidden: true,
                    title: '领取记录',
                    parentPath: '/content',
                    icon: 'icon_yingyongcenter',
                    permission: ['view']
                },
                component: () => import('@/views/content/email/receive_lists.vue')
            }
        ]
    }
]

export default routes
