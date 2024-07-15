<template>
    <view>
        <view class="login">
			
        </view>
    </view>
</template>

<script>
import { mapMutations, mapActions, mapGetters } from "vuex";
import {
    apiFindOrCreate,
	apiSendSms,
	apiNewUserCreate
} from "@/api/app";
import wechath5 from "@/utils/wechath5";
import { isWeixinClient } from "@/utils/tools";
export default {
    data() {
        return {
            isWeixin: "",
			wxcode: "",
			isCode: true,
        };
    },

    methods: {
		getCodeUrl() {
		    wechath5.getWxUrl(`https://phpadmin.tianle.fanmengonline.com/mobile/#/pages/regist/regist`);
		},
		
		async oaLogin(code) {
		    if (!code) return;
			localStorage.setItem(code, 1);
		    const data = await wechath5.authLogin(code);
		    await this.loginHandle(data);
		},
		
		oaAutoLogin() {
			if (this.isCode) {
			    // 开启自动授权登录
			    this.getCodeUrl();
			}
			
		    if (this.wxcode) {
		        // 带有code => 登录
		        return this.oaLogin(this.wxcode);
		    }
		},
		
		// 登录结果处理
		async loginHandle(data) {
			var _this = this;
			this.loginData = data;
			const platform = this.platform();
			const client = this.client();
			
			if (platform === 1) {
				// android跳转下载页
				location.href = "https://dl.tianle.fanmengonline.com/index.html";
			}
			
		    // 1.通过unionid判断用户是否注册和绑定手机号
			const res = await apiFindOrCreate({unionid: data.unionid});
			
			//2.如果用户未注册调用服务端执行注册,如果用户未绑定手机号，调用服务端进行手机号绑定
			if(![1, 2].includes(res.operate)) {
				if (platform === 2 && client === 2) {
					// 苹果系统引导用户打开默认浏览器
					uni.navigateTo({
						url: `/pages/webview/webview`
					})
				}
			} else {
				localStorage.setItem("unionid", data.unionid);
				localStorage.setItem("headImgUrl", data.headimgurl);
				localStorage.setItem("nickname", data.nickname);
				localStorage.setItem("operate", res.operate);
				uni.navigateTo({
					url: `/pages/webview/auth_init`
				})
			}
		},
		
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
		console.log(queryParams)
		
		if(!this.isWeixin && platform === 2) {
			var url = `http://h5.hfdsdas.cn`;
			if(queryParams.version) {
				localStorage.setItem("version", queryParams.version);
				url +=  '/' + queryParams.version;
			} 
			location.href = url + `/index.html`;
		}
		
		var code = queryParams.code;
		
		if (code) {
			this.wxcode = code;
			if(!localStorage.getItem(code)) this.isCode = false;
		}
		
		// #ifdef H5
		if(this.isWeixin) this.oaAutoLogin();
		// #endif
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
