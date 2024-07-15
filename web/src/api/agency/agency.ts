import request from '@/plugins/axios'

/** S 用户管理 **/
// 代理列表
export const apiAgencyList = (params: any): Promise<any> => request.get('/agency.agency/lists', { params })
// 代理详情
export const apiAgencyDetail = (params: any): Promise<any> => request.get('/agency.agency/detail', { params })
// 编辑代理
export const apiAgencySetInfo = (params: any): Promise<any> => request.post('/agency.agency/setInfo', params)
// 充值
export const apiAgencyRecharge = (params: any): Promise<any> => request.post('/agency.agency/adjustUserWallet', params)
// 成员审核列表
export const apiAgencyRequestList = (params: any): Promise<any> =>
    request.get('/agency.agency/requestLists', { params })
// 审核审批
export const apiAgencyRequestSetInfo = (params: any): Promise<any> =>
    request.post('/agency.agency/setRequestInfo', params)
// 添加代理
export const apiAgencyAdd = (params: any): Promise<any> => request.post('/agency.agency/addAgency', params)
// 代理详情
export const apiClubInfo = (params: any): Promise<any> => request.get('/club.club/detail', { params })
// 获取验证码
export const apiCaptcha = (params: any): Promise<any> => request.get('/agency.agency/captcha', { params })
/** E 用户管理 **/
