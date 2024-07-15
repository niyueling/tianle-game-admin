'use strict'

import Vue from 'vue'
import store from '@/store'
import axios from 'axios'
import configs from '@/config'
import { APIResponseCodeType } from '@/utils/type'
import router from '@/router'
import { Message } from 'element-ui'
import { throttle } from '@/utils/util'

const config = {
    baseURL: `${configs.baseURL}/adminapi`,
    headers: {
        'content-type': 'content/json'
    }
    // baseURL: '/'
    // timeout: 60 * 1000, // Timeout
    // withCredentials: true, // Check cross-site Access-Control
}

// 事件集
const eventResponse: any = {
    // 成功
    success({ show, msg, data }: any): any {
        if (show * 1) {
            Message({ type: 'success', message: msg })
        }
        return data
    },
    // 失败
    error({ show, msg }: any): any {
        if (show * 1) {
            Message({ type: 'error', message: msg })
        }
        return Promise.reject()
    },
    // 重定向
    redirect: throttle(() => {
        store.commit('setToken', '')
        store.commit('setUserInfo', {})
        store.commit('setPermission', {})
        router.push('/account/login')
        return Promise.reject()
    }),
    // 打开新的页面
    page({ data }: any): any {
        window.location.href = data.url
        return data
    }
}

const _axios = axios.create(config)

_axios.interceptors.request.use(
    config => {
        const token: string = store.getters.token
        // header参入Token
        if (token) {
            config.headers.token = token
        }
        config.headers.version = configs.version
        return config
    },
    error => {
        return Promise.reject(error)
    }
)

// Add a response interceptor
_axios.interceptors.response.use(
    (response): any => {
        const { code } = response.data

        return eventResponse[APIResponseCodeType[code]].call(this, response.data)
    },
    error => {
        Message({ type: 'error', message: '系统错误，请稍候再试' })
        return Promise.reject(error)
    }
)

const plugin = {
    install: (Vue: any) => {
        Vue.axios = _axios
        Object.defineProperties(Vue.prototype, {
            $axios: {
                get() {
                    return _axios
                }
            }
        })
    }
}
Vue.use(plugin)

export default _axios
