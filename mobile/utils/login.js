import {
	isWeixinClient,
	currentPage,
	trottle
} from './tools'
import store from '@/store'
import Cache from './cache'
import {
	BACK_URL,
	INVITE_CODE
} from '@/config/cachekey'
import wechath5 from './wechath5'
import {
	apiSilentLogin
} from '@/api/app'
import {
	router
} from '@/router'

// 获取登录凭证（code）

export function getWxCode() {
	return new Promise((resolve, reject) => {
		uni.login({
			success(res) {
				resolve(res.code);
			},

			fail(res) {
				reject(res);
			}

		});
	});
}
//小程序获取用户信息
export function getUserProfile() {
	return new Promise((resolve, reject) => {
		uni.getUserProfile({
			desc: '获取用户信息，完善用户资料 ',
			success: (res) => {
				resolve(res);
			},
			fail(res) {
				console.log(res)
			}

		})
	})

}

//小程序静默授权

export async function wxMnpLogin() {
	const {
		coerce_mobile
	} = store.getters.appConfig
	const code = await getWxCode()
	const loginData = await apiSilentLogin({
		code
	})
	const {
		options,
		onLoad,
		onShow,
		route
	} = currentPage()
	// 需要强制绑定手机号
	if(coerce_mobile && !loginData.mobile) {
		return
	}
	if (loginData.token ) {
		store.commit('login', loginData)
		store.dispatch('getUser')
		// 刷新页面
		onLoad && onLoad(options)
		onShow && onShow()
	}
	
}
export const toLogin = trottle(_toLogin, 2000)

function _toLogin() {
	//#ifdef  MP-WEIXIN
	const {
		mnp_auto_wechat_auth,
		coerce_mobile
	} = store.getters.appConfig
	if (!mnp_auto_wechat_auth) return
	wxMnpLogin()
	// #endif

	//#ifndef MP-WEIXIN
	const {
		currentRoute
	} = router
	if (currentRoute.meta.auth) {
		router.push('/pages/login/login')
	}
	// #endif
}
