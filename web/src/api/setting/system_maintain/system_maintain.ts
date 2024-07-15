import request from '@/plugins/axios'
import * as Interface from '@/api/setting/system_maintain/system_maintain.d.ts'

/** S 系统维护 **/
// 获取系统日志列表
export const apiSystemlogList = (params: Interface.Journal_Req): Promise<Interface.Journal_Res> =>
    request.get('/settings.system.log/lists', { params })

// 清除系统缓存
export const apiSystemCacheClear = () => request.post('/settings.system.cache/clear')

// 定时任务列表
export const apiCrontabLists = () => request.get('/crontab.crontab/lists')

// 添加定时任务
export const apiCrontabAdd = (params: any) => request.post('/crontab.crontab/add', params)

// 查看详情
export const apiCrontabDetail = (params: any) => request.get('/crontab.crontab/detail', { params })

// 编辑定时任务
export const apiCrontabEdit = (params: any) => request.post('/crontab.crontab/edit', params)

// 删除定时任务
export const apiCrontabDel = (params: any) => request.post('/crontab.crontab/delete', params)

// 获取规则执行时间
export const apiCrontabExpression = (params: any) => request.get('/crontab.crontab/expression', { params })

// 操作定时任务
export const apiSrontabOperate = (params: any) => request.post('/crontab.crontab/operate', params)

/** E 系统维护 **/

/** S 系统更新 **/
// 系统更新列表
export const apiSystemUpgradeLists = (params: any) => request.get('/settings.system.upgrade/lists', { params })

// 下载更新包
export const apiSystemUpgradeDownloadPkg = (params: any) => request.post('/settings.system.upgrade/downloadPkg', params)

// 一键更新
export const apiSystemUpgrade = (params: any) => request.post('/settings.system.upgrade/upgrade', params)
/** E 系统更新 **/
