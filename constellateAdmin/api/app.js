import request from '@/utils/request'
import wechath5 from '@/utils/wechath5'
import store from 'store'

// 账号登录
export const apiAccountLogin = data => 
	request.post("account/login", {...data})
	
// 重置登录密码
export const apiResetPassword = data => 
	request.post("agency.agency/setInfo", data)

// 获取服务协议
export const apiPolicyAgreement = (params) => request.get('settings.admin.admin_setting/getPolicyAgreement', {params})

// 获取公共配置
export const apiConfig = () => request.get('config/getConfig')

//微信sdk配置
export function apiJsConfig() {
    return request.get("wechat.wechat/jsConfig", {
        params: {
            url: wechath5.signLink(),
        },
    });
}

// 预支付
export const apiPrepay = data => request.post("agency.agency/prepay", data)

// 预支付
export const apiAliPrepay = data => request.post("alipay.alipay/webPay", data)

// 支付结果查询
export const apiPayStatus = (params) => request.get("agency.agency/payStatus", { params });

// 预支付
export const apiPlayerPrepay = data => request.post("user.user/prepay", data)

// 支付结果查询
export const apiPlayerPayStatus = (params) => request.get("user.user/payStatus", { params });

//新用户注册
export const apiFindOrCreate = (params) => request.get("user.user/findOrCreate", { params });

//获取验证码
export const apiSendSms = (params) => request.get("user.user/captcha", { params });

//注册账户
export const apiNewUserCreate = data => request.post("user.user/create", data);