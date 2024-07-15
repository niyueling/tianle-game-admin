import {
	mapGetters,
	mapState
} from 'vuex'
export default {
	data() {},
	computed: {
		...mapGetters(['isLogin', 'shopInfo', 'appConfig']),
	},
	// 全局配置分享
	onShareAppMessage() {
		const { share_image, share_intro, share_title } = this.appConfig
		const { code } = this.shopInfo
		const share = {
			title: share_title,
			path: `/pages/index/index?invite_code=${code}`,
			imageUrl: share_image
		}
		this.$Route.meta.auth && (share.path = this.$Route.fullPath)
		return share
	}
};
