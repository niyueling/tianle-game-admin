<template>
    <div class="setting-protocol">
        <el-form ref="form" :model="form" label-width="120px" size="small">
            <!-- 服务协议 -->
            <div class="ls-card">
                <div class="card-title">
                    <div>自助充值方案</div>
                    <div class="muted xs m-l-16">
                        代理可以进行自助充值，本方案为自助充值方案介绍，使用自助充值可以享受更大的折扣力度。
                    </div>
                </div>
                <div class="card-content m-t-24">
                    <el-form-item label="名称">
                        <el-input class="ls-input" v-model="form.recharge_agreement_name" show-word-limit disabled />
                    </el-form-item>
                    <el-form-item label="内容">
                        <ls-editor v-model="form.recharge_agreement_content" />
                    </el-form-item>
                </div>
            </div>
        </el-form>
    </div>
</template>

<script lang="ts">
import { Vue, Component } from 'vue-property-decorator'
import LsEditor from '@/components/editor.vue'
import { apiProtocolInfo, apiProtocolEdit } from '../../../api/setting/admin'

@Component({
    components: {
        LsEditor
    }
})
export default class SettingProtocol extends Vue {
    /** S Data **/
    // 表单数据
    form: any = {
        recharge_agreement_name: '', // 服务协议名称
        recharge_agreement_content: '' // 服务协议内容
    }
    /** E Data **/

    /** S Methods **/
    // 初始化表单数据
    initFormData() {
        apiProtocolInfo()
            .then(res => {
                // 表单同步于接口响应数据
                for (const key in res) {
                    if (!this.form.hasOwnProperty(key)) {
                        continue
                    }
                    this.form[key] = res[key]
                }
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

            apiProtocolEdit({
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
    /** E Methods **/

    /** S Life Cycle **/
    created() {
        this.initFormData()
    }
    /** E Life Cycle **/
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
