<template>
    <view class="pages">
        <view class="login">
            <view class="logo">
                <view class="bold">{{ this.appConfig.name }}</view>
            </view>
            <view class="acount-login">
                <view class="input">
                    <u-field label-width="0" clear-size="38" icon="account" v-model="account" placeholder="请输入账号"
                        :input-border="false" />
                </view>
                <view class="input">
                    <u-field label-width="0" icon="lock-open" @right-icon-click="showPassword"
                        :right-icon="isPassword ? 'eye' : 'eye-fill'" :password="isPassword" v-model="password"
                        placeholder="请输入密码" :input-border="false" />
                </view>

                <button class="login-btn white" size="lg" @tap="loginFun">登录</button>
            </view>
			
			<view class="other-login flex-col flex-1">
			    <view class="m-b-40 sm flex row-center">
			        已阅读并同意
			        <router-link
			            to="/bundle/pages/server_explan/server_explan?type=2"
			        >
			            <view class="agreement">《用户隐私协议》</view>
			        </router-link>
			    </view>
			</view>

        </view>
    </view>
</template>

<script>
import {
    mapMutations,
    mapActions,
    mapGetters
} from 'vuex'
import {
	CONFIG
} from '@/config/cachekey';
import {
    apiAuthLogin,
    apiAccountLogin,
    apiLoginCaptcha
} from '@/api/app';
import wechath5 from '@/utils/wechath5'
import {
    isWeixinClient,
    currentPage,
    client,
    trottle
} from '@/utils/tools'
import Cache from "@/utils/cache"
import {
    BACK_URL,
    INVITE_CODE
} from '@/config/cachekey'
import {
    getWxCode,
    getUserProfile
} from '@/utils/login'
import store from 'store'
export default {
    data() {
        return {
            password: '',
            account: '',
            // checked: false,
            isPassword: true,
            client: store.getters.client, //1-微信小程序 2-微信公众号 3-安卓app 4-苹果app 5-pc端 6-h5
            appConfig:'',
			code: "",
			openid: ""
        };
    },

    methods: {
        ...mapMutations(['login']),
        ...mapActions(['getUser']),

        // 账号登录
        async loginFun() {
            const {
                account,
                password,
            } = this

            if (!account) return this.$toast({
                title: '请输入账号'
            })

            if (!password) return this.$toast({
                title: '请输入密码'
            })

            apiAccountLogin({
                username: account,
                password: password,
				openid: this.openid
            }).then(res => {
                this.loginHandle(res)
            })
        },
        // 登录结果处理
        async loginHandle(data) {
            this.login(data)
            this.getUser()
            uni.hideLoading()
			// location.href = "https://phpadmin.tianle.fanmengonline.com/mobile";
			uni.reLaunch({ url: '/pages/index/index' });
        },

        showPassword() {
            this.isPassword = !this.isPassword
        },
		
		// 公众号微信登录
		async oaLogin() {
		    if (!this.code) return;
		    const data = await wechath5.authLogin(this.code);
		    this.openid = data.openid;
		},
		
		getCodeUrl() {
			this.setItem('getCodeUrl', 1, 1000 * 30);
		    wechath5.getWxUrl("https://phpadmin.tianle.fanmengonline.com/mobile");
		},
		
		oaAutoLogin() {
		    // H5微信登录
		    this.isWeixin = isWeixinClient();
		    if (this.code) {
		        // 带有code => 登录
		        return this.oaLogin();
		    }
		    if (this.isWeixin && !this.getItem('getCodeUrl')) {
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
    onLoad(options) {
		// 获取URL中的code参数
		var queryParams = this.parseQueryString(window.location.href);
		console.log("params", queryParams);
		var code = queryParams.code;
		
		if (code) {
			this.code = code;
			console.log('Found code parameter with value: ' + code);
		}
        // #ifdef H5
        this.oaAutoLogin();
        // #endif
    },
    onUnload() {

    },
};
</script>
<style lang="scss">
page {
    background-color: #fff;

    padding: 0;
}

.login {
    height: 100vh;
    padding-top: 80rpx;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;

    .logo {
        height: 126rpx;
        text-align: center;

        view:first-child {
            font-size: 52rpx;
        }

        // view:last-child {
        //     font-size: 36rpx;
        // }
    }


    .acount-login,
    .other-login {
        margin-top: 63rpx;
        width: calc(100% - 80rpx);

        .input {
            padding-top: 20rpx;
        }

        .login-btn {
            border-radius: 8rpx;
            margin: 30rpx 0 50rpx;
            background-color: $-color-primary;
            border-radius: 40rpx;
        }
    }

    .agreement {
        color: $-color-primary;
    }
}
</style>
