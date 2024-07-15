<!-- 用户管理 -->
<template>
    <div class="user-management">
        <div class="ls-User__top ls-card">
            <el-alert
                class="xxl"
                title="温馨提示：1.管理商品列表，可以进行商品管理等操作。"
                type="info"
                :closable="false"
                show-icon
            ></el-alert>
            <div class="member-search m-t-16">
                <el-form ref="form" inline :model="form" label-width="70px" size="small">
                    <el-form-item label="商品类别">
                        <el-select class="ls-select" v-model="form.goodsType" placeholder="全部">
                            <el-option label="钻石" :value="0"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="是否上架">
                        <el-select class="ls-select" v-model="form.isOnline" placeholder="全部">
                            <el-option label="上架" :value="1"></el-option>
                            <el-option label="下架" :value="2"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-button size="small" type="primary" @click="query()">查询</el-button>
                    <el-button size="small" @click="onReset()">重置</el-button>
                </el-form>
            </div>
        </div>

        <div class="ls-User__centent ls-card m-t-16">
            <div class="list-header">
                <div class="flex">
                    <ls-good-add @refresh="getUserList" :box="boxLists" action="add">
                        <el-button type="text" slot="trigger" size="small">新增商品</el-button>
                    </ls-good-add>
                </div>
            </div>
            <div class="list-table m-t-16">
                <el-table
                    :data="pager.lists"
                    style="width: 100%"
                    size="mini"
                    v-loading="pager.loading"
                    :header-cell-style="{ background: '#f5f8ff' }"
                >
                    <el-table-column prop="_id" label="商品编号"></el-table-column>
                    <el-table-column label="商品类别">
                        <template slot-scope="scope">{{ scope.row.goodsType == 0 ? '钻石' : '其他' }}</template>
                    </el-table-column>
                    <el-table-column prop="amount" label="数量"></el-table-column>
                    <el-table-column prop="androidPrice" label="安卓价格"></el-table-column>
                    <el-table-column prop="iosPrice" label="苹果价格"></el-table-column>
                    <el-table-column prop="firstTimeAmount" label="首次购买赠送"></el-table-column>
                    <el-table-column prop="zhadanStr" label="炸弹宝箱"></el-table-column>
                    <el-table-column prop="mahjongStr" label="麻将宝箱"></el-table-column>
                    <el-table-column prop="bfStr" label="标分宝箱"></el-table-column>
                    <el-table-column prop="pdkStr" label="跑得快宝箱"></el-table-column>
                    <el-table-column prop="sssStr" label="十三水宝箱"></el-table-column>
                    <el-table-column prop="boxCount" label="宝箱个数"></el-table-column>
                    <el-table-column label="状态" min-width="80">
                        <template slot-scope="scope">{{ scope.row.isOnline == 1 ? '上架' : '下架' }}</template>
                    </el-table-column>
                    <el-table-column fixed="right" label="操作" min-width="120">
                        <template slot-scope="scope">
                            <ls-good-add
                                class="m-l-10 inline"
                                title="编辑商品"
                                action="edit"
                                :good="scope.row"
                                :box="boxLists"
                                @refresh="getUserList"
                            >
                                <el-button type="text" slot="trigger" size="small">编辑</el-button>
                            </ls-good-add>

                            <ls-dialog
                                class="m-l-10 inline"
                                content="是否删除商品吗？删除后，操作将无法回退"
                                @confirm="deleteGoods(scope.row)"
                            >
                                <el-button slot="trigger" type="text" size="small">删除</el-button>
                            </ls-dialog>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
            <!-- 底部分页栏  -->
            <div class="flex row-right m-t-16 row-right">
                <ls-pagination v-model="pager" @change="getUserList()" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { apiGoodList, apiDelGoods, apiTreasureBoxList } from '@/api/marketing'
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
import LsDialog from '../../../components/ls-dialog.vue'
import LsGoodAdd from '@/components/ls-good-add.vue'
@Component({
    components: {
        LsGoodAdd,
        LsPagination,
        LsDialog
    }
})
export default class UserManagement extends Vue {
    form = {
        goodsType: '',
        isOnline: ''
    }
    boxLists = []
    // 分页查询
    pager: RequestPaging = new RequestPaging()

    // 查询按钮
    query() {
        this.pager.page = 1
        this.getUserList()
    }

    //获取用户列表数据
    getUserList() {
        this.pager.request({
            callback: apiGoodList,
            params: {
                ...this.form
            }
        })
    }

    // 重置按钮
    onReset() {
        this.form = {
            goodsType: '',
            isOnline: ''
        }
        this.getUserList()
    }

    deleteGoods(good: any) {
        apiDelGoods({
            _id: good._id
        }).then(res => {
            this.getUserList()
        })
    }

    getTreasureBoxLists() {
        apiTreasureBoxList({page_no: 1, page_size: 20}).then(res => {
            this.boxLists = res.lists;
            console.log(this.boxLists)
        })
    }

    created() {
        this.getUserList()
        this.getTreasureBoxLists();
    }
}
</script>

<style lang="scss" scoped>
.user-management {
    .ls-user__top {
        padding: 16px 24px;
    }
}
</style>
