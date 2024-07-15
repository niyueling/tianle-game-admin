import request from '@/utils/request'

// 个人中心
export const apiUserCentre = () => request.get('/auth.admin/getAdminInfo')
// 获取用户信息
export const apiGetUserInfo = () => request.get('/auth.admin/getAdminInfo')
// 退出登录
export const apiLogout = () => request.post('account/logout')
// 设置用户登录登录密码
export const apiSetPassword = params => request.post('shop/changePwd', params)
//  E 个人设置
// 账户明细
export const userBill = (params) => request.get('account_log/lists', {params})
//获取充值模板
export const apiRechargeTemplateLists = (params) => request.get('agency.agency/getRechargeTemplate', {params})
//充值
export const apiRecharge = (params) => request.post('agency.agency/recharge', params)
export const apiBindOrGetUserInfo = (params) => request.post('user.user/bindOrGetUserInfo', params)
//获取充值模板
export const apiPlayerRechargeTemplateLists = (params) => request.get('user.user/getRechargeTemplate', {params})
//充值
export const apiPlayerRecharge = (params) => request.post('user.user/recharge', params)
//获取最近一条待支付订单
export const apiLastPayOrder = (params) => request.get('user.user/getLastPayOrder', {params})

export const getTemplates = (params) => request.get('user.user/getTemplates', {params})
