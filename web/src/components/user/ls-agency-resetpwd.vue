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
                    <el-form-item label="请输入新密码" prop="password">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.password"
                                placeholder="请输入新密码"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入新密码</div>
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
import { apiAgencySetInfo } from '@/api/agency/agency'
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
        password: '' // 新密码
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

    /** S Methods **/
    // 调整用户钱包
    updateUserAdjustUserWallet() {
        if (!this.form.password) {
            return this.$message.error('请输入新密码')
        }
        apiAgencySetInfo({
            user_id: this.form.user_id,
            field: 'pass',
            value: this.form.password
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
        this.$set(this.form, 'password', '')
    }
    /** E Methods **/

    /** S Life Cycle **/
    /** E Life Cycle **/
}
</script>

<style scoped lang="scss"></style>
