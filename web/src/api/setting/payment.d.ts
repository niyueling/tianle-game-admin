import * as Common from '../common'

/** S 支付方式 **/

export interface PaymentMethodSet_Req {
    id: number //支付方式的ID
    is_default: Number //是否默认支付
    scene?: Number
    status: Number //状态是否开启
}

/** E 支付方式 **/

/** S 支付配置 **/

// 支付配置设置——请求时的参数
export interface PaymentConfig_Req extends Common.Indexes {
    id?: Number //支付配置ID
    name: String //支付名称
    icon: String //支付图标
    sort: String //排序
    remark?: String //备注
    merchant_type?: String //（微信支付 ｜｜ 支付宝）商户类型ordinary_merchant-普通商户
    interface_version?: String //微信支付接口版本v2-v2
    mch_id?: String //微信支付商户号
    pay_sign_key?: String //微信商户支付API密钥
    apiclient_cert?: String //微信支付证书
    apiclient_key?: String //微信支付证书密钥
    pattern?: String //模式：normal_mode普通商户
    app_id?: String //应用ID
    private_key?: String //支付宝公钥
    ali_public_key?: String //应用私钥
}

// 支付配置获取——请求时的参数
export interface PaymentConfigGet_Req {
    id: Number //支付配置ID
}

/** E 支付配置 **/
