import request from '@/utils/request'

// 用户充值记录
export const apiUserRechargeLists = (params) => request.get('account_log/userRechargeLists', {params})

// 用户充值统计
export const apiUserRechargeStatisticsLists = (params) => request.get('account_log/userRechargeStatisticsLists', {params})

// 用户房卡记录
export const apiUserGemLists = (params) => request.get('account_log/userGemLists', {params})

// 用户金币记录
export const apiUserClubGoldLists = (params) => request.get('account_log/userClubGoldLists', {params})

// 用户红包领取记录
export const apiUserRedPocketLists = (params) => request.get('account_log/userRedPocketLists', {params})

// 用户红包提现记录
export const apiUserRedPocketithdrawLists = (params) => request.get('account_log/userRedPocketWithdrawLists', {params})

// 用户房间记录
export const apiUserRoomLists = (params) => request.get('account_log/roomLists', {params})

// 用户红包排行榜
export const apiUserRedPocketRankingLists = (params) => request.get('account_log/userRedPocketRankingLists', {params})

// 代理充值记录
export const apiAgencyRechargeLists = (params) => request.get('account_log/agencyRechargeLists', {params})

// 代理房卡记录
export const apiAgencyGemLists = (params) => request.get('account_log/agencyGemLists', {params})

// 俱乐部流水记录
export const apiClubAccountLogLists = (params) => request.get('account_log/clubAccountLists', {params})

// 俱乐部金币记录
export const apiClubGoldLists = (params) => request.get('account_log/userClubGoldLists', {params})

// 俱乐部房间记录
export const apiClubRoomLists = (params) => request.get('account_log/clubRoomLists', {params})

// 俱乐部房间排行榜
export const apiClubRoomRankingLists = (params) => request.get('account_log/clubRoomRanking', {params})

// 金币管理记录
export const apiClubCreatorGoldLists = (params) => request.get('account_log/clubCreatorGoldLists', {params})

// 商品订单管理
export const apiGoodsOrderLists = (params) => request.get('account_log/payGoodsOrderLists', {params})