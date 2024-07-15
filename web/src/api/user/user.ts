import request from '@/plugins/axios'

/** S 用户管理 **/
// 用户列表
export const apiUserList = (params: any): Promise<any> => request.get('/user.user/lists', { params })
// 用户详情
export const apiUserDetail = (params: any): Promise<any> => request.get('/user.user/detail', { params })
// 更新用户基本信息
export const apiUserSetInfo = (params: any): Promise<any> => request.post('/user.user/setInfo', params)
// 调整用户钱包
export const apiUserRecharge = (params: any): Promise<any> => request.post('/user.user/adjustUserWallet', params)
// 用户概况
export const apiUserIndex = (): Promise<any> => request.get('/user.user/index')
// 房间管理
export const apiUserRoomList = (params: any): Promise<any> => request.get('/user.user/roomLists', { params })
// 房间详情
export const apiUserRoomDetail = (params: any): Promise<any> => request.get('/user.user/roomDetail', { params })
// 邀请列表
export const apiUserInviterLists = (params: any): Promise<any> => request.get('/user.user/inviteLists', { params })
// 删除用户
export const apiDeleteUserInfo = (params: any): Promise<any> => request.post('/user.user/delete', params )
/** E 用户管理 **/
