<!-- 用户管理 -->
<template>
    <div class="user-management">
        <div class="ls-User__centent ls-card m-t-16">
            <div class="list-header">
                <div class="flex">
                    <ls-cheer-add @refresh="getUserList" action="add">
                        <el-button type="text" slot="trigger" size="small">新增</el-button>
                    </ls-cheer-add>
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
                    <el-table-column prop="name" label="名称"></el-table-column>
                  <el-table-column prop="timesStr" label="倍率"></el-table-column>
                  <el-table-column prop="gemStr" label="消耗钻石"></el-table-column>
                  <el-table-column prop="blessStr" label="运势"></el-table-column>
                  <el-table-column prop="orderIndex" label="排序"></el-table-column>
                    <el-table-column fixed="right" label="操作" min-width="120">
                        <template slot-scope="scope">
                            <ls-cheer-add
                                class="m-l-10 inline"
                                title="编辑"
                                action="edit"
                                :cheer="scope.row"
                                @refresh="getUserList"
                            >
                                <el-button type="text" slot="trigger" size="small">编辑</el-button>
                            </ls-cheer-add>

                            <ls-dialog
                                class="m-l-10 inline"
                                content="是否删除此条数据吗？删除后，操作将无法回退"
                                @confirm="deleteGame(scope.row)"
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
import { apiCheerList, apiDelCheer } from "@/api/marketing";
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
import LsDialog from '../../components/ls-dialog.vue'
import LsGameAdd from '@/components/ls-game-add.vue'
import LsGameCategoryAdd from '@/components/ls-game-category-add.vue'
import LsConversionAdd from '@/components/ls-conversion-add.vue'
import LsCheerAdd from "@/components/ls-cheer-add.vue";
@Component({
    components: {
      LsCheerAdd,
        LsConversionAdd,
        LsGameCategoryAdd,
        LsGameAdd,
        LsPagination,
        LsDialog
    }
})
export default class UserManagement extends Vue {
    form = {
        title: '',
        category: ''
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
            callback: apiCheerList,
            params: {
                ...this.form
            }
        })
    }

    // 重置按钮
    onReset() {
        this.form = {
            title: '',
            category: ''
        }
        this.pager.page = 1
        this.getUserList()
    }

    deleteGame(good: any) {
      apiDelCheer({
            _id: good._id
        }).then(res => {
            this.pager.page = 1
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
