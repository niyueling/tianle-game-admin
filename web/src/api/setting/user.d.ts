import * as Common from '@/api/common'

/** S 用户设置 **/
// 获取商品设置
export interface UserConfig_Res {
    default_avatar: string // 默认头像
    register_way: 1 | 2 // 注册方式：1-账号密码注册；2-手机号码注册
    login_way: 1 | 2 //登录方式：1-账号密码登录；2-手机短信验证码登录
    is_mobile_register_code: 0 | 1 // 手机号码注册需验证码：0-关闭；1-开启
    coerce_mobile: 0 | 1 // 手机号码注册需验证码：0-关闭；1-开启
    h5_wechat_auth: 0 | 1 // 微信公众号-微信授权登录：0-关闭；1-开启
    h5_auto_wechat_auth: 0 | 1 // 微信公众    	// 号-自动微信授权登录:0-关闭；1-开启;
    mnp_wechat_auth: 0 | 1 // 小程序-微信授权登录 :0-关闭；1-开启;
    mnp_auto_wechat_auth: 0 | 1 // 小程序-自动微信授权登录:0-关闭；1-开启;
    app_wechat_auth: 0 | 1 // app-微信授权登录:0-关闭；1-开启;
    withdraw_way: Array // 提现方式：1-钱包余额；2-微信零钱；3-银行卡；4-微信收款码；5-支付宝收款码
    withdraw_min_money: number // 最低提现金额
    withdraw_max_money: number // 最高提现金额
    withdraw_service_charge: number // 提现手续费
    scene: string // 场景：user-用户设置；register-注册设置；withdraw-提现设置
    invite_open: 0 | 1 // 邀请下级：0-关闭 1-开启
    invite_ways: 1 | 2 // 邀请下级资格：1-全部用户可邀请 2-分销会员可邀请
    invite_appoint_user: Array // 邀请下级指定用户；1-分销会员；2-股东会员；3-代理会员（邀请下级资格为1时留空）
    invite_condition: 1 // 成为下级条件：1-邀请码
}
/** E 用户设置 **/
