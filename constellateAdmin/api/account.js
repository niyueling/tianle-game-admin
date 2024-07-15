import request from '@/utils/request'
import wechath5 from '@/utils/wechath5'


// 账号登录
export const apiAccountLogin = params => 
	request.post("account/login", {...params}) 

// 公众号登录
export const apiOALogin = params => 
	request.post('wechat.wechat/oaLogin', params)

// 向微信请求code的链接
export const apiCodeUrlGet = params =>
    request.get("wechat.wechat/codeUrl", {
        params: params,
        headers: { 1: 1 },
    });
