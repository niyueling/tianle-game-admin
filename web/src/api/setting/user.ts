import request from '@/plugins/axios'
import * as Interface from '@/api/setting/user.d.ts'

/** S 用户设置 **/
// 获取用户设置
export const apiUserConfig = (): Promise<Interface.UserConfig_Res> => request.get('/settings.user.user/getConfig')
// 设置用户设置
export const apiUserConfigSet = (data: any): Promise<any> => request.post('/settings.user.user/setConfig', data)
// 获取登录注册配置
export const apiRegisterConfig = (): Promise<Interface.UserConfig_Res> =>
    request.get('/settings.user.user/getRegisterConfig')
// 设置登录注册配置
export const apiRegisterConfigSet = (data: any): Promise<any> =>
    request.post('/settings.user.user/setRegisterConfig', data)
// 获取用户设置
export const apiWithdrawConfig = (): Promise<Interface.UserConfig_Res> =>
    request.get('/settings.user.user/getWithdrawConfig')
// 设置用户设置
export const apiWithdrawConfigSet = (data: any): Promise<any> =>
    request.post('/settings.user.user/setWithdrawConfig', data)
/** E 用户设置 **/
