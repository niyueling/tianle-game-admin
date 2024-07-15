import request from '@/plugins/axios'

// 查询用户充值记录
export const apiAccountLog = (params: any) => request.get('/account_log/userRechargeLists', { params })
// 查询用户充值统计
export const apiUserRechargeStatistics = (params: any) => request.get('/account_log/userRechargeStatisticsLists', { params })
// 查询用户钻石记录
export const apiUserGemLog = (params: any) => request.get('/account_log/userGemLists', { params })
// 查询用户红包排行榜记录
export const apiUserRedPocketRanking = (params: any) => request.get('/account_log/userRedPocketRankingLists', { params })
// 查询用户红包领取记录
export const apiUserRedPocketLog = (params: any) => request.get('/account_log/userRedPocketLists', { params })
// 查询用户红包提现记录
export const apiUserRedPocketithdrawLog = (params: any) => request.get('/account_log/userRedPocketWithdrawLists', { params })
// 查询用户战队金币记录
export const apiUserClubGoldLog = (params: any) => request.get('/account_log/userClubGoldLists', { params })
// 用户房间管理
export const apiUserRoomList = (params: any): Promise<any> => request.get('/account_log/roomLists', { params })
// 用户房间详情
export const apiUserRoomDetail = (params: any): Promise<any> => request.get('/account_log/roomDetail', { params })
// 查询宝箱掉落记录
export const apiUserGameRateList = (params: any) => request.get('/account_log/userGameRateLists', { params })
// 查询用户游戏胜率
export const apiUserGameRateRankList = (params: any) => request.get('/account_log/userGameRateRankLists', { params })
// 查询代理充值记录
export const apiAgencyRechargeLog = (params: any) => request.get('/account_log/agencyRechargeLists', { params })
// 查询代理自助充值记录
export const apiAgencyAutoRechargeLog = (params: any) => request.get('/account_log/agencyAutoRechargeLists', { params })
// 查询代理钻石记录
export const apiAgencyGemLog = (params: any) => request.get('/account_log/agencyGemLists', { params })
// 查询俱乐部流水记录
export const apiClubAccountLog = (params: any) => request.get('/account_log/clubAccountLists', { params })
// 俱乐部房间管理
export const apiClubRoomList = (params: any): Promise<any> => request.get('/account_log/clubRoomLists', { params })
// 俱乐部房间详情
export const apiClubRoomDetail = (params: any): Promise<any> => request.get('/account_log/clubRoomDetail', { params })
// 俱乐部房间排行榜
export const apiClubRankLog = (params: any): Promise<any> => request.get('/account_log/clubRoomRanking', { params })
// 查询战队主操作金币记录
export const apiClubCreatorGoldLog = (params: any) => request.get('/account_log/clubCreatorGoldLists', { params })
// 查询商品购买订单
export const apiGoodsPayOrderLog = (params: any) => request.get('/account_log/payGoodsOrderLists', { params })
//查询4王局
export const apiFourJokerGameLog = (params: any) => request.get('/account_log/gameHaveFourLists', { params })
//查询4王局详情
export const apiFourJokerGameDetail = (params: any) => request.get('/account_log/gameHaveFourDetail', { params })
export const apiUserRoomRuleDetail = (params: any) => request.get('/account_log/roomRuleDetail', { params })

//解散房间
export const apiUserRoomDissolve = (params: any) => request.post('/account_log/userRoomDissolve',  params )
export const apiUserGameHelpList = (params: any) => request.get('/account_log/userGameHelpRecord', {params} )
export const apiUserDiamondList = (params: any) => request.get('/account_log/userDiamondLists', {params} )
export const apiUserGoldList = (params: any) => request.get('/account_log/userGoldLists', {params} )
