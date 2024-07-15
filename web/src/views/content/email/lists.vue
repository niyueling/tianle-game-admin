<template>
    <div class="help_manage">
        <div class="ls-card">
            <!-- 操作提示 -->
            <el-alert
                title="温馨提示：1、平台发布的邮件，包含普通邮件和礼物邮件。用户可在游戏的邮件功能查看；"
                type="info"
                show-icon
                :closable="false"
            />
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
                    <el-form-item label="标题">
                        <el-input class="ls-select-keyword" v-model="form.title" placeholder="请输入标题"></el-input>
                    </el-form-item>
                    <el-form-item label="读取状态">
                        <div class="flex">
                            <el-select style="width: 120px" v-model="form.state" placeholder="全部">
                                <el-option label="全部" value></el-option>
                                <el-option label="未读" :value="1"></el-option>
                                <el-option label="已读" :value="2"></el-option>
                            </el-select>
                        </div>
                    </el-form-item>
                    <el-form-item label="礼物状态">
                        <div class="flex">
                            <el-select style="width: 120px" v-model="form.giftState" placeholder="全部">
                                <el-option label="全部" value></el-option>
                                <el-option label="未领" :value="1"></el-option>
                                <el-option label="已领" :value="2"></el-option>
                            </el-select>
                        </div>
                    </el-form-item>
                    <el-form-item label="邮件类型">
                        <div class="flex">
                            <el-select style="width: 120px" v-model="form.type" placeholder="全部">
                                <el-option label="全部" value></el-option>
                                <el-option label="普通邮件" value="message"></el-option>
                                <el-option label="礼物邮件" value="gift"></el-option>
                            </el-select>
                        </div>
                    </el-form-item>
                    <el-form-item label="时间">
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
                </el-form>
            </div>
        </div>

        <div class="ls-card m-t-24">
            <!-- 新增邮件按钮 -->
            <div class="add-btn">
                <ls-email-add @refresh="getEmailList" title="新增邮件" action="add">
                    <el-button type="primary" slot="trigger" size="mini">新增邮件</el-button>
                </ls-email-add>
            </div>

            <!-- 公告数据列表 -->
            <div class="m-t-24">
                <el-table :data="pager.lists" v-loading="pager.loading" size="mini" style="width: 100%">
                    <el-table-column sortable prop="_id" label="ID" />
                    <el-table-column sortable prop="typeStr" label="类型" />
                    <el-table-column sortable prop="playerShortId" label="用户ID" />
                    <el-table-column sortable prop="username" label="用户昵称" />
                    <el-table-column sortable prop="phone" label="手机号" />
                    <el-table-column prop="title" label="标题"></el-table-column>
                    <el-table-column prop="content" label="内容" min-width="200"></el-table-column>
                    <el-table-column sortable prop="stateStr" label="读取状态" />
                    <el-table-column sortable prop="giftStateStr" label="领取状态" />
                    <el-table-column sortable prop="createAt" label="创建时间" />
                    <el-table-column label="操作" min-width="200">
                        <template slot-scope="scope">
                            <ls-dialog title="删除邮件" class="m-l-10 inline" @confirm="onEmailDel(scope.row)">
                                <el-button type="text" size="mini" slot="trigger">删除 </el-button>
                            </ls-dialog>
                        </template>
                    </el-table-column>
                </el-table>

                <!-- 分页 -->
                <div class="m-t-24 flex row-right">
                    <ls-pagination v-model="pager" @change="getEmailList" />
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import LsDialog from '@/components/ls-dialog.vue'
import LsPagination from '@/components/ls-pagination.vue'
import { apiEmailLists, apiEmailDel } from '@/api/content/email'
import { RequestPaging } from '@/utils/util'
import LsEmailAdd from '@/components/ls-email-add.vue'
import DatePicker from '@/components/date-picker.vue'

@Component({
    components: {
        LsEmailAdd,
        LsDialog,
        LsPagination,
        DatePicker
    }
})
export default class HelpManage extends Vue {
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
    form = {
        title: '',
        state: '',
        start_time: '',
        end_time: '',
        name: '',
        playerShortId: '',
        playerPhone: '',
        realName: '',
        giftState: '',
        type: ''
    }

    isNameSN = '' // 选择用户编号1 选择用户昵称2 手机号码3,真实姓名4

    // 分页
    pager: RequestPaging = new RequestPaging()

    splitTime() {
        if (this.tableData != null) {
            this.form.start_time = this.tableData[0]
            this.form.end_time = this.tableData[1]
        }
    }

    // 获取公告管理列表
    getEmailList() {
        this.pager
            .request({
                callback: apiEmailLists,
                params: {
                    ...this.form
                }
            })
            .catch(() => {
                this.$message.error('数据请求失败，刷新重载!')
            })
    }

    // 删除公告文章
    onEmailDel(data: any) {
        apiEmailDel({ _id: data._id }).then(() => {
            this.getEmailList()
        })
    }

    // 重置按钮
    onReset() {
        this.form = {
            title: '',
            state: '',
            start_time: '',
            end_time: '',
            name: '',
            playerShortId: '',
            playerPhone: '',
            realName: '',
            giftState: '',
            type: ''
        }
        this.getEmailList()
    }

    // 查询按钮
    query() {
        this.pager.page = 1
        this.getEmailList()
    }

    created() {
        this.getEmailList()
    }
}
</script>

<style lang="scss" scoped></style>
