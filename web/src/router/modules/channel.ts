import Main from '@/layout/main.vue'
import Blank from '@/layout/blank.vue'
const routes = [
    {
        path: '/channel',
        name: 'channel',
        meta: { title: '内容' },
        redirect: '/channel/mp_wechat',
        component: Main,
        children: [
            {
                path: '/channel/mp_wechat',
                name: 'mp_wechat',
                meta: {
                    title: '微信公众号',
                    parentPath: '/channel',
                    icon: 'icon_qudao_weixin'
                },
                component: Blank,
                redirect: '/channel/mp_wechat/index',
                children: [
                    {
                        path: '/channel/mp_wechat/index',
                        name: 'mp_wechat_index',
                        meta: {
                            title: '渠道设置',
                            parentPath: '/channel',
                            permission: ['view']
                        },
                        component: () => import('@/views/channel/mp_wechat/index.vue')
                    },
                    {
                        path: '/channel/mp_wechat/menu',
                        name: 'mp_wechat_menu',
                        meta: {
                            title: '菜单管理',
                            parentPath: '/channel',
                            permission: ['view']
                        },
                        component: () => import('@/views/channel/mp_wechat/menu.vue')
                    },
                    {
                        path: '/channel/mp_wechat/reply/follow_reply',
                        name: 'follow_reply',
                        meta: {
                            title: '关注回复',
                            parentPath: '/channel',
                            permission: ['view']
                        },
                        component: () => import('@/views/channel/mp_wechat/reply/follow_reply.vue')
                    },
                    {
                        path: '/channel/mp_wechat/reply/keyword_reply',
                        name: 'keyword_reply',
                        meta: {
                            title: '关键字回复',
                            parentPath: '/channel',
                            permission: ['view']
                        },
                        component: () => import('@/views/channel/mp_wechat/reply/keyword_reply.vue')
                    },
                    {
                        path: '/channel/mp_wechat/reply/default_reply',
                        name: 'default_reply',
                        meta: {
                            title: '默认回复',
                            parentPath: '/channel',
                            permission: ['view']
                        },
                        component: () => import('@/views/channel/mp_wechat/reply/default_reply.vue')
                    },
                    {
                        path: '/channel/mp_wechat/reply/reply_edit',
                        name: 'reply_edit',
                        meta: {
                            title: '默认编辑',
                            parentPath: '/channel',
                            hidden: true
                        },
                        component: () => import('@/views/channel/mp_wechat/reply/reply_edit.vue')
                    }
                ]
            },
            {
                path: '/channel/wechat_app/wechat_app',
                name: 'wechat_app',
                meta: {
                    title: '微信小程序',
                    parentPath: '/channel',
                    icon: 'icon_qudao_xiaochengxu'
                },
                component: Blank,
                redirect: '/channel/wechat_app/wechat_app',
                children: [
                    {
                        path: '/channel/wechat_app/wechat_app',
                        name: 'wechat_app',
                        meta: {
                            title: '小程序设置',
                            parentPath: '/channel',
                            permission: ['view']
                        },
                        component: () => import('@/views/channel/wechat_app/wechat_app.vue')
                    }
                ]
            },
            {
                path: '/channel/app_store/app_store',
                name: 'app_store',
                meta: {
                    title: 'APP',
                    parentPath: '/channel',
                    icon: 'icon_qudao_app'
                },
                component: Blank,
                redirect: '/channel/app_store/app_store',
                children: [
                    {
                        path: '/channel/app_store/app_store',
                        name: 'app_store',
                        meta: {
                            title: 'APP设置',
                            parentPath: '/channel',
                            permission: ['view']
                        },
                        component: () => import('@/views/channel/app_store/app_store.vue')
                    }
                ]
            },
            {
                path: '/channel/h5_store/h5_store',
                name: 'h5_store',
                meta: {
                    title: 'H5',
                    parentPath: '/channel',
                    icon: 'icon_qudao_h5'
                },
                component: Blank,
                redirect: '/channel/h5_store/h5_store',
                children: [
                    {
                        path: '/channel/h5_store/h5_store',
                        name: 'h5_store',
                        meta: {
                            title: 'H5设置',
                            parentPath: '/channel',
                            permission: ['view']
                        },
                        component: () => import('@/views/channel/h5_store/h5_store.vue')
                    }
                ]
            },
            {
                path: '/channel/mp_toutiao',
                name: 'mp_toutiao',
                meta: {
                    title: '字节小程序',
                    parentPath: '/channel',
                    icon: 'icon_dianpu_xiangqing'
                },
                component: Blank,
                redirect: '/channel/mp_toutiao/index',
                children: [
                    {
                        path: '/channel/mp_toutiao/index',
                        name: 'mp_toutiao',
                        meta: {
                            title: '小程序设置',
                            parentPath: '/channel',
                            permission: ['view']
                        },
                        component: () => import('@/views/channel/mp_toutiao/index.vue')
                    }
                ]
            },
            {
                path: '/channel/pc_store',
                name: 'pc_store',
                meta: {
                    title: 'PC商城',
                    parentPath: '/channel',
                    icon: 'icon_pcshop'
                },
                component: Blank,
                redirect: '/channel/pc_store/index',
                children: [
                    {
                        path: '/channel/pc_store/index',
                        name: 'pc_store',
                        meta: {
                            title: '渠道设置',
                            parentPath: '/channel',
                            permission: ['view']
                        },
                        component: () => import('@/views/channel/pc_store/index.vue')
                    }
                ]
            }
        ]
    }
]

export default routes
