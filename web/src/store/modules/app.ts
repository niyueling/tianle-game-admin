import { ActionTree, Module, MutationTree } from 'vuex'
import { AppState, RootState } from '../type'
import { GetterTree } from 'vuex'
import { apiAuth, apiconfig } from '@/api/app'

const state: AppState = {
    permission: {},
    config: {}
}

const getters: GetterTree<AppState, RootState> = {
    permission: state => state.permission,
    config: state => state.config
}

const mutations: MutationTree<AppState> = {
    /**
     * @description 设置权限
     * @param { Object } state
     * @param { Array } data
     */
    setPermission(state, data) {
        state.permission = data
    },
    /**
     * @description 设置配置
     * @param { Object } state
     * @param { Array } data
     */
    setCoonfig(state, data) {
        state.config = data
    }
}

const actions: ActionTree<AppState, RootState> = {
    /**
     * @description 获取权限
     * @param { Object } state
     * @param { Function } commit
     */
    getPermission({ state, commit }) {
        return apiAuth().then(res => {
            commit('setPermission', res)
            return Promise.resolve(res)
        })
    },
    getConfig({ state, commit }) {
        return apiconfig().then(res => {
            commit('setCoonfig', res)
            return Promise.resolve(res)
        })
    }
}

const app: Module<AppState, RootState> = {
    state,
    mutations,
    actions,
    getters
}

export default app
