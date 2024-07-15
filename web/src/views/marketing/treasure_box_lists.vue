<!-- 用户管理 -->
<template>
    <div class="user-management">
        <div class="ls-User__top ls-card">
            <el-alert
                class="xxl"
                title="温馨提示：1.管理宝箱列表，可以进行宝箱管理等操作。"
                type="info"
                :closable="false"
                show-icon
            ></el-alert>
            <div class="member-search m-t-16">
                <el-form ref="form" inline :model="form" label-width="70px" size="small">
                    <el-form-item label="名称">
                        <el-input v-model="form.name" placeholder="请输入宝箱名称"> </el-input>
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
                    <ls-treasure-box-add @refresh="getUserList" action="add">
                        <el-button type="text" slot="trigger" size="small">新增宝箱</el-button>
                    </ls-treasure-box-add>
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
                    <el-table-column prop="level" label="等级"></el-table-column>
                    <el-table-column prop="name" label="名称"></el-table-column>
                    <el-table-column prop="zhadanStr" label="炸弹牌型" width="160"></el-table-column>
                    <el-table-column prop="mahjongStr" label="麻将牌型" width="230"></el-table-column>
                    <el-table-column prop="pdkStr" label="跑得快牌型" width="230"></el-table-column>
                    <el-table-column prop="sssStr" label="十三水牌型" width="230"></el-table-column>
                    <el-table-column prop="bfStr" label="标分牌型" width="230"></el-table-column>
                    <el-table-column prop="count" label="补助次数"></el-table-column>
                    <el-table-column prop="juCount" label="释放局数"></el-table-column>
                    <el-table-column prop="coolingCount" label="冷却房间数"></el-table-column>
                    <el-table-column prop="gameStr" label="游戏"></el-table-column>
                    <el-table-column label="状态" min-width="80">
                        <template slot-scope="scope">{{ scope.row.isOnline === 1 ? '上架' : '下架' }}</template>
                    </el-table-column>
                    <el-table-column fixed="right" label="操作" min-width="120">
                        <template slot-scope="scope">
                            <ls-treasure-box-add
                                class="m-l-10 inline"
                                title="编辑宝箱"
                                action="edit"
                                :box="scope.row"
                                @refresh="getUserList"
                            >
                                <el-button type="text" slot="trigger" size="small">编辑</el-button>
                            </ls-treasure-box-add>

                            <ls-dialog
                                class="m-l-10 inline"
                                content="是否删除宝箱吗？删除后，操作将无法回退"
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
import { apiTreasureBoxList, apiDelTreasureBox } from '@/api/marketing'
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
import LsDialog from '@/components/ls-dialog.vue'
import LsTreasureBoxAdd from "@/components/ls-treasure-box-add.vue";
@Component({
    components: {
        LsTreasureBoxAdd,
        LsPagination,
        LsDialog
    }
})
export default class UserManagement extends Vue {
    form = {
        name: '',
        isOnline: ''
    }
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
            callback: apiTreasureBoxList,
            params: {
                ...this.form
            }
        })
    }

    // 重置按钮
    onReset() {
        this.form = {
            name: '',
            isOnline: ''
        }
        this.getUserList()
    }

    deleteGoods(good: any) {
        apiDelTreasureBox({
            _id: good._id
        }).then(res => {
            this.getUserList()
        })
    }

    created() {
        this.getUserList()
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
