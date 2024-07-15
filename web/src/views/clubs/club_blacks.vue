<!-- 用户管理 -->
<template>
    <div class="user-management">
        <!-- 导航头部 -->
        <div class="ls-card">
            <el-page-header @back="$router.go(-1)" content="黑名单管理" />
        </div>
        <div class="ls-User__top ls-card">
            <el-alert
                class="xxl"
                title="温馨提示：1.管理俱乐部成员黑名单列表，可以进行俱乐部黑名单成员管理操作。"
                type="info"
                :closable="false"
                show-icon
            ></el-alert>
            <div class="member-search m-t-16">
                <el-form ref="form" inline :model="form" label-width="70px" size="small">
                    <el-form-item label="用户信息">
                        <el-select v-model="isNameSN" placeholder="全部" style="width: 120px">
                            <el-option label="用户ShortId" value="1"></el-option>
                            <el-option label="用户昵称" value="2"></el-option>
                            <el-option label="手机号码" value="3"></el-option>
                            <el-option label="真实姓名" value="4"></el-option>
                        </el-select>
                        <el-input v-if="isNameSN == 1" v-model="form.playerShortId" placeholder=""> </el-input>
                        <el-input v-if="isNameSN == 2" v-model="form.name" placeholder=""></el-input>
                        <el-input v-if="isNameSN == 3" v-model="form.playerPhone" placeholder=""></el-input>
                        <el-input v-if="isNameSN == 4" v-model="form.realName" placeholder=""></el-input>
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
                    <el-table-column prop="name" label="用户名称"></el-table-column>
                    <el-table-column label="手机号">
                        <template slot-scope="scope">
                            <div @click="navicatToUserByPhone(scope.row.phone)" style="color: #3967ff; cursor: pointer">
                                {{ scope.row.phone }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="club.shortId" label="俱乐部编号"></el-table-column>
                    <el-table-column prop="club.name" label="俱乐部名称"></el-table-column>
                    <el-table-column prop="gold" label="金币"></el-table-column>
                    <el-table-column prop="gem" label="钻石"></el-table-column>
                    <el-table-column prop="redPocket" label="红包"></el-table-column>
                    <el-table-column label="游客" min-width="80">
                        <template slot-scope="scope"> {{ scope.row.isTourist ? '是' : '否' }} </template>
                    </el-table-column>
                    <el-table-column fixed="right" label="操作" min-width="120">
                        <template slot-scope="scope">
                            <ls-dialog
                                class="m-l-10 inline"
                                content="是否确认将该成员从黑名单移除吗？同意后，该成员将马上移除俱乐部黑名单"
                                @confirm="setBlack(scope.row)"
                            >
                                <el-button slot="trigger" type="text" size="small">解除黑名单</el-button>
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
import { Component, Vue, Watch } from 'vue-property-decorator'
import { apiClubBlackList, apiSetBlackInfo } from '@/api/club/club'
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
        name: '',
        playerShortId: '',
        playerPhone: '',
        realName: '',
        shortId: ''
    }

    isNameSN = '' // 选择用户编号1 选择用户昵称2 手机号码3,真实姓名4

    // 分页查询
    pager: RequestPaging = new RequestPaging()
    apiUserList = apiClubBlackList // 传递给导出组件的api

    // 监听用户信息查询框条件
    @Watch('isNameSN', {
        immediate: true
    })
    getChange(val: any) {
        // 初始值
        this.form.name = ''
        this.form.playerShortId = ''
        this.form.playerPhone = ''
        this.form.realName = ''
    }

    // 查询按钮
    query() {
        this.pager.page = 1
        this.getUserList()
    }

    setBlack(userInfo: any) {
        apiSetBlackInfo({
            _id: userInfo._id,
            action: 2,
            club: userInfo.club._id
        }).then(res => {
            this.getUserList()
        })
    }

    //获取用户列表数据
    getUserList() {
        this.pager.request({
            callback: apiClubBlackList,
            params: {
                ...this.form
            }
        })
    }

    // 重置按钮
    onReset() {
        this.form = {
            name: '',
            playerShortId: '',
            playerPhone: '',
            realName: '',
            shortId: this.form.shortId
        }
        this.getUserList()
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
