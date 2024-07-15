import request from '@/plugins/axios'

/** S 存储设置 **/
// 获取存储引擎列表
export const apiStorageList = (): Promise<any> => request.get('/settings.admin.Storage/lists')

// 获取存储配置信息
export const apiStorageIndex = (params: any): Promise<any> => request.get('/settings.admin.Storage/index', { params })

// 更新配置
export const apiStorageSetup = (params: any): Promise<any> => request.post('/settings.admin.Storage/setup', params)

// 切换默认存储引擎
export const apiStorageChange = (params: any): Promise<any> => request.post('/settings.admin.Storage/change', params)
/** E 存储设置 **/
