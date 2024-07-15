<!-- 用户管理 -->
<template>
    <div class="user-management">
        <div class="ls-User__top ls-card">
            <el-alert
                class="xxl"
                title="温馨提示：1.管理用户信息，可以进行编辑、账户调整和资料查看等操作。"
                type="info"
                :closable="false"
                show-icon
            ></el-alert>
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
                            placeholder="请输入shortId"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="openid">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.playerOpenid"
                            placeholder="请输入openid"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="机器人">
                        <el-select class="ls-select" v-model="form.robot" placeholder="全部">
                            <el-option label="全部" value=""></el-option>
                            <el-option label="是" :value="true"></el-option>
                            <el-option label="否" :value="false"></el-option>
                        </el-select>
                    </el-form-item>

                  <el-form-item label="游客">
                    <el-select class="ls-select" v-model="form.tourist" placeholder="全部">
                      <el-option label="全部" value=""></el-option>
                      <el-option label="是" :value="true"></el-option>
                      <el-option label="否" :value="false"></el-option>
                    </el-select>
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
                    <el-table-column prop="_id" label="用户编号"></el-table-column>
                    <el-table-column label="用户头像">
                        <template slot-scope="scope">
                            <div class="flex">
                                <el-image :src="scope.row.avatar" style="width: 34px; height: 34px"></el-image>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="shortId" label="用户ID"></el-table-column>
                    <el-table-column prop="nickname" label="用户昵称"></el-table-column>
                    <el-table-column prop="phone" label="手机号码"></el-table-column>
                    <el-table-column prop="diamond" label="钻石"></el-table-column>
                    <el-table-column prop="gold" label="金豆"></el-table-column>
                    <el-table-column prop="tlGold" label="天乐豆"></el-table-column>
                    <el-table-column prop="juCount" label="局数"></el-table-column>
                    <el-table-column prop="juRank" label="胜率"></el-table-column>
                    <el-table-column label="游客" min-width="80">
                        <template slot-scope="scope">{{ scope.row.tourist ? '是' : '否' }}</template>
                    </el-table-column>
                  <el-table-column label="机器人" min-width="80">
                    <template slot-scope="scope">{{ scope.row.robot ? '是' : '否' }}</template>
                  </el-table-column>
                  <el-table-column prop="ip" label="登录ip"></el-table-column>
                  <el-table-column label="定位" min-width="80">
                    <template slot-scope="scope">{{ scope.row.province + scope.row.city }}</template>
                  </el-table-column>
                    <el-table-column prop="createAt" label="注册时间" min-width="120"></el-table-column>
                    <el-table-column fixed="right" label="操作" min-width="120">
                        <template slot-scope="scope">
                            <el-button @click="DetailsClick(scope.row)" type="text" size="small">详情</el-button>
                            <ls-user-change
                                class="m-l-10 inline"
                                title="钻石调整"
                                :value="scope.row.diamond"
                                :type="1"
                                :userId="scope.row._id"
                                @refresh="getUserList"
                            >
                                <el-button type="text" slot="trigger" size="small">充值</el-button>
                            </ls-user-change>
                            <el-button @click="roomsClick(scope.row)" type="text" size="small" style="margin-left: 10px">历史开房</el-button>
                            <el-button @click="gmRecordClick(scope.row)" type="text" size="small">钻石记录</el-button>
                            <ls-dialog
                                class="m-l-10 inline"
                                content="是否确认删除用户吗？恢复后，确认删除操作将不可回退"
                                @confirm="deleteClick(scope.row)"
                            >
                                <el-button slot="trigger"  type="text" size="small">删除</el-button>
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
import { apiUserList, apiDeleteUserInfo } from '@/api/user/user'
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
import LsUserChange from '@/components/user/ls-user-change.vue'
import LsDialog from '@/components/ls-dialog.vue'
@Component({
    components: {
        LsUserChange,
        LsPagination,
        LsDialog
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
        playerOpenid: '',
        tourist: '',
        robot: '',
        start_time: 0,
        end_time: 0
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
            callback: apiUserList,
            params: {
                ...this.form
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
            playerOpenid: '',
            tourist: '',
            robot: '',
            start_time: 0,
            end_time: 0
        }
        this.timeForm = []
        this.getUserList()
    }

    roomsClick(item: any) {
        this.$router.push({
            path: '/user/room',
            query: {
                playerShortId: item.shortId
            }
        })
    }

    // 用户详情
    DetailsClick(item: any) {
        this.$router.push({
            path: '/user/user_details',
            query: {
                id: item._id
            }
        })
    }

    gmRecordClick(item: any) {
        this.$router.push({
            path: '/user/diamond',
            query: {
                shortId: item.shortId
            }
        })
    }

    deleteClick(item: any) {
        apiDeleteUserInfo({
            user_id: item._id
        }).then(res => {
            this.getUserList()
        })
    }

    created() {
        const query: any = this.$route.query
        if (query.shortId) {
            this.form.playerShortId = query.shortId
        }
        if (query.phone) {
            this.form.playerPhone = query.phone
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
