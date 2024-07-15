<!-- 用户管理 -->
<template>
    <div class="user-management">
        <!-- 导航头部 -->
        <div class="ls-card">
            <el-page-header @back="$router.go(-1)" content="房间记录" />
        </div>
        <div class="ls-User__top ls-card">
            <el-alert
                class="xxl"
                title="温馨提示：查看用户历史房间记录，可以进行用户历史房间记录的详细查询。"
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
                    <el-form-item label="房间号">
                        <el-input class="ls-select-keyword" v-model="form.roomId" placeholder="请输入房间号"></el-input>
                    </el-form-item>
                    <el-form-item label="ShortId">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.clubShortId"
                            placeholder="请输入战队ShortId"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="局数">
                        <el-input class="ls-select-keyword" v-model="form.juShu" placeholder="请输入局数"></el-input>
                    </el-form-item>
                    <el-form-item label="人数">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.playerCount"
                            placeholder="请输入人数"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="游戏类型">
                        <div class="flex">
                            <el-select style="width: 120px" v-model="form.category" placeholder="全部">
                                <el-option label="全部" value></el-option>
                                <el-option label="炸弹" value="zhadan"></el-option>
                                <el-option label="标分" value="biaofen"></el-option>
                                <el-option label="麻将" value="majiang"></el-option>
                                <el-option label="跑得快" value="paodekuai"></el-option>
                                <el-option label="十三水" value="shisanshui"></el-option>
                            </el-select>
                        </div>
                    </el-form-item>
                    <el-form-item label="赢家付">
                        <div class="flex">
                            <el-select style="width: 120px" v-model="form.winnerPay" placeholder="全部">
                                <el-option label="全部" value></el-option>
                                <el-option label="是" value="1"></el-option>
                                <el-option label="否" value="2"></el-option>
                            </el-select>
                        </div>
                    </el-form-item>
                    <el-form-item label="战队主付">
                        <div class="flex">
                            <el-select style="width: 120px" v-model="form.clubOwnerPay" placeholder="全部">
                                <el-option label="全部" value></el-option>
                                <el-option label="是" value="1"></el-option>
                                <el-option label="否" value="2"></el-option>
                            </el-select>
                        </div>
                    </el-form-item>
                    <el-form-item label="AA付">
                        <div class="flex">
                            <el-select style="width: 120px" v-model="form.share" placeholder="全部">
                                <el-option label="全部" value></el-option>
                                <el-option label="是" value="1"></el-option>
                                <el-option label="否" value="2"></el-option>
                            </el-select>
                        </div>
                    </el-form-item>
                    <el-form-item label="金币房">
                        <div class="flex">
                            <el-select style="width: 120px" v-model="form.useClubGold" placeholder="全部">
                                <el-option label="全部" value></el-option>
                                <el-option label="是" value="1"></el-option>
                                <el-option label="否" value="2"></el-option>
                            </el-select>
                        </div>
                    </el-form-item>
                    <el-form-item label="状态">
                        <el-select style="width: 120px" v-model="form.roomState" placeholder="全部">
                            <el-option label="全部" value></el-option>
                            <el-option label="零局解散" value="zero_ju"></el-option>
                            <el-option label="对局中" value="normal"></el-option>
                            <el-option label="完成" value="normal_last"></el-option>
                            <el-option label="解散" value="dissolve"></el-option>
                            <el-option label="尾局解散" value="dissolve_last"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="记录时间">
                        <el-date-picker
                            v-model="tableData"
                            type="datetimerange"
                            align="right"
                            unlink-panels
                            range-separator="至"
                            start-placeholder="开始时间"
                            end-placeholder="结束时间"
                            :picker-options="pickerOptions"
                            @change="splitTime"
                            value-format="yyyy-MM-dd HH:mm:ss"
                        >
                        </el-date-picker>
                    </el-form-item>

                    <el-button size="small" type="primary" @click="query()">查询</el-button>
                    <el-button size="small" @click="onReset()">重置</el-button>
                    <el-button size="small" @click="onExport">导出</el-button>
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
                    <el-table-column prop="roomNum" label="房间编号"></el-table-column>
                    <el-table-column label="房主">
                        <template slot-scope="scope">
                            <div
                                @click="navicatToUserByShortId(scope.row.player.shortId)"
                                style="color: #3967ff; cursor: pointer"
                            >
                                {{ scope.row.player.name }}({{ scope.row.player.shortId }})
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="俱乐部房间" min-width="80">
                        <template slot-scope="scope"> {{ scope.row.club ? '是' : '否' }} </template>
                    </el-table-column>
                    <el-table-column label="俱乐部">
                        <template slot-scope="scope">
                            <div
                                @click="navicatToClubByShortId(scope.row.club.shortId)"
                                style="color: #3967ff; cursor: pointer"
                                v-if="scope.row.club.name != '/'"
                            >
                                {{ scope.row.club.name }}({{ scope.row.club.shortId }})
                            </div>
                            <div v-else>/</div>
                        </template>
                    </el-table-column>
                    <el-table-column label="房间类型">
                        <template slot-scope="scope"> {{ formatRoomType(scope.row) }} </template>
                    </el-table-column>
                    <el-table-column label="游戏类型" min-width="80">
                        <template slot-scope="scope"> {{ formatGameType(scope.row.category) }} </template>
                    </el-table-column>
                    <el-table-column prop="rule.juShu" label="局数"></el-table-column>
                    <el-table-column prop="rule.playerCount" label="人数"></el-table-column>

                    <el-table-column label="钻石" min-width="80">
                        <template slot-scope="scope"> {{ formatPayType(scope.row.rule) }} </template>
                    </el-table-column>
                    <el-table-column label="状态" min-width="80">
                        <template slot-scope="scope"> {{ formatRoomState(scope.row.roomState) }} </template>
                    </el-table-column>
                    <el-table-column prop="createAt" label="结束时间" min-width="120"></el-table-column>
                    <el-table-column fixed="right" label="操作" min-width="120">
                        <template slot-scope="scope">
                            <el-button slot="trigger" type="text" size="small" @click="DetailsClick(scope.row)"
                                >查看回放</el-button
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
import { Component, Vue, Watch } from 'vue-property-decorator'
import { apiClubCreatorGoldLog, apiClubRoomList } from '@/api/finance/finance'
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
import DatePicker from '@/components/date-picker.vue'
@Component({
    components: {
        LsPagination,
        DatePicker
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

    tableData = []

    splitTime() {
        if (this.tableData != null) {
            this.time.start_time = this.tableData[0]
            this.time.end_time = this.tableData[1]
        }
    }

    form: any = {
        _id: '',
        roomId: '',
        category: '',
        start_time: '',
        end_time: '',
        name: '',
        playerShortId: '',
        playerPhone: '',
        clubShortId: '',
        realName: '',
        juShu: '',
        playerCount: '',
        winnerPay: '',
        useClubGold: '',
        clubOwnerPay: '',
        share: '',
        roomState: ''
    }

    time: any = {
        start_time: '',
        end_time: ''
    }

    isNameSN = '' // 选择用户编号1 选择用户昵称2 手机号码3,真实姓名4
    // 分页查询
    pager: RequestPaging = new RequestPaging()

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

    formatPayType(rule: any, clubName: string) {
        if (clubName != '/') {
            return '战队主付'
        } else if (rule.hasOwnProperty('isPublic') && rule.isPublic) {
            return '/'
        } else if (rule.hasOwnProperty('clubOwnerPay') && rule.clubOwnerPay) {
            return '战队主付'
        } else if (rule.hasOwnProperty('share') && rule.share) {
            return 'AA付'
        } else if (rule.hasOwnProperty('winnerPay') && rule.winnerPay) {
            return '赢家付'
        }
        return '房主付'
    }

    // 查询按钮
    query() {
        this.pager.page = 1
        this.getUserList()
    }

    formatRoomState(state: string) {
        if (state == 'normal_last') {
            return '正常结束'
        }
        if (state == 'normal') {
            return '对局中'
        }
        if (state == 'zero_ju') {
            return '零局解散'
        }
        if (state == 'dissolve') {
            return '解散'
        }
        if (state == 'dissolve_last') {
            return '尾局解散'
        }
    }

    formatGameType(game: string) {
        if (game == 'paodekuai') {
            return '跑得快'
        }
        if (game == 'biaofen') {
            return '标分'
        }
        if (game == 'shisanshui') {
            return '十三水'
        }
        if (game == 'majiang') {
            return '麻将'
        }
        if (game == 'zhadan') {
            return '炸弹'
        }
        if (game == 'xmmajiang') {
            return '厦门麻将'
        }
    }

    formatRoomType(item: any) {
        if (!item.club) {
            return '个人房'
        }
        if (item.rule.useClubGold) {
            return '金币房'
        }
        if (item.rule.isPublic) {
            return '金豆房'
        }
        return '公共房'
    }

    DetailsClick(item: any) {
        this.$router.push({
            path: '/user/room/detail',
            query: {
                _id: item._id
            }
        })
    }

    formatInitTime() {
        // 获取当前时间
        let today = new Date()

        // 获取当日零点时间并转化为格式化字符串
        let zeroTime = new Date(today.getFullYear(), today.getMonth(), today.getDate(), 0, 0, 0).toLocaleString('zh', {
            hour12: false
        })

        // 获取当日23：59：59时间并转化为格式化字符串
        let endTime = new Date(today.getFullYear(), today.getMonth(), today.getDate(), 23, 59, 59).toLocaleString(
            'zh',
            { hour12: false }
        )

        // 输出格式化后的时间
        console.log('当日零点时间：' + zeroTime) // 2022-03-09 00:00:00
        console.log('当日23：59：59时间：' + endTime) // 2022-03-09 23:59:59
        return [zeroTime, endTime]
    }

    isFormEmpty() {
        let flag = true
        for (let prop in this.form) {
            if (this.form.hasOwnProperty(prop) && this.form[prop] != '' && !['start_time', 'end_time'].includes(prop)) {
                flag = false
            }
        }
        this.form.start_time = this.time.start_time ? this.time.start_time : flag ? this.formatInitTime()[0] : ''
        this.form.end_time = this.time.end_time ? this.time.end_time : flag ? this.formatInitTime()[1] : ''
    }

    //获取用户列表数据
    getUserList() {
        this.form.export = ''
        this.isFormEmpty()
        this.pager.request({
            callback: apiClubRoomList,
            params: {
                ...this.form
            }
        })
    }

    onExport() {
        this.form.export = 1
        apiClubRoomList(this.form).then((res: any) => {
            window.location.href = res.url
        })
    }

    // 重置按钮
    onReset() {
        this.form = {
            _id: '',
            roomId: '',
            category: '',
            start_time: '',
            end_time: '',
            name: '',
            playerShortId: '',
            playerPhone: '',
            realName: '',
            juShu: '',
            playerCount: '',
            winnerPay: '',
            useClubGold: '',
            clubOwnerPay: '',
            share: '',
            clubShortId: '',
            roomState: ''
        }
        this.getUserList()
    }

    navicatToUserByShortId(shortId: any) {
        this.$router.push({
            path: '/user/lists',
            query: {
                shortId
            }
        })
    }

    navicatToClubByShortId(shortId: any) {
        this.$router.push({
            path: '/clubs/lists',
            query: {
                shortId
            }
        })
    }

    created() {
        const query: any = this.$route.query
        if (query.clubShortId) {
            this.form.clubShortId = JSON.parse(query.clubShortId)
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
