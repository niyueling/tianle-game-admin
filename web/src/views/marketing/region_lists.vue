<!-- 用户管理 -->
<template>
    <div class="user-management">
        <div class="ls-User__top ls-card">
            <el-alert
                class="xxl"
                title="温馨提示：1.管理县区列表，可以进行县区管理等操作。"
                type="info"
                :closable="false"
                show-icon
            ></el-alert>
        </div>

        <div class="ls-User__centent ls-card m-t-16">
            <div class="list-header">
                <div class="flex">
                    <ls-region-add @refresh="getUserList" action="add">
                        <el-button type="text" slot="trigger" size="small">新增县区</el-button>
                    </ls-region-add>
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
                    <el-table-column prop="_id" label="县区编号"></el-table-column>
                    <el-table-column label="城市" prop="city"></el-table-column>
                    <el-table-column prop="county" label="县区"></el-table-column>
                    <el-table-column fixed="right" label="操作" min-width="120">
                        <template slot-scope="scope">
                            <ls-region-add
                                class="m-l-10 inline"
                                title="编辑县区"
                                action="edit"
                                :region="scope.row"
                                @refresh="getUserList"
                            >
                                <el-button type="text" slot="trigger" size="small">编辑</el-button>
                            </ls-region-add>

                            <ls-dialog
                                class="m-l-10 inline"
                                content="是否删除县区吗？删除后，操作将无法回退"
                                @confirm="deleteRegion(scope.row)"
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
import { apiRegionList, apiDelRegion } from '@/api/marketing'
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
import LsDialog from '../../components/ls-dialog.vue'
import LsRegionAdd from '@/components/ls-region-add.vue'
@Component({
    components: {
        LsRegionAdd,
        LsPagination,
        LsDialog
    }
})
export default class UserManagement extends Vue {
    form = {}
    // 分页查询
    pager: RequestPaging = new RequestPaging()
    apiUserList = apiRegionList

    // 查询按钮
    query() {
        this.pager.page = 1
        this.getUserList()
    }

    //获取用户列表数据
    getUserList() {
        this.pager.request({
            callback: apiRegionList,
            params: {
                ...this.form
            }
        })
    }

    // 重置按钮
    onReset() {
        this.form = {}
        this.getUserList()
    }

    deleteRegion(region: any) {
        apiDelRegion({
            _id: region._id
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
