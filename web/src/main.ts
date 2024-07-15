import Vue from 'vue'
import './plugins/axios'
import './plugins/element'
import './plugins/umy-ui'
import './plugins/echarts'
import App from './App.vue'
import router from './router'
import store from './store'
import './permission'
import 'nprogress/nprogress.css'
import './styles/element-variables.scss'
import appMixin from './mixin/app'
import './directives/action'
import { getImageUri } from './utils/util'

Vue.config.productionTip = false

Vue.prototype.$getImageUri = getImageUri

Vue.mixin(appMixin)
new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')
