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
                    <el-form-item label="充值类别">
                        <el-select class="ls-select" v-model="form.goodsType" placeholder="全部">
                            <el-option label="钻石" :value="0"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="充值数量" prop="amount">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.amount"
                                placeholder="请输入充值数量"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入充值数量</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="安卓价格" prop="androidPrice">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.androidPrice"
                                placeholder="请输入安卓价格"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入安卓价格</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="苹果价格">
                        <el-select class="ls-select" v-model="form.applePriceId" placeholder="全部">
                            <el-option label="1000元" value="tianle.majiang.pay_7"></el-option>
                            <el-option label="3000元" value="tianle.majiang.pay_8"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="购买赠送" prop="extraAmount">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.extraAmount"
                                placeholder="请输入购买赠送数量"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入购买赠送数量</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="级别">
                        <el-select class="ls-select" v-model="form.level" placeholder="全部">
                            <el-option label="无级别" :value="0"></el-option>
                            <el-option label="1级代理" :value="1"></el-option>
                            <el-option label="2级代理" :value="2"></el-option>
                            <el-option label="3级代理" :value="3"></el-option>
                            <el-option label="金牌代理" :value="4"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="状态">
                        <el-select class="ls-select" v-model="form.isOnline" placeholder="全部">
                            <el-option label="上架" :value="1"></el-option>
                            <el-option label="下架" :value="2"></el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
            </div>

            <!-- 底部弹窗页脚 -->
            <div slot="footer" class="dialog-footer">
                <el-button size="small" @click="close">取消</el-button>
                <el-button size="small" @click="updateOrAddRecharge" type="primary">确认</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator'
import { apiaddRecharge, apiSetRechargeInfo } from '@/api/marketing'
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
    recharge!: object //编辑的商品信息
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
        goodsType: '',
        amount: '',
        androidPrice: '',
        applePriceId: '',
        extraAmount: '',
        isOnline: '',
        level: ''
    }

    // 表单验证
    valueRules = {}

    @Watch('recharge', {
        immediate: true
    })
    getRecharge(val: any) {
        this.$set(this, 'form', val)
    }
    updateOrAddRecharge() {
        if (!this.form.amount || !this.form.androidPrice || !this.form.applePriceId || !this.form.isOnline) {
            return this.$message.error('请输入基本信息')
        }

        if (this.action == 'add') {
            return this.addRecharge(this.form)
        }

        return this.updateRecharge(this.form)
    }

    addRecharge(good: any) {
        apiaddRecharge(good)
            .then((res: any) => {
                this.$emit('refresh')
                this.visible = false
            })
            .catch((res: any) => {
                this.visible = false
            })
    }

    updateRecharge(good: any) {
        apiSetRechargeInfo(good)
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
