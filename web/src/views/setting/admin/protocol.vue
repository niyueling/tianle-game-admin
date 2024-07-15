<template>
    <div class="setting-protocol">
        <el-form ref="form" :model="form" label-width="120px" size="small">
            <!-- 服务协议 -->
            <div class="ls-card">
                <div class="card-title">
                    <div>服务协议</div>
                    <div class="muted xs m-l-16">用户登录注册页面显示服务协议</div>
                </div>
                <div class="card-content m-t-24">
                    <el-form-item label="协议名称">
                        <el-input class="ls-input" v-model="form.service_agreement_name" show-word-limit />
                    </el-form-item>
                    <el-form-item label="协议内容">
                        <ls-editor v-model="form.service_agreement_content" />
                    </el-form-item>
                </div>
            </div>

            <!-- 隐私协议 -->
<!--            <div class="ls-card m-t-16">-->
<!--                <div class="card-title">-->
<!--                    <div>隐私协议</div>-->
<!--                    <div class="muted xs m-l-16">用户登录注册页面显示隐私政策</div>-->
<!--                </div>-->
<!--                <div class="card-content m-t-24">-->
<!--                    <el-form-item label="协议名称">-->
<!--                        <el-input class="ls-input" v-model="form.privacy_policy_name" show-word-limit />-->
<!--                    </el-form-item>-->
<!--                    <el-form-item label="协议内容">-->
<!--                        <ls-editor v-model="form.privacy_policy_content" />-->
<!--                    </el-form-item>-->
<!--                </div>-->
<!--            </div>-->
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
import { Vue, Component } from 'vue-property-decorator'
import LsEditor from '@/components/editor.vue'
import { apiAdminProtocolInfo, apiAdminProtocolEdit } from '@/api/setting/admin'

@Component({
    components: {
        LsEditor
    }
})
export default class SettingProtocol extends Vue {
    // 表单数据
    form: any = {
        service_agreement_name: '', // 服务协议名称
        service_agreement_content: '', // 服务协议内容
        privacy_policy_name: '', // 隐私政策名称
        privacy_policy_content: '' // 隐私政策内容
    }

    // 初始化表单数据
    initFormData() {
        apiAdminProtocolInfo()
            .then((res: any) => {
                this.form = res
            })
            .catch(() => {
                this.$message.error('数据加载失败，请刷新重载')
            })
    }

    // 重置表单数据
    onResetFrom() {
        this.initFormData()
        this.$message.info('已重置数据')
    }

    // 提交表单
    onSubmitFrom(formName: string) {
        const refs = this.$refs[formName] as HTMLFormElement

        refs.validate((valid: boolean) => {
            if (!valid) {
                return
            }
            const loading = this.$loading({ text: '保存中' })

            apiAdminProtocolEdit({
                ...this.form
            })
                .then(() => {
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
.setting-protocol {
    padding-bottom: 60px;
}

.ls-card {
    .ls-input {
        width: 280px;
    }

    .card-title {
        display: flex;
        align-items: flex-end;
        font-size: 14px;
        font-weight: 500;
    }
}
</style>
