<template>
    <div class="sms_edit p-b-60">
        <div class="ls-card">
            <el-page-header @back="$router.go(-1)" content="短信设置" />
        </div>

        <div class="ls-card m-t-24">
            <el-form ref="form" label-width="135px">
                <el-form-item label="短信渠道">
                    {{ detail.name }}
                </el-form-item>

                <el-form-item label="开启状态" required>
                    <el-radio v-model="detail.status" :label="typeof detail.status == 'string' ? '0' : 0"
                        >关闭</el-radio
                    >
                    <el-radio v-model="detail.status" :label="typeof detail.status == 'string' ? '1' : 1"
                        >开启</el-radio
                    >
                </el-form-item>

                <el-form-item label="短信签名" size="mini" required>
                    <el-input v-model="detail.sign"></el-input>
                </el-form-item>

                <el-form-item v-if="type == 'tencent'" label="APP_ID" size="mini" required>
                    <el-input v-model="detail.app_id"></el-input>
                </el-form-item>

                <el-form-item label="APP_KEY" v-if="type == 'ali'" size="mini" required>
                    <el-input v-model="detail.app_key"></el-input>
                </el-form-item>

                <el-form-item v-if="type == 'tencent'" label="SECRET_ID" size="mini" required>
                    <el-input v-model="detail.secret_id"></el-input>
                </el-form-item>

                <el-form-item label="SECRET_KEY" size="mini" required>
                    <el-input v-model="detail.secret_key"></el-input>
                </el-form-item>
            </el-form>
        </div>

        <!-- 底部保存或取消 -->
        <div class="bg-white flex row-center ls-fixed-footer">
            <div class="row-center flex">
                <el-button size="small" @click="$router.go(-1)">取消</el-button>
                <el-button size="small" type="primary" @click="onSubmit('form')">保存</el-button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { apiSmsGetConfigDetail, apiSmsSet } from '@/api/content/sms'
@Component
export default class SmsEdit extends Vue {
    type: any = 1
    detail: any = {
        name: '',
        app_key: '',
        status: 1,
        sign: '',
        secret_id: '',
        app_id: '',
        secret_key: ''
    }

    // 提交保存
    onSubmit() {
        apiSmsSet({ ...this.detail, type: this.type })
            .then(res => {
                this.$router.go(-1)
                this.$message.success('保存成功!')
            })
            .catch(() => {
                this.$message.error('数据请求失败，刷新重载!')
            })
    }

    // 获取详情
    getNoticeDetail() {
        apiSmsGetConfigDetail({ type: this.type })
            .then(res => {
                this.detail = res
            })
            .catch(() => {
                this.$message.error('数据请求失败，刷新重载!')
            })
    }

    created() {
        this.type = this.$route.query.id
        this.type && this.getNoticeDetail()
    }
}
</script>

<style lang="scss" scoped></style>
