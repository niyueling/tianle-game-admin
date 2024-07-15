<template>
	<view>
		<!-- 头部修改头像 -->
		<view class="header bg-white p-t-30">
			<button class="flex flex-col row-center" hover-class="none">
				<image :src="userInfos.headImgUrl!=''? userInfos.headImgUrl:'../../static/images/icon_wechat.png'"></image>
			</button>
		</view>

		<view class="item nr flex row-between">
			<view class="label">ID</view>
			<view class="content">{{userInfos.shortId}}</view>
		</view>
		<view class="item nr flex row-between">
			<view class="label">昵称</view>
			<view class="content">{{userInfos.name}}</view>
		</view>
		<view class="item nr flex row-between">
			<view class="label">房卡</view>
			<view class="content">{{userInfos.gem}}</view>
		</view>
		<view class="item nr flex row-between">
			<view class="label">红包</view>
			<view class="content">{{userInfos.redPocket}}</view>
		</view>
		<view class="item nr flex row-between">
			<view class="label">手机号</view>
			<view class="content">{{userInfos.phone==''?'未绑定手机号':userInfos.phone}}</view>
		</view>

		<view class="item nr flex row-between">
			<view class="label">注册时间</view>
			<view class="content">{{userInfos.createAt}}</view>
		</view>
	</view>
</template>

<script>
	import { mapActions, mapGetters } from 'vuex'
	import Cache from "@/utils/cache.js"
	import wechath5 from '@/utils/wechath5'
	import { isWeixinClient } from '@/utils/tools'
	import { apiBindOrGetUserInfo } from '@/api/user';
	export default {
		data() {
			return {
				userInfos: {}, //用户信息
				wx_code: "",
				wxdata: {}
			}
		},
		methods: {
			// 公众号微信登录
			async oaLogin() {
			    if (!this.wx_code) return;
			    const data = await wechath5.authLogin(this.wx_code);
			    this.wxdata = data;
				this.bindOrGetUserInfo();
			},
			
			bindOrGetUserInfo() {
				apiBindOrGetUserInfo(this.wxdata).then(res => {
				    this.userInfos = res;
				})
			}, 
			
			getCodeUrl() {
				this.setItem('getPlayerCodeUrl', 1, 1000 * 30);
			    wechath5.getWxUrl("https://phpadmin.tianle.fanmengonline.com/mobile/#/bundle/pages/user_profile/user_bind_profile");
			},
			
			oaAutoLogin() {
			    // H5微信登录
			    this.isWeixin = isWeixinClient();
			    if (this.wx_code && this.getItem('getPlayerCodeUrl')) {
			        // 带有code => 登录
			        return this.oaLogin();
			    }
			    if (this.isWeixin && !this.getItem('getPlayerCodeUrl')) {
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
		},

		async onLoad() {
			// 获取URL中的code参数
			var queryParams = this.parseQueryString(window.location.href);
			console.log("params", queryParams);
			var wx_code = queryParams.code;
			
			if (wx_code) {
				this.wx_code = wx_code;
				console.log('Found code parameter with value: ' + wx_code);
			}
			// #ifdef H5
			this.oaAutoLogin();
			// #endif
		},
	}
</script>

<style lang="scss">
	.header {
		width: 100%;
		height: 240rpx;
		border-radius: 14rpx;

		image {
			width: 120rpx;
			height: 120rpx;
			border-radius: 50%;
		}
	}

	.item {
		margin-top: 2rpx;
		padding: 30rpx 20rpx;
		border-radius: 14rpx;
		background-color: #FFFFFF;

		.label {
			width: 150rpx;
		}

		.content {
			flex: 1;
			width: 80%;
		}

		.bind {
			height: 56rpx;
			border-width: 1rpx;
			border-style: solid;
			@include font_color();
			@include border_color();
		}
	}

	.btn {
		height: 70rpx;
		margin: 60rpx 50rpx 0;
		@include background_color();
	}
</style>
