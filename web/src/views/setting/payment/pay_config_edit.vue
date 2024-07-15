<template>
    <div>
        <!-- 头部导航 -->
        <div class="ls-card">
            <el-page-header @back="$router.go(-1)" content="设置支付方式" />
        </div>

        <div class="ls-card m-t-24 m-b-60">
            <div class="nr m-b-30 card-title">支付信息</div>

            <el-form
                ref="paymentConfigData"
                :hide-required-asterisk="false"
                :rules="rules"
                class="m-l-24"
                :model="paymentConfigData"
                label-width="120px"
                size="small"
            >
                <el-form-item label="支付方式" label-position="right">
                    <el-input v-model="PayWay" :disabled="true" class="ls-input"></el-input>
                </el-form-item>

                <el-form-item label="显示名称" prop="name" label-position="right">
                    <el-input v-model="paymentConfigData.name" class="ls-input"></el-input>
                </el-form-item>

                <el-form-item label="显示图标" prop="icon" label-position="right">
                    <material-select :limit="1" :disabled="false" v-model="paymentConfigData.icon" />
                    <span class="desc">建议尺寸：152*42像素，支持jpg，jpeg，png格式</span>
                </el-form-item>

                <!-- S 微信 -->

                <el-form-item
                    v-if="paymentConfigData.pay_way == 2"
                    prop="interface_version"
                    label="微信支付接口版本"
                    label-position="right"
                >
                    <el-radio-group v-model="paymentConfigData.interface_version">
                        <el-radio :label="paymentConfigData.interface_version"></el-radio>
                    </el-radio-group>
                    <span class="desc">暂时只支持V2版本</span>
                </el-form-item>

                <el-form-item
                    v-if="paymentConfigData.pay_way == 2"
                    label="商户类型"
                    prop="merchant_type"
                    label-position="right"
                >
                    <el-radio-group v-model="paymentConfigData.merchant_type">
                        <el-radio :label="paymentConfigData.merchant_type">普通商户</el-radio>
                    </el-radio-group>
                    <span class="desc">暂时只支持普通商户类型，服务商户类型模式暂不支持</span>
                </el-form-item>

                <el-form-item
                    v-if="paymentConfigData.pay_way == 2"
                    label="微信支付商户号"
                    prop="mch_id"
                    label-position="right"
                >
                    <el-input v-model="paymentConfigData.mch_id" class="ls-input"></el-input>
                    <span class="desc">微信支付商户号（MCHID）</span>
                </el-form-item>

                <el-form-item
                    v-if="paymentConfigData.pay_way == 2"
                    label="商户API密钥"
                    prop="pay_sign_key"
                    label-position="right"
                >
                    <el-input v-model="paymentConfigData.pay_sign_key" class="ls-input"></el-input>
                    <span class="desc">微信支付商户API密钥（paySignKey）</span>
                </el-form-item>

                <el-form-item
                    v-if="paymentConfigData.pay_way == 2"
                    label="微信支付证书"
                    prop="apiclient_cert"
                    label-position="right"
                >
                    <el-input
                        type="textarea"
                        v-model="paymentConfigData.apiclient_cert"
                        :rows="4"
                        class="ls-input"
                    ></el-input>
                    <span class="desc"> 微信支付证书（apiclient_cert.pem），前往微信商家平台生成并黏贴至此处 </span>
                </el-form-item>

                <el-form-item
                    v-if="paymentConfigData.pay_way == 2"
                    label="微信支付证书密钥"
                    prop="apiclient_key"
                    label-position="right"
                >
                    <el-input
                        type="textarea"
                        v-model="paymentConfigData.apiclient_key"
                        class="ls-input"
                        :rows="4"
                    ></el-input>
                    <span class="desc"> 微信支付证书密钥（apiclient_key.pem），前往微信商家平台生成并黏贴至此处 </span>
                </el-form-item>

                <el-form-item v-if="paymentConfigData.pay_way == 2" label="支付授权目录" label-position="right">
                    <div>
                        {{ paymentConfigData.domain }}
                        <span class="copy" @click="onCopy">复制</span>
                    </div>
                    <span class="desc">支付授权目录仅用于参考，复制后前往微信商家平台填写</span>
                </el-form-item>
                <!-- E 微信 -->

                <!-- S 支付宝 -->

                <el-form-item v-if="paymentConfigData.pay_way == 3" label="模式" prop="pattern" label-position="right">
                    <el-radio-group v-model="paymentConfigData.pattern">
                        <el-radio :label="paymentConfigData.pattern">普通模式</el-radio>
                    </el-radio-group>
                    <span class="desc">暂时仅支持支付宝普通模式</span>
                </el-form-item>

                <el-form-item
                    v-if="paymentConfigData.pay_way == 3"
                    label="商户类型"
                    prop="merchant_type"
                    label-position="right"
                >
                    <el-radio-group v-model="paymentConfigData.merchant_type">
                        <el-radio :label="paymentConfigData.merchant_type">普通商户</el-radio>
                    </el-radio-group>
                    <span class="desc">暂时只支持普通商户类型，服务商户类型模式暂不支持</span>
                </el-form-item>

                <el-form-item v-if="paymentConfigData.pay_way == 3" label="应用ID" prop="app_id" label-position="right">
                    <el-input v-model="paymentConfigData.app_id" class="ls-input"></el-input>
                    <span class="desc">支付宝应用APP_ID</span>
                </el-form-item>

                <el-form-item
                    v-if="paymentConfigData.pay_way == 3"
                    label="应用私钥"
                    prop="private_key"
                    label-position="right"
                >
                    <el-input v-model="paymentConfigData.private_key" class="ls-input"></el-input>
                    <span class="desc">应用私钥（private_key）</span>
                </el-form-item>

                <el-form-item
                    v-if="paymentConfigData.pay_way == 3"
                    label="支付宝公钥"
                    prop="ali_public_key"
                    label-position="right"
                >
                    <el-input v-model="paymentConfigData.ali_public_key" class="ls-input"></el-input>
                    <span class="desc">支付宝公钥（ali_public_key）</span>
                </el-form-item>

                <!-- E 支付宝 -->

                <el-form-item label="排序" prop="sort" label-position="right">
                    <el-input v-model.number="paymentConfigData.sort" class="ls-input"></el-input>
                    <span class="desc">排序值越小，排序越前</span>
                </el-form-item>
            </el-form>
        </div>

        <!-- 底部保存或取消 -->
        <div class="bg-white ls-fixed-footer">
            <div class="row-center flex m-t-15">
                <el-button size="small" @click="$router.go(-1)">取消</el-button>
                <el-button size="small" type="primary" @click="onSubmit('paymentConfigData')"> 保存 </el-button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { apiPaymentConfigSet, apiPaymentConfigGet } from '@/api/setting/payment'
