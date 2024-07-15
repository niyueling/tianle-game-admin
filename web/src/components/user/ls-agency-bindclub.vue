<!-- 用户详情·钱包调整 -->
<template>
    <div>
        <div class="ls-dialog__trigger" @click="onTrigger">
            <!-- 触发弹窗 -->
            <slot name="trigger"></slot>
        </div>
        <el-dialog
            coustom-class="ls-dialog__content"
            :title="title"
            :visible="visible"
            :width="width"
            :top="top"
            :modal-append-to-body="false"
            center
            :before-close="close"
            :close-on-click-modal="false"
            @close="close"
        >
            <!-- 弹窗主要内容-->
            <div class="">
                <el-form :rules="valueRules" ref="valueRef" :model="form" label-width="120px" size="small">
                    <el-form-item label="代理ID">
                        <div>{{ form.user_id }}</div>
                    </el-form-item>
                    <el-form-item label="代理名称">
                        <div>{{ form.username }}</div>
                    </el-form-item>
                    <el-form-item label="手机号" v-if="form.phone">
                        <div>{{ form.phone.substr(0, 3) + '****' + form.phone.substr(7, 11) }}</div>
                    </el-form-item>
                    <el-form-item label="请输入俱乐部ID" prop="password">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.club_id"
                                placeholder="请输入俱乐部ID"
                                style="width: 300px"
                                @input="searchClubPhone"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入俱乐部ID</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="获取验证码" prop="code" v-if="form.phone">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.code"
                                placeholder="请输入验证码"
                                style="width: 300px"
                            >
                                <div slot="append" @click="sendSMS" style="color: #101010">
                                    {{ form.code_btn_text }}
                                </div>
                            </el-input>
                        </div>
                    </el-form-item>
                </el-form>
            </div>

            <!-- 底部弹窗页脚 -->
            <div slot="footer" class="dialog-footer">
                <el-button size="small" @click="close">取消</el-button>
                <el-button size="small" @click="updateUserAdjustUserWallet" type="primary">确认</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator'
import { apiAgencySetInfo, apiClubInfo, apiCaptcha } from '@/api/agency/agency'
@Component({
    components: {}
})
export default class LsUserChange extends Vue {
    @Prop() userName?: string
    @Prop() userId?: string // 用户id
    @Prop({
        default: ''
    })
    title!: string //弹窗标题
    @Prop({
        default: '660px'
    })
    width!: string | number //弹窗的宽度
    @Prop({
        default: '20vh'
    })
    top!: string | number //弹窗的距离顶部位置
    /** S Data **/
    visible = false
    $refs!: {
        valueRef: any
    }
    form = {
        user_id: '', // 用户id
        username: '',
        club_id: '', // 俱乐部ID
        code: '',
        phone: '',
        disabled: false,
        times: 60,
        code_btn_text: '获取验证码'
    }

    // 表单验证
    valueRules = {}

    @Watch('userId', {
        immediate: true
    })
    getuserId(val: any) {
        // 初始值
        this.$set(this.form, 'user_id', val)
    }

    @Watch('userName', {
        immediate: true
    })
    getValue(val: any) {
        // 初始值
        this.$set(this.form, 'username', val)
    }

    sendSMS() {
        let _this = this
        if (!this.form.disabled) {
            this.form.disabled = true

            //获取验证码
            apiCaptcha({
                phone: this.form.phone
            })
                .then((res: any) => {
                    const timer = setInterval(function () {
                        _this.form.code_btn_text = _this.form.times + 's后重新获取'
                        --_this.form.times

                        if (_this.form.times == 0) {
                            _this.form.code_btn_text = '获取验证码'
                            _this.form.times = 60
                            _this.form.disabled = false
                            clearInterval(timer)
                        }
                    }, 1000)
                })
                .catch((res: any) => {
                    _this.form.disabled = false
                })
        }
    }

    searchClubPhone() {
        apiClubInfo({
            shortId: this.form.club_id
        }).then((res: any) => {
            console.log(res)
            this.form.phone = res.player.phone
            console.log(this.form.phone)
        })
    }

    updateUserAdjustUserWallet() {
        if (!this.form.club_id || !this.form.code) {
            return this.$message.error('请输入基本信息')
        }
        apiAgencySetInfo({
            user_id: this.form.user_id,
            field: 'club_id',
            value: this.form.club_id,
            code: this.form.code,
            phone: this.form.phone
        })
            .then((res: any) => {
                // 重新获取用户详情
                this.$emit('refresh')
                this.visible = false
            })
            .catch((res: any) => {
                this.visible = false
            })
    }

    onTrigger() {
        this.visible = true
    }

    // 关闭弹窗
    close() {
        this.visible = false

        // 重制表单内容
        this.$set(this.form, 'club_id', '')
    }
    /** E Methods **/

    /** S Life Cycle **/
    /** E Life Cycle **/
}
</script>

<style scoped lang="scss"></style>
