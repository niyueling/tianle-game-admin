<!-- 用户管理 -->
<template>
    <div class="user-management">
        <div class="ls-card">
            <el-page-header @back="$router.go(-1)" content="成员审核" />
        </div>
        <div class="ls-User__top ls-card">
            <el-alert
                class="xxl"
                title="温馨提示：1.管理俱乐部成员申请列表，可以进行俱乐部成员审核操作。"
                type="info"
                :closable="false"
                show-icon
            ></el-alert>
            <div class="member-search m-t-16">
                <el-form ref="form" inline :model="form" label-width="70px" size="small">
                    <el-form-item label="用户Id">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.playerShortId"
                            placeholder="请输入用户shortId"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="名称">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.playerName"
                            placeholder="请输入用户昵称"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="手机号">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.playerPhone"
                            placeholder="请输入手机号"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="真实姓名">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.realName"
                            placeholder="请输入真实姓名"
                        ></el-input>
                    </el-form-item>

                    <el-button size="small" type="primary" @click="query()">查询</el-button>
                    <el-button size="small" @click="onReset()">重置</el-button>
                </el-form>
            </div>
        </div>

        <div class="ls-User__centent ls-card m-t-16">
            <div class="list-table m-t-16">
                <el-table
                    :data="pager.lists"
                    style="width: 100%"
                    size="mini"
                    v-loading="pager.loading"
                    :header-cell-style="{ background: '#f5f8ff' }"
                >
                    <el-table-column prop="_id" label="申请编号"></el-table-column>
                    <el-table-column prop="playerShortId" label="用户编号"></el-table-column>
                    <el-table-column prop="playerName" label="用户名称"></el-table-column>
                    <el-table-column prop="phone" label="手机号"></el-table-column>
                    <el-table-column prop="clubShortId" label="俱乐部编号"></el-table-column>
                    <el-table-column prop="gold" label="金币"></el-table-column>
                    <el-table-column prop="gem" label="钻石"></el-table-column>
                    <el-table-column prop="redPocket" label="红包"></el-table-column>
                    <el-table-column label="游客" min-width="80">
                        <template slot-scope="scope"> {{ scope.row.isTourist ? '是' : '否' }} </template>
                    </el-table-column>

                    <el-table-column prop="createAt" label="申请时间" min-width="120"></el-table-column>
                    <el-table-column fixed="right" label="操作" min-width="120">
                        <template slot-scope="scope">
                            <ls-dialog
                                class="m-l-10 inline"
                                content="是否确认同意该成员加入俱乐部吗？同意后，该成员将马上成为俱乐部成员"
                                @confirm="handleFrozen(scope.row, 1)"
                            >
                                <el-button slot="trigger" type="text" size="small">通过</el-button>
                            </ls-dialog>
                            <ls-dialog
                                class="m-l-10 inline"
                                content="是否拒绝该成员加入俱乐部吗？"
                                @confirm="handleFrozen(scope.row, 2)"
                            >
                                <el-button slot="trigger" type="text" size="small">拒绝</el-button>
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
import { apiAgencyRequestList, apiAgencyRequestSetInfo } from '@/api/agency/agency'
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
import LsDialog from '../../components/ls-dialog.vue'
@Component({
    components: {
        LsPagination,
        LsDialog
    }
})
export default class UserManagement extends Vue {
    form = {
        playerShortId: '',
        playerName: '',
        playerPhone: '',
        realName: '',
        _id: ''
    }
    // 分页查询
    pager: RequestPaging = new RequestPaging()
    /** E Data **/

    /** S Methods **/
    apiUserList = apiAgencyRequestList // 传递给导出组件的api

    // 查询按钮
    query() {
        this.pager.page = 1
        this.getUserList()
    }

    //获取用户列表数据
    getUserList() {
        this.pager.request({
            callback: apiAgencyRequestList,
            params: {
                ...this.form
            }
        })
    }

    // 重置按钮
    onReset() {
        this.form = {
            playerShortId: '',
            playerName: '',
            playerPhone: '',
            realName: '',
            _id: ''
        }
        this.getUserList()
    }

    // 冻结用户
    handleFrozen(userInfo: any, status: number) {
        let { _id } = userInfo
        apiAgencyRequestSetInfo({
            id: _id,
            status: status
        }).then(res => {
            this.getUserList()
        })
    }

    created() {
        const query: any = this.$route.query
        if (query.id) {
            this.form._id = query.id
        }
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
