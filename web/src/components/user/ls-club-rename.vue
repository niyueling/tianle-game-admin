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
                    <el-form-item label="俱乐部ID">
                        <div>{{ form.id }}</div>
                    </el-form-item>
                    <el-form-item label="俱乐部shortId">
                        <div>{{ form.club_id }}</div>
                    </el-form-item>
                    <el-form-item label="请输入俱乐部名称" prop="password">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.name"
                                placeholder="请输入俱乐部名称"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入俱乐部名称</div>
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
import { apiClubSetInfo } from '@/api/club/club'
@Component({
    components: {}
})
export default class LsUserChange extends Vue {
    @Prop() name?: string
    @Prop() clubId?: string
    @Prop() id?: string
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
        name: '',
        club_id: '', // 俱乐部ID
        id: ''
    }

    // 表单验证
    valueRules = {}

    @Watch('clubId', {
        immediate: true
    })
    getClubId(val: any) {
        // 初始值
        this.$set(this.form, 'club_id', val)
    }

    @Watch('id', {
        immediate: true
    })
    getId(val: any) {
        // 初始值
        this.$set(this.form, 'id', val)
    }

    @Watch('name', {
        immediate: true
    })
    getName(val: any) {
        // 初始值
        this.$set(this.form, 'name', val)
    }

    updateUserAdjustUserWallet() {
        if (!this.form.id || !this.form.name) {
            return this.$message.error('请输入基本信息')
        }
        apiClubSetInfo({
            club_id: this.form.id,
            field: 'name',
            value: this.form.name
        })
            .then((res: any) => {
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
        this.$set(this.form, 'name', '')
    }
}
</script>

<style scoped lang="scss"></style>
