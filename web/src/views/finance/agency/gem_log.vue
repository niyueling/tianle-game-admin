<template>
    <div class="user-withdrawal">
        <div class="ls-card">
            <el-alert class="xxl" title="温馨提示： 1.代理钻石记录。" type="info" :closable="false" show-icon>
            </el-alert>
            <div class="journal-search m-t-16">
                <el-form ref="formRef" inline :model="form" label-width="70px" size="small" class="ls-form">
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

                    <el-button size="small" type="primary" @click="getList(1)">查询</el-button>
                    <el-button size="small" @click="onReset">重置</el-button>
                    <el-button size="small" @click="onExport">导出</el-button>
                </el-form>
            </div>
        </div>
        <!-- 提现记录表 -->
        <div class="ls-withdrawal__centent ls-card m-t-16">
            <div class="list-table m-t-16">
                <el-table
                    :data="pager.lists"
                    style="width: 100%"
                    v-loading="pager.loading"
                    size="mini"
                    :header-cell-style="{ background: '#f5f8ff' }"
                >
                    <el-table-column prop="gm" label="代理编号"> </el-table-column>
                    <el-table-column prop="gmInfo.username" label="代理昵称"> </el-table-column>
                    <el-table-column prop="gmInfo.role" label="角色"> </el-table-column>
                    <el-table-column prop="gmInfo.gem" label="持有钻石"></el-table-column>
                    <el-table-column prop="note" label="操作备注"> </el-table-column>
                    <el-table-column prop="createAt" label="记录时间"> </el-table-column>
                </el-table>
            </div>
            <div class="flex row-right m-t-16 row-right">
                <ls-pagination v-model="pager" @change="getList()" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { apiAgencyGemLog, apiClubCreatorGoldLog } from '@/api/finance/finance'
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
@Component({
    components: {
        LsPagination
    }
})
export default class AccountLog extends Vue {
    /** S Data **/
    // 日期选择器
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
    // 顶部查询表单
    pager: RequestPaging = new RequestPaging()
    // 顶部查询表单
    form: any = {
        start_time: '',
        end_time: '',
        export: ''
    }
    time: any = {
        start_time: '',
        end_time: ''
    }

    splitTime() {
        if (this.tableData != null) {
            this.time.start_time = this.tableData[0]
            this.time.end_time = this.tableData[1]
        }
    }
    // 重置
    onReset() {
        this.form = {
            start_time: '',
            end_time: '',
            export: ''
        }
        this.tableData = []

        this.getList()
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

    // 资金记录
    getList(page?: number): void {
        this.form.export = ''
        this.isFormEmpty()
        page && (this.pager.page = page)
        this.pager
            .request({
                callback: apiAgencyGemLog,
                params: {
                    ...this.form
                }
            })
            .then((res: any) => {})
    }

    onExport() {
        this.form.export = 1
        apiAgencyGemLog(this.form).then((res: any) => {
            window.location.href = res.url
        })
    }

    created() {
        this.getList()
    }
}
</script>

<style lang="scss" scoped></style>
