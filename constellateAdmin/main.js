import Vue from 'vue'
import App from './App'
import store from './store'
import {
	toast,
	px2rpx,
	getImageUri,
	copy
} from './utils/tools'
import Cache from './utils/cache'
import uView from "@/components/uview-ui";
import minxinsApp from '@/mixins/app'
import MescrollBody from "@/components/mescroll-uni/mescroll-body.vue"
import routerLink from './js_sdk/hhyang-uni-simple-router/link.vue'
import {
	router,
	RouterMount
} from './router'
Vue.component('mescroll-body', MescrollBody)
Vue.prototype.$toast = toast
Vue.prototype.$Cache = Cache
Vue.prototype.$px2rpx = px2rpx
Vue.prototype.$getImageUri = getImageUri
Vue.prototype.$copy = copy
Vue.config.productionTip = false
Vue.component('RouterLink', routerLink)

Vue.use(router)
Vue.mixin(minxinsApp);
Vue.use(uView);
App.mpType = 'app'
const app = new Vue({
	...App,
	store
})
//v1.3.5起 H5端 你应该去除原有的app.$mount();使用路由自带的渲染方式
// #ifdef H5
RouterMount(app, router, '#app');
// #endif

// #ifndef H5
app.$mount(); //为了兼容小程序及app端必须这样写才有效果
// #endif
