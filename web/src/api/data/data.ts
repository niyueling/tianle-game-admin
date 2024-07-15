import request from '@/plugins/axios'

/** S 数据概况 **/
// 获取数据概况
export const apiDataCenter = (): Promise<any> => request.get('/data.center/index')
// 获取房间分析
export const apiDataCenterVisit = (params: any): Promise<any> => request.get('/data.center/trafficAnalysis', { params })
// 获取用户分析
export const apiUserAnalysis = (params: any): Promise<any> => request.get('/data.center/userAnalysis', { params })
// 获取用户分析
export const apiRechargeAnalysis = (params: any): Promise<any> =>
    request.get('/data.center/rechargeAnalysis', { params })
// 获取交易分析
export const apiConsumeAnalysis = (params: any): Promise<any> => request.get('/data.center/consumeAnalysis', { params })
// 获取访问量
export const apiVisitorAnalysis = (params: any): Promise<any> => request.get('/data.center/visitorAnalysis', { params })
