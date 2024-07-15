<template>
    <div class="permissions-role">
        <div class="ls-card">
            <!--   功能键组   -->
            <div class="function-container">
                <el-button size="small" type="primary" @click="onRoleAdd">添加角色</el-button>
            </div>

            <!--   表格   -->
            <div class="ls-content__table m-t-16">
                <el-table :data="pager.lists" style="width: 100%" v-loading="pager.loading" size="mini">
                    <el-table-column prop="id" label="ID" width="180" />
                    <el-table-column prop="name" label="名称" width="180" />
                    <el-table-column prop="desc" label="说明" />
                    <el-table-column prop="create_time" label="创建时间" />
                    <el-table-column fixed="right" label="操作">
                        <template slot-scope="scope">
                            <el-button type="text" size="small" @click="onRoleEdit(scope.row)">编辑</el-button>
                            <ls-dialog class="m-l-10 inline" @confirm="onRoleDel(scope.row)">
                                <el-button type="text" size="small" slot="trigger">删除</el-button>
                            </ls-dialog>
                        </template>
                    </el-table-column>
                </el-table>

                <!--    分页器    -->
                <div class="ls-content__pagination flex row-right m-t-16">
                    <ls-pagination v-model="pager" @change="getRoleList" />
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { RequestPaging } from '@/utils/util'
import { apiRoleList, apiRoleDelete } from '@/api/setting/permissions'
import LsPagination from '@/components/ls-pagination.vue'
import LsDialog from '@/components/ls-dialog.vue'
import { PageMode } from '@/utils/type'

@Component({
    components: {
        LsDialog,
        LsPagination
    }
})
export default class Role extends Vue {
    /** S Data **/
    pager: RequestPaging = new RequestPaging()

    /** E Data **/

    /** S Methods **/
    // 添加角色
    onRoleAdd(): void {
        this.$router.push({
            path: '/setting/permissions/role_edit',
            query: { mode: PageMode.ADD }
        })
    }

    // 编辑角色
    onRoleEdit(item: any): void {
        this.$router.push({
            path: '/setting/permissions/role_edit',
            query: { mode: PageMode.EDIT, id: item.id }
        })
    }

    // 删除角色
    onRoleDel(data: any): void {
        apiRoleDelete({
            id: data.id
        })
            .then(() => {
                this.getRoleList()
                this.$message.success('删除成功!')
            })
            .catch(() => {
                this.$message.error('删除失败!')
            })
    }

    // 获取数据
    getRoleList(): void {
        this.pager
            .request({
                callback: apiRoleList
            })
            .catch(() => {
                this.$message.error('数据请求失败，刷新重载!')
            })
    }
    /** E Methods **/

    /** S Life Cycle **/
    created() {
        this.getRoleList()
    }
    /** E Life Cycle **/
}
</script>

<style lang="scss" scoped></style>
