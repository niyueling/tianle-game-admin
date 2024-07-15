<template>
    <div class="setting-shop">
        <div class="ls-card">
            <!-- 提示 -->
            <el-alert
                title="温馨提示：后台名称、后台Logo、默认图片等信息的配置。"
                type="info"
                :closable="false"
                show-icon
            />
        </div>

        <el-form ref="form" :model="form" :rules="rules" label-width="120px" size="small">
            <!-- 后台设置 -->
            <div class="ls-card m-t-16">
                <div class="card-title">后台设置</div>
                <div class="card-content m-t-24">
                    <el-form-item label="后台名称" prop="name">
                        <el-input class="ls-input" v-model="form.name" maxlength="12" show-word-limit/>
                    </el-form-item>
                    <el-form-item label="后台LOGO" prop="logo">
                        <material-select :limit="1" v-model="form.logo"/>
                        <div class="flex">
                            <div class="muted xs m-r-16">建议尺寸：200*200像素/240*60像素，支持jpg，jpeg，png格式</div>
                            <el-popover placement="right" width="500" trigger="hover">
                                <el-image :src="images.adminLogo"/>
                                <el-button slot="reference" type="text">查看示例</el-button>
                            </el-popover>
                        </div>
                    </el-form-item>
                    <el-form-item label="登录封面图" prop="admin_login_image">
                        <material-select :limit="1" v-model="form.admin_login_image"/>
                        <div class="flex">
                            <div class="muted xs m-r-16">建议尺寸：500*500像素，支持jpg，jpeg，png格式</div>
                            <el-popover placement="right" trigger="hover">
                                <img :src="images.adminLogin"/>
                                <el-button slot="reference" type="text">查看示例</el-button>
                            </el-popover>
                        </div>
                    </el-form-item>
                    <el-form-item label="登录安全">
                        <el-radio-group v-model="form.login_restrictions" class="m-r-16">
                            <el-radio :label="0">不限制</el-radio>
                            <el-radio :label="1">限制</el-radio>
                        </el-radio-group>
                        <div class="flex m-t-24" v-show="form.login_restrictions">
                            <div>
                                <span class="p-r-6">输错密码</span>
                                <el-input
                                    v-model="form.password_error_times"
                                    type="number"
                                    style="width: 138px"
                                    #append
                                >
                                    <span class="login_limit-unit">次</span>
                                </el-input>
                            </div>

                            <div class="m-l-20">
                                <span class="p-r-6">限制登录</span>
                                <el-input v-model="form.limit_login_time" type="number" style="width: 138px" #append>
                                    <span class="login_limit-unit">分钟</span>
                                </el-input>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="祈福/求签开关">
                        <el-radio-group v-model="form.open_cheer_qian" class="m-r-16">
                            <el-radio :label="1">正常</el-radio>
                            <el-radio :label="0">关闭</el-radio>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item label="充值开关">
                        <el-radio-group v-model="form.open_mnp_recharge" class="m-r-16">
                            <el-radio :label="1">正常</el-radio>
                            <el-radio :label="0">关闭</el-radio>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item label="限制充值版本号">
                        <el-input
                            v-model="form.mnp_recharge_version"
                            type="text"
                            style="width: 138px"
                            #append
                        >
                        </el-input>
                    </el-form-item>
                    <el-form-item label="苹果沙箱版本号">
                        <el-input
                            v-model="form.ios_sandbox_version"
                            type="text"
                            style="width: 138px"
                            #append
                        >
                        </el-input>
                    </el-form-item>
                    <el-form-item label="安全邮箱">
                        <el-radio-group v-model="form.open_mail_notice" class="m-r-16">
                            <el-radio :label="0">不限制</el-radio>
                            <el-radio :label="1">限制</el-radio>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item>
                        <div class="m-t-24">
                            <span class="p-r-6">邮箱1</span>
                            <el-input v-model="form.mail_1" type="text" style="width: 272px" #append></el-input>
                        </div>
                    </el-form-item>
                    <el-form-item>
                        <div class="m-t-24">
                            <span class="p-r-6">邮箱2</span>
                            <el-input v-model="form.mail_2" type="text" style="width: 272px" #append></el-input>
                        </div>
                    </el-form-item>
                    <el-form-item>
                        <div class="m-t-24">
                            <span class="p-r-6">邮箱3</span>
                            <el-input v-model="form.mail_3" type="text" style="width: 272px" #append></el-input>
                        </div>
                    </el-form-item>

                    <el-form-item label="网页登录设置">
                        <div class="m-t-24">
                            <span class="p-r-6">appid</span>
                            <el-input v-model="form.web_platform_appid" type="text" style="width: 272px" #append></el-input>
                        </div>
                        <div class="m-t-24">
                            <span class="p-r-6">secret</span>
                            <el-input v-model="form.web_platform_secret" type="text" style="width: 272px" #append></el-input>
                        </div>
                    </el-form-item>

                    <el-form-item label="虚拟支付设置">
                        <div class="m-t-24">
                            <span class="p-r-6">offerid</span>
                            <el-input v-model="form.wxgame_offerid" type="text" style="width: 272px" #append></el-input>
                        </div>
                        <div class="m-t-24">
                            <span class="p-r-6">zoneid</span>
                            <el-input v-model="form.wxgame_zoneid" type="text" style="width: 272px" #append></el-input>
                        </div>
                        <div class="m-t-24">
                            <span class="p-r-6">appkey</span>
                            <el-input v-model="form.wxgame_appkey" type="text" style="width: 272px" #append></el-input>
                        </div>
                    </el-form-item>
                </div>
            </div>
        </el-form>

        <!--  表单功能键  -->
        <div class="bg-white ls-fixed-footer">
            <div class="row-center flex" style="height: 100%">
                <el-button size="small" type="primary" @click="onSubmitFrom('form')">保存</el-button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import {Vue, Component} from 'vue-property-decorator'
