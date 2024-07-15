<template>
	<view class="user">
		<view class="flex-1" style="min-height: 0">
			<view class="user-info">
				<!-- Header -->
				<view class="header">
					<view class="info flex col-top">
						<view class="flex flex-1">
							<image class="logo m-r-20 flex-none" src="/static/images/tianlemajiang.png" />
						</view>
					</view>
				</view>
			
				<view class="section">
					<view class="nav bg-white m-t-20">
						<view class="title md bold normal">旧版客户端入口</view>
						<view class="nav-item flex flex-wrap">
							<button v-for="(item, index) in menuList" :key="index"
								class="item flex-col col-center m-b-16" style="width: 33%" @tap="menuJump(item)"
								hover-class="none">
								<image class="nav-icon" :src="item.image"></image>
								<view class="sm nav-name">{{ item.name }}</view>
							</button>
						</view>
					</view>
					
					<view class="nav bg-white m-t-20">
						<view class="title md bold normal">新版客户端入口</view>
								
						<view class="nav-item flex flex-wrap">
							<button v-for="(item, index) in newList" :key="index"
								class="item flex-col col-center m-b-16" style="width: 25%" @tap="menuJump(item)"
								hover-class="none">
								<image class="nav-icon" :src="item.image"></image>
								<view class="sm nav-name">{{ item.name }}</view>
							</button>
						</view>
					</view>
					
					<view class="nav bg-white m-t-20">
						<view class="title md bold normal">服务端地址</view>
								
						<view class="nav-item flex flex-wrap">
							<button v-for="(item, index) in serverList" :key="index"
								class="item flex-col col-center m-b-20" style="width: 100%"
								hover-class="none">
								<view class="sm nav-name">{{ item.name }}</view>
								<view class="sm nav-name" v-if="item.daTing">大厅：{{ item.daTing }}</view>
								<view class="sm nav-name" v-if="item.game">游戏服：{{ item.game }}</view>
								<view class="sm nav-name" v-if="item.url">{{ item.url }}</view>
							</button>
						</view>
					</view>
				</view>
			</view>
		</view>
</view>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import { apiLogout } from "@/api/user";
import { apiSetShopInfo } from "@/api/store";
import MescrollMixin from "@/components/mescroll-uni/mescroll-mixins";
export default {
	mixins: [MescrollMixin],
	data() {
		return {
			info: {},
			user_info: {},
			menuList: [
				{
					name: "测试服炸弹",
					link: "http://192.168.124.20:81/cszd-neiwang/zhadan/",
					image: "/static/images/zhadan.png",
				},
				{
					name: "外网正式炸弹",
					link: "http://192.168.124.20:81/waiwang/zhadan/",
					image: "/static/images/zhadan.png",
				},
				{
					name: "外网H5炸弹",
					link: "http://h5.fsdgag.cn/zhadan/index.html",
					image: "/static/images/zhadan.png",
				},
				{
					name: "测试服麻将",
					link: "http://192.168.124.20:81/cszd-neiwang/mahjong/",
					image: "/static/images/mahjong.png",
				},
				{
					name: "外网正式麻将",
					link: "http://192.168.124.20:81/waiwang/mahjong/",
					image: "/static/images/mahjong.png",
				},
				{
					name: "外网H5麻将",
					link: "http://h5.fsdgag.cn/shisanzhang/index.html",
					image: "/static/images/mahjong.png",
				},
				{ 
					name: "测试服标分",
					link: "http://192.168.124.20:81/cszd-neiwang/biaofen/",
					image: "/static/images/biaofen.png",
				},
				{
					name: "外网正式标分",
					link: "http://192.168.124.20:81/waiwang/biaofen/",
					image: "/static/images/biaofen.png",
				},
				{
					name: "外网H5标分",
					link: "http://h5.fsdgag.cn/biaofen/index.html",
					image: "/static/images/biaofen.png",
				},
				{
					name: "测试服跑得快",
					link: "http://192.168.124.20:81/cszd-neiwang/paodekuai/",
					image: "/static/images/paodekuai.png",
				},
				{
					name: "外网正式跑得快",
					link: "http://192.168.124.20:81/waiwang/paodekuai/",
					image: "/static/images/paodekuai.png",
				},
				{
					name: "外网H5跑得快",
					link: "http://h5.fsdgag.cn/paodekuai/index.html",
					image: "/static/images/paodekuai.png",
				},
			],
			newList: [
				{
					name: "内网",
					link: "http://192.168.124.20:81/hebingLocal",
					image: "/static/images/tianlemajiang.jpg",
				},
				{
					name: "测试服",
					link: "http://192.168.124.20:81/testLocal",
					image: "/static/images/tianlemajiang.jpg",
				},
				{
					name: "外网正式",
					link: "http://192.168.124.20:81/test-waiwang",
					image: "/static/images/tianlemajiang.jpg",
				},
				{
					name: "外网H5",
					link: "http://tg.fsdgag.cn/5wRJtV",
					image: "/static/images/tianlemajiang.jpg",
				}
			],
			serverList: [
				{
					name: "旧版测试服",
					daTing: "ws://dl-test.fanmengonline.com/oldws",
					game: "ws://game-test.fanmengonline.com/oldws",
				},
				{
					name: "旧版外网",
					daTing: "wss://denglu.fanmengonline.com/ws",
					game: "wss://game.fanmengonline.com"
				},
				{
					name: "新版内网",
					daTing: "ws://192.168.124.20:9529",
					game: "ws://192.168.124.20:9527",
				},
				{
					name: "新版测试服",
					daTing: "ws://dl-test.fanmengonline.com/ws",
					game: "ws://game-test.fanmengonline.com/ws",
				},
				{
					name: "新版外网",
					daTing: "wss://lobby.tianle.fanmengonline.com/ws",
					game: "wss://game.tianle.fanmengonline.com/ws",
				},
				{
					name: "旧版http服务",
					url: "https://phpadmin.pc.fanmengonline.com",
				},
				{
					name: "新版http服务",
					url: "https://phpadmin.tianle.fanmengonline.com",
				},
				{
					name: "http接口文档",
					url: "https://note.youdao.com/s/EZVWUqcI",
				}
			],
		};
	},
	methods: {
		menuJump(item) {
			window.location = item.link;
		},
	}
};
</script>

<style lang="scss">
page {
	height: 100%;
	padding: 0;
}

.user {
	height: 100%;
	display: flex;
	flex-direction: column;

	&-info {
		position: relative;

		.header {
			padding: 40rpx 20rpx 150rpx 20rpx;
			background-color: $-color-primary;
			
			.logo {
				height: 160rpx;
				width: 60%;
				margin-left: 20%;
				overflow: hidden;
			}

			.name {
				text-align: left;
				margin-bottom: 5rpx;

				.account {
					color: white;
					font-size: small;
					margin-top: 10rpx;
				}
			}

			.user-setting {
				width: 58rpx;
				height: 58rpx;
			}
		}

		.section {
			padding: 0 20rpx;
			width: 100%;
			margin-top: -80rpx;

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
						width: 90rpx;
						height: 90rpx;
						margin-top: 16rpx;
					}

					.nav-name {
						width: 80%;
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
	}
}
</style>
