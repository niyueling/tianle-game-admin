<template>
	<view>
		<view class="recharge">
			<view class="recharge-content" :class="color">
				<view class="normal md m-b-14" style="color: white;">
					<text>{{admin.username}}</text>
					<image v-if="vip_img" :src="vip_img" style="width: 80rpx; height: 80rpx; margin-left: 15rpx;">
					</image>
				</view>

				<view class="input flex">
					<text style="font-size: 30rpx; width: 130rpx;">房卡</text>
					<input type="text" v-model="admin.gem" disabled />张
				</view>

				<view class="m-t-25 xs muted">
					<view class="container">
						<text>v{{this.admin.level}}</text>
						<view>
							<progress class="uni-progress" :percent="percent" activeColor="#00c853"
								background-color="#f5f5f5" :stroke-width="3" style="padding: 5px;"></progress>
						</view>

						<text
							style="width: 20%;padding-left: 5px;">v{{this.admin.level+1 >= 4 ?4 : this.admin.level+1}}({{this.admin.nextLevelRechargeAmount}})</text>
					</view>
					<text class="m-t-25 xs muted" v-if="admin.level >= 0 && admin.level < 4" style="color: #1c0cc8;">
						升级后充值1000赠{{admin.nextLevelRechargeInfo.one}}房卡,充值3000赠{{admin.nextLevelRechargeInfo.two}}房卡
					</text>
					<text class="m-t-25 xs muted" v-if="admin.level == 4" style="color: #1c0cc8;">
						您已经是金牌级代理，无法继续升级了。
					</text>
				</view>

			</view>

			<view class="recommend-recharge m-t-25">
				<view class="xxl normal m-b-40">
					推荐充值
				</view>

				<view class="recommend-item" @click="selectItem(item._id)"
					v-for="(item, index) in rechargeTemplateLists" :class="{'selected': item.selected}" :key="index">
					<view class="xxl">
						{{item.amount + item.extraAmount}} 房卡
					</view>
					<view class="xxl xs m-t-10">
						售价:￥{{item.androidPrice}} 元
					</view>
				</view>
			</view>

			<div class="btn-wrapper">
				<view class="btn-recharge flex row-center br60 lg white" @click="recharge('')">
					立即充值
				</view>
			</div>
		</view>
	</view>
</template>

<script>
	import {
		apiRechargeTemplateLists,
		apiRecharge
	} from "@/api/user.js";
	import {
		wxpay
	} from '@/utils/pay'
	import {
		apiPrepay, apiPayStatus
	} from "@/api/app.js";
	import {
		PaymentStatusEnum
	} from '@/utils/enum'
	import wechath5 from '@/utils/wechath5'

	export default {
		data() {
			return {
				rechargeTemplateLists: [], //推荐充值模板
				admin: {},
				rechargeData: {
					pay_way: 2,
					template_id: '',
					money: ''
				},
				levelTemplates: [],
				color: "color_level",
				vip_img: "",
				percent: 0,
				type: 'primary',
				wxcofig: {}
			}
		},

		onShow() {
			this.getRechargeTemplateLists();
		},

		onLoad() {
			// 监听全局duringPayment事件
			uni.$on('duringPayment', ({
				result
			}) => {
				if (result === PaymentStatusEnum['SUCCESS']) {
					this.$Router.back()
					this.amount = ''
					setTimeout(() => {
						this.$toast({
							title: '支付成功'
						})
					}, 500)
				}
			})
		},

		onUnload() {
			uni.$off('duringPayment')
		},

		computed: {
			maxAmount() {
				return this.levelTemplates[this.levelTemplates.length - 1].amount;
			}
		},

		methods: {
			updateProgress() {
				let curAmount = 0;
				let nextAmount = 0;
				let curLevel = this.admin.level;
				let nextLevel = this.admin.level + 1;
				this.levelTemplates.forEach((item, index) => {
					if (item.level == curLevel) curAmount = item.amount;
					if (item.level == nextLevel) nextAmount = item.amount;
				})
				this.percent = ((this.admin.totalRechargeAmount) / nextAmount) * 100;
				console.log("this.percent=", this.percent)
			},

			// 获取充值模板
			getRechargeTemplateLists() {
				apiRechargeTemplateLists().then(res => {
					this.admin = res.admin;
					this.rechargeTemplateLists = res.templates;
					this.levelTemplates = res.levellists;
					this.formatVipImage();
					this.rechargeTemplateLists = this.rechargeTemplateLists.map(item => ({
						...item,
						selected: false
					}));

					this.updateProgress();
				})
			},

			formatVipImage() {
				if (this.admin.level == 1) this.vip_img = "/bundle/static/images/vip/icon_grade1.png";
				if (this.admin.level == 2) this.vip_img = "/bundle/static/images/vip/icon_grade2.png";
				if (this.admin.level == 3) this.vip_img = "/bundle/static/images/vip/icon_grade3.png";
				if (this.admin.level == 4) this.vip_img = "/bundle/static/images/vip/icon_grade4.png";
			},

			// 充值
			selectItem(id) {
				// 先清除所有选中状态
				this.rechargeTemplateLists.forEach(item => {
					item.selected = false;
				});

				// 给当前选项添加选中状态
				const selectedItem = this.rechargeTemplateLists.find(item => item._id === id);
				selectedItem.selected = true;
			},

			recharge() {
				const selectedItem = this.rechargeTemplateLists.find(item => item.selected === true);
				apiRecharge(selectedItem).then(data => {
					this.prepayWx(data);
				})
			},
			
			prepayWx(data) {
				apiPrepay({
					order_id: data.order_id,
					from: data.from
				}).then(async ({ config }) => {
					await this.handleWechatPay(config, data.order_id);
				}).then(() => {
					this.handlePayResult(PaymentStatusEnum['SUCCESS'])
				}).catch(errMsg => {
					this.handlePayResult(PaymentStatusEnum['FAIL'])
					console.log('PAYMENT_ERROR_MSG:', errMsg)
				}).finally(() => {
					
				})
			},
			
			// 微信支付
			handleWechatPay(data, order_id) {
				return new Promise((resolve, reject) => {
					wechath5.wxPay(data).then(async (res) => {
							// #ifndef H5
							resolve(res)
							// #endif
			
							// #ifdef H5
							this.$on('h5_payment', () => {
								this.queryPayResult({order_id}).then(result => {
									result ? resolve(res) : reject(res)
								}).catch(errMsg => reject(errMsg))
							})
							// #endif
						}).catch(errMsg => reject(errMsg))
				})
			},
			
			// 查询支付结果
			queryPayResult(order_id) {
				return new Promise((resolve, reject) => {
					apiPayStatus({
						order_id: order_id,
					}).then(({
						pay_status
					}) => {
						resolve(!!pay_status)
					}).catch(errMsg => reject(errMsg))
				})
			},
			
			// 处理结果
			handlePayResult(result) {
				uni.$emit('duringPayment', {
					result,
					order_id: this.order_id,
				})
			},

			goPage(url) {
				uni.navigateTo({
					url: url
				})
			}
		}
	}