import {apiSettingShopEdit, apiSettingShopInfo} from '@/api/setting/admin'
import materialSelect from '@/components/material-select/index.vue'

@Component({
    components: {
        materialSelect
    }
})
export default class SettingShop extends Vue {
    // 表单数据
    form = {
        name: '', // 商城名称
        logo: '', // 商城logo
        admin_login_image: '', // 管理后台登录页图片
        login_restrictions: 0, // 管理后台登录限制: 0-不限制 1-限制
        password_error_times: 0, // 限制密码错误次数
        limit_login_time: 0, // 限制禁止多少分钟不能登录
        open_mail_notice: 0, //是否开启安全邮箱设置
        mail_1: '', //安全邮箱1
        mail_2: '', //安全邮箱2
        mail_3: '', //安全邮箱3
        open_mnp_recharge: "",
        mnp_recharge_version: "",
        ios_sandbox_version: "",
        web_platform_appid: "",
        web_platform_secret: "",
        wxgame_offerid: "",
        wxgame_zoneid: "",
        wxgame_appkey: "",
        open_cheer_qian: ""
    }

    // 图片
    images = {
        storeLogin: require('@/assets/images/setting/img_shili_store_login.png'),
        storeClose: require('@/assets/images/setting/img_shili_store_close.png'),
        adminLogin: require('@/assets/images/setting/img_shili_admin_login.png'),
        adminLogo: require('@/assets/images/setting/admin_logo_example.png'),
        pcLogo: require('@/assets/images/setting/pc_logo_example.png')
    }

    // 表单验证
    rules: object = {
        name: [{required: true, message: '必填项不能为空', trigger: 'blur'}],
        logo: [{required: true, message: '必填项不能为空', trigger: 'blur'}],
        admin_login_image: [{required: true, message: '必填项不能为空', trigger: 'blur'}]
    }

    // 初始化表单数据
    initFormData() {
        apiSettingShopInfo()
            .then((res: any) => {
                this.form = res
            })
            .catch(() => {
                this.$message.error('数据加载失败，请刷新重载')
            })
    }

    // 提交表单
    onSubmitFrom(formName: string) {
        const refs = this.$refs[formName] as HTMLFormElement

        refs.validate((valid: boolean) => {
            if (!valid) {
                return this.$message.error('请完善信息')
            }
            const loading = this.$loading({text: '保存中'})

            apiSettingShopEdit({
                ...this.form
            })
                .then(() => {
                    this.$store.dispatch('getConfig')
                    this.$message.success('保存成功')
                })
                .catch(() => {
                    this.$message.error('保存失败')
                })
                .finally(() => {
                    loading.close()
                })
        })
    }

    created() {
        this.initFormData()
    }
}
</script>

<style lang="scss" scoped>
.setting-shop {
    padding-bottom: 60px;
}

.ls-card {
    .ls-input {
        width: 280px;
    }

    .card-title {
        font-size: 14px;
        font-weight: 500;
    }

    .login_limit-unit {
        display: inline-block;
        width: 2em;
        text-align: center;
    }
}
</style>
