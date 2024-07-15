import {
	RouterMount,
	createRouter,
	runtimeQuit
} from './js_sdk/hhyang-uni-simple-router/uni-simple-router';
import {
	BACK_URL
} from './config/cachekey'
import store from './store'
import {
	getWxCode
} from './utils/login'
import Cache from './utils/cache'
import wechath5 from './utils/wechath5'
import {isWeixinClient} from './utils/tools'
const scrollInfo = {};
const whiteList = ['register', 'login', 'forget_pwd']
const router = createRouter({
	platform: process.env.VUE_APP_PLATFORM,
	APP: {
		animation: {}
	},
	routerErrorEach: ({
		type,
		msg
	}) => {
		router.$lockStatus = false;
		// #ifdef APP-PLUS
		if (type === 3) {
			runtimeQuit();
		}
		// #endif
	},
	debugger: false,
	routes: [
		...ROUTES,
		{
			path: '*',
			redirect: (to) => {
				return {
					name: '404'
				}
			}
		},
	]
});

console.log(router)

let count = 0;
router.beforeEach((to, from, next) => {

	const index = whiteList.findIndex((item) => from.path.includes(item))
	
	if (index == -1 && !store.getters.token) {
		//保存登录前的路径
		Cache.set(BACK_URL, from.fullPath)
	}
	if (to.meta.auth && !store.getters.token) {
		next('/pages/login/login');
		return
	} else {
		next()
	}
	
});
router.afterEach( (to, from, next) => {
	// #ifdef H5
	// 添加定时器防止拿到的域名是上一个域名
	setTimeout(async () => {
		if (isWeixinClient()) {
			// jssdk配置
			await wechath5.config()
			// 分享配置
			if (to.path.includes('goods_detail')) return
			store.dispatch('setWxShare')
		}
	})
	
	// #endif
});

export {
	router,
	RouterMount
}
