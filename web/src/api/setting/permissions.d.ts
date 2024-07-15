import * as Common from '@/api/common'

/** S 管理员 **/
export interface AdminList_Req extends Common.Paging_Req {
    name?: string // 名称
    account?: string // 账号
    role_id?: number // 角色ID
}

export interface AdminList_Res extends Common.Paging_Res {
    lists: [
        {
            id: number // ID
            name: string // 名称
            account: string // 账号
            role_id: number // 角色id
            role_name: string // 角色名称
            disable: 0 | 1 // 禁用状态 0-正常 1-禁用
            disable_desc: string // 禁用状态描述
            login_time: string // 最后登录时间
            login_ip: string // 最后登录ip
            multipoint_login: number //	同一账号是否支持多端登录 0-否 1-是
        }
    ]
}

export interface AdminDetail_Req {
    id: number // 管理员ID
}

export interface AdminDetail_Res extends Common.Indexes {
    name: string // 名称
    account: string // 账号
    role_id: number // 角色id
    avatar: string // 头像
    disable: 0 | 1 // 禁用状态 0-正常 1-禁用
    multipoint_login: number //	同一账号是否支持多端登录 0-否 1-是
}

export interface AdminAdd_Req {
    account: string // 账号
    name: string // 名称
    password: string // 密码
    password_confirm: string // 确认密码
    role_id: number // 角色ID
    disable: 0 | 1 // 禁用【0：否 | 1：是】
    multipoint_login: 0 | 1 // 支持【0：否 | 1：是】
    avatar?: string // 头像URL
}

export interface AdminDelete_Req {
    id: number // 管理员ID
}

export interface AdminEdit_Req {
    id: number // 管理员ID
    account: string // 账号
    name: string // 名称
    password?: string // 密码
    password_confirm?: string // 确认密码
    role_id: number // 角色ID
    disable: 0 | 1 // 禁用【0：否 | 1：是】
    multipoint_login: 0 | 1 // 禁用【0：否 | 1：是】
}
/** E 管理员 **/

/** S 角色 **/
export type RoleList_Req = Common.Paging_Req

export interface RoleList_Res extends Common.Paging_Res {
    lists: [
        {
            id: number // 角色ID
            name: string // 名称
            desc: string // 描述
            create_time: string // 创建时间
            auth_desc: string // 权限
        }
    ]
}

export interface RoleDetail_Req {
    id: number // 角色ID
}

export interface RoleDetail_Res extends Common.Indexes {
    name: string // 名称
    auth_ids: any // 权限
    desc: string // 描述
}

export interface RoleAdd_Req {
    name: string // 名称
    auth_keys: any[] // 权限
    desc?: string // 描述
}

export interface RoleEdit_Req {
    id: number // 角色ID
    name: string // 名称
    auth_ids?: any // 权限
    desc?: string // 描述
}

export interface RoleDelete_Req {
    id: number // 角色ID
}
/** E 角色 **/
