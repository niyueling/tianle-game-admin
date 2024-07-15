import request from '@/plugins/axios'
/** S 邮件 **/
// 添加邮件
export const apiEmailAdd = (params: any): Promise<any> => request.post('/email.email/add', params)
// 邮件列表
export const apiEmailLists = (params: any): Promise<any> => request.get('/email.email/lists', { params })
// 删除邮件
export const apiEmailDel = (params: any): Promise<any> => request.post('/email.email/del', params)
/** E 邮件 **/

/** S 公共邮件 **/
// 添加邮件
export const apiPublicEmailAdd = (params: any): Promise<any> => request.post('/email.email/addPublicEmail', params)
// 邮件列表
export const apiPublicEmailLists = (params: any): Promise<any> => request.get('/email.email/publicLists', { params })
// 删除邮件
export const apiPublicEmailDel = (params: any): Promise<any> => request.post('/email.email/delPublicEmail', params)
// 公共/圈主邮件领取列表
export const apiReceiveEmailLists = (params: any): Promise<any> => request.get('/email.email/receiveLists', { params })
/** E 公共邮件 **/

export const apiUserClubLists = (params: any): Promise<any> => request.get('/email.email/userClubLists', { params })
