import Vue from 'vue'
import VueRouter, { RouteConfig } from 'vue-router'
import Secondary from '@/layout/secondary.vue'
import index from './modules'
import user from './modules/user'
import marketing from './modules/marketing'
import data from './modules/data'
import content from './modules/content'
import setting from './modules/setting'
Vue.use(VueRouter)

export const asyncRoutes: Array<RouteConfig> = [
    ...index, //首页
    ...user, //用户
    ...data, //数据
    ...marketing, //营销
    ...content, //内容
    ...setting //系统
]

const constantRoutes: Array<RouteConfig> = [
    {
        path: '*',
        redirect: '/error/404'
    },
    {
        path: '/account',
        name: 'account',
        component: Secondary,
        redirect: '/account/login',
        children: [
            {
                path: '/account/login',
                name: 'login',
                meta: {
                    title: '登录',
                    parentPath: '/account'
                },
                component: () => import('@/views/account/login.vue')
            }
        ]
    },
    {
        path: '/error',
        name: 'error',
        component: Secondary,
        redirect: '/error/404',
        children: [
            {
                path: '/error/404',
                name: 'error_404',
                meta: {
                    title: '404',
                    parentPath: '/error'
                },
                component: () => import('@/views/error/404.vue')
            },
            {
                path: '/error/500',
                name: 'error_500',
                meta: {
                    title: '500',
                    parentPath: '/error'
                },
                component: () => import('@/views/error/500.vue')
            }
        ]
    }
]

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes: [...asyncRoutes, ...constantRoutes]
})

export default router
