<!-- 用户管理 -->
<template>
    <div class="user-management">
        <div class="ls-User__top ls-card">
            <el-alert
                class="xxl"
                title="温馨提示：1.管理红包列表，可以进行红包管理等操作。"
                type="info"
                :closable="false"
                show-icon
            ></el-alert>
            <div class="member-search m-t-16">
                <el-form ref="form" inline :model="form" label-width="70px" size="small">
                    <el-form-item label="游戏名称">
                        <div class="flex">
                            <el-select style="width: 120px" v-model="form.game" placeholder="全部">
                                <el-option label="全部" value></el-option>
                                <el-option label="炸弹" value="zhadan"></el-option>
                                <el-option label="标分" value="biaofen"></el-option>
                                <el-option label="麻将" value="majiang"></el-option>
                                <el-option label="跑得快" value="paodekuai"></el-option>
                                <el-option label="十三水" value="shisanshui"></el-option>
                                <el-option label="厦门麻将" value="xmmajiang"></el-option>
                            </el-select>
                        </div>
                    </el-form-item>
                    <el-form-item label="类型">
                        <el-select class="ls-select" v-model="form.type" placeholder="全部">
                            <el-option label="全部" value></el-option>
                            <el-option label="幸运红包" value="lucky"></el-option>
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
                    <ls-redpocket-add @refresh="getUserList" action="add">
                        <el-button type="text" slot="trigger" size="small">新增红包</el-button>
                    </ls-redpocket-add>
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
                    <el-table-column prop="_id" label="红包编号"></el-table-column>
                    <el-table-column prop="gameStr" label="游戏名称"></el-table-column>
                    <el-table-column prop="typeStr" label="类型"></el-table-column>
                    <el-table-column prop="redPocket" label="红包金额"></el-table-column>
                    <el-table-column prop="probability" label="概率"></el-table-column>
                    <el-table-column fixed="right" label="操作" min-width="120">
                        <template slot-scope="scope">
                            <ls-redpocket-add
                                class="m-l-10 inline"
                                title="编辑红包"
                                action="edit"
                                :redpocket="scope.row"
                                @refresh="getUserList"
                            >
                                <el-button type="text" slot="trigger" size="small">编辑</el-button>
                            </ls-redpocket-add>

                            <ls-dialog
                                class="m-l-10 inline"
                                content="是否删除红包吗？删除后，操作将无法回退"
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
import { apiRedPocketList, apiDelRedPocket } from '@/api/marketing'
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
import LsDialog from '../../components/ls-dialog.vue'
import LsGoodAdd from '@/components/ls-good-add.vue'
import LsRedpocketAdd from '@/components/ls-redpocket-add.vue'
@Component({
    components: {
        LsRedpocketAdd,
        LsGoodAdd,
        LsPagination,
        LsDialog
    }
})
export default class UserManagement extends Vue {
    form = {
        game: '',
        type: ''
    }
    // 分页查询
    pager: RequestPaging = new RequestPaging()
    apiUserList = apiRedPocketList

    // 查询按钮
    query() {
        this.pager.page = 1
        this.getUserList()
    }

    //获取用户列表数据
    getUserList() {
        this.pager.request({
            callback: apiRedPocketList,
            params: {
                ...this.form
            }
        })
    }

    // 重置按钮
    onReset() {
        this.form = {
            game: '',
            type: ''
        }
        this.getUserList()
    }

    deleteGoods(good: any) {
        apiDelRedPocket({
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
