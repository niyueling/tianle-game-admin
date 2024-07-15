import * as Common from '../../common'

/** S 系统维护 **/
// 系统日志列表
export interface Journal_Req extends Common.Indexes {
    admin_name: string // 管理员
    url: string // 访问链接
    ip: string // 来源IP
    type: string // 访问方式
    start_time: string // 日志时间开始
    end_time: string // 日志时间结束
}

export interface Journal_Res extends Common.Paging_Res {
    id: number // 记录ID
    admin_name: string // 管理员
    admin_id: string // 管理员ID
    url: string // 访问链接
    type: string // 访问方式
    params: string // 访问参数
    ip: string // 来源IP
    create_time: string // 日志时间
}
/** E 系统维护 **/
