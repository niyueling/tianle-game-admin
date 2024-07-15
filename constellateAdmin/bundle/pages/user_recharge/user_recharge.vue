<template>
	<view>
		<view class="recharge">
			<view class="user-top flex-1">
				<view class="flex">
					<view>
						<image class="icon-avatar" :src="userInfo.avatar" />
					</view>
					<view class="m-l-30 flex-1">
						<view class="xxl"  v-if="userInfo.nickname">
							{{userInfo.nickname}}
						</view>
						<view class="xxl" style="font-size: 24rpx;" v-if="userInfo.diamond">
							钻石：{{userInfo.diamond}} 
						</view>
						<view class="flex user-id m-t-10" v-if="userInfo.shortId">
							<view class="xs m-r-20">会员ID: {{userInfo.shortId}}</view>
						</view>
					</view>
				</view>
			</view>
			
			<view class="recommend-recharge m-t-25">
				<view class="xxl normal m-b-40">
					推荐充值
				</view>
			
				<view class="recommend-item" @click="selectItem(item._id)"
					v-for="(item, index) in rechargeTemplateLists" :class="{'selected': item.selected}" :key="index">
					<view class="first-pay" v-if="item.firstTimeAmount > 0">
						<view style="margin-right: 10rpx; font-size: 20rpx; color: white;">首送 <text style="font-size: 40rpx;"> {{item.firstTimeAmount}} </text> 代金券</view>
					</view>
					<view class="xxl" style="margin-top: 25rpx">
						{{item.amount}} 钻石
					</view>
					<view class="xxl xs m-t-10">
						售价:￥{{item.androidPrice}}
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
		apiPlayerRechargeTemplateLists,
		apiPlayerRecharge,
		apiLastPayOrder
	} from "@/api/user.js";
	import {
		wxpay
	} from '@/utils/pay'
	import {
		apiPlayerPrepay,
		apiPlayerPayStatus
	} from "@/api/app.js";
	import {
		PaymentStatusEnum
	} from '@/utils/enum'
	import wechath5 from '@/utils/wechath5'
	import { isWeixinClient } from '@/utils/tools'

	export default {
		data() {
			return {
				money: '', //充值的金额
				userInfo: {},
				rechargeTemplateLists: [], //推荐充值模板
				wxdata: {},
				title: "充值"
			}
		},

		onLoad() {
			// 获取URL中的code参数
			var queryParams = this.parseQueryString(window.location.href);
			var code = queryParams.code;
			var order_id = queryParams.order_id;
			
			if(order_id) localStorage.setItem("order_id", order_id);
			
			if (code) {
				this.code = code;
			}
			// #ifdef H5
			this.oaAutoLogin();
			// this.getRechargeTemplateLists();
			// #endif
		},

		methods: {
			// 获取充值模板
			getRechargeTemplateLists() {
				apiPlayerRechargeTemplateLists({
					unionid: this.wxdata.unionid
				}).then(res => {
					this.rechargeTemplateLists = res.templates
					this.userInfo = res.player
					this.rechargeTemplateLists = this.rechargeTemplateLists.map(item => ({
						...item,
						selected: false
					}));
				})
			},
			
			getLastPayOrder(order_id) {
				apiLastPayOrder({order_id: order_id}).then(res => {
					this.order = res
					if(!res.empty) this.prepayWx({order_id: res.data._id, from: "playerRecharge"});
				})
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
				selectedItem.user_id = this.userInfo._id;
				apiPlayerRecharge({_id: selectedItem._id, user_id: this.userInfo._id, terminal: 2}).then(data => {
					this.prepayWx(data);
				})
			},

			prepayWx(data) {
				apiPlayerPrepay({
					order_id: data.order_id,
					from: data.from,
					openid: this.wxdata.openid
				}).then(async ({
					config
				}) => {
					await this.handleWechatPay(config, data.order_id);
				})
			},

			// 微信支付
			handleWechatPay(data, order_id) {
				var that = this;
				return new Promise((resolve, reject) => {
					wechath5.wxPay(data).then(async (res) => {
						that.getRechargeTemplateLists();
						setTimeout(() => {
							return this.$toast({
							    title: '支付成功'
							})
						}, 500)
						
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
			
			// 公众号微信登录
			async oaLogin() {
			    if (!this.code) return;
			    const data = await wechath5.authLogin(this.code);
			    this.wxdata = data;
				this.getRechargeTemplateLists();
				var order_id = localStorage.getItem("order_id");
				localStorage.removeItem("order_id");
				if(order_id) this.getLastPayOrder(order_id);
			},
			
			getCodeUrl() {
				this.setItem('getPlayerRechargeCodeUrl', 1, 1000 * 5);
			    wechath5.getWxUrl("https://phpadmin.tianle.fanmengonline.com/mobile/#/bundle/pages/user_recharge/user_recharge");
			},
			
			oaAutoLogin() {
			    // H5微信登录
			    this.isWeixin = isWeixinClient();
			    if (this.code && this.getItem('getPlayerRechargeCodeUrl')) {
			        // 带有code => 登录
			        return this.oaLogin();
			    }
			    if ((this.isWeixin && !this.getItem('getPlayerRechargeCodeUrl')) || !this.code) {
			        // 开启自动授权登录
			        this.getCodeUrl();
			    }
			},
			
			// 存储数据时同时存储过期时间
			setItem(key, value, expire) {
			  const data = {
			    value,
			    expire: expire ? new Date().getTime() + expire : null
			  };
			  localStorage.setItem(key, JSON.stringify(data));
			},
			
			// 获取数据时检查过期时间
			getItem(key) {
			  const data = JSON.parse(localStorage.getItem(key));
			  if (data && (!data.expire || data.expire > new Date().getTime())) {
			    return data.value;
			  }
			  localStorage.removeItem(key);
			  return null;
			},
			
			// 解析URL中的查询字符串，返回一个包含参数名和值的对象
			parseQueryString(url) {
			  var queryString = url.split('?')[1];
			  var queryParams = {};
			
			  if (!queryString) {
			    return queryParams;
			  }
			
			  queryString.split('&').forEach(function (param) {
			    var parts = param.split('=');
			    var name = parts[0];
			    var value = parts[1] ? decodeURIComponent(parts[1].replace(/\+/g, ' ')) : '';
			
			    queryParams[name] = value;
			  });
			
			  return queryParams;
			}
		}
	}
</script>

<style lang="scss">
	.recharge {
		padding: 30rpx;
		
		.user-top {
			padding: 0 30rpx;
			// #ifdef  H5
			padding-top: 30rpx;
		
			// #endif
			.icon-avatar {
				width: 110rpx;
				height: 110rpx;
				border-radius: 50%;
				border: 1px solid black;
			}
		
			.user-id {
				display: inline-flex;
				border: 1px solid black;
				color: black;
				border-radius: 100rpx;
				padding-left: 20rpx;
			}
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
					width: 170rpx;
					// padding-left: 30rpx;
					font-size: 46rpx;
					height: 50rpx;
				}
			}
	
			.tips {
				@include font_color();
			}
		}
		
		.first-pay {
			position: absolute;
			top: 0;
			right: 0;
			width: 180rpx;
			background-image: url('@/static/images/firstBuy.png');
			background-size: contain; /* 或者 cover */
			background-repeat: no-repeat;
			background-position: center center;
			padding-left: 15rpx;
			
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
	}
</style>