import { PaymentConfig_Req, PaymentConfigGet_Req } from '@/api/setting/payment.d'
import MaterialSelect from '@/components/material-select/index.vue'
@Component({
    components: {
        MaterialSelect
    }
})
export default class PayMethodEdit extends Vue {
    /** S Data **/

    identity: Number = 0

    status: any = ''

    // 支付配置设置的数据
    paymentConfigData: PaymentConfig_Req = {
        name: '', //支付名称
        icon: '', //支付图标
        sort: '', //排序
        remark: '', //备注
        merchant_type: '', //（微信支付 ｜｜ 支付宝）商户类型ordinary_merchant-普通商户
        interface_version: 'v2', //微信支付接口版本v2-v2
        mch_id: '', //微信支付商户号
        pay_sign_key: '', //微信商户支付API密钥
        apiclient_cert: '', //微信支付证书
        apiclient_key: '', //微信支付证书密钥
        pattern: '', //模式：normal_mode普通商户
        app_id: '', //应用ID
        private_key: '', //支付宝公钥
        ali_public_key: '' //应用私钥
    }

    // 表单验证
    rules: any = {
        name: [{ required: true, message: '请输入显示名称', trigger: 'blur' }],
        icon: [{ required: true, message: '请输入上传图标', trigger: 'change' }],
        mch_id: [
            {
                required: true,
                message: '请输入微信支付商户号',
                trigger: 'blur'
            }
        ],
        pay_sign_key: [
            {
                required: true,
                message: '请输入微信商户支付API密钥',
                trigger: 'blur'
            }
        ],
        apiclient_cert: [{ required: true, message: '请输入微信支付证书', trigger: 'blur' }],
        apiclient_key: [
            {
                required: true,
                message: '请输入微信支付证书密钥',
                trigger: 'blur'
            }
        ],
        private_key: [{ required: true, message: '请输入支付宝公钥', trigger: 'blur' }],
        ali_public_key: [{ required: true, message: '请输入应用私钥', trigger: 'blur' }],
        app_id: [{ required: true, message: '请输入应用ID', trigger: 'blur' }],
        sort: [
            { required: true, message: '请输入排序', trigger: 'blur' },
            {
                type: 'number',
                pattern: !/-|\+|(\.[0-9])/,
                message: '请输入正确的排序',
                trigger: 'blur'
            }
        ]
    }

