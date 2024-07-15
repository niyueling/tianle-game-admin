<template>
    <div>
        <div class="ls-card">
            <!-- 提示 -->
            <el-alert title="温馨提示：设置系统支持的支付方式" type="info" show-icon :closable="false" />
        </div>

        <div class="ls-card m-t-24">
            <!-- 支付列表主体 -->
            <el-table
                :data="paymentConfigData"
                v-loading="paymentConfigData.length == 0"
                style="width: 100%"
                size="mini"
            >
                <el-table-column prop="pay_way_name" label="支付方式" min-width="150"> </el-table-column>
                <el-table-column prop="icon" label="图标" min-width="150">
                    <template slot-scope="scope">
                        <img :src="scope.row.icon" alt="图标" style="width: 34px; height: 34px" />
                    </template>
                </el-table-column>
                <el-table-column prop="name" label="显示名称" min-width="150"> </el-table-column>

                <el-table-column prop="sort" label="排序" min-width="150"> </el-table-column>

                <el-table-column label="操作" min-width="150">
                    <!-- 操作 -->
                    <template slot-scope="scope">
                        <el-button type="text" size="mini" @click="goPayConfigEdit(scope.row)">编辑 </el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import LsDialog from '@/components/ls-dialog.vue'
import { apiPaymentConfigGetList } from '@/api/setting/payment.ts'
@Component({
    components: {
        LsDialog
    }
})
export default class PayTemplate extends Vue {
    /** S Data **/
    paymentConfigData: Array<Object> = []
    /** E Data **/

    /** S Methods **/

    // 获取支付方式数据
    getPaymentConfigList() {
        apiPaymentConfigGetList()
            .then((res: any) => {
                this.paymentConfigData = res.lists
            })
            .catch(() => {
                this.$message.error('数据初始化失败，请刷新重载！')
            })
    }

    goPayConfigEdit(row: any) {
        this.$router.push({
            path: '/setting/payment/pay_config_edit',
            query: {
                id: row.id
            }
        })
    }
    /** E Methods **/

    /** S Life Cycle **/
    created() {
        this.getPaymentConfigList()
    }
}
</script>

<style lang="scss" scoped></style>
