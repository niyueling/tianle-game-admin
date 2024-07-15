<script>
import {
	mapActions,
	mapGetters,
	mapMutations
} from 'vuex'
import Cache from '@/utils/cache'
export default {
	async onLaunch(options) {
		// 获取配置
		await this.getConfig();
		// 获取个人信息
		// this.getUser()
	},
	onHide: function () {
		console.log('App Hide')
	},
	methods: {
		...mapActions([
			'getUser',
			'getConfig',
			'isLogin'
		]),
		...mapMutations(['logout'])
	}
}
</script>

<style lang="scss">
/*每个页面公共css */
@import 'styles/common.scss';
@import "components/uview-ui/index.scss";
</style>
