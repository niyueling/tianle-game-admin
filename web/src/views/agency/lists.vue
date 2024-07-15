<!-- 用户管理 -->
<template>
    <div class="user-management">
        <div class="ls-User__top ls-card">
            <el-alert
                class="xxl"
                title="温馨提示：1.管理代理列表，可以进行资料编辑、充值、新增代理、绑定俱乐部，俱乐部成员审核和资料查看等操作。"
                type="info"
                :closable="false"
                show-icon
            ></el-alert>
            <div class="member-search m-t-16">
                <el-form ref="form" inline :model="form" label-width="70px" size="small">
                    <el-form-item label="代理账号">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.username"
                            placeholder="请输入代理账号"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="角色">
                        <el-select class="ls-select" v-model="form.role" placeholder="全部">
                            <el-option label="超管" value="super"></el-option>
                            <el-option label="代理" value="role"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="代理状态">
                        <el-select class="ls-select" v-model="form.disable" placeholder="全部">
                            <el-option label="冻结" :value="1"></el-option>
                            <el-option label="正常" :value="0"></el-option>
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
                    <ls-agency-add @refresh="getUserList">
                        <el-button type="text" slot="trigger" size="small" style="font-size: 15px">新增代理</el-button>
                    </ls-agency-add>
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
                    <el-table-column prop="_id" label="代理编号"></el-table-column>
                    <el-table-column prop="username" label="代理昵称"></el-table-column>
                    <el-table-column prop="role" label="代理角色"></el-table-column>
                    <el-table-column prop="gold" label="金币"></el-table-column>
                    <el-table-column prop="gem" label="钻石"></el-table-column>
                    <el-table-column prop="spendGold" label="消耗金币"></el-table-column>
                    <el-table-column prop="spendGem" label="消耗钻石"></el-table-column>
                    <el-table-column label="超管" min-width="80">
                        <template slot-scope="scope">{{ scope.row.root == 1 ? '是' : '否' }}</template>
                    </el-table-column>
                    <el-table-column label="状态" min-width="80">
                        <template slot-scope="scope">{{ scope.row.disable == 0 ? '冻结' : '正常' }}</template>
                    </el-table-column>
                    <el-table-column prop="last_login_ip" label="登录IP" min-width="120"></el-table-column>
                    <el-table-column prop="login_time" label="最后登陆时间" min-width="120"></el-table-column>
                    <el-table-column fixed="right" label="操作" min-width="120">
                        <template slot-scope="scope">
                            <el-button @click="DetailsClick(scope.row)" type="text" size="small" slot="trigger"
                                >详情</el-button
                            >
                            <ls-agency-change
                                class="m-l-10 inline"
                                title="钻石充值"
                                :value="scope.row.gem"
                                :type="1"
                                :userId="scope.row._id"
                                @refresh="getUserList"
                            >
                                <el-button type="text" slot="trigger" size="small">充值</el-button>
                            </ls-agency-change>
                            <el-button
                                style="margin-left: 10px"
                                v-if="scope.row.is_bind_club"
                                @click="clubClick(scope.row)"
                                type="text"
                                size="small"
                                slot="trigger"
                                >俱乐部管理</el-button
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
import { apiAgencyList } from '@/api/agency/agency'
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
import LsAgencyAdd from '@/components/user/ls-agency-add.vue'
import LsAgencyChange from '@/components/user/ls-agency-change.vue'
@Component({
    components: {
        LsAgencyChange,
        LsAgencyAdd,
        LsPagination
    }
})
export default class UserManagement extends Vue {
    form = {
        username: '',
        role: '',
        disable: ''
    }
    // 分页查询
    pager: RequestPaging = new RequestPaging()
    apiUserList = apiAgencyList // 传递给导出组件的api

    // 查询按钮
    query() {
        this.pager.page = 1
        this.getUserList()
    }

    //获取用户列表数据
    getUserList() {
        this.pager.request({
            callback: apiAgencyList,
            params: {
                ...this.form
            }
        })
    }

    // 重置按钮
    onReset() {
        this.form = {
            username: '',
            role: '',
            disable: ''
        }
        this.getUserList()
    }

    // 详情
    DetailsClick(item: any) {
        this.$router.push({
            path: '/agency/agency_detail',
            query: {
                id: item._id
            }
        })
    }

    // 俱乐部管理
    clubClick(item: any) {
        this.$router.push({
            path: '/clubs/lists',
            query: {
                club_ids: JSON.stringify(item.club_id)
            }
        })
    }

    created() {
        const query: any = this.$route.query
        if (query.username) {
            this.form.username = query.username
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
