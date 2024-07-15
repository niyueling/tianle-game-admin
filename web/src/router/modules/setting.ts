import Main from '@/layout/main.vue'
import Blank from '@/layout/blank.vue'
const routes = [
    {
        path: '/setting',
        name: 'setting',
        meta: { title: '系统' },
        redirect: '/setting/admin',
        component: Main,
        children: [
            {
                path: '/setting/admin',
                name: 'setting_admin',
                meta: {
                    title: '基本设置',
                    parentPath: '/setting',
                    icon: 'icon_set_store'
                },
                component: Blank,
                redirect: '/setting/admin/index',
                children: [
                    {
                        path: '/setting/admin/index',
                        name: 'setting_admin_index',
                        meta: {
                            title: '基本信息',
                            parentPath: '/setting',
                            permission: ['view']
                        },
                        component: () => import('@/views/setting/admin/admin.vue')
                    },
                    {
                        path: '/setting/admin/protocol',
                        name: 'setting_admin_protocol',
                        meta: {
                            title: '用户协议',
                            parentPath: '/setting',
                            permission: ['view']
                        },
                        component: () => import('@/views/setting/admin/protocol.vue')
                    }
                ]
            },
            {
                path: '/setting/payment/pay_method',
                name: 'setting_payment',
                meta: {
                    title: '支付设置',
                    parentPath: '/setting',
                    icon: 'icon_set_pay'
                },
                component: Blank,
                redirect: '/setting/payment/pay_method',
                children: [
                    {
                        path: '/setting/payment/pay_method',
                        name: 'setting_pay_method',
                        meta: {
                            title: '支付方式',
                            parentPath: '/setting',
                            permission: ['view']
                        },
                        component: () => import('@/views/setting/payment/pay_method.vue')
                    },
                    {
                        path: '/setting/payment/pay_config',
                        name: 'setting_pay_config',
                        meta: {
                            title: '支付配置',
                            parentPath: '/setting',
                            permission: ['view']
                        },
                        component: () => import('@/views/setting/payment/pay_config.vue')
                    },
                    {
                        path: '/setting/payment/pay_method_edit',
                        name: 'setting_pay_method_edit',
                        meta: {
                            hidden: true,
                            title: '支付方式设置',
                            parentPath: '/setting',
                            prevPath: '/setting/payment/pay_method'
                        },
                        component: () => import('@/views/setting/payment/pay_method_edit.vue')
                    },
                    {
                        path: '/setting/payment/pay_config_edit',
                        name: 'setting_pay_config_edit',
                        meta: {
                            hidden: true,
                            title: '支付配置设置',
                            parentPath: '/setting',
                            prevPath: '/setting/payment/pay_config'
                        },
                        component: () => import('@/views/setting/payment/pay_config_edit.vue')
                    }
                ]
            },
            {
                path: '/setting/sms',
                name: 'setting_sms',
                meta: {
                    title: '通知设置',
                    parentPath: '/setting',
                    icon: 'icon_set_pay'
                },
                component: Blank,
                redirect: '/setting/sms/index',
                children: [
                    {
                        path: '/setting/sms/index',
                        name: 'setting_sms_index',
                        meta: {
                            title: '短信设置',
                            parentPath: '/setting',
                            permission: ['view']
                        },
                        component: () => import('@/views/sms/sms.vue')
                    },
                    {
                        path: '/setting/sms/edit',
                        name: 'setting_sms_edit',
                        meta: {
                            hidden: true,
                            title: '修改配置',
                            parentPath: '/setting',
                            permission: ['view']
                        },
                        component: () => import('@/views/sms/sms_edit.vue')
                    },
                    {
                        path: '/setting/sms/notice',
                        name: 'setting_sms_notice',
                        meta: {
                            title: '短信通知',
                            parentPath: '/setting',
                            permission: ['view']
                        },
                        component: () => import('@/views/sms/buyers/buyers.vue')
                    },
                    {
                        path: '/setting/sms/sms',
                        name: 'setting_sms_sms',
                        meta: {
                            title: '验证码',
                            parentPath: '/setting',
                            permission: ['view']
                        },
                        component: () => import('@/views/sms/buyers/business.vue')
                    },
                    {
                        path: '/setting/sms/settings',
                        name: 'setting_sms_settings',
                        meta: {
                            hidden: true,
                            title: '修改模板',
                            parentPath: '/setting',
                            permission: ['view']
                        },
                        component: () => import('@/views/sms/buyers/setting.vue')
                    }
                ]
            },
            {
                path: '/setting/permissions',
                name: 'admin',
                meta: {
                    title: '平台权限',
                    parentPath: '/setting',
                    icon: 'icon_set_quanxian'
                },
                component: Blank,
                redirect: '/setting/permissions/admin',
                children: [
                    {
                        path: '/setting/permissions/admin',
                        name: 'permissions_admin',
                        meta: {
                            title: '管理员',
                            parentPath: '/setting',
                            permission: ['view']
                        },
                        component: () => import('@/views/setting/permissions/admin.vue')
                    },
                    {
                        path: '/setting/permissions/admin_edit',
                        name: 'permissions_admin_edit',
                        meta: {
                            hidden: true,
                            title: '管理员',
                            parentPath: '/setting',
                            prevPath: '/setting/permissions/admin'
                        },
                        component: () => import('@/views/setting/permissions/admin_edit.vue')
                    },
                    {
                        path: '/setting/permissions/role',
                        name: 'permissions_role',
                        meta: {
                            title: '角色',
                            parentPath: '/setting',
                            permission: ['view']
                        },
                        component: () => import('@/views/setting/permissions/role.vue')
                    },
                    {
                        path: '/setting/permissions/role_edit',
                        name: 'permissions_role_edit',
                        meta: {
                            hidden: true,
                            title: '编辑角色',
                            parentPath: '/setting',
                            prevPath: '/setting/permissions/role'
                        },
                        component: () => import('@/views/setting/permissions/role_edit.vue')
                    }
                ]
            },
            {
                path: '/setting/storage/storage',
                name: 'order',
                meta: {
                    title: '存储设置',
                    parentPath: '/setting',
                    icon: 'icon_set_save'
                },
                component: Blank,
                redirect: '/setting/storage/storage',
                children: [
                    {
                        path: '/setting/storage/storage',
                        name: 'setting_storage',
                        meta: {
                            title: '设置A',
                            parentPath: '/setting',
                            hidden: true
                        },
                        component: () => import('@/views/setting/storage/storage.vue')
                    },
                    {
                        path: '/setting/storage/index',
                        name: 'setting_storage_index',
                        meta: {
                            title: '存储设置',
                            parentPath: '/setting',
                            permission: ['view']
                        },
                        component: () => import('@/views/setting/storage/index.vue')
                    }
                ]
            },
            {
                path: '/setting/system_maintain/journal',
                name: 'system_maintain',
                meta: {
                    title: '系统维护',
                    parentPath: '/setting',
                    icon: 'icon_set_weihu'
                },
                component: Blank,
                redirect: '/setting/system_maintain/journal',
                children: [
                    {
                        path: '/setting/system_maintain/journal',
                        name: 'journal',
                        meta: {
                            title: '系统日志',
                            parentPath: '/setting',
                            permission: ['view']
                        },
                        component: () => import('@/views/setting/system_maintain/journal.vue')
                    },
                    {
                        path: '/setting/system_maintain/cache',
                        name: 'cache',
                        meta: {
                            hidden: false,
                            title: '系统缓存',
                            parentPath: '/setting',
                            permission: ['view']
                        },
                        component: () => import('@/views/setting/system_maintain/cache.vue')
                    },
                    {
                        path: '/setting/task',
                        name: 'task',
                        meta: {
                            title: '定时任务',
                            parentPath: '/setting',
                            permission: ['view']
                        },
                        component: () => import('@/views/setting/task/task.vue')
                    },
                    {
                        path: '/setting/task_edit',
                        name: 'task_edit',
                        meta: {
                            hidden: true,
                            title: '定时任务',
                            parentPath: '/setting'
                        },
                        component: () => import('@/views/setting/task/task_edit.vue')
                    }
                ]
            }
        ]
    }
]

export default routes
