<!-- 用户管理 -->
<template>
    <div class="user-management">
        <!-- 导航头部 -->
        <div class="ls-card">
            <el-page-header @back="$router.go(-1)" content="成员管理" />
        </div>
        <div class="ls-User__top ls-card">
            <el-alert
                class="xxl"
                title="温馨提示：1.管理俱乐部成员列表，可以进行俱乐部成员充值和踢出操作。"
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
            <div class="list-header">
                <div class="flex">
                    <el-button @click="ApplysClick()" type="text" size="small">成员审核</el-button>
                    <el-button @click="BlacksClick()" type="text" size="small">黑名单管理</el-button>
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
                    <el-table-column label="用户编号">
                        <template slot-scope="scope">
                            <div
                                @click="navicatToUserByShortId(scope.row.shortId)"
                                style="color: #3967ff; cursor: pointer"
                            >
                                {{ scope.row.shortId }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="username" label="用户名称"></el-table-column>
                    <el-table-column label="手机号">
                        <template slot-scope="scope">
                            <div @click="navicatToUserByPhone(scope.row.phone)" style="color: #3967ff; cursor: pointer">
                                {{ scope.row.phone }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="clubId" label="俱乐部编号"></el-table-column>
                    <el-table-column prop="clubGold" label="战队金币"></el-table-column>
                    <el-table-column prop="gold" label="金币"></el-table-column>
                    <el-table-column prop="gem" label="钻石"></el-table-column>
                    <el-table-column prop="redPocket" label="红包"></el-table-column>
                    <el-table-column label="游客" min-width="80">
                        <template slot-scope="scope"> {{ scope.row.isTourist ? '是' : '否' }} </template>
                    </el-table-column>
                    <el-table-column label="管理员" min-width="80">
                        <template slot-scope="scope"> {{ scope.row.role == 'admin' ? '是' : '否' }} </template>
                    </el-table-column>
                    <el-table-column prop="joinAt" label="申请时间" min-width="120"></el-table-column>
                    <el-table-column fixed="right" label="操作" min-width="120">
                        <template slot-scope="scope">
                            <el-button @click="clubGoldRecordClick(scope.row)" type="text" size="small"
                                >金币记录</el-button
                            >
                            <ls-memeber-change
                                class="m-l-10 inline"
                                title="战队金币充值"
                                :value="scope.row.clubGold"
                                :userId="scope.row._id"
                                @refresh="getUserList"
                            >
                                <el-button type="text" slot="trigger" size="small">充值</el-button>
                            </ls-memeber-change>
                            <ls-dialog
                                class="m-l-10 inline"
                                content="是否确认将该成员踢出俱乐部吗？同意后，该成员将马上踢出俱乐部"
                                @confirm="kickMember(scope.row)"
                            >
                                <el-button slot="trigger" type="text" size="small">踢出</el-button>
                            </ls-dialog>
                            <ls-dialog
                                v-if="scope.row.role == 'admin'"
                                class="m-l-10 inline"
                                content="是否确认将该成员取消管理员吗？同意后，该成员将马上失去俱乐部管理员身份"
                                @confirm="setAdmin(scope.row)"
                            >
                                <el-button slot="trigger" type="text" size="small">取消管理员</el-button>
                            </ls-dialog>
                            <ls-dialog
                                v-else
                                class="m-l-10 inline"
                                content="是否确认将该成员设置成管理员吗？同意后，该成员将马上成为俱乐部管理员"
                                @confirm="setAdmin(scope.row)"
                            >
                                <el-button slot="trigger" type="text" size="small">设置管理员</el-button>
                            </ls-dialog>

                            <ls-dialog
                                v-if="scope.row.isBlack"
                                class="m-l-10 inline"
                                content="是否确认将该成员从黑名单移除吗？同意后，该成员将马上移除俱乐部黑名单"
                                @confirm="setBlack(scope.row, 2)"
                            >
                                <el-button slot="trigger" type="text" size="small">解除黑名单</el-button>
                            </ls-dialog>
                            <ls-dialog
                                v-else
                                class="m-l-10 inline"
                                content="是否确认将该成员添加入黑名单吗？同意后，该成员将马上移入俱乐部黑名单"
                                @confirm="setBlack(scope.row, 1)"
                            >
                                <el-button slot="trigger" type="text" size="small">添加黑名单</el-button>
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
import { apiClubMemberList, apiKickMember, apiSetMemberInfo, apiSetBlackInfo } from '@/api/club/club'
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
import LsDialog from '../../components/ls-dialog.vue'
import LsMemeberChange from '@/components/user/ls-memeber-change.vue'
@Component({
    components: {
        LsMemeberChange,
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
        shortId: ''
    }
    // 分页查询
    pager: RequestPaging = new RequestPaging()

    // 查询按钮
    query() {
        this.pager.page = 1
        this.getUserList()
    }

    // 踢出用户
    kickMember(userInfo: any) {
        apiKickMember({
            _id: userInfo._id
        }).then(res => {
            this.getUserList()
        })
    }

    setAdmin(userInfo: any) {
        apiSetMemberInfo({
            _id: userInfo._id,
            role: userInfo.role == 'admin' ? '' : 'admin'
        }).then(res => {
            this.getUserList()
        })
    }

    setBlack(userInfo: any, state: any) {
        apiSetBlackInfo({
            _id: userInfo.member,
            action: state,
            club: userInfo.club
        }).then(res => {
            this.getUserList()
        })
    }

    ApplysClick() {
        this.$router.push({
            path: '/clubs/apply',
            query: {
                shortId: this.form.shortId
            }
        })
    }

    BlacksClick() {
        this.$router.push({
            path: '/clubs/blacks',
            query: {
                shortId: this.form.shortId
            }
        })
    }

    clubGoldRecordClick(item: any) {
        this.$router.push({
            path: '/clubs/gold',
            query: {
                playerShortId: item.shortId
            }
        })
    }

    //获取用户列表数据
    getUserList() {
        this.pager.request({
            callback: apiClubMemberList,
            params: {
                ...this.form
            }
        })
    }

    navicatToUserByPhone(phone: any) {
        if (phone == '/') {
            return
        }
        this.$router.push({
            path: '/user/lists',
            query: {
                phone
            }
        })
    }

    navicatToUserByShortId(shortId: any) {
        this.$router.push({
            path: '/user/lists',
            query: {
                shortId
            }
        })
    }

    // 重置按钮
    onReset() {
        this.form.playerShortId = ''
        this.form.playerName = ''
        this.form.playerPhone = ''
        this.form.realName = ''
        this.getUserList()
    }

    created() {
        const query: any = this.$route.query
        if (query.shortId) {
            this.form.shortId = query.shortId
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
