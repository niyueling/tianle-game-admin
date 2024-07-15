import request from '@/plugins/axios'

/** S 素材管理 **/
// 添加文件分类
export const apiFileAddCate = (params: any): Promise<any> => request.post('/file/addCate', params)

// 编辑文件分类
export const apiFileEditCate = (params: any): Promise<any> => request.post('/file/editCate', params)

// 删除文件分类
export const apiFileDelCate = (data: { id: number }) => request.post('/file/delCate', data)

// 文件分类列表
export const apiFileListCate = (params: any) => request.get('/file/listCate', { params })

// 文件列表
export const apiFileList = (params: any) => request.get('/file/lists', { params })

// 文件删除
export const apiFileDel = (data: { ids: any[] }) => request.post('/file/delete', data)

// 文件移动
export const apiFileMove = (data: { ids: any[]; cid: number }) => request.post('/file/move', data)

// 文件重命名
export const apiFileRename = (data: { id: number; name: string }) => request.post('file/rename', data)

/** E 素材管理 **/
