<!-- 用户概述 -->
<template>
    <div class="user-profile">
        <div class="ls-card">
            <div class="card-title">用户数据</div>
            <div class="card-content m-t-24">
                <el-row :gutter="20">
                    <el-col :span="6" class="flex-col col-center">
                        <div class="lighter m-b-8">用户数</div>
                        <div class="font-size-30">
                            {{ UserData.user_count }}
                        </div>
                    </el-col>
                    <el-col :span="6" class="flex-col col-center">
                        <div class="lighter m-b-8">今日新增用户数</div>
                        <div class="font-size-30">
                            {{ UserData.user_new_count }}
                        </div>
                    </el-col>
                    <el-col :span="6" class="flex-col col-center">
                        <div class="lighter m-b-8">今日开房数</div>
                        <div class="font-size-30">
                            {{ UserData.purchase_count }}
                        </div>
                    </el-col>
                </el-row>
            </div>
        </div>

        <div class="ls-card m-t-16">
            <div class="card-title">新增用户数</div>
            <div class="card-content m-t-24 ls-chart--visitors">
                <div class="content">
                    <e-chart class="chart" :option="option" />
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import * as echarts from 'echarts/core'
import { BarChart, LineChart } from 'echarts/charts'
import { GridComponent, TitleComponent, LegendComponent, PolarComponent } from 'echarts/components'
import { CanvasRenderer } from 'echarts/renderers'
import { apiUserIndex } from '@/api/user/user'
echarts.use([BarChart, GridComponent, CanvasRenderer, TitleComponent, LegendComponent, PolarComponent, LineChart])
@Component
export default class UserProfile extends Vue {
    /** S Data **/
    UserData = {
        echarts_data: []
    }
    tableData = []
    option = {
        xAxis: {
            type: 'category',
            data: [0]
        },
        yAxis: {
            type: 'value'
        },
        legend: {
            data: ['人数']
        },
        itemStyle: {
            // 点的颜色。
            color: 'red'
        },
        tooltip: {
            trigger: 'axis'
        },
        series: [
            {
                name: '人数',
                data: [0],
                type: 'line'
                //smooth: true,
            }
        ]
    }
    /** E Data **/

    /** S Methods **/
    apiUserIndexFunc() {
        this.option.xAxis.data = []
        apiUserIndex()
            .then(res => {
                this.UserData = res

                // 清空echarts 数据
                this.option.xAxis.data = []
                this.option.series[0].data = []

                // 写入从后台拿来的数据
                this.UserData.echarts_data.forEach((item: any) => {
                    this.option.xAxis.data.push(item.day)
                    this.option.series[0].data.push(item.user_new_count)
                })
            })
            .catch(() => {
                this.$message.error('请求数据失败，请刷新重载!')
            })
    }
    /** E Methods **/

    /** S Life Cycle **/
    created() {
        // this.apiUserIndexFunc()
    }
    mounted() {
        this.apiUserIndexFunc()
    }
    /** E Life Cycle **/
}
</script>

<style lang="scss" scoped>
.ls-card {
    .card-title {
        font-size: 14px;
        font-weight: 500;
        padding-bottom: 20px;
        border-bottom: 1px solid $--background-color-base;
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
