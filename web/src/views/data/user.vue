<!-- 流量分析 -->
<template>
    <div class="user">
        <div class="ls-card ls-card-top">
            <div class="journal-search m-t-16">
                <el-form ref="formRef" inline :model="summary" label-width="80px" size="small">
                    <el-form-item label="统计时间">
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

                    <el-button size="small" type="primary" @click="getDataCenterVisit">查询</el-button>
                    <el-button size="small" type="" @click="onReset">重置</el-button>
                </el-form>
            </div>
        </div>
        <div class="ls-card m-t-16">
            <div class="card-title">数据汇总</div>
            <div class="card-content m-t-24">
                <el-row :gutter="20">
                    <el-col :span="6" class="flex-col col-center">
                        <div class="lighter m-b-8">用户人数</div>
                        <div class="font-size-30">{{ summary.user }}</div>
                    </el-col>
                    <el-col :span="6" class="flex-col col-center">
                        <div class="lighter m-b-8">新增用户</div>
                        <div class="font-size-30">{{ summary.new_user }}</div>
                    </el-col>
                </el-row>
            </div>
        </div>

        <div class="ls-card m-t-24 ls-chart--visitors">
            <div class="card-title">用户人数</div>
            <div class="content">
                <e-chart class="chart" :option="user" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import * as echarts from 'echarts/core'
import { BarChart, LineChart, PieChart } from 'echarts/charts'
import { GridComponent, TitleComponent, LegendComponent, PolarComponent } from 'echarts/components'
import { CanvasRenderer } from 'echarts/renderers'
import { apiUserAnalysis } from '@/api/data/data'
echarts.use([
    BarChart,
    GridComponent,
    CanvasRenderer,
    TitleComponent,
    LegendComponent,
    PolarComponent,
    LineChart,
    PieChart
])
@Component
export default class User extends Vue {
    /** S Data **/
    summary = {
        user: '',
        new_user: ''
    }

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

    form = {
        start_time: '',
        end_time: ''
    }

    user = {
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data: ['订单金额']
        },
        xAxis: {
            type: 'category',
            data: ['周一', '周二', '周三', '周四', '周五', '周六', '周日'],
            splitLine: {
                show: true, // 是否显示分隔线。默认数值轴显示，类目轴不显示
                interval: '1' // 坐标轴刻度标签的显示间隔，在类目轴中有效.0显示所有
            }
        },
        yAxis: {
            type: 'value'
            // type: "category"
        },
        series: [
            {
                name: '订单金额',
                type: 'line',
                stack: '总量',
                data: []
            }
        ]
    }

    splitTime() {
        if (this.tableData != null) {
            this.form.start_time = this.tableData[0]
            this.form.end_time = this.tableData[1]
        }
    }

    onReset() {
        this.form.start_time = ''
        this.form.end_time = ''
        this.getDataCenterVisit()
    }

    // 获取数据
    getDataCenterVisit() {
        // 清空echarts 数据
        this.user.xAxis.data = []

        apiUserAnalysis(this.form).then(res => {
            this.summary = res.summary
            this.user.xAxis.data = res.user.date

            res.user.list.forEach((item: any, index: number) => {
                this.user.series[index].data = item.data
                this.user.series[index].name = item.name
                this.user.legend.data[index] = item.name
            })
        })
    }

    created() {
        this.getDataCenterVisit()
    }
}
</script>

<style lang="scss" scoped>
.user {
    .ls-card {
        .card-title {
            font-size: 14px;
            font-weight: 500;
        }
    }

    .ls-card-top {
        padding-bottom: 0;
    }

    .ls-chart--turnover,
    .ls-chart--visitors {
        height: 460px;
        min-width: 500px;

        .chart {
            height: 400px;
        }
    }
}
</style>
