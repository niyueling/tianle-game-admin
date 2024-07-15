<template>
    <div id="pay-method">
        <div class="m-t-24">
            <el-button type="primary" size="small" @click="onSetPayMethod">设置支付方式 </el-button>
        </div>

        <!-- 各平台支付 -->
        <div
            class="ls-card m-t-24"
            style="padding-bottom: 50px"
            v-for="(item, index) in paymentMethodData"
            v-loading="paymentMethodData.length == 0"
            :key="index"
        >
            <div class="lg m-b-24 card-title" v-if="index == 1">
                微信小程序<span class="xs muted m-l-10">在微信小程序中付款的场景</span>
            </div>
            <div class="lg m-b-24 card-title" v-if="index == 2">
                微信公众号<span class="xs muted m-l-10">在微信公众号H5页面中付款的场景，公众号类型一般为服务号</span>
            </div>
            <div class="lg m-b-24 card-title" v-if="index == 3">
                H5支付<span class="xs muted m-l-10">在浏览器H5页面中付款的场景</span>
            </div>
            <div class="lg m-b-24 card-title" v-if="index == 4">
                PC商城<span class="xs muted m-l-10">在PC商城页面中付款的场景</span>
            </div>
            <div class="lg m-b-24 card-title" v-if="index == 5">
                APP支付<span class="xs muted m-l-10">在APP中付款的场景</span>
            </div>
            <div class="lg m-b-24 card-title" v-if="index == 7">
                字节小程序<span class="xs muted m-l-10">在字节小程序中付款的场景</span>
            </div>

            <!-- 支付列表主体 -->
            <el-table :data="item" style="width: 100%" size="mini">
                <el-table-column prop="icon" label="图标" min-width="150">
                    <template slot-scope="scope">
                        <img :src="scope.row.icon" alt="图标" style="width: 34px; height: 34px" />
                    </template>
                </el-table-column>
                <el-table-column prop="pay_way_name" label="支付方式" min-width="150"> </el-table-column>
                <el-table-column prop="is_default" label="默认支付" min-width="150">
                    <template slot-scope="scope">
                        {{ scope.row.is_default == 1 ? '默认' : '-' }}
                    </template>
                </el-table-column>
                <el-table-column prop="status" label="开启状态" min-width="150">
                    <template slot-scope="scope">
                        {{ scope.row.status == 1 ? '开启' : '关闭' }}
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { apiPaymentMethodGet } from '@/api/setting/payment.ts'
import * as Interface from '@/api/setting/payment.d.ts'
@Component({
    components: {}
})
export default class PayMethod extends Vue {
    /** S Data **/
    paymentMethodData: Array<Object> = []
    /** E Data **/

    /** S Methods **/

    // 获取支付方式数据
    getPaymentMethodList() {
        apiPaymentMethodGet()
            .then((res: any) => {
                this.paymentMethodData = res
            })
            .catch(() => {
                this.$message.error('数据初始化失败，请刷新重载！')
            })
    }

    onSetPayMethod() {
        this.$router.push('/setting/payment/pay_method_edit')
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
