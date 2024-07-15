import request from '@/utils/request'

// 首页
export const apiIndex = (params) => request.get('workbench/index', {params})

//获取管理员信息
export const apiGetShopInfo = (params) => request.post('settings.admin.admin_setting/getShopInfo')

// 设置管理员信息
export const apiSetShopInfo = (params) => request.post('settings.admin.admin_setting/setShopInfo', params)

// 房间分析
export const apiTrafficAnalysis = (params) => request.get('data.center/trafficAnalysis', {params})

// 用户分析
export const apiUserAnalysis = (params) => request.get('data.center/userAnalysis', {params})

// 充值分析
export const apiRechargeAnalysis = (params) => request.get('data.center/rechargeAnalysis', {params})

// 消耗分析
export const apiConsumeAnalysis = (params) => request.get('data.center/consumeAnalysis', {params})

// 访问量分析
export const apiVisitorAnalysis = (params) => request.get('data.center/visitorAnalysis', {params})