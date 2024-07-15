import request from '@/plugins/axios'

/** S 俱乐部管理 **/
// 俱乐部列表
export const apiClubList = (params: any): Promise<any> => request.get('/club.club/lists', { params })
// 俱乐部详情
export const apiClubDetail = (params: any): Promise<any> => request.get('/club.club/detail', { params })
// 编辑俱乐部
export const apiClubSetInfo = (params: any): Promise<any> => request.post('/club.club/setInfo', params)
// 解散俱乐部
export const apiClubDelete = (params: any): Promise<any> => request.post('/club.club/deleteClub', params)
// 成员审核列表
export const apiClubRequestList = (params: any): Promise<any> => request.get('/club.club/requestLists', { params })
// 审核审批
export const apiClubRequestSetInfo = (params: any): Promise<any> => request.post('/club.club/setRequestInfo', params)
// 充值
export const apiClubRecharge = (params: any): Promise<any> => request.post('/club.club/adjustUserWallet', params)
// 添加俱乐部
export const apiClubAdd = (params: any): Promise<any> => request.post('/club.club/addClub', params)
// 成员管理
export const apiClubMemberList = (params: any): Promise<any> => request.get('/club.club/memberLists', { params })
// 踢出成员
export const apiKickMember = (params: any): Promise<any> => request.post('/club.club/kickMember', params)
// 房间管理
export const apiClubRoomList = (params: any): Promise<any> => request.get('/club.club/roomLists', { params })
// 俱乐部房间详情
export const apiClubRoomDetail = (params: any): Promise<any> => request.get('/club.club/roomDetail', { params })
// 实时房间管理
export const apiClubRoomOnlineList = (params: any): Promise<any> =>
    request.get('/club.club/onLineRoomLists', { params })
// 实时房间详情
export const apiClubOnlineRoomDetail = (params: any): Promise<any> =>
    request.get('/club.club/onlineRoomDetail', { params })
// 设置管理员
export const apiSetMemberInfo = (params: any): Promise<any> => request.post('/club.club/setMemberInfo', params)
// 黑名单管理
export const apiClubBlackList = (params: any): Promise<any> => request.get('/club.club/blackLists', { params })
// 设置黑名单
export const apiSetBlackInfo = (params: any): Promise<any> => request.post('/club.club/setBlackInfo', params)
/** E 俱乐部管理 **/
