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
                    <el-form-item label="钻石数量" prop="gemCount">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.gemCount"
                                placeholder="请输入钻石数量"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入钻石数量</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="金豆数量" prop="rubyAmount">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.rubyAmount"
                                placeholder="请输入金豆数量"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入金豆数量</div>
                        </div>
                    </el-form-item>
                </el-form>
            </div>

            <!-- 底部弹窗页脚 -->
            <div slot="footer" class="dialog-footer">
                <el-button size="small" @click="close">取消</el-button>
                <el-button size="small" @click="updateOrAddConversion" type="primary">确认</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator'
import { apiaddConversion, apiSetConversionInfo } from '@/api/marketing'
@Component({
    components: {}
})
export default class LsUserChange extends Vue {
    @Prop({
        default: ''
    })
    title!: string //弹窗标题
    @Prop({
        default: 'add'
    })
    action!: string //弹窗类型
    @Prop({
        default: {}
    })
    conversion!: object //编辑的信息
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
        gemCount: '',
        rubyAmount: '',
        _id: ''
    }

    // 表单验证
    valueRules = {}

    @Watch('conversion', {
        immediate: true
    })
    getConversion(val: any) {
        this.$set(this, 'form', val)
    }
    updateOrAddConversion() {
        if (!this.form.gemCount || !this.form.rubyAmount) {
            return this.$message.error('请输入基本信息')
        }

        if (this.action == 'add') {
            return this.addConversion(this.form)
        }

        return this.updateConversion(this.form)
    }

    addConversion(good: any) {
        apiaddConversion(good)
            .then((res: any) => {
                this.$emit('refresh')
                this.visible = false
            })
            .catch((res: any) => {
                this.visible = false
            })
    }

    updateConversion(category: any) {
        apiSetConversionInfo(category)
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
        // this.$set(this, 'form', {})
    }
}
</script>

<style scoped lang="scss"></style>
