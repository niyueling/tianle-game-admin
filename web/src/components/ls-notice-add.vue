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
                    <el-form-item label="公告内容" prop="city">
                        <div class="">
                            <textarea
                                class="ls-input"
                                v-model="form.message"
                                placeholder="请输入公告内容"
                                style="width: 350px"
                                rows="15"
                            >
                            </textarea>
                            <div class="muted xs m-r-16">请输入公告内容</div>
                        </div>
                    </el-form-item>
                </el-form>
            </div>

            <!-- 底部弹窗页脚 -->
            <div slot="footer" class="dialog-footer">
                <el-button size="small" @click="close">取消</el-button>
                <el-button size="small" @click="updateOrAddNotice" type="primary">确认</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator'
import { apiNoticeAdd, apiNoticeEdit } from '@/api/content/notice'
@Component({
    components: {}
})
export default class LsUserChange extends Vue {
    @Prop({
        default: ''
    })
    title!: string
    @Prop({
        default: 'add'
    })
    action!: string
    @Prop({
        default: {}
    })
    notice!: object
    @Prop({
        default: '660px'
    })
    width!: string | number //弹窗的宽度
    @Prop({
        default: '20vh'
    })
    top!: string | number //弹窗的距离顶部位置
    visible = false
    $refs!: {
        valueRef: any
    }
    form = {
        message: '',
        _id: ''
    }

    // 表单验证
    valueRules = {}

    @Watch('notice', {
        immediate: true
    })
    getNotice(val: any) {
        this.$set(this, 'form', val)
    }
    updateOrAddNotice() {
        if (!this.form.message) {
            return this.$message.error('请输入公告内容')
        }

        if (this.action == 'add') {
            return this.addNotice(this.form)
        }

        return this.updateNotice(this.form)
    }

    addNotice(notice: any) {
        apiNoticeAdd(notice)
            .then((res: any) => {
                this.$emit('refresh')
                this.visible = false
            })
            .catch((res: any) => {
                this.visible = false
            })
    }

    updateNotice(notice: any) {
        apiNoticeEdit(notice)
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
        this.$set(this, 'form', {})
    }
}
</script>

<style scoped lang="scss"></style>