</script>

<style lang="scss">
	.container {
		height: 20px;
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;

		view {
			width: 70%;
		}

		text {}

	}

	.uni-progress {
		width: 100%;
	}

	.recharge {
		padding: 30rpx;

		.color_level {
			background: #97deff linear-gradient(to right, #97deff, #aba6f7);
			;
		}

		.recharge-content {
			width: 100%;
			height: 400rpx;
			padding: 66rpx;
			border-radius: 20rpx;

			.input {
				padding: 24rpx 0;
				font-size: 46rpx;
				border-bottom: 1rpx solid #E5E5E5;
				display: flex;
				/* 设置为flex布局 */
				align-items: center;
				/* 垂直居中 */

				/* 给.label设置宽度 */
				.label {
					width: 10rpx;
				}

				input {
					width: 200rpx;
					// padding-left: 30rpx;
					font-size: 46rpx;
					height: 50rpx;
				}
			}

			.tips {
				@include font_color();
			}
		}

		.btn-wrapper {
			position: fixed;
			left: 0;
			bottom: 0;
			width: 100%;
			display: flex;
			justify-content: center;
			align-items: center;
			padding: 10px;
			box-sizing: border-box;
			background-color: #fff;
			border-top: 1px solid #ddd;
		}

		.btn-recharge {
			background: #97deff linear-gradient(to right, #97deff, #aba6f7);
			;
			color: #fff;
			width: 95%;
			font-size: 16px;
			font-weight: bold;
			padding: 10px 20px;
			border-radius: 5px;
			border: none;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
			transition: all 0.3s ease;
		}

		.btn-recharge:hover {
			background-color: #1976d2;
			cursor: pointer;
		}

		.recommend-item {
			position: relative;
			width: 314rpx;
			height: 160rpx;
			padding: 30rpx;
			float: left;
			text-align: center;
			border-width: 1rpx;
			border-style: solid;
			border-radius: 10rpx;
			margin-right: 24rpx;
			margin-bottom: 24rpx;
			background-image: url("@/static/images/gm_plan_bg.png");
			background-size: 100%;
			background-repeat: no-repeat;
			background-position-y: 70rpx;
			// 其他样式属性
		}

		.recommend-item:active {
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
			transform: translateY(2px);
		}

		.recommend-item:active .xxl {
			color: #fff;
		}

		.recommend-item.selected {
			border: 2px solid orange;
			box-shadow: 0 0 10px 0 orange;
			background-color: #fff7e6;
		}

		.recommend-item.selected .xxl {
			color: orange;
		}

		.recommend-item:active::before {
			content: "";
			position: absolute;
			z-index: -1;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(255, 255, 255, 0.2);
			border-radius: 10px;
		}

		.recommend-item:nth-child(3n-2) {
			margin-right: 0;
		}

		.record {
			width: 100%;
			left: 0;
			bottom: 80rpx;
			box-sizing: border-box;
			position: absolute;
		}
	}
</style>
