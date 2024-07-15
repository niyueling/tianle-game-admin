<template>
	<view>
		<mescroll-body ref="mescrollRef" @init="mescrollInit" @down="downCallback" @up="upCallback"
			:up="{ use: false }">
			<view class="index">
				<view class="index-wrap">
					<!-- Header -->
					<view class="index-header flex row-between">
						<view class="md" style="font-weight: 400">{{
                            pagesData.shop_name || "-"
                        }}</view>
						<view class="xs">{{ time }}</view>
					</view>

					<view class="index-section">
						<view class="today-data">
							<view class="title">今日数据</view>

							<view class="flex">
								<block>
									<view class="item-data">
										<view>新增用户（人）</view>
										<view class="today-data-item">{{ pagesData.today.today_user_num }}</view>
									</view>
									<view class="item-data">
										<view>开房数（间）</view>
										<view class="today-data-item">{{ pagesData.today.today_room_num }}</view>
									</view>
									<view class="item-data">
										<view>充值金额（元）</view>
										<view class="today-data-item">{{ pagesData.today.recharge_amount }}</view>
									</view>
									<view class="item-data">
									 <view>访问量（人）</view>
										<view class="today-data-item">{{ pagesData.today.today_visitor_num }}</view>
									</view>
								</block>
							</view>
						</view>

						<view class="today-data m-t-30">
							<view class="title">数据统计</view>
							<view class="flex">
								<block>
									<view class="item-data">
										<view>新增用户</view>
										<view class="item-data-form">今日：{{pagesData.pending.add_user.today_num}}人</view>
										<view class="item-data-form">昨日：{{pagesData.pending.add_user.yestoday_num}}人
										</view>
									</view>
									<view class="item-data">
										<view>开房数</view>
										<view class="item-data-form">今日：{{pagesData.pending.add_room.today_num}}间</view>
										<view class="item-data-form">昨日：{{pagesData.pending.add_room.yestoday_num}}间
										</view>
									</view>
									<view class="item-data">
										<view>访问量</view>
										<view class="item-data-form">今日：{{pagesData.pending.visit.today_num}}人</view>
										<view class="item-data-form">昨日：{{pagesData.pending.visit.yestoday_num}}人</view>
									</view>
								</block>
							</view>

							<view class="flex">
								<block>
									<view class="item-data">
										<view>在线充值</view>
										<view class="item-data-form">今日：{{pagesData.pending.online_recharge.today_num}}元
										</view>
										<view class="item-data-form">
											昨日：{{pagesData.pending.online_recharge.yestoday_num}}元</view>
									</view>
									<view class="item-data">
										<view>后台充值</view>
										<view class="item-data-form">今日：{{pagesData.pending.back_recharge.today_num}}元
										</view>
										<view class="item-data-form">
											昨日：{{pagesData.pending.back_recharge.yestoday_num}}元</view>
									</view>
									<view class="item-data">
										<view>房卡充值</view>
										<view class="item-data-form">今日：{{pagesData.pending.fk_recharge.today_num}}元
										</view>
										<view class="item-data-form">昨日：{{pagesData.pending.fk_recharge.yestoday_num}}元
										</view>
									</view>
								</block>
							</view>
							
							<view class="flex">
								<block>
									<view class="item-data">
										<view>红包领取</view>
										<view class="item-data-form">今日：{{pagesData.pending.red_packet.today_num}}元</view>
										<view class="item-data-form">昨日：{{pagesData.pending.red_packet.yestoday_num}}元
										</view>
									</view>
									<view class="item-data">
										<view>红包提现</view>
										<view class="item-data-form">今日：{{pagesData.pending.red_packet_withdraw.today_num}}元
										</view>
										<view class="item-data-form">
											昨日：{{pagesData.pending.red_packet_withdraw.yestoday_num}}元</view>
									</view>
									<view class="item-data">
										<view>房卡消耗</view>
										<view class="item-data-form">今日：{{pagesData.pending.fk_consume.today_num}}元</view>
										<view class="item-data-form">昨日：{{pagesData.pending.fk_consume.yestoday_num}}元
										</view>
									</view>
								</block>
							</view>
						</view>

						<view class="e-data m-t-30 p-20">
							<view class="title">近5日充值趋势图</view>

							<view class="e-content m-t-20">
								<charts ids="canvasColumn" width="100%" height="544rpx" :chartData="turnoverData">
								</charts>
							</view>
						</view>

						<view class="e-data m-t-30 p-b-60 p-20">
							<view class="title">近5日访问量趋势图</view>

							<view class="e-content m-t-20">
								<charts ids="canvasColumn2" width="100%" height="544rpx" :chartData="visitData">
								</charts>
							</view>
						</view>

						<view class="e-data m-t-30 p-b-60 p-20">
							<view class="title">近5日开房量趋势图</view>

							<view class="e-content m-t-20">
								<charts ids="canvasColumn2" width="100%" height="544rpx" :chartData="roomData">
								</charts>
						 </view>
						</view>
					</view>
				</view>
			</view>
		</mescroll-body>
	</view>
