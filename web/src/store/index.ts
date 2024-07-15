import Vue from 'vue'
import Vuex, { StoreOptions } from 'vuex'
import modules from './modules'
import { RootState } from './type'
import VuexPersistence from 'vuex-persist'
Vue.use(Vuex)

const vuexLocal = new VuexPersistence({
    key: 'vuexbase', // 这里可以自定义存入localStorage的键名，默认vuex
    storage: window.localStorage,
    modules: []
})

const store: StoreOptions<RootState> = {
    state: {
        version: '1.0.0', // a simple property
        coder: 'ls'
    },
    modules: modules,
    plugins: [vuexLocal.plugin]
}

export default new Vuex.Store<RootState>(store)
