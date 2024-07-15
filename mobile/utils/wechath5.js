// #ifdef H5
import weixin from "@/js_sdk/jweixin-module";
import {
	isAndroid
} from "./tools"
import {
	apiJsConfig,
} from '@/api/app'
import {
	apiCodeUrlGet,
	apiOALogin
} from '@/api/account'
import store from '../store'
import Cache from './cache'
class Wechath5 {
	//获取微信配置url
	signLink() {
		if (typeof window.entryUrl === 'undefined' || window.entryUrl === '') {
			window.entryUrl = location.href.split('#')[0]
		}
		return isAndroid() ? location.href.split('#')[0] : window.entryUrl;
	}
	//微信sdk配置
	config() {
		return new Promise((resolve) => {
			apiJsConfig().then(res => {
				localStorage.setItem("wxcofig", JSON.stringify(res));
				weixin.config({
					debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
					appId: res.appId, // 必填，公众号的唯一标识
					timestamp: res.timestamp, // 必填，生成签名的时间戳
					nonceStr: res.nonceStr, // 必填，生成签名的随机串
					signature: res.signature, // 必填，签名
					jsApiList: res.jsApiList // 必填，需要使用的JS接口列表
				});
				resolve()
			})
		})
	}

	//获取微信登录url
	getWxUrl(url) {
		apiCodeUrlGet({url}).then(res => {
			location.href = res.url
		})
	}

	//微信授权
	authLogin(code) {
		return new Promise((resolve, reject) => {
			apiOALogin({
				code
			}).then(res => {
				store.commit("login", res);
				resolve(res);
			})
		});
	}

	//微信分享
	share(option) {
		weixin.ready(() => {
			const {
				shareTitle,
				shareLink,
				shareImage,
				shareDesc
			} = option
			weixin.updateTimelineShareData({
				title: shareTitle, // 分享标题
				link: shareLink, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
				imgUrl: shareImage, // 分享图标
				success: function(res) {
					// 设置成功
				}
			});
			// 发送给好友
			weixin.updateAppMessageShareData({
				title: shareTitle, // 分享标题
				link: shareLink, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
				imgUrl: shareImage, // 分享图标
				desc: shareDesc,
				success: function(res) {
					// 设置成功
				}
			});
			// 发送到tx微博
			weixin.onMenuShareWeibo({
				title: shareTitle, // 分享标题
				link: shareLink, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
				imgUrl: shareImage, // 分享图标
				desc: shareDesc,
				success: function(res) {
					// 设置成功
				}
			})
		})
	}
	wxPay(opt) {
		return new Promise((reslove, reject) => {
			weixin.ready(() => {
				weixin.chooseWXPay({
					timestamp: opt.timeStamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
					nonceStr: opt.nonceStr, // 支付签名随机串，不长于 32 位
					package: opt.package, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
					signType: opt.signType, // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
					paySign: opt.paySign, // 支付签名
					success: (res) => {
						reslove()
					},
					cancel: (res) => {
						reject()
					},
					fail: (res) => {
						reject()
					},
				});
			});
		})

	}

	getWxAddress() {
		return new Promise((reslove, reject) => {
			weixin.ready(() => {
				weixin.openAddress({
					success: (res) => {
						reslove(res)
					},
				})
			})
		})
	}
	
	scanQRCode() {
			console.log('ready')
			return new Promise((reslove, reject) => {
				weixin.scanQRCode({
					needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
					scanType: ["qrCode", "barCode"], // 可以指定扫二维码还是一维码，默认二者都有
					success: function(res) {
						reslove(res.resultStr)
					},
					 error:function(res){
						 console.log(res)
						alert(res)
					  }
				});
			})
		}
}

export default new Wechath5()
// #endif
