<template>
	<view>
		<u-tabs :list="list" :is-scroll="false" :current="current" @change="change" active-color="#3868f9"></u-tabs>

		<view class="m-t-20" style="padding: 0 20rpx;">

			<!-- 房间分析 -->
			<template v-if="current == 0">
				<view class="normal m-t-20 m-b-20 sm">最近7天</view>
				<view class="flex row-between">
					<view class="card">
						<view class="xs lighter">累计房间量</view>
						<view class="normal">{{roomInfo.totalRoomNum}}</view>
					</view>
					<view class="card">
						<view class="xs lighter">实时房间量</view>
						<view class="primary">{{roomInfo.currentRoomNum}}</view>
					</view>
				</view>
				<view class="e-data m-t-30 m-b-50">
					<view class="e-title sm">房间分析</view>

					<view class="e-content m-t-20">
						<charts ids="canvasColumn2" width="100%" height="544rpx" :chartData="turnoverData"></charts>
					</view>
				</view>
			</template>

			<!-- 用户分析 -->
			<template v-if="current == 1">
				<view class="normal m-t-20 m-b-20 sm">最近7天</view>
				<view class="flex row-between">
					<view class="card">
						<view class="xs lighter">用户数</view>
						<view class="normal">{{userInfo.user}}</view>
					</view>
					<view class="card">
						<view class="xs lighter">新增用户</view>
						<view class="primary">{{userInfo.new_user}}</view>
					</view>
				</view>
				<view class="e-data m-t-30 m-b-50">
					<view class="e-title sm">用户分析</view>

					<view class="e-content m-t-20">
						<charts ids="canvasColumn2" width="100%" height="544rpx" :chartData="userData"></charts>
					</view>
				</view>
			</template>

			<!-- 充值分析 -->
			<template v-if="current == 2">
				<view class="normal m-t-20 m-b-20 sm">最近7天</view>
				<view class="flex row-between">
					<view class="card">
						<view class="xs lighter">微信充值</view>
						<view class="normal">{{rechargeInfo.wechat}}</view>
					</view>
					<view class="card">
						<view class="xs lighter">后台充值</view>
						<view class="primary">{{rechargeInfo.admin}}</view>
					</view>
				</view>
				<view class="e-data m-t-30 m-b-50">
					<view class="e-title sm">充值分析</view>

					<view class="e-content m-t-20">
						<charts ids="canvasColumn2" width="100%" height="544rpx" :chartData="rechargeData"></charts>
					</view>
				</view>
			</template>

			<!-- 消耗分析 -->
			<template v-if="current == 3">
				<view class="normal m-t-20 m-b-20 sm">最近7天</view>
				<view class="flex row-between">
					<view class="card">
						<view class="xs lighter">累计消耗</view>
						<view class="normal">{{consumeInfo.totalConsume}}</view>
					</view>
				</view>
				<view class="e-data m-t-30 m-b-50">
					<view class="e-title sm">消耗分析</view>

					<view class="e-content m-t-20">
						<charts ids="canvasColumn2" width="100%" height="544rpx" :chartData="consumeData"></charts>
					</view>
				</view>
			</template>

			<!-- 访问分析 -->
			<template v-if="current == 4">
				<view class="normal m-t-20 m-b-20 sm">最近7天</view>
				<view class="flex row-between">
					<view class="card">
						<view class="xs lighter">昨日访问</view>
						<view class="normal">{{visitInfo.totalCount}}</view>
					</view>
				</view>
				<view class="e-data m-t-30 m-b-50">
					<view class="e-title sm">访问分析</view>

					<view class="e-content m-t-20">
						<charts ids="canvasColumn2" width="100%" height="544rpx" :chartData="visitData"></charts>
					</view>
				</view>
			</template>
		</view>

		<u-toast ref="uToast" />
	</view>
</template>

<script>
	import {
		mapGetters,
		mapActions
	} from 'vuex'
	import {
		apiTrafficAnalysis,
		apiUserAnalysis,
		apiRechargeAnalysis,
		apiConsumeAnalysis,
		apiVisitorAnalysis
	} from '@/api/store'
	export default {
		data() {
			return {
				list: [{
					name: '房间分析'
				}, {
					name: '用户分析'
				}, {
					name: '充值分析'
				}, {
					name: '消耗分析'
				}, {
					name: '访问分析'
				}],
				current: 0,
				roomInfo: {},
				turnoverData: {},
				userInfo: {},
				userData: {},
				rechargeInfo: {},
				rechargeData: {},
				consumeInfo: {},
				consumeData: {},
				visitInfo: {},
				visitData: {},
			}
		},

		onLoad() {
			this.getDataFunc(this.current)
		},

		methods: {
			change(index) {
				this.current = index;
				console.log(this.current)
				this.getDataFunc(index);
			},

			toDetail(id) {
				this.$Router.push({
					path: '/pages/goods_detail/goods_detail',
					query: {
						id
					}
				})
			},

			async getDataFunc(index) {
				if (index == 0) {
					const res = await apiTrafficAnalysis({
						day: 7
					})
					this.roomInfo = res.summary;
					const turnover = {
						categories: res.room.date,
						series: []
					}

					res.room.list.forEach((item, index) => {
						turnover.series.push({name: item.name, data: item.data})
					})
					console.log(turnover)
					this.turnoverData = turnover
				} else if (index == 1) {
					const res = await apiUserAnalysis({
						day: 7
					})
					this.userInfo = res.summary;
					const turnover = {
						categories: res.user.date,
						series: [{
							name: res.user.list[0].name,
							data: res.user.list[0].data
						}]
					}
					this.userData = turnover
				} else if (index == 2) {
					const res = await apiRechargeAnalysis({
						day: 7
					})
					this.rechargeInfo = res.summary;
					const turnover = {
						categories: res.recharge.date,
						series: [{
							name: res.recharge.list[0].name,
							data: res.recharge.list[0].data
						}, {
							name: res.recharge.list[1].name,
							data: res.recharge.list[1].data
						}]
					}
					this.rechargeData = turnover
				} else if (index == 3) {
					const res = await apiConsumeAnalysis({
						day: 7
					})
					this.consumeInfo = res.summary;
					const turnover = {
						categories: res.consume.date,
						series: [{
							name: res.consume.list[0].name,
							data: res.consume.list[0].data
						}]
					}
					this.consumeData = turnover
				} else if (index == 4) {
					const res = await apiVisitorAnalysis({
						day: 7
					})
					this.visitInfo = res.summary;
					const visit = {
						categories: res.visitor.date,
						series: [{
							"name": res.visitor.list[0].name,
							data: res.visitor.list[0].data
						}]
					}
					this.visitData = visit
				}
			}
		}
	}
</script>

<style lang="scss">
	.card {
		width: 346rpx;
		padding: 30rpx 36rpx;
		border-radius: 14rpx;
		background-color: $-color-white;

		view:last-child {
			font-size: 44rpx;
			font-weight: 500;
		}

		.primary {
			color: $-color-primary;
		}
	}

	// 数据图
	.e-data {
		.e-content {
			padding: 20rpx 0;
			border-radius: 14rpx;
			background-color: $-color-white;
		}
	}

	.goods {
		padding: 20rpx;
		border-radius: 14rpx;
		background-color: $-color-white;
		display: flex;

		.right {
			flex: 1;
			padding: 0 10rpx;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
		}
	}
</style>
