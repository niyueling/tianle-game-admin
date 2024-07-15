/*
枚举类型
*/

// 拼团类型
export enum teamType {
    'all',
    'not',
    'conduct',
    'end'
}

// 拼团类型
export enum SeckillType {
    'all',
    'not',
    'conduct',
    'end'
}

// 分销会员申请类型
export enum disType {
    'all',
    'wait',
    'pass',
    'refuse'
}

// 接口Code状态
export enum APIResponseCodeType {
    'success' = 1, // 成功
    'error' = 0, // 失败
    'redirect' = -1, // 重定向
    'page' = 2 // 跳转页面
}

// 页面模式
export enum PageMode {
    'ADD' = 'add', // 添加
    'EDIT' = 'edit' // 编辑
}