</template>
<script>
	import {
		apiIndex
	} from "@/api/store";
	import {
		apiVisit
	} from "@/api/app";
	import MescrollMixin from "@/components/mescroll-uni/mescroll-mixins";
	export default {
		mixins: [MescrollMixin],
		data() {
			return {
				pagesData: {
					today: {},
					pending: {
						add_user: {},
						add_room: {},
						visit: {},
						online_recharge: {},
						back_recharge: {},
						fk_recharge: {},
						red_packet: {},
						red_packet_withdraw: {},
						fk_consume: {}
					}
				},
				loading: true,

				time: "",
				totleAmount: '123',
				todayObj: [{
						name: "成交订单",
						val: "0.00",
					},
					{
						name: "新增用户",
						val: "0",
					},
					{
						name: "访客数",
						val: "0",
					},
				],
				orderObj: [{
						name: "待发货订单",
						val: "0.00",
					},
					{
						name: "待审核售后",
						val: "0",
					},
					{
						name: "待审核评价",
						val: "0",
					},
					{
						name: "售罄商品",
						val: "0",
					},
				],

				turnoverData: {},
				roomData: {},
				visitData: {},
			};
		},

		methods: {
			async downCallback() {
				await this.getPageInfo();
				this.mescroll.endSuccess(0, false);
			},

			async getPageInfo() {
				const res = await apiIndex({
					day: 7
				});
				console.log(res);
				const turnover = {
					categories: res.business15.date,
					series: res.business15.list
				};
				const visit = {
					categories: res.visitor15.date,
					series: res.visitor15.list
				};
				const room = {
					categories: res.room15.date,
					series: res.room15.list
				};
				this.turnoverData = turnover;
				this.visitData = visit;
				this.roomData = room;
				this.pagesData = res;
				res.shop_name = res.shop_info.name;
				console.log(res)

			},

			formatGrapNum(num, unit) {
				if (num > 0) return `递增：${num}${unit}`;
				if (num == 0) return '持平';
				if (num < 0) return `递减：${num}${unit}`;
			},

			showTime() {
				var d = new Date();
				var year = d.getFullYear();
				var month = d.getMonth() + 1; //0~11
				var date = d.getDate();

				var hour =
					Number(d.getHours()) <= 9 ?
					"0" + Number(d.getHours()) :
					Number(d.getHours());
				var min =
					Number(d.getMinutes()) <= 9 ?
					"0" + Number(d.getMinutes()) :
					Number(d.getMinutes());
				var sec =
					Number(d.getSeconds()) <= 9 ?
					"0" + Number(d.getSeconds()) :
					Number(d.getSeconds());

				var str =
					year + "-" + month + "-" + date + "  " + hour + ":" + min + ":" + sec;
				this.time = str;
			},
		},
		onLoad() {
			setInterval(this.showTime, 1000);
		},
	};
</script>

<style lang="scss" scoped>
	.index {
		// overflow: hidden;
		// margin-bottom: 100rpx;

		&-wrap {

			// 头部
			.index-header {
				color: $-color-white;
				padding: 30rpx 24rpx;
				background-repeat: no-repeat;
				background-size: 100% 100rpx;
				background-color: #3868f9;
				// padding-bottom: 200rpx;
			}

			.index-section {
				width: 100%;
				// position: absolute;
				// top: 100rpx;

				// 今日数据卡片
				.today-data {
					padding: 30rpx 30rpx 40rpx 30rpx;
					border-radius: 14rpx;
					background-color: $-color-white;
					
					.today-data-item {
						margin-left: 25%;
						padding-top: 10%;
					}

					.item-data {
						width: 220rpx;

						.item-data-form {
							font-size: 16rpx;
						}

						view:first-child {
							color: $-color-muted;
							font-size: 24rpx;
						}

						view:last-child {
							// color: $-color-normal;
							font-size: 17rpx;
							font-weight: 500;
							margin-top: 6rpx;
							height: 30px;
							word-wrap: break-word;
							word-break: break-all;
						}
					}

					.item-data:first-child {
						width: 260rpx !important;
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

				.title {
					display: flex;
					align-items: center;
					height: 100%;
					font-size: 32rpx;
					font-weight: 500;
					margin-bottom: 38rpx;

					&:before {
						display: block;
						width: 5rpx;
						height: 38rpx;
						background-color: #3868f9;
						margin-right: 10rpx;
						content: '';
					}
				}
			}
		}
	}
</style>
