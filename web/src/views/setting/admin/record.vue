<template>
    <div class="setting-record">
        <div class="ls-card">
            <!-- 提示 -->
            <el-alert
                title="温馨提示：应工信部的要求，请务必填写公安备案号和网站备案号，保存的备案信息，将展示在后台登录页面。"
                type="info"
                :closable="false"
                show-icon
            />
        </div>

        <el-form ref="form" :model="form" :rules="rules" label-width="120px" size="small">
            <!-- 备案信息 -->
            <div class="ls-card m-t-16">
                <div class="card-title">备案信息</div>
                <div class="card-content m-t-24">
                    <el-form-item label="版权信息">
                        <el-input class="ls-input" v-model="form.copyright" show-word-limit />
                        <div class="muted xs">例如填写，Copyright © 2019-2020 公司名称</div>
                    </el-form-item>
                    <el-form-item label="备案号">
                        <el-input class="ls-input" v-model="form.record_number" show-word-limit />
                    </el-form-item>
                    <el-form-item label="备案号链接" prop="record_system_link">
                        <el-input class="ls-input" v-model="form.record_system_link" show-word-limit />
                        <div class="muted xs">例如填写域名信息备案系统链接，http://beian.miit.gov.cn</div>
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
import { Vue, Component } from 'vue-property-decorator'
import { apiRecordInfo, apiRecordEdit } from '@/api/setting/admin'
import materialSelect from '@/components/material-select/index.vue'

@Component({
    components: {
        materialSelect
    }
})
export default class SettingRecord extends Vue {
    /** S Data **/
    // 表单数据
    form: any = {
        copyright: '', // 版权信息
        record_number: '', // 备案号
        record_system_link: '' // 备案号链接
    }

    // 表单验证
    rules: object = {
        record_system_link: [
            {
                pattern: /[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?/,
                message: '请输入合法链接',
                trigger: 'blur'
            }
        ]
    }

    // 初始化表单数据
    initFormData() {
        apiRecordInfo()
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

    // 提交表单
    onSubmitFrom(formName: string) {
        const refs = this.$refs[formName] as HTMLFormElement

        refs.validate((valid: boolean) => {
            if (!valid) {
                return
            }
            const loading = this.$loading({ text: '保存中' })
            const params = { ...this.form }

            apiRecordEdit({
                ...params
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
.setting-record {
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
}
</style>
