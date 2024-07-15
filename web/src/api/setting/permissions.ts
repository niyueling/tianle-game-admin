import request from '@/plugins/axios'
import * as Interface from '@/api/setting/permissions.d'

/** S 管理员 **/
// 管理员列表
export const apiAdminList = (params: Interface.AdminList_Req): Promise<Interface.AdminList_Res> =>
    request.get('/auth.admin/lists', { params })

// 管理员详情
export const apiAdminDetail = (params: Interface.AdminDetail_Req): Promise<Interface.AdminDetail_Res> =>
    request.get('/auth.admin/detail', { params })

// 添加管理员
export const apiAdminAdd = (data: Interface.AdminAdd_Req): Promise<any> => request.post('/auth.admin/add', data)

// 删除管理员
export const apiAdminDelete = (data: Interface.AdminDelete_Req): Promise<any> =>
    request.post('/auth.admin/delete', data)

// 编辑管理员
export const apiAdminEdit = (data: Interface.AdminEdit_Req): Promise<any> => request.post('/auth.admin/edit', data)

/** E 管理员 **/

/** S 角色 **/
// 角色列表
export const apiRoleList = (params?: Interface.RoleList_Req): Promise<Interface.RoleList_Res> =>
    request.get('/auth.role/lists', { params })

// 角色详情
export const apiRoleDetail = (params: Interface.RoleDetail_Req): Promise<Interface.RoleDetail_Res> =>
    request.get('/auth.role/detail', { params })

// 添加角色
export const apiRoleAdd = (data: Interface.RoleAdd_Req): Promise<any> => request.post('/auth.role/add', data)

// 编辑角色
export const apiRoleEdit = (data: Interface.RoleEdit_Req): Promise<any> => request.post('/auth.role/edit', data)

// 删除角色
export const apiRoleDelete = (data: Interface.RoleDelete_Req): Promise<any> => request.post('/auth.role/delete', data)

// 权限菜单
export const apiAuthMenu = () => request.post('/config/getMenu')
/** E 角色 **/
