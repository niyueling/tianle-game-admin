<!-- 用户管理 -->
<template>
    <div class="user-management">
        <div class="ls-User__top ls-card">
            <el-alert
                class="xxl"
                title="温馨提示：1.管理俱乐部列表，可以进行资料编辑、充值、新增俱乐部、解散和资料查看等操作。"
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
                    <el-form-item label="shortId">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.clubShortId"
                            placeholder="请输入俱乐部shortId"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="名称">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.clubName"
                            placeholder="请输入俱乐部名称"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="状态">
                        <el-select class="ls-select" v-model="form.state" placeholder="全部">
                            <el-option label="解散" value="off"></el-option>
                            <el-option label="正常" value="on"></el-option>
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
                    <ls-club-add @refresh="getUserList" title="新增俱乐部">
                        <el-button type="text" slot="trigger" size="small" style="font-size: 15px">新增俱乐部</el-button>
                    </ls-club-add>
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
                    <el-table-column prop="_id" label="俱乐部编号"></el-table-column>
                    <el-table-column prop="name" label="名称"></el-table-column>
                    <el-table-column prop="shortId" label="俱乐部ID"></el-table-column>
                    <el-table-column label="队长ID">
                        <template slot-scope="scope">
                            <div
                                @click="navicatToUserByShortId(scope.row.player.shortId)"
                                style="color: #3967ff; cursor: pointer"
                            >
                                {{ scope.row.player.shortId }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="player.name" label="队长昵称"></el-table-column>
                    <el-table-column prop="player.realName" label="队长姓名"></el-table-column>
                    <el-table-column prop="player.gem" label="持有钻石"></el-table-column>
                    <el-table-column prop="gameList" label="游戏类型"></el-table-column>
                    <el-table-column label="状态" min-width="80">
                        <template slot-scope="scope">{{ scope.row.state === 'off' ? '暂停' : '正常' }}</template>
                    </el-table-column>
                    <el-table-column prop="createAt" label="创建时间" min-width="120"></el-table-column>
                    <el-table-column fixed="right" label="操作" min-width="120">
                        <template slot-scope="scope">
                            <el-button @click="DetailsClick(scope.row)" type="text" size="small">详情</el-button>
                            <el-button @click="MembersClick(scope.row)" type="text" size="small">成员管理</el-button>

                            <el-button @click="RoomsClick(scope.row)" type="text" size="small">历史房间</el-button>
                            <el-button @click="creatorClick(scope.row)" type="text" size="small">圈主记录</el-button>

                            <ls-dialog
                                v-if="scope.row.state === 'on'"
                                class="m-l-10 inline"
                                content="是否确认暂停俱乐部吗？暂停后，用户将无法开始游戏对局"
                                @confirm="handleFrozen(scope.row)"
                            >
                                <el-button slot="trigger" type="text" size="small">暂停</el-button>
                            </ls-dialog>
                            <ls-dialog
                                v-else
                                class="m-l-10 inline"
                                content="是否确认恢复俱乐部吗？恢复后，用户将恢复正常对局"
                                @confirm="handleFrozen(scope.row)"
                            >
                                <el-button slot="trigger" type="text" size="small">恢复</el-button>
                            </ls-dialog>

                            <ls-dialog
                                class="m-l-10 inline"
                                content="是否确认解散俱乐部吗？解散后，俱乐部将马上消失，操作无法回退"
                                @confirm="deleteClick(scope.row)"
                            >
                                <el-button slot="trigger" type="text" size="small">解散</el-button>
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
import { apiClubList, apiClubDelete, apiClubSetInfo } from '@/api/club/club'
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
import LsDialog from '@/components/ls-dialog.vue'
import LsClubAdd from '@/components/user/ls-club-add.vue'
@Component({
    components: {
        LsClubAdd,
        LsPagination,
        LsDialog
    }
})
export default class UserManagement extends Vue {
    form = {
        clubShortId: '',
        playerShortId: '',
        clubName: '',
        playerPhone: '',
        realName: '',
        name: '',
        state: '',
        club_ids: ''
    }
    isNameSN = ''
    pager: RequestPaging = new RequestPaging()

    // 查询按钮
    query() {
        this.pager.page = 1
        this.getUserList()
    }

    //获取用户列表数据
    getUserList() {
        this.pager.request({
            callback: apiClubList,
            params: {
                ...this.form
            }
        })
    }

    // 重置按钮
    onReset() {
        this.form = {
            clubShortId: '',
            playerShortId: '',
            clubName: '',
            playerPhone: '',
            realName: '',
            name: '',
            state: '',
            club_ids: this.form.club_ids
        }
        this.getUserList()
    }

    // 用户详情
    DetailsClick(item: any) {
        this.$router.push({
            path: '/clubs/clubs_detail',
            query: {
                id: item._id
            }
        })
    }

    MembersClick(item: any) {
        this.$router.push({
            path: '/clubs/members',
            query: {
                shortId: item.shortId
            }
        })
    }

    RoomsClick(item: any) {
        this.$router.push({
            path: '/clubs/room',
            query: {
                clubShortId: item.shortId
            }
        })
    }

    creatorClick(item: any) {
        this.$router.push({
            path: '/clubs/creator_gold',
            query: {
                clubShortId: item.shortId
            }
        })
    }

    handleFrozen(userInfo: any) {
        apiClubSetInfo({
            club_id: userInfo._id,
            field: 'state',
            value: userInfo.state == 'on' ? 'off' : 'on'
        }).then(res => {
            this.getUserList()
        })
    }

    deleteClick(item: any) {
        apiClubDelete({
            club_id: item._id
        }).then(res => {
            this.getUserList()
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
        if (query.club_ids) {
            this.form.club_ids = JSON.parse(query.club_ids)
        }
        if (query.shortId) {
            this.form.clubShortId = query.shortId
        }
        if (query.playerShortId) {
            this.form.playerShortId = query.playerShortId
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
