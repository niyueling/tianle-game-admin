import request from '@/plugins/axios'

// 登录
export const apiLogin = (data: any) => request.post('/account/login', data)

// 退出登录
export const apiLogout = () => request.get('/account/logout')

// 配置
export const apiconfig = () => request.get('/config/getConfig')

// 权限列表
export const apiAuth = () => request.get('/config/getAuth')

// 获取管理员基本信息
export const apiAdminInfo = () => request.get('/auth.admin/getAdminInfo')

// 获取角色列表
export const apiRoleList = () => request.get('/auth.role/lists')

// 修改管理员密码
export const apiAdminResetPassword = (params: any) => request.post('/auth.admin/resetPassword', params)
