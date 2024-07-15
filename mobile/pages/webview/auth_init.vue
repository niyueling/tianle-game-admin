<template>
    <view>
        <view class="login">
			<image class="image-reg" src="/static/images/head_reg.png" mode="widthFix"></image>
			<view class="input_reg">
				<view class="input-item">
					<view class="input">
					    <u-field
							icon="/static/images/input_account_reg_icon.png"
							:icon-style="{width: '40%','margin-left': '30rpx', 'margin-right': '30rpx'}"
					        label-width="0"
					        v-model="account"        
					        placeholder="请输入手机号"
							type="number"
					        :input-border="false"
					    />
					</view>
					<view class="input">
					    <u-field
							icon="/static/images/input_code_reg_icon.png"
							:icon-style="{width: '60%','margin-left': '30rpx', 'margin-right': '30rpx'}"
					        label-width="0"
					        v-model="code"
							type="number"
					        placeholder="请输入验证码"
					        :input-border="false"
					    >
					        <view slot="right">
					            <view class="sms-btn" @tap="sendSMS">
					                <u-verification-code
					                    unique-key="login"
					                    ref="uCode"
					                    @change="codeChange"
					                >
					                </u-verification-code>
					                <view class="xs code-btn">{{ codeTips }}</view>
					            </view>
					        </view>
					    </u-field>
					</view>
					<button class="login-btn white br60" size="lg" @tap="loginFun">
					    绑定
					</button>
				</view>
			</view>
			<image class="image-reg" src="/static/images/bottom_reg.png" mode="widthFix"></image>
        </view>
    </view>
</template>

<script>
import { mapMutations, mapActions, mapGetters } from "vuex";
import {
	apiSendSms,
	apiNewUserCreate
} from "@/api/app";
import wechath5 from "@/utils/wechath5";
import { isWeixinClient } from "@/utils/tools";
export default {
    data() {
        return {
            account: "",
            code: "",
            isWeixin: "",
            codeTips: "",
            showLoginPop: false,
			operate: '',
            unionid: "",
			headImgUrl: "",
			nickname: ""
        };
    },

    methods: {
		platform() {
			if(uni.getSystemInfoSync().platform == 'android') {
			    return 1;
			} else {
			    return 2;
			}
		},
		
		client() {
			// 判断平台
			let client = 1;
			
			// #ifdef H5
			if (navigator.userAgent.indexOf('Mobile')>-1){
				client = 6;
			} else {
			    client = 5;
			}
			// 判断是不是微信浏览器
			let ua = navigator.userAgent.toLowerCase()
			if (ua.match(/MicroMessenger/i) == "micromessenger"){
				client = 2;
			}
			// #endif
			
			return client;
		},
		
        // 发送验证码
        sendSMS() {
            if (!this.$refs.uCode.canGetCode) return;
            if (!this.account) {
                this.$toast({
                    title: "请输入手机号",
                });
                return;
            }
            apiSendSms({
                phone: this.account,
            }).then((res) => {
                this.$refs.uCode.start();
            });
        },

        codeChange(tip) {
            this.codeTips = tip;
        },
		
        closePopup() {
            this.showLoginPop = false;
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
		},
		
		async loginFun() {
			const regRes = await apiNewUserCreate({
				unionid: this.unionid,
				code: this.code,
				phone: this.account,
				operate: this.operate,
				headImgUrl: this.headImgUrl,
				name: this.nickname
			})
			
			this.$toast({ title: "绑定成功"});
			
			setTimeout(function() {
				uni.navigateTo({
					url: `/pages/webview/webview`
				})
			}, 100)
		}
    },
    onLoad() {
		// H5微信登录
		this.isWeixin = isWeixinClient();
		const platform = this.platform();
		const client = this.client();
		
		if (platform === 1) {
			location.href = "https://dl.tianle.fanmengonline.com/index.html";
		}
		
        var queryParams = this.parseQueryString(window.location.href);
		
		if(localStorage.getItem("unionid")) this.unionid = localStorage.getItem("unionid");
		if(localStorage.getItem("operate")) this.operate = localStorage.getItem("operate");
		if(localStorage.getItem("headImgUrl")) this.headImgUrl = localStorage.getItem("headImgUrl");
		if(localStorage.getItem("nickname")) this.nickname = localStorage.getItem("nickname");
		console.log(this.unionid, this.operate)
        
		if(!this.isWeixin && platform === 2) {
			var url = `http://h5.hfdsdas.cn`;
			if(localStorage.getItem("version")) url +=  '/' + queryParams.version;
			location.href = url + `/index.html`;
		}
    },
    onUnload() {},
};
</script>
<style lang="scss">
page {
    background-color: #fff;
	width: 100%;
	height: 100%;
}

.login {
    height: 100%;
	width: 100%;
    // padding-top: 80rpx;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
	
	.image-reg {
		background-size: cover;
		background-repeat: no-repeat;
		background-position: center center;
		width: 100%;
		height: 480rpx;
		z-index: 1;
	}
	
	.input_reg {
		background-image: url('../../static/images/input_reg.png');
		background-size: cover;
		background-repeat: no-repeat;
		background-position: center center;
		border-radius: 40rpx;
		width: 90%;
		margin: 3% 0 0 3%;
		margin-top: -150rpx;
		height: 500rpx;
		z-index: 2;
		
		.input-item {
			padding-top: 10rpx;
		}
		
		.input {
			border: 1px #d7d7d7 solid;
			border-radius: 60rpx;
			width: 80%;
			color: #d7d7d7;
			height: 90rpx;
			margin: 10% 0 0 10%;
		}
		
		.login-btn {
		    margin: 10% 0 0 10%;
			width: 80%;
		    background-image: url('../../static/images/input_btn_reg_color.png');
			background-size: contain;
			background-repeat: no-repeat;
			background-position: center center;
			padding-bottom: 15rpx;
		}
	}
	
	.code-btn {
		color: #82919f;
	}
}
</style>
