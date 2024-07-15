import request from '@/plugins/axios'
import * as Interface from '@/api/setting/payment.d.ts'

/** S 支付方式 **/

// 获取支付方式列
export const apiPaymentMethodGet = (): Promise<any> => request.get('/settings.pay.pay_way/getPayWay')

export const apiPaymentMethodSet = (params: Interface.PaymentMethodSet_Req): Promise<any> =>
    request.post('/settings.pay.pay_way/setPayWay', params)
/** E 支付方式 **/

/** S 支付配置 **/

// 支付配置
export const apiPaymentConfigSet = (params: Interface.PaymentConfig_Req): Promise<any> =>
    request.post('/settings.pay.pay_config/setConfig', params)

// 获取支付配置
export const apiPaymentConfigGet = (params: Interface.PaymentConfigGet_Req): Promise<any> =>
    request.get('/settings.pay.pay_config/getConfig', { params })

// 获取支付配置的列表
export const apiPaymentConfigGetList = (): Promise<any> => request.get('/settings.pay.pay_config/lists')

/** E 支付配置 **/
