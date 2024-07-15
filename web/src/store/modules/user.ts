import { ActionTree, Module, MutationTree } from 'vuex'
import { UserState, RootState } from '../type'
import { GetterTree } from 'vuex'
import { apiAdminInfo, apiLogin, apiLogout } from '@/api/app'
import cache from '@/utils/cache'
export const TOKEN = 'token'
// State
const state: UserState = {
    token: cache.get(TOKEN),
    userInfo: {}
}

// Getters
const getters: GetterTree<UserState, RootState> = {
    token: state => state.token || ''
}

// Mutations
const mutations: MutationTree<UserState> = {
    // 设置用户信息
    setUserInfo: (state, payload): void => {
        state.userInfo = payload
    },

    // 设置token
    setToken(state, data) {
        state.token = data
        cache.set(TOKEN, data)
    }
}

// Actions
const actions: ActionTree<UserState, RootState> = {
    // 登录
    login({ commit }, data) {
        const { username, password } = data

        return new Promise((resolve, reject) => {
            apiLogin({
                username,
                password
            })
                .then((data: any) => {
                    commit('setToken', data.token)
                    commit('setUserInfo', data)
                    resolve(data)
                })
                .catch(error => {
                    reject(error)
                })
        })
    },
    // 退出登录
    logout({ commit }) {
        return new Promise((resolve, reject) => {
            apiLogout()
                .then(data => {
                    commit('setToken', '')
                    commit('setUserInfo', {})
                    cache.remove(TOKEN)
                    resolve(data)
                })
                .catch(error => {
                    reject(error)
                })
        })
    },
    // 获取管理员信息
    getUser({ commit }) {
        return new Promise((resolve, reject) => {
            apiAdminInfo()
                .then((data: any) => {
                    commit('setUserInfo', data)
                    resolve(data)
                })
                .catch(error => {
                    reject(error)
                })
        })
    }
}

const user: Module<UserState, RootState> = {
    state,
    mutations,
    actions,
    getters
}

export default user
