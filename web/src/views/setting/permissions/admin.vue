<template>
    <div class="admin">
        <div class="ls-card">
            <!-- 提示 -->
            <el-alert title="这是管理员页面，默认管理员。" type="info" show-icon :closable="false" />

            <!-- 头部表单 -->
            <div class="m-t-20">
                <el-form :inline="true" :model="form" size="small">
                    <!-- 账号 -->
                    <el-form-item label="账号">
                        <el-input v-model="form.username" placeholder="请输入账号" />
                    </el-form-item>

                    <!-- 角色选择 -->
                    <el-form-item label="角色" class="m-l-24">
                        <el-select v-model="form.name" placeholder="所有角色">
                            <el-option label="所有角色" value=""></el-option>
                            <!-- 渲染角色选择列表 -->
                            <el-option
                                v-for="(item, index) in roleList"
                                :key="index"
                                :label="item.name"
                                :value="item.id"
                            ></el-option>
                        </el-select>
                    </el-form-item>

                    <!-- 搜索查询 -->
                    <el-form-item class="m-l-24">
                        <el-button type="primary" @click="search">查询 </el-button>
                        <el-button @click="resetSearch">重置</el-button>
                        <export-data
                            class="m-l-10"
                            :method="apiAdminList"
                            :param="form"
                            :pageSize="pager._size"
                        ></export-data>
                    </el-form-item>
                </el-form>
            </div>
        </div>

        <div class="ls-card m-t-24">
            <!-- 管理员数据列表 -->
            <div class="m-t-24">
                <el-table
                    :data="pager.lists"
                    v-loading="pager.loading"
                    :default-sort="{ prop: 'create_time', order: 'descending' }"
                    style="width: 100%"
                    size="mini"
                >
                    <el-table-column prop="username" label="账号" min-width="120" />
                    <el-table-column prop="role" label="角色" min-width="120" />
                    <el-table-column prop="gem" label="钻石" min-width="120" />
                    <el-table-column prop="gold" label="金币" min-width="120" />
                    <el-table-column prop="spendGem" label="累计消耗钻石" min-width="120" />
                    <el-table-column prop="spendGold" label="累计消耗金币" min-width="120" />
                    <el-table-column label="伙伴">
                        <template slot-scope="scope">
                            {{ scope.row.partner == 1 ? '是' : '否' }}
                        </template>
                    </el-table-column>
                    <el-table-column prop="login_time" label="最后登录时间" width="150" />
                    <el-table-column prop="last_login_ip" label="最后登录IP" width="150" />
                    <el-table-column prop="disable" label="状态">
                        <template slot-scope="scope">
                            {{ scope.row.disable == 1 ? '正常' : '冻结' }}
                        </template>
                    </el-table-column>
                </el-table>

                <!-- 分页 -->
                <div class="m-t-24 pagination">
                    <ls-pagination v-model="pager" @change="getAdminList" />
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { apiAdminList, apiAdminDelete, apiRoleList, apiAdminEdit } from '@/api/setting/permissions'
import { PageMode } from '@/utils/type'
import { RequestPaging } from '@/utils/util'
import LsDialog from '@/components/ls-dialog.vue'
import LsPagination from '@/components/ls-pagination.vue'
import { AdminList_Req } from '@/api/setting/permissions.d.ts'
import ExportData from '@/components/export-data/index.vue'

@Component({
    components: {
        LsDialog,
        LsPagination,
        ExportData
    }
})
export default class Admin extends Vue {
    /** S Data **/
    apiAdminList = apiAdminList
    // 表单数据
    form: AdminList_Req = {
        account: '', //账号
        name: '', //名称
        role_id: undefined //角色id
    }
    pager: RequestPaging = new RequestPaging()
    roleList: Array<object> = [] // 角色列表

    /** E Data **/

    /** S Methods **/
    // 搜索角色
    search() {
        this.pager.page = 1
        this.getAdminList()
    }

    // 重置搜索
    resetSearch() {
        Object.keys(this.form).map(key => {
            this.$set(this.form, key, '')
        })
        this.getAdminList()
    }

    // 获取管理员列表
    getAdminList() {
        // 请求管理员列表
        this.pager
            .request({
                callback: apiAdminList,
                params: this.form
            })
            .catch(() => {})
    }

    // 获取角色列表
    getRoleList() {
        apiRoleList({
            page_type: 1
        }).then(res => {
            this.roleList = res.lists
        })
    }

    // 添加管理员
    addAdmin() {
        this.$router.push({
            path: '/setting/permissions/admin_edit',
            query: {
                mode: PageMode.ADD
            }
        })
    }

    // 删除管理员
    onAdminDelete(e: any) {
        apiAdminDelete({ id: e.id }).then(() => {
            // 删除成功就请求新列表
            this.getAdminList()
            this.$message.success('删除成功!')
        })
    }

    // 编辑管理员
    goAdminEdit(item: any) {
        const isAdd: any = false
        this.$router.push({
            path: '/setting/permissions/admin_edit',
            query: {
                mode: PageMode.EDIT,
                id: item.id
            }
        })
    }

    // 更改管理员开关
    changeDisableSwitchStatus(value: 0 | 1, data: any) {
        apiAdminEdit({
            id: data.id,
            account: data.account,
            name: data.name,
            role_id: data.role_id,
            disable: data.disable,
            multipoint_login: data.multipoint_login
        }).finally(() => {
            this.getAdminList()
        })
    }

    /** E Methods **/

    /** S Life Cycle **/
    created() {
        this.getAdminList()
        this.getRoleList()
    }

    /** E Life Cycle **/
}
</script>
s

<style lang="scss" scoped>
.pagination {
    display: flex;
    justify-content: flex-end;
}
</style>
