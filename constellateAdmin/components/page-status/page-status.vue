<template>
	<view class="page-status" v-if="status !== PageStatusEnum['NORMAL']">
		<!-- Loading -->
		<template v-if="status === PageStatusEnum['LOADING']">
			<slot name="loading">
				<u-loading :size="60" mode="flower" />
			</slot>
		</template>
		<!-- Error -->
		<template v-if="status === PageStatusEnum['ERROR']">
			<slot name="error"></slot>
		</template>
		<!-- Empty -->
		<template v-if="status === PageStatusEnum['EMPTY']">
			<slot name="empty"></slot>
		</template>
	</view>
</template>


<script>
	import { PageStatusEnum } from '@/utils/enum'
	
	export default {
		name: 'PageStatus',
		
		props: {
			status: {
				type: String,
				default: PageStatusEnum['LOADING']
			},
		},
		
		data() {
			return {
				
			}
		},
		
		computed: {
			PageStatusEnum: () => PageStatusEnum,
		}
	}
</script>


<style>
	.page-status {
		position: fixed;
		top: 0;
		left: 0;
		width: 100vw;
		height: 100vh;
		z-index: 900;
		display: flex;
		justify-content: center;
		align-items: center;
		background-color: #FFFFFF;
	}
</style>
 