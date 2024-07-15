// 分页请求
export interface Paging_Req {
    page_no?: number // 页码
    page_size?: number // 页数量
    page_type?: number // 是否分页
}

// 分页响应
export interface Paging_Res {
    count: number // 数据数量
    page_no: number // 页码
    page_size: number // 页数量
}

// 扩展更多属性
export interface Indexes {
    [Prop: string]: any
}
