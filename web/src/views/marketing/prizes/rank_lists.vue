<!-- 用户管理 -->
<template>
    <div class="user-management">
        <div class="ls-User__top ls-card">
            <el-alert
                class="xxl"
                title="温馨提示：1.管理活动排行榜列表，可以进行排行榜管理等操作。"
                type="info"
                :closable="false"
                show-icon
            ></el-alert>
            <div class="member-search m-t-16">
                <el-form ref="form" inline :model="form" label-width="70px" size="small">
                    <el-form-item label="名称">
                        <el-input v-model="form.name" style="width: 120px"></el-input>
                    </el-form-item>
                    <el-form-item label="期数">
                        第<el-input v-model="form.rankNo" style="width: 120px" />期
                    </el-form-item>
                    <el-button size="small" type="primary" @click="query()">查询</el-button>
                    <el-button size="small" @click="onReset()">重置</el-button>
                </el-form>
            </div>
        </div>

        <div class="ls-User__centent ls-card m-t-16">
            <div class="list-header">
                <div class="flex">
                    <ls-rank-add @refresh="getUserList" action="add">
                        <el-button type="text" slot="trigger" size="small">新增排行榜</el-button>
                    </ls-rank-add>
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
                    <el-table-column prop="_id" label="编号"></el-table-column>
                    <el-table-column prop="rankNoStr" label="期数"></el-table-column>
                    <el-table-column prop="name" label="名称"></el-table-column>
                    <el-table-column prop="startTime" label="开始时间" min-width="120"></el-table-column>
                    <el-table-column prop="endTime" label="结束时间" min-width="120"></el-table-column>
                    <el-table-column label="状态" min-width="80">
                        <template slot-scope="scope">{{ scope.row.isOpen ? '开放' : '关闭' }}</template>
                    </el-table-column>
                    <el-table-column fixed="right" label="操作" min-width="120">
                        <template slot-scope="scope">
                            <ls-rank-add
                                class="m-l-10 inline"
                                title="编辑排行榜"
                                action="edit"
                                :rank="scope.row"
                                @refresh="getUserList"
                            >
                                <el-button type="text" slot="trigger" size="small">编辑</el-button>
                            </ls-rank-add>

                            <ls-dialog
                                class="m-l-10 inline"
                                content="是否删除排行榜吗？删除后，操作将无法回退"
                                @confirm="deleteRank(scope.row)"
                            >
                                <el-button slot="trigger" type="text" size="small">删除</el-button>
                            </ls-dialog>
                            <el-button
                                class="m-l-10 inline"
                                @click="navicatToSetRule(scope.row._id)"
                                type="text"
                                size="small"
                                >奖品设置</el-button
                            >
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
import { apiRankLists, apiDeleteRankInfo } from '@/api/marketing'
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
import LsDialog from '@/components/ls-dialog.vue'
import LsGoodAdd from '@/components/ls-good-add.vue'
import LsRankAdd from '@/components/ls-rank-add.vue'
@Component({
    components: {
        LsRankAdd,
        LsGoodAdd,
        LsPagination,
        LsDialog
    }
})
export default class UserManagement extends Vue {
    form = {
        name: '',
        rankNo: ''
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
            callback: apiRankLists,
            params: {
                ...this.form
            }
        })
    }

    // 重置按钮
    onReset() {
        this.form = {
            name: '',
            rankNo: ''
        }
        this.getUserList()
    }

    deleteRank(rank: any) {
        apiDeleteRankInfo({
            _id: rank._id
        }).then(res => {
            this.getUserList()
        })
    }

    navicatToSetRule(_id: any) {
        this.$router.push({
            path: '/marketing/prizes/rule',
            query: {
                rankId: _id
            }
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
