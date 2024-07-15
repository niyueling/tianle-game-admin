import request from '@/plugins/axios'
/** S 商城公告 **/

// 添加公告
export const apiNoticeAdd = (params: any): Promise<any> => request.post('/notice.notice/add', params)
// 公告列表
export const apiNoticeLists = (params: any): Promise<any> => request.get('/notice.notice/lists', { params })
// 编辑公告
export const apiNoticeEdit = (params: any): Promise<any> => request.post('/notice.notice/edit', params)
// 删除公告
export const apiNoticeDel = (params: any): Promise<any> => request.post('/notice.notice/del', params)
/** E 商城公告 **/
