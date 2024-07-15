<template>
	<view class="store-setting">
		<view class="container">
			<view class="container__title lighter sm">
				店铺信息
			</view>
			<view class="container__card">
				<view class="card-item row-between">
					<view class="card-item__label">
						后台LOGO
					</view>
					<view class="card-item__content">
						<u-image width="100rpx" height="100rpx" :src="form.logo" shape="circle"
							@click="chooseImg"></u-image>
					</view>
				</view>
				<view class="card-item row-between">
					<view class="card-item__label">
						后台名称
					</view>
					<view class="card-item__content ">
						<u-input input-align="right" v-model="form.name"></u-input>
					</view>
				</view>
			</view>
		</view>
		<view class="container">
			<view class="container__title lighter sm">
				安全设置
			</view>
			<view class="container__card">
				<view class="card-item row-between">
					<view class="card-item__label card-item__label--width">
						登陆安全限制
					</view>
					<view class="card-item__content">
						<u-switch v-model="form.login_restrictions"></u-switch>
					</view>
				</view>
			</view>
		</view>
		<view class="container">
			<view class="container__title lighter sm">
				登陆安全设置
			</view>
			<view class="container__card ">
				<view class="card-item">
					<view class="card-item__label card-item__label--width">
						输错密码
					</view>
					<view class="card-item__content">
						<u-input  v-model="form.password_error_times">
							
						</u-input><span class="login_limit-unit">次</span>
					</view>
				</view>
				<view class="card-item">
					<view class="card-item__label card-item__label--width">
						限制登陆
					</view>
					<view class="card-item__content">
						<u-input  v-model="form.limit_login_time"></u-input><span class="login_limit-unit">分钟</span>

					</view>
				</view>
			</view>
		</view>
		
		<view class="footer">
			<view class="fixed-footer">
				<view class="save-btn" @click="submit">
					保存
				</view>
			</view>
		</view>
		<u-toast ref="uToast" />
</view>
</template>

<script>
import {
	apiSetShopInfo,
	apiGetShopInfo
} from '@/api/store'
import area from '@/utils/area'
import { uploadFile } from '@/utils/tools.js'
export default {
	data() {
		return {
			form: {
				name: '',//商城名称
				logo: '',//logo
				login_restrictions: '',
				password_error_times: '',
				limit_login_time: '',
			}
		}
	}, 
	
	created() {
		uni.$on('uAvatarCropper', path => {
			this.avatar = path;
			const fileInfo = uploadFile(path).then(res => {
				this.form.logo = res.uri
			})
		})
	},
	methods: {
		async getData() {
			this.form = await apiGetShopInfo()
			this.form.login_restrictions = !!this.form.login_restrictions
		},
		async submit() {
			await apiSetShopInfo(this.form)
			uni.showToast({
				title: '保存成功！',
				duration: 1000
			});
		},
		//选择图片
		chooseImg() {
			uni.navigateTo({
				url: 'components/uview-ui/components/u-avatar-cropper/u-avatar-cropper?destWidth=300&rectWidth=200&fileType=jpg'
			})
		}
	},
	onShow() {
		this.lists = area; //省市区数据
		this.getData()
	}
}
</script>

<style lang="scss">
.store-setting {
	.container {
		margin-bottom: 20rpx;

		&__title {
			padding: 26rpx 20rpx;
		}

		&__card {
			background-color: #fff;
			margin: 0 20rpx;
			padding: 0 30rpx;
			border-radius: 10rpx;

			.card-item {
				display: flex;
				padding: 30rpx 0;
				align-items: center;
				// justify-content: space-between;

				&:not(:last-of-type) {
					border-bottom: $-solid-border;
				}

				&__label {
					margin-right: 40rpx;

					&--width {
						width: 140rpx;
					}
				}

				&__content {
					display: flex;
					justify-content: end;
					width: 300rpx;
				}
			}
		}
	}
	
	.login_limit-unit {
		margin-top: 5%;
	}

	.footer {
		height: 120rpx;

		.fixed-footer {
			position: fixed;
			bottom: 0;
			box-sizing: content-box;
			width: 100%;
			background-color: #fff;
			padding-bottom: env(safe-area-inset-bottom);

			.save-btn {
				height: 82rpx;
				line-height: 82rpx;
				text-align: center;
				border-radius: 41rpx;
				margin: 20rpx;
				background-color: $-color-primary;
				color: #fff;
				font-size: 30rpx;
			}
		}
	}
}
</style>