    /** E Data **/

    /** S Methods **/

    // 获取支付方式数据
    getPaymentConfigDetail() {
        apiPaymentConfigGet({
            id: this.identity
        })
            .then((res: any) => {
                // 解构出结果的
                const result = {
                    ...res.config,
                    ...res
                }

                delete result.config

                if (result.pay_way == 2) {
                    result.interface_version = 'v2'
                }
                if (result.pay_way == 3) {
                    result.mode = 'normal_mode'
                }
                result.merchant_type = 'ordinary_merchant'

                this.paymentConfigData = result
            })
            .catch(() => {
                this.$message.error('数据初始化失败，请刷新重载！')
            })
    }

    // 点击表单提交
    onSubmit(formName: string) {
        // 验证表单格式是否正确
        const refs = this.$refs[formName] as HTMLFormElement
        refs.validate((valid: boolean): any => {
            if (!valid) {
                return
            }
            if ((this.paymentConfigData as any).sort <= 0) {
                return this.$message.error('请输入正整数')
            }
            this.handlePayConfigEdit()
        })
    }

    // 编辑支付配置
    handlePayConfigEdit() {
        const params = this.paymentConfigData
        const id: number = this.identity as number
        apiPaymentConfigSet({ ...params, id } as PaymentConfig_Req)
            .then(() => {
                setTimeout(() => this.$router.go(-1), 500)
            })
            .catch(() => {
                this.$message.error('保存失败!')
            })
    }

    // 复制域名
    onCopy() {
        const createInput = document.createElement('input')
        createInput.value = 'http://xxx.com/pay/'
        document.body.appendChild(createInput)
        createInput.select() // 选择对象
        document.execCommand('Copy') // 执行浏览器复制命令
        createInput.style.display = 'none'
        this.$message({ message: '复制成功', type: 'success' })
    }

    /** E Methods **/

    /** S Life Cycle **/
    created() {
        const query: any = this.$route.query
        this.identity = query.id
        this.getPaymentConfigDetail()
    }
    /** E Life Cycle **/

    /** S Compute Attr **/

    get PayWay() {
        const identity: Number = Number(this.paymentConfigData.pay_way)
        switch (identity) {
            case 1:
                return '余额支付'
            case 2:
                return '微信支付'
            case 3:
                return '支付宝支付'
            case 4:
                return '字节支付'
        }
    }

    /** E Compute Attr **/
}
</script>

<style lang="scss" scoped>
.ls-input {
    width: 280px;
}

.desc {
    color: #999999;
    display: block;
    height: 20px;
    line-height: 30px;
}

.copy {
    color: #4073fa;
    margin-left: 16px;
    cursor: pointer;
}
.card-title {
    font-size: 14px;
    font-weight: 500;
}
</style>
