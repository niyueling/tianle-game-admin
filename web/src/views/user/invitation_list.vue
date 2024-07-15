<!-- 用户管理 -->
<template>
    <div class="user-management">
        <div class="ls-User__top ls-card">
            <el-alert class="xxl" title="温馨提示：查看粉丝信息。" type="info" :closable="false" show-icon></el-alert>
            <div class="member-search m-t-16">
                <el-form ref="form" inline :model="form" label-width="70px" size="small">
                    <el-form-item label="真实姓名">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.realName"
                            placeholder="请输入用户真实姓名"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="昵称">
                        <el-input class="ls-select-keyword" v-model="form.name" placeholder="请输入用户昵称"></el-input>
                    </el-form-item>
                    <el-form-item label="手机号">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.playerPhone"
                            placeholder="请输入用户手机号"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="shortId">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.playerShortId"
                            placeholder="请输入用户shortId"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="注册时间">
                        <el-date-picker
                            v-model="timeForm"
                            type="datetimerange"
                            align="right"
                            unlink-panels
                            range-separator="至"
                            start-placeholder="开始日期"
                            end-placeholder="结束日期"
                            :picker-options="pickerOptions"
                            @change="splitTime"
                            value-format="yyyy-MM-dd HH:mm:ss"
                        ></el-date-picker>
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
                    <el-table-column prop="inviteePlayerId" label="用户编号"></el-table-column>
                    <el-table-column label="用户头像">
                        <template slot-scope="scope">
                            <div class="flex">
                                <el-image
                                    :src="scope.row.player.headImgUrl"
                                    style="width: 34px; height: 34px"
                                ></el-image>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="inviteeShortId" label="用户ID"></el-table-column>
                    <el-table-column prop="player.name" label="用户昵称"></el-table-column>
                    <el-table-column prop="player.phone" label="手机号码"></el-table-column>
                    <el-table-column prop="player.gem" label="钻石"></el-table-column>
                    <el-table-column prop="player.gold" label="金币"></el-table-column>
                    <el-table-column label="创建俱乐部" min-width="80">
                        <template slot-scope="scope">
                            <div
                                @click="navicatToClubByShortId(scope.row.inviteeShortId)"
                                style="color: #3967ff; cursor: pointer"
                            >
                                {{ scope.row.isCreator ? scope.row.club.name : '/' }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="joinClubCount" label="俱乐部(个)"></el-table-column>
                    <el-table-column prop="createAt" label="注册时间" min-width="120"></el-table-column>
                    <el-table-column fixed="right" label="操作" min-width="120">
                        <template slot-scope="scope">
                            <el-button @click="DetailsClick(scope.row)" type="text" size="small">详情</el-button>
                            <el-button @click="roomsClick(scope.row)" type="text" size="small">历史开房</el-button>
                            <el-button @click="gmRecordClick(scope.row)" type="text" size="small">钻石记录</el-button>
                            <el-button @click="clubGoldRecordClick(scope.row)" type="text" size="small"
                                >金币记录</el-button
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
import { apiUserInviterLists } from '@/api/user/user'
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
@Component({
    components: {
        LsPagination
    }
})
export default class UserManagement extends Vue {
    pickerOptions: any = {
        shortcuts: [
            {
                text: '今日',
                onClick(picker: any) {
                    let end = new Date().setHours(23, 59, 59, 999)
                    let start = new Date().setHours(0, 0, 0, 0)
                    picker.$emit('pick', [new Date(start), new Date(end)])
                }
            },
            {
                text: '昨日',
                onClick(picker: any) {
                    let start = new Date().setHours(0, 0, 0, 0)
                    let end = new Date().setHours(0, 0, 0, 0)
                    start = start - 3600 * 1000 * 24
                    picker.$emit('pick', [new Date(start), new Date(end)])
                }
            },
            {
                text: '最近三天',
                onClick(picker: any) {
                    let start = new Date().setHours(0, 0, 0, 0)
                    let end = new Date().setHours(0, 0, 0, 0)
                    start = start - 3600 * 1000 * 24 * 3
                    picker.$emit('pick', [new Date(start), new Date(end)])
                }
            },
            {
                text: '最近一周',
                onClick(picker: any) {
                    let start = new Date().setHours(0, 0, 0, 0)
                    let end = new Date().setHours(0, 0, 0, 0)
                    start = start - 3600 * 1000 * 24 * 7
                    picker.$emit('pick', [new Date(start), new Date(end)])
                }
            },
            {
                text: '本周',
                onClick(picker: any) {
                    let day = new Date().getDay();
                    let end = new Date().setHours(0, 0, 0, 0)
                    let start = end - 3600 * 1000 * 24 * day
                    picker.$emit('pick', [new Date(start), new Date(end)])
                }
            },
            {
                text: '上周',
                onClick(picker: any) {
                    let day = new Date().getDay();
                    let end = new Date().setHours(0, 0, 0, 0) - 3600 * 1000 * 24 * day;
                    let start = end - 3600 * 1000 * 24 * 7
                    picker.$emit('pick', [new Date(start), new Date(end)])
                }
            },
            {
                text: '本月',
                onClick(picker: any) {
                    var today = new Date();
                    var month = new Date(today.getFullYear(), today.getMonth(), 1);
                    var firstDay = new Date(month.getFullYear(), month.getMonth(), 1).getTime();
                    var lastDay = new Date().setHours(0,0,0,0);

                    picker.$emit('pick', [new Date(firstDay), new Date(lastDay)])
                }
            },
            {
                text: '上月',
                onClick(picker: any) {
                    var today = new Date();
                    var lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    var firstDay = new Date(lastMonth.getFullYear(), lastMonth.getMonth(), 1).getTime();
                    var lastDay = new Date(lastMonth.getFullYear(), lastMonth.getMonth() + 1, 1).getTime();

                    picker.$emit('pick', [new Date(firstDay), new Date(lastDay)])
                }
            },
            {
                text: '最近一个月',
                onClick(picker: any) {
                    let start = new Date().setHours(0, 0, 0, 0)
                    let end = new Date().setHours(0, 0, 0, 0)
                    start = start - 3600 * 1000 * 24 * 30
                    picker.$emit('pick', [new Date(start), new Date(end)])
                }
            },
            {
                text: '最近三个月',
                onClick(picker: any) {
                    let start = new Date().setHours(0, 0, 0, 0)
                    let end = new Date().setHours(0, 0, 0, 0)
                    start = start - 3600 * 1000 * 24 * 90
                    picker.$emit('pick', [new Date(start), new Date(end)])
                }
            }
        ]
    }
    form = {
        realName: '',
        name: '',
        playerPhone: '',
        playerShortId: '',
        start_time: 0,
        end_time: 0,
        user_id: ''
    }
    // 日期选择器数据
    timeForm = []

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
            callback: apiUserInviterLists,
            params: {
                ...this.form
            }
        })
    }

    navicatToClubByShortId(shortId: any) {
        this.$router.push({
            path: '/clubs/lists',
            query: {
                playerShortId: shortId
            }
        })
    }

    // 转换为时间
    add(m: number) {
        return m < 10 ? '0' + m : m
    }
    // 拆分日期选择器时间
    splitTime() {
        if (this.timeForm != null) {
            this.form.start_time = new Date(this.timeForm[0]).getTime() / 1000
            this.form.end_time = new Date(this.timeForm[1]).getTime() / 1000
        }
    }
    // 重置按钮
    onReset() {
        this.form = {
            realName: '',
            name: '',
            playerPhone: '',
            playerShortId: '',
            start_time: 0,
            end_time: 0,
            user_id: ''
        }
        this.timeForm = []
        this.getUserList()
    }

    roomsClick(item: any) {
        this.$router.push({
            path: '/user/room',
            query: {
                _id: item.player._id
            }
        })
    }

    // 用户详情
    DetailsClick(item: any) {
        this.$router.push({
            path: '/user/user_details',
            query: {
                id: item.player._id
            }
        })
    }

    clubGoldRecordClick(item: any) {
        this.$router.push({
            path: '/user/gold',
            query: {
                playerShortId: item.player.shortId
            }
        })
    }

    gmRecordClick(item: any) {
        this.$router.push({
            path: '/user/gem',
            query: {
                shortId: item.player.shortId
            }
        })
    }

    created() {
        const query: any = this.$route.query
        if (query.id) {
            this.form.user_id = query.id
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
