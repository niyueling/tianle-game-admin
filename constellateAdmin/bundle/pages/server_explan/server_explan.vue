<template>
	<view class="p-24">
		<u-parse :html="content" :selectable="true" :show-with-animation="true"></u-parse>
	</view>
</template>

<script>
	import {
		apiPolicyAgreement,
	} from "@/api/app"
	export default {
		data() {
			return {
				content: ''
			}
		},

		methods: {
			// 获取服务协议
			getPolicyAgreement() {
				apiPolicyAgreement({
					type: 2
				}).then(res => {
					this.content = res.service_agreement_content;
				})
			},

		},

		onLoad() {
			this.getPolicyAgreement()
			uni.setNavigationBarTitle({ title: '隐私政策' })
			
		}
	}
</script>

<style>
</style>
