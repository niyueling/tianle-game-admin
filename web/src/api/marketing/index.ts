import request from '@/plugins/axios'

// 商品管理
export const apiGoodList = (params: any) => request.get('/marketing.goods/goodLists', { params })
// 删除商品
export const apiDelGoods = (params: any) => request.post('/marketing.goods/delGoods', params)
// 编辑商品
export const apiSetGoodInfo = (params: any) => request.post('/marketing.goods/setGoodInfo', params)
// 新增商品
export const apiaddGoods = (params: any) => request.post('/marketing.goods/addGoods', params)
// 充值管理
export const apiRechargeList = (params: any) => request.get('/marketing.recharge/goodLists', { params })
// 删除商品
export const apiDelRecharge = (params: any) => request.post('/marketing.recharge/delGoods', params)
// 编辑商品
export const apiSetRechargeInfo = (params: any) => request.post('/marketing.recharge/setGoodInfo', params)
// 新增商品
export const apiaddRecharge = (params: any) => request.post('/marketing.recharge/addGoods', params)
// 县区管理
export const apiRegionList = (params: any) => request.get('/marketing.region/regionLists', { params })
// 县区商品
export const apiDelRegion = (params: any) => request.post('/marketing.region/delRegion', params)
// 县区商品
export const apiSetRegionInfo = (params: any) => request.post('/marketing.region/setRegionInfo', params)
// 县区商品
export const apiaddRegion = (params: any) => request.post('/marketing.region/addRegion', params)
// 游戏区域管理
export const apiGameList = (params: any) => request.get('/marketing.game/gameLists', { params })
// 游戏区域搜索条件
export const apiSearchList = (params: any) => request.get('/marketing.game/searchLists', { params })
// 游戏区域商品
export const apiDelGame = (params: any) => request.post('/marketing.game/delGame', params)
// 游戏区域商品
export const apiSetGameInfo = (params: any) => request.post('/marketing.game/setGameInfo', params)
// 游戏区域商品
export const apiaddGame = (params: any) => request.post('/marketing.game/addGame', params)
// 红包管理
export const apiRedPocketList = (params: any) => request.get('/marketing.redpocket/redPocketLists', { params })
// 红包删除
export const apiDelRedPocket = (params: any) => request.post('/marketing.redpocket/delRedPocket', params)
// 红包编辑
export const apiSetRedPocketInfo = (params: any) => request.post('/marketing.redpocket/setRedPocketInfo', params)
// 红包新增
export const apiaddRedPocket = (params: any) => request.post('/marketing.redpocket/addRedPocket', params)
// 救助概览
export const apiRateIndex = (params: any) => request.post('/marketing.rate/index', params)
// 救助规则
export const apiRateRuleList = (params: any) => request.post('/marketing.rate/rule', params)
// 设置救助规则
export const apiSetRateRuleList = (params: any) => request.post('/marketing.rate/setRule', params)
// 查询用户是否触发规则
export const apiUserCanRateRuleLists = (params: any) => request.get('/account_log/userCanRateRuleLists', { params })
// 救助记录
export const apiUserHelpRecordLists = (params: any) => request.get('/marketing.rate/record', { params })
// 抽奖规则
export const apiPrizeRuleList = (params: any) => request.get('/marketing.prize/rule', { params })
// 设置救助规则
export const apiSetPrizes = (params: any) => request.post('/marketing.prize/setRule', params)
// 获取排行榜列表
export const apiRankLists = (params: any) => request.get('/marketing.prize/index', { params })
// 新增排行榜
export const apiAddRank = (params: any) => request.post('/marketing.prize/addInviteRankLists', params)
// 修改排行榜
export const apimodifyRankInfo = (params: any) => request.post('/marketing.prize/modifyInviteRank', params)
// 删除排行榜
export const apiDeleteRankInfo = (params: any) => request.post('/marketing.prize/deleteInviteRank', params)
// 游戏分类列表
export const apiGameCategoryList = (params: any) => request.get('/marketing.category/lists', { params })
// 删除游戏分类
export const apiDelGameCategory = (params: any) => request.post('/marketing.category/del', params)
// 修改游戏分类
export const apiSetGameCategoryInfo = (params: any) => request.post('/marketing.category/setInfo', params)
// 添加游戏分类
export const apiaddGameCategory = (params: any) => request.post('/marketing.category/add', params)

export const apiConversionList = (params: any) => request.get('/marketing.conversion/lists', { params })

export const apiDelConversion = (params: any) => request.post('/marketing.conversion/del', params)

export const apiSetConversionInfo = (params: any) => request.post('/marketing.conversion/setInfo', params)

export const apiaddConversion = (params: any) => request.post('/marketing.conversion/add', params)

export const apiGameCategoryItemLists = (params: any) => request.get('/marketing.category/gameItemList', { params })

export const apiGameItemList = (params: any) => request.get('/marketing.gameCategory/lists', { params })

export const apiDelGameItem = (params: any) => request.post('/marketing.gameCategory/del', params)

export const apiSetGameItemInfo = (params: any) => request.post('/marketing.gameCategory/setInfo', params)

export const apiaddGameItem = (params: any) => request.post('/marketing.gameCategory/add', params)

export const apiTreasureBoxList = (params: any) => request.get('/marketing.TreasureBox/lists', { params })

export const apiDelTreasureBox = (params: any) => request.post('/marketing.TreasureBox/del', params)

export const apiSetTreasureBoxInfo = (params: any) => request.post('/marketing.TreasureBox/setInfo', params)

export const apiAddTreasureBox = (params: any) => request.post('/marketing.TreasureBox/add', params)

export const apiCheerList = (params: any) => request.get('/marketing.cheer/lists', { params })

export const apiDelCheer = (params: any) => request.post('/marketing.cheer/del', params)

export const apiSetCheerInfo = (params: any) => request.post('/marketing.cheer/setInfo', params)

export const apiAddCheer = (params: any) => request.post('/marketing.cheer/add', params)

export const apiQianList = (params: any) => request.get('/marketing.qian/lists', { params })

export const apiDelQian = (params: any) => request.post('/marketing.qian/del', params)

export const apiSetQianInfo = (params: any) => request.post('/marketing.qian/setInfo', params)

export const apiAddQian = (params: any) => request.post('/marketing.qian/add', params)

export const apiUserLotteryRecordLists = (params: any) => request.get('/marketing.prize/record', { params })
