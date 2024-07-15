<template>
    <div class="ls-dialog">
        <div class="ls-dialog__trigger" @click="onTrigger">
            <!-- 触发弹窗 -->
            <slot name="trigger"></slot>
        </div>
        <el-dialog
            coustom-class="ls-dialog__content"
            :visible="visible.val"
            :width="width"
            :top="top"
            :modal-append-to-body="true"
            :append-to-body="true"
            :before-close="close"
            :close-on-click-modal="clickModalClose"
        >
            <!-- 弹窗内容 -->
            <div v-if="title" slot="title" class="nr">
                {{ title }}
            </div>
            <div v-else slot="title" class="nr flex">
                <i class="el-icon-info title-icon"></i>
                温馨提示
            </div>

            <div v-if="!$slots.default" class="dialog-body normal p-l-30">
                {{ content }}
            </div>
            <!-- 自定义内容 -->
            <slot v-else></slot>
            <!-- 底部弹窗页脚 -->
            <div slot="footer" class="dialog-footer">
                <el-button size="small" v-if="cancelButtonText" @click="handleEvent('cancel')">{{
                    cancelButtonText
                }}</el-button>
                <el-button size="small" @click="handleEvent('confirm')" type="primary">{{
                    confirmButtonText
                }}</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script lang="ts">
import { Component, Prop, Provide, Vue } from 'vue-property-decorator'

@Component
export default class Dialog extends Vue {
    visible = {
        val: false
    }
    @Provide('visible')
    visibleVal = this.visible
    // props
    @Prop({ default: '' }) title!: string //弹窗标题
    @Prop({ default: '确认要删除？' }) content!: string //弹窗内容
    @Prop({ default: '确认' }) confirmButtonText!: string //确认按钮内容
    @Prop({ default: '取消' }) cancelButtonText!: string | boolean //取消按钮内容
    @Prop({ default: '400px' }) width!: string | number //弹窗的宽度
    @Prop({ default: '40vh' }) top!: string | number //弹窗的距离顶部位置
    @Prop({ default: false }) disabled!: boolean //是否禁用
    @Prop({ default: false }) async!: boolean //是否开启异步关闭
    @Prop({ default: true }) clickModalClose!: boolean // 点击遮罩层关闭对话窗口
    // data

    // methods
    handleEvent(type: 'cancel' | 'confirm') {
        this.$emit(type)
        if (!this.async || type === 'cancel') {
            this.close()
        }
    }

    onTrigger() {
        if (this.disabled) {
            return
        }
        this.open()
    }

    close() {
        this.visible.val = false
        this.$emit('close')
    }
    open() {
        this.visible.val = true
        this.$emit('open')
    }
}
</script>

<style scoped lang="scss">
.dialog-body {
    white-space: pre-line;
}
.title-icon {
    font-size: 24px;
    color: $--color-warning;
    margin-right: 5px;
}
</style>
