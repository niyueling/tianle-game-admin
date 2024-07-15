import request from '@/plugins/axios'

// 通知列表
export const apiNoticeData = (params: any) => request.get('/sms.notice/settingLists', { params })

// 通知设置详情
export const apiNoticeDetail = (params: any) => request.get('/sms.notice/detail', { params })

// 通知设置
export const apiNoticeSet = (params: any) => request.post('/sms.notice/set', params)

// 获取短信设置列表
export const apiSmsGetConfig = () => request.get('/sms.sms_config/getConfig')

// 获取短信设置列表
export const apiSmsGetConfigDetail = (params: any) => request.get('/sms.sms_config/detail', { params })

// 通知设置
export const apiSmsSet = (params: any) => request.post('/sms.sms_config/setConfig', params)
