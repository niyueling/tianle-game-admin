<template>
	<view class="container">
		<view class="section">
			<view class="nav bg-white m-t-20">
				<view class="title md bold normal">用户</view>
				<view class="nav-item flex flex-wrap">
					<button v-for="(item, index) in userList" :key="index" class="item flex-col col-center m-b-20"
						style="width: 25%" @tap="menuJump(item)" hover-class="none">
						<image class="nav-icon" :src="item.image"></image>
						<view class="sm nav-name">{{ item.name }}</view>
					</button>
				</view>
			</view>
		</view>

		<view class="section">
			<view class="nav bg-white m-t-20">
				<view class="title md bold normal">代理</view>
				<view class="nav-item flex flex-wrap">
					<button v-for="(item, index) in agencyList" :key="index" class="item flex-col col-center m-b-20"
						style="width: 25%" @tap="menuJump(item)" hover-class="none">
						<image class="nav-icon" :src="item.image"></image>
						<view class="sm nav-name">{{ item.name }}</view>
					</button>
				</view>
			</view>
		</view>

		<view class="section">
			<view class="nav bg-white m-t-20">
				<view class="title md bold normal">俱乐部</view>
				<view class="nav-item flex flex-wrap">
					<button v-for="(item, index) in clubList" :key="index" class="item flex-col col-center m-b-20"
						style="width: 25%" @tap="menuJump(item)" hover-class="none">
						<image class="nav-icon" :src="item.image"></image>
						<view class="sm nav-name">{{ item.name }}</view>
					</button>
				</view>
			</view>
		</view>

		<view class="section">
			<view class="nav bg-white m-t-20">
				<view class="title md bold normal">商品</view>
				<view class="nav-item flex flex-wrap">
					<button v-for="(item, index) in goodList" :key="index" class="item flex-col col-center m-b-20"
						style="width: 25%" @tap="menuJump(item)" hover-class="none">
						<image class="nav-icon" :src="item.image"></image>
						<view class="sm nav-name">{{ item.name }}</view>
					</button>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		name: 'finance',
		data() {
			return {
				userList: [{
						name: "充值记录",
						link: "/pages/user_finance/user_recharge_record",
						image: "/static/images/verification.png",
					},
					{
						name: "充值统计",
						link: "/pages/user_finance/user_recharge_statistics",
						image: "/static/images/goods_management.png",
					},
					{
						name: "房卡记录",
						link: "/pages/user_finance/user_gem_record",
						image: "/static/images/order_management.png",
					},
					{
						name: "金币记录",
						link: "/pages/user_finance/user_gold_record",
						image: "/static/images/financial_overview.png",
					},
					{
						name: "红包领取",
						link: "/pages/user_finance/user_redpocket_record",
						image: "/static/images/data_statistics.png",
					},
					{
						name: "红包提现",
						link: "/pages/user_finance/user_redpocket_withdraw_record",
						image: "/static/images/store_setting.png",
					},
					{
						name: "房间记录",
						link: "/pages/user_finance/room_record",
						image: "/static/images/goods_management.png",
					},
					{
						name: "红包排行榜",
						link: "/pages/user_finance/user_redpocket_ranking",
						image: "/static/images/personal_setting.png",
					},
				],

				agencyList: [{
						name: "充值记录",
						link: "/pages/agency_finance/agency_recharge_record",
						image: "/static/images/verification.png",
					},
					{
						name: "房卡记录",
						link: "/pages/agency_finance/agency_gem_record",
						image: "/static/images/order_management.png",
					},
					{
						name: "自助充值记录",
						link: "/pages/finance/finance",
						image: "/static/images/financial_overview.png",
						disable: true
					}
				],

				clubList: [{
						name: "流水记录",
						link: "/pages/club_finance/club_account_record",
						image: "/static/images/verification.png",
					},
					{
						name: "金币纪录",
						link: "/pages/club_finance/club_gold_record",
						image: "/static/images/goods_management.png",
					},
					{
						name: "房间记录",
						link: "/pages/club_finance/room_record",
						image: "/static/images/order_management.png",
					},
					{
						name: "房间排行榜",
						link: "/pages/finance/finance",
						image: "/static/images/financial_overview.png",
						disable: true
					},
					{
						name: "金币管理记录",
						link: "/pages/club_finance/club_gold_manage_record",
						image: "/static/images/data_statistics.png",
					}
				],

				goodList: [{
					name: "订单记录",
					link: "/pages/good_finance/order_record",
					image: "/static/images/verification.png",
				}],
			}
		},
		methods: {
			menuJump(item) {
				if (item.disable) {
					return this.$toast({
						title: "暂未开放",
					});
				}
				this.$Router.push(item.link);
			}
		},
	}
</script>

<style lang="scss" scoped>
	.container {
		padding: 28rpx 20rpx 0rpx 20rpx;

		.manage {}
	}

	.title {
		font-size: 32rpx;
		font-weight: 500;
		margin-bottom: 28rpx;
	}

	.card {
		background: white;
		border-radius: 14rpx;
		padding: 30rpx 0 10rpx;
		margin-bottom: 48rpx;

		.item {
			display: flex;
			flex-direction: column;
			width: 50%;
			margin-bottom: 30rpx;
			padding: 0 20rpx;
		}
	}

	.section {
		padding: 0 20rpx;
		width: 100%;
		// margin-top: 40rpx;

		.wallet {
			padding: 50rpx 40rpx;
			border-radius: 14rpx;

			&-price {
				font-size: 60rpx;
			}

			&-btn {
				width: 160rpx;
				height: 60rpx;
				background-color: $-color-primary;
			}
		}

		// 导航
		.nav {
			border-radius: 14rpx;

			.title {
				padding: 24rpx 0;
				margin: 0 30rpx;
				border-bottom: $-solid-border;
			}

			&-item {
				padding: 26rpx 0 0;

				.item {
					width: 25%;
					border-radius: 0;
				}

				.nav-icon {
					width: 56rpx;
					height: 56rpx;
					margin-top: 16rpx;
				}

				.nav-name {
					width: 80%;
					font-size: 16rpx;
					height: 40rpx;
					margin: 0 24rpx;
					margin-bottom: 24rpx;
					text-align: center;
				}
			}
		}

		.logout-btn {
			height: 88rpx;
			border-radius: 100rpx;
			margin: 40rpx 24rpx;
		}
	}
</style>
