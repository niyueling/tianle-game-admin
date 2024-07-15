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
                    <el-form-item label="城市" prop="city">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.city"
                                placeholder="请输入城市"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入城市名称</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="县区" prop="county">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.county"
                                placeholder="请输入县区"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入县区名称</div>
                        </div>
                    </el-form-item>
                </el-form>
            </div>

            <!-- 底部弹窗页脚 -->
            <div slot="footer" class="dialog-footer">
                <el-button size="small" @click="close">取消</el-button>
                <el-button size="small" @click="updateOrAddRegion" type="primary">确认</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator'
import { apiaddRegion, apiSetRegionInfo } from '@/api/marketing'
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
    region!: object //编辑的商品信息
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
        city: '',
        county: ''
    }

    // 表单验证
    valueRules = {}

    @Watch('region', {
        immediate: true
    })
    getRegion(val: any) {
        this.$set(this, 'form', val)
        //
    }
    updateOrAddRegion() {
        console.log(this.form)
        if (!this.form.city || !this.form.county) {
            return this.$message.error('请输入基本信息')
        }

        if (this.action == 'add') {
            return this.addRegion(this.form)
        }

        return this.updateRegion(this.form)
    }

    addRegion(good: any) {
        apiaddRegion(good)
            .then((res: any) => {
                this.$emit('refresh')
                this.visible = false
            })
            .catch((res: any) => {
                this.visible = false
            })
    }

    updateRegion(good: any) {
        apiSetRegionInfo(good)
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
