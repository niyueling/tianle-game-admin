<template>
	<view class="loading" :style="{'background-color': bgColor}">
		<u-loading :size="size" mode="flower"></u-loading>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				
			}
		},

		props: {
			bgColor: {
				type:  String,
				default: '#fff'
			},
			size: {
				type: [Number, String],
				default: 60
			}
		},
	}
</script>
<style>
	.loading {
		position: fixed;
		top: 0;
		left: 0;
		width: 100vw;
		height: 100vh;
		z-index: 9999;
		display: flex;
		justify-content: center;
		align-items: center;
	}
</style>
