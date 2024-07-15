<template>
    <div>
        <!-- 头部导航 -->
        <div class="ls-card">
            <el-page-header @back="$router.go(-1)" content="设置支付方式" />
        </div>

        <!-- H5 支付 -->
        <div class="m-b-60">
            <div
                class="ls-card m-t-24"
                style="padding-bottom: 50px"
                v-for="(item, index) in paymentMethodData"
                :key="index"
            >
                <div class="lg m-b-24 card-title" v-if="index == 1">
                    微信小程序
                    <span class="xs muted m-l-10">在微信小程序中付款的场景</span>
                </div>
                <div class="lg m-b-24 card-title" v-if="index == 2">
                    微信公众号
                    <span class="xs muted m-l-10">在微信公众号H5页面中付款的场景，公众号类型一般为服务号</span>
                </div>
                <div class="lg m-b-24 card-title" v-if="index == 3">
                    H5支付
                    <span class="xs muted m-l-10">在浏览器H5页面中付款的场景</span>
                </div>
                <div class="lg m-b-24 card-title" v-if="index == 4">
                    PC商城
                    <span class="xs muted m-l-10">在PC商城页面中付款的场景</span>
                </div>
                <div class="lg m-b-24 card-title" v-if="index == 5">
                    APP支付
                    <span class="xs muted m-l-10">在APP中付款的场景</span>
                </div>
                <div class="lg m-b-24 card-title" v-if="index == 7">
                    字节小程序
                    <span class="xs muted m-l-10">在字节小程序中付款的场景</span>
                </div>
                <!-- 支付列表主体 -->
                <el-table :data="item" style="width: 100%" size="mini">
                    <el-table-column prop="icon" label="图标" width="150">
                        <template slot-scope="scope">
                            <!-- {{scope.row}} -->
                            <img :src="scope.row.icon" alt style="width: 34px; height: 34px" />
                        </template>
                    </el-table-column>
                    <el-table-column prop="pay_way_name" label="支付方式" min-width="150"></el-table-column>
                    <el-table-column label="默认支付" min-width="150">
                        <template slot-scope="scope">
                            <el-radio
                                v-model="currentDefault[index]"
                                :label="scope.row.id"
                                @change="changeRadioPaymentSet(scope.$index, index)"
                                >设为默认</el-radio
                            >
                        </template>
                    </el-table-column>
                    <el-table-column prop="status" label="开启状态" min-width="150">
                        <template slot-scope="scope">
                            <el-switch
                                v-model="scope.row.status"
                                :active-value="1"
                                :inactive-value="0"
                                :active-color="styleConfig.primary"
                                inactive-color="#f4f4f5"
                                @change="changeStatusPaymentSet(scope.$index, index)"
                            />
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>

        <!-- 底部保存或取消 -->
        <div class="bg-white ls-fixed-footer">
            <div class="row-center flex m-t-15">
                <!-- <el-button size="small" @click="onReset()">重置</el-button> -->
                <el-button size="small" type="primary" @click="onSubmit(paymentMethodData)">保存</el-button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { apiPaymentMethodGet, apiPaymentMethodSet } from '@/api/setting/payment'
import { PaymentMethodSet_Req } from '@/api/setting/payment.d'
@Component({
    components: {}
})
export default class PayMethodEdit extends Vue {
    /** S Data **/

    // 支付方式的数据
    paymentMethodData: any = []

    // 支付方式的默认支付单选
    currentDefault: any = []

    /** E Data **/

    /** S Methods **/

    // 获取支付方式数据
    getPaymentMethodList() {
        apiPaymentMethodGet()
            .then((res: any) => {
                this.currentDefault = this.basePaymentMethodList(res)
                this.paymentMethodData = res
            })
            .catch(() => {
                this.$message.error('数据初始化失败，请刷新重载！')
            })
    }

    // 初始化支付方式的默认支付单选
    basePaymentMethodList(data: any): Array<any> {
        const array: any = []
        Object.keys(data).map((res, i) => {
            data[res].forEach((el: any, index: any): any => {
                if (el.is_default == 1) {
                    array[res] = el.id
                }
            })
        })
        return array
    }

    changeStatusPaymentSet(cIndex: number, index: number) {
        this.paymentMethodData[index][cIndex].status = this.paymentMethodData[index][cIndex].status
    }

    changeRadioPaymentSet(cIndex: number, index: number) {
        this.paymentMethodData[index].forEach((item: any, i: number) => {
            this.paymentMethodData[index][i].is_default = 0
        })
        this.paymentMethodData[index][cIndex].is_default = 1
    }

    // 设置支付方式
    onSubmit(params: PaymentMethodSet_Req) {
        apiPaymentMethodSet(params).then(() => {
            // this.$message.success('状态修改成功')
        })
    }

    /** E Methods **/

    /** S Life Cycle **/
    created() {
        this.getPaymentMethodList()
    }
    /** E Life Cycle **/
}
</script>

<style lang="scss" scoped>
.card-title {
    font-size: 14px;
    font-weight: 500;
}
</style>
