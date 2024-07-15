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
            @open="getRoleList"
            :modal-append-to-body="false"
            center
            :before-close="close"
            :close-on-click-modal="false"
            @close="close"
        >
            <!-- 弹窗主要内容-->
            <div class="">
                <el-form :rules="valueRules" ref="valueRef" :model="form" label-width="120px" size="small">
                    <el-form-item label="请输入角色" prop="role">
                        <el-select class="ls-select" v-model="form.role">
                            <el-option
                                v-for="(item, index) in roleList"
                                key="index"
                                :label="item.name"
                                :value="item.name"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="请输入账号" prop="username">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.username"
                                placeholder="请输入账号"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入账号</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="请输入密码" prop="pass">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.pass"
                                placeholder="请输入密码"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入密码</div>
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
import { Component, Prop, Vue } from 'vue-property-decorator'
import { apiAgencyAdd } from '@/api/agency/agency'
import { apiRoleList } from '@/api/app'
@Component({
    components: {}
})
export default class LsUserChange extends Vue {
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
        role: '', // 角色
        username: '',
        pass: '' // 密码
    }

    roleList = []

    // 表单验证
    valueRules = {}

    updateUserAdjustUserWallet() {
        if (!this.form.pass || !this.form.role || !this.form.username) {
            return this.$message.error('请输入基本信息')
        }
        apiAgencyAdd({
            role: this.form.role,
            username: this.form.username,
            pass: this.form.pass
        })
            .then((res: any) => {
                this.$emit('refresh')
                this.visible = false
            })
            .catch((res: any) => {
                this.visible = false
            })
    }

    getRoleList() {
        apiRoleList().then((res: any) => {
            console.log(res)
            this.roleList = res.lists
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
}
</script>

<style scoped lang="scss"></style>
