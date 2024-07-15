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
                    <el-form-item :label="'当前' + typeName">
                        <div>{{ form.value }}</div>
                    </el-form-item>
                    <el-form-item :label="typeName + '增减'">
                        <!-- 单选按钮 -->
                        <el-radio-group class="m-r-16" v-model="form.action">
                            <el-radio :label="1">增加{{ typeName }}</el-radio>
                            <el-radio :label="0">扣减{{ typeName }}</el-radio>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item :label="'调整' + typeName" prop="num">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.num"
                                placeholder="请输入调整的数量"
                                style="width: 300px"
                            >
                                <template slot="append">{{ typeName }}</template>
                            </el-input>
                            <div class="muted xs m-r-16">输入调整的数量</div>
                        </div>
                    </el-form-item>
                    <el-form-item :label="'调整后' + typeName">
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
import { apiUserRecharge } from '@/api/user/user'
@Component({
    components: {}
})
export default class LsUserChange extends Vue {
    @Prop() value?: number
    @Prop() type?: number // 变动类型：1-钻石；2-金币；3-红包，4-抽奖次数，5-代金券
    @Prop() userId?: string // 用户id
    @Prop({
        default: ''
    })
    title!: string //弹窗标题
    @Prop({
        default: 1
    })
    rank!: number //参数比例
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
        user_id: '', // 用户id
        type: 1, // 变动类型：1-钻石；2-金币；3-红包，4-抽奖次数，5-代金券
        action: 1, // 调整类型：0-减少；1-增加
        num: 0, // 调整数量
        remark: '', // 备注
        value: '', // 初始值
        rank: 1
    }
    typeName = '' // 变动类型名称

    // 表单验证
    valueRules = {}
    // 修改后的值
    get lastValue(): number {
        let total = this.value
        if (this.form.action == 1) {
            total = Number(this.form.value) + this.form.num * this.form.rank
        } else {
            total = Number(this.form.value) - this.form.num * this.form.rank
        }
        return total
    }
    /** E Data **/

    @Watch('userId', {
        immediate: true
    })
    getuserId(val: any) {
        // 初始值
        this.$set(this.form, 'user_id', val)
    }

    @Watch('value', {
        immediate: true
    })
    getValue(val: any) {
        // 初始值
        this.$set(this.form, 'value', val)
    }

    @Watch('rank', {
        immediate: true
    })
    getRank(val: any) {
        // 初始值
        this.$set(this.form, 'rank', val)
    }

    @Watch('type', {
        immediate: true
    })
    getType(val: any) {
        // 变动类型：1-钻石；2-金币， 3-红包
        if (val == 1) {
            this.typeName = '钻石'
        } else if (val == 2) {
            this.typeName = '金豆'
        } else if (val == 3) {
            this.typeName = '红包'
        } else if (val == 4) {
            this.typeName = '抽奖次数'
        } else if (val == 5) {
          this.typeName = '代金券'
        }

        this.$set(this.form, 'type', val)
    }

    // 调整用户钱包
    updateUserAdjustUserWallet() {
        const num = this.form.num * 1
        if (num <= 0) {
            return this.$message.error('请输入大于0的数字')
        }
        apiUserRecharge(this.form)
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
        this.$set(this.form, 'num', 0)
        this.$set(this.form, 'remark', '')
    }
}
</script>

<style scoped lang="scss"></style>
