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
                    <el-form-item :label="'当前金币'">
                        <div>{{ form.value }}</div>
                    </el-form-item>
                    <el-form-item label="金币增减">
                        <!-- 单选按钮 -->
                        <el-radio-group class="m-r-16" v-model="form.action">
                            <el-radio :label="1">增加金币</el-radio>
                            <el-radio :label="0">扣减金币</el-radio>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item label="调整金币" prop="num">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.num"
                                placeholder="请输入调整的数量"
                                style="width: 300px"
                            >
                                <template slot="append">金币</template>
                            </el-input>
                            <div class="muted xs m-r-16">输入调整的数量</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="调整后金币">
                        <div>{{ lastValue }}</div>
                    </el-form-item>
                    <el-form-item label="备注">
                        <el-input
                            class="ls-input"
                            type="textarea"
                            :rows="3"
                            placeholder="请输入备注"
                            v-model="form.remark"
                            style="width: 300px"
                        >
                        </el-input>
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
import { apiClubRecharge } from '@/api/club/club'
@Component({
    components: {}
})
export default class LsUserChange extends Vue {
    @Prop() value?: number
    @Prop() userId?: number // 用户id
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
        _id: '', // 用户id
        action: 1, // 调整类型：0-减少；1-增加
        num: 0, // 调整数量
        remark: '', // 备注
        value: '' // 初始值
    }
    typeName = '' // 变动类型名称

    // 表单验证
    valueRules = {}
    // 修改后的值
    get lastValue(): number {
        let total = this.value
        if (this.form.action == 1) {
            total = Number(this.form.value) + this.form.num * 1
        } else {
            total = Number(this.form.value) - this.form.num * 1
        }
        return total
    }
    /** E Data **/

    @Watch('userId', {
        immediate: true
    })
    getuserId(val: any) {
        // 初始值
        //this.form.value = val
        this.$set(this.form, '_id', val)
    }

    @Watch('value', {
        immediate: true
    })
    getValue(val: any) {
        // 初始值
        //this.form.value = val
        this.$set(this.form, 'value', val)
    }

    /** S Methods **/
    // 调整用户钱包
    updateUserAdjustUserWallet() {
        const num = this.form.num * 1
        if (num <= 0) {
            return this.$message.error('请输入大于0的数字')
        }
        apiClubRecharge(this.form)
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
        this.$set(this.form, 'num', 0)
        this.$set(this.form, 'remark', '')
    }
    /** E Methods **/

    /** S Life Cycle **/
    /** E Life Cycle **/
}
</script>

<style scoped lang="scss"></style>
