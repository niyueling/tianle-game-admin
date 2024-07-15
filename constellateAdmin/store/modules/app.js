import {
	apiConfig
} from '@/api/app'
import {
	apiUserCentre,
	apiDistributionCode
} from '@/api/user'
import {
	CONFIG,
	USER_INFO,
	TOKEN
} from '@/config/cachekey';
import wechath5 from '@/utils/wechath5'
import Cache from '@/utils/cache'
import {router} from '@/router'
import { getClient } from '@/utils/tools'

const state = {
	config: Cache.get(CONFIG) || {},
	shopInfo: {},
	token: Cache.get(TOKEN) || false,
	client: getClient() || null,
	user_info:Cache.get(USER_INFO)||{}
};

const mutations = {
	setConfig(state, data) {
		state.config = data
		Cache.set(CONFIG, data);
	},
	login(state, data) {
		state.token = data.token;
		state.user_info = data
		Cache.set(USER_INFO,data)
		Cache.set(TOKEN, data.token);
	},
	logout(state) {
		state.token = false;
		state.shopInfo = {}
		Cache.remove(TOKEN);
	},
	setUserInfo(state, data) {
		state.shopInfo = data
	}
};

const actions = {
    getConfig({
    	state,
    	commit
    }) {
    	return new Promise((resolve, reject) => {
    		apiConfig().then(res => {
    			commit('setConfig', res)
    			resolve(res)
    		}).catch(() => {
    			reject()
    		})
    	})
    },
	getUser({
		state,
		commit
	}) {
		return new Promise((resolve, reject) => {
			apiUserCentre().then(res => {
				commit('setUserInfo', res)
				resolve(res)
			}).catch(() => {
				reject()
			})
		})
	},
	setWxShare({
		state
	}, opt) {
		// #ifdef H5
		const {
			share_image,
			share_intro,
			share_title
		} = state.config
		const inviteCode = state.shopInfo.code
		const href = window.location.href
		const sym = href.includes('?') ? '&' : '?'
		const option = {
			shareTitle: share_title,
			shareLink: inviteCode ? `${href}${sym}invite_code=${inviteCode}` : href,
			shareImage: share_image,
			shareDesc: share_intro
		}
		wechath5.share(Object.assign(option, opt))
		// #endif
	}

};

export default {
	state,
	mutations,
	actions
};
