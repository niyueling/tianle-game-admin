import request from '@/plugins/axios'

/** S 店铺信息 **/
// 店铺信息--获取
export const apiSettingShopInfo = (): Promise<any> => request.get('/settings.admin.admin_setting/getShopInfo')

// 店铺信息--设置
export const apiSettingShopEdit = (params: any): Promise<any> =>
    request.post('/settings.admin.admin_setting/setShopInfo', params)
/** E 店铺信息 **/

/** S 备案信息 **/
// 获取备案信息
export const apiRecordInfo = (): Promise<any> => request.get('/settings.admin.admin_setting/getRecordInfo')

// 编辑备案信息
export const apiRecordEdit = (params: any): Promise<any> =>
    request.post('/settings.admin.admin_setting/setRecordInfo', params)
/** E 备案信息 **/

/** S 自助充值方案 **/
// 获取自助充值方案
export const apiProtocolInfo = (): Promise<any> => request.get('/agency.agency/getPolicyAgreement')

// 设置自助充值方案
export const apiProtocolEdit = (params: any): Promise<any> => request.post('/agency.agency/setPolicyAgreement', params)
/** E 自助充值方案 **/

/** S 政策协议 **/
// 获取政策协议
export const apiAdminProtocolInfo = (): Promise<any> => request.get('/settings.admin.admin_setting/getPolicyAgreement')

// 设置政策协议
export const apiAdminProtocolEdit = (params: any): Promise<any> =>
    request.post('/settings.admin.admin_setting/setPolicyAgreement', params)
/** E 政策协议 **/
