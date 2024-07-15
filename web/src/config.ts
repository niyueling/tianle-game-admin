// 开发环境域名

const host_development = 'http://admin.hfdsdas.cn/'

export default {
    // 版本
    version: '2.0.3',
    baseURL: process.env.NODE_ENV == 'production' ? '' : host_development,
    tencentMapKey: 'FWEBZ-WHSHV-IRFPO-UNMRL-5EUWV-BFBFW'
}
