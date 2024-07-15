<template>
    <div class="ls-home">
        <el-row :gutter="16" type="flex" class="ls-home__top col-stretch">
            <el-col :span="6">
                <div class="ls-card ls-top__store">
                    <div class="title weight-500">后台信息</div>
                    <div class="content">
                        <div class="flex">
                            <el-image
                                fit="scale-down"
                                style="width: 58px; height: 58px; border-radius: 50%"
                                :src="indexData.shop_info.logo"
                            >
                            </el-image>
                            <div class="m-l-20">
                                <div class="store-name sm flex weight-600">
                                    <span>{{ indexData.shop_info.name }}</span>
                                </div>
                                <div class="store-status m-t-14 flex">
                                    <span class="label m-r-20">管理员</span>
                                    <span>{{ indexData.shop_info.admin_name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </el-col>
            <el-col :span="18">
                <div class="ls-card ls-top__data">
                    <div class="title">今日数据</div>
                    <div class="content">
                        <el-row :gutter="20">
                            <el-col :span="6">
                                <div class="lighter">新增用户（人）</div>
                                <div class="m-t-8 font-size-30">
                                    {{ indexData.today.today_user_num }}
                                </div>
                            </el-col>
                            <el-col :span="6">
                                <div class="lighter">开房数（间）</div>
                                <div class="m-t-8 font-size-30">
                                    {{ indexData.today.today_room_num }}
                                </div>
                            </el-col>
                            <el-col :span="6">
                                <div class="lighter">充值金额（元）</div>
                                <div class="m-t-8 font-size-30">
                                    {{ indexData.today.recharge_amount }}
                                </div>
                            </el-col>
                            <el-col :span="6">
                                <div class="lighter">访问量（人）</div>
                                <div class="m-t-8 font-size-30">
                                    {{ indexData.today.today_visitor_num }}
                                </div>
                            </el-col>
                        </el-row>
                    </div>
                </div>
            </el-col>
        </el-row>
        <div class="ls-card ls-home_todo m-t-16">
            <div class="title">数据统计</div>
            <div class="content">
                <el-row :gutter="20">
                    <el-col :span="5" style="border-right: solid #cccccc 1px">
                        <div class="pointer">
                            <div class="lighter" style="font-weight: bold; color: black">新增用户（人）</div>
                            <div class="m-t-8 font-size-18">
                                今日新增：{{ indexData.pending.add_user.today_num || 0 }} 人
                            </div>
                            <div class="m-t-8 font-size-18">
                                昨日新增：{{ indexData.pending.add_user.yestoday_num || 0 }} 人
                            </div>
                            <div class="m-t-8 font-size-18">
                                今日相比昨日{{
                                    formatInfoText(indexData.pending.add_user.grap_num) +
                                    (indexData.pending.add_user.grap_num != 0 ? '：' : '')
                                }}
                                {{
                                    indexData.pending.add_user.grap_num != 0
                                        ? indexData.pending.add_user.grap_num + '人'
                                        : ''
                                }}
                            </div>
                        </div>
                    </el-col>
                    <el-col :span="5" style="border-right: solid #cccccc 1px">
                        <div class="pointer">
                            <div class="lighter" style="font-weight: bold; color: black">开房数（间）</div>
                            <div class="m-t-8 font-size-18">
                                今日新增：{{ indexData.pending.add_room.today_num || 0 }} 间
                            </div>
                            <div class="m-t-8 font-size-18">
                                昨日新增：{{ indexData.pending.add_room.yestoday_num || 0 }} 间
                            </div>
                            <div class="m-t-8 font-size-18">
                                今日相比昨日{{
                                    formatInfoText(indexData.pending.add_room.grap_num) +
                                    (indexData.pending.add_room.grap_num != 0 ? '：' : '')
                                }}
                                {{
                                    indexData.pending.add_room.grap_num != 0
                                        ? indexData.pending.add_room.grap_num + '间'
                                        : ''
                                }}
                            </div>
                        </div>
                    </el-col>
                    <el-col :span="5" style="border-right: solid #cccccc 1px">
                        <div class="pointer">
                            <div class="lighter" style="font-weight: bold; color: black">访问量（人）</div>
                            <div class="m-t-8 font-size-18">
                                今日新增：{{ indexData.pending.visit.today_num || 0 }} 人
                            </div>
                            <div class="m-t-8 font-size-18">
                                昨日新增：{{ indexData.pending.visit.yestoday_num || 0 }} 人
                            </div>
                            <div class="m-t-8 font-size-18">
                                今日相比昨日{{
                                    formatInfoText(indexData.pending.visit.grap_num) +
                                    (indexData.pending.visit.grap_num != 0 ? '：' : '')
                                }}
                                {{
                                    indexData.pending.visit.grap_num != 0 ? indexData.pending.visit.grap_num + '人' : ''
                                }}
                            </div>
                        </div>
                    </el-col>
                    <el-col :span="5" style="border-right: solid #cccccc 1px">
                        <div class="pointer">
                            <div class="lighter" style="font-weight: bold; color: black">用户在线充值（元）</div>
                            <div class="m-t-8 font-size-18">
                                今日新增：{{ indexData.pending.online_recharge.today_num || 0 }} 元
                            </div>
                            <div class="m-t-8 font-size-18">
                                昨日新增：{{ indexData.pending.online_recharge.yestoday_num || 0 }} 元
                            </div>
                            <div class="m-t-8 font-size-18">
                                今日相比昨日{{
                                    formatInfoText(indexData.pending.online_recharge.grap_num) +
                                    (indexData.pending.online_recharge.grap_num != 0 ? '：' : '')
                                }}
                                {{
                                    indexData.pending.online_recharge.grap_num != 0
                                        ? indexData.pending.online_recharge.grap_num + '元'
                                        : ''
                                }}
                            </div>
                        </div>
                    </el-col>
                    <el-col :span="4" style="border-right: solid #cccccc 1px">
                        <div class="pointer">
                            <div class="lighter" style="font-weight: bold; color: black">后台用户充值（钻石）</div>
                            <div class="m-t-8 font-size-18">
                                今日新增：{{ indexData.pending.back_recharge.today_num || 0 }} 钻石
                            </div>
                            <div class="m-t-8 font-size-18">
                                昨日新增：{{ indexData.pending.back_recharge.yestoday_num || 0 }} 钻石
                            </div>
                            <div class="m-t-8 font-size-18">
                                今日相比昨日{{
                                    formatInfoText(indexData.pending.back_recharge.grap_num) +
                                    (indexData.pending.back_recharge.grap_num != 0 ? '：' : '')
                                }}
                                {{
                                    indexData.pending.back_recharge.grap_num != 0
                                        ? indexData.pending.back_recharge.grap_num + '钻石'
                                        : ''
                                }}
                            </div>
                        </div>
                    </el-col>
                </el-row>
            </div>
        </div>

        <div class="ls-card ls-home_todo m-t-16">
            <div class="content">
                <el-row :gutter="20">
                    <el-col :span="5" style="border-right: solid #cccccc 1px">
                        <div class="pointer">
                            <div class="lighter" style="font-weight: bold; color: black">后台金豆充值（金豆）</div>
                            <div class="m-t-8 font-size-18">
                                今日新增：{{ indexData.pending.gold_recharge.today_num || 0 }} 金豆
                            </div>
                            <div class="m-t-8 font-size-18">
                                昨日新增：{{ indexData.pending.gold_recharge.yestoday_num || 0 }} 金豆
                            </div>
                            <div class="m-t-8 font-size-18">
                                今日相比昨日{{
                                    formatInfoText(indexData.pending.gold_recharge.grap_num) +
                                    (indexData.pending.gold_recharge.grap_num != 0 ? '：' : '')
                                }}
                                {{
                                    indexData.pending.gold_recharge.grap_num != 0
                                        ? indexData.pending.gold_recharge.grap_num + '金豆'
                                        : ''
                                }}
                            </div>
                        </div>
                    </el-col>
                    <!--                    <el-col :span="4" style="border-right:solid #cccccc 1px">-->
                    <!--                        <div class="pointer">-->
                    <!--                            <div class="lighter" style="font-weight: bold; color: black;">绑定手机赠送钻石（钻石）</div>-->
                    <!--                            <div class="m-t-8 font-size-18">-->
                    <!--                                今日新增：{{ indexData.pending.bind_mobile.today_num || 0 }} 钻石-->
                    <!--                            </div>-->
                    <!--                            <div class="m-t-8 font-size-18">-->
                    <!--                                昨日新增：{{ indexData.pending.bind_mobile.yestoday_num || 0 }} 钻石-->
                    <!--                            </div>-->
                    <!--                            <div class="m-t-8 font-size-18">-->
                    <!--                                今日相比昨日{{ formatInfoText(indexData.pending.bind_mobile.grap_num) + (indexData.pending.bind_mobile.grap_num != 0 ? "：" : "") }} {{ indexData.pending.bind_mobile.grap_num != 0 ? indexData.pending.bind_mobile.grap_num + "钻石" : "" }}-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    </el-col>-->
                    <!--                    <el-col :span="4" style="border-right:solid #cccccc 1px">-->
                    <!--                        <div class="pointer">-->
                    <!--                            <div class="lighter" style="font-weight: bold; color: black;">实名认证赠送钻石（钻石）</div>-->
                    <!--                            <div class="m-t-8 font-size-18">-->
                    <!--                                今日新增：{{ indexData.pending.auth.today_num || 0 }} 钻石-->
                    <!--                            </div>-->
                    <!--                            <div class="m-t-8 font-size-18">-->
                    <!--                                昨日新增：{{ indexData.pending.auth.yestoday_num || 0 }} 钻石-->
                    <!--                            </div>-->
                    <!--                            <div class="m-t-8 font-size-18">-->
                    <!--                                今日相比昨日{{ formatInfoText(indexData.pending.auth.grap_num) + (indexData.pending.auth.grap_num != 0 ? "：" : "") }} {{ indexData.pending.auth.grap_num != 0 ? indexData.pending.auth.grap_num + "钻石" : "" }}-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    </el-col>-->

                    <!--                    <el-col :span="4" style="border-right:solid #cccccc 1px">-->
                    <!--                        <div class="pointer">-->
                    <!--                            <div class="lighter" style="font-weight: bold; color: black;">钻石充值（元）</div>-->
                    <!--                            <div class="m-t-8 font-size-18">-->
                    <!--                                今日新增：{{ indexData.pending.diamond_recharge.today_num || 0 }} 元-->
                    <!--                            </div>-->
                    <!--                            <div class="m-t-8 font-size-18">-->
                    <!--                                昨日新增：{{ indexData.pending.diamond_recharge.yestoday_num || 0 }} 元-->
                    <!--                            </div>-->
                    <!--                            <div class="m-t-8 font-size-18">-->
                    <!--                                今日相比昨日{{ formatInfoText(indexData.pending.diamond_recharge.grap_num) + (indexData.pending.diamond_recharge.grap_num != 0 ? "：" : "") }} {{ indexData.pending.diamond_recharge.grap_num != 0 ? indexData.pending.diamond_recharge.grap_num + "元" : "" }}-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    </el-col> -->
<!--                    <el-col :span="4" style="border-right: solid #cccccc 1px">-->
<!--                        <div class="pointer">-->
<!--                            <div class="lighter" style="font-weight: bold; color: black">红包领取（元）</div>-->
<!--                            <div class="m-t-8 font-size-18">-->
<!--                                今日新增：{{ indexData.pending.red_packet.today_num || 0 }} 元-->
<!--                            </div>-->
<!--                            <div class="m-t-8 font-size-18">-->
<!--                                昨日新增：{{ indexData.pending.red_packet.yestoday_num || 0 }} 元-->
<!--                            </div>-->
<!--                            <div class="m-t-8 font-size-18">-->
<!--                                今日相比昨日{{-->
<!--                                    formatInfoText(indexData.pending.red_packet.grap_num) +-->
<!--                                    (indexData.pending.red_packet.grap_num != 0 ? '：' : '')-->
<!--                                }}-->
<!--                                {{-->
<!--                                    indexData.pending.red_packet.grap_num != 0-->
<!--                                        ? indexData.pending.red_packet.grap_num + '元'-->
<!--                                        : ''-->
<!--                                }}-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </el-col>-->
<!--                    <el-col :span="4" style="border-right: solid #cccccc 1px">-->
<!--                        <div class="pointer">-->
<!--                            <div class="lighter" style="font-weight: bold; color: black">红包提现（元）</div>-->
<!--                            <div class="m-t-8 font-size-18">-->
<!--                                今日新增：{{ indexData.pending.red_packet_withdraw.today_num || 0 }} 元-->
<!--                            </div>-->
<!--                            <div class="m-t-8 font-size-18">-->
<!--                                昨日新增：{{ indexData.pending.red_packet_withdraw.yestoday_num || 0 }} 元-->
<!--                            </div>-->
<!--                            <div class="m-t-8 font-size-18">-->
<!--                                今日相比昨日{{-->
<!--                                    formatInfoText(indexData.pending.red_packet_withdraw.grap_num) +-->
<!--                                    (indexData.pending.red_packet_withdraw.grap_num != 0 ? '：' : '')-->
<!--                                }}-->
<!--                                {{-->
<!--                                    indexData.pending.red_packet_withdraw.grap_num != 0-->
<!--                                        ? indexData.pending.red_packet_withdraw.grap_num + '元'-->
<!--                                        : ''-->
<!--                                }}-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </el-col>-->
                    <el-col :span="5" style="border-right: solid #cccccc 1px">
                        <div class="pointer">
                            <div class="lighter" style="font-weight: bold; color: black">实名认证（钻石）</div>
                            <div class="m-t-8 font-size-18">
                                今日新增：{{ indexData.pending.auth.today_num || 0 }} 钻石
                            </div>
                            <div class="m-t-8 font-size-18">
                                昨日新增：{{ indexData.pending.auth.yestoday_num || 0 }} 钻石
                            </div>
                            <div class="m-t-8 font-size-18">
                                今日相比昨日{{
                                    formatInfoText(indexData.pending.auth.grap_num) +
                                    (indexData.pending.auth.grap_num != 0 ? '：' : '')
                                }}
                                {{
                                    indexData.pending.auth.grap_num != 0 ? indexData.pending.auth.grap_num + '钻石' : ''
                                }}
                            </div>
                        </div>
                    </el-col>
                  <el-col :span="5" style="border-right: solid #cccccc 1px">
                    <div class="pointer">
                      <div class="lighter" style="font-weight: bold; color: black">绑定手机（钻石）</div>
                      <div class="m-t-8 font-size-18">
                        今日新增：{{ indexData.pending.bind_mobile.today_num || 0 }} 钻石
                      </div>
                      <div class="m-t-8 font-size-18">
                        昨日新增：{{ indexData.pending.bind_mobile.yestoday_num || 0 }} 钻石
                      </div>
                      <div class="m-t-8 font-size-18">
                        今日相比昨日{{
                          formatInfoText(indexData.pending.bind_mobile.grap_num) +
                          (indexData.pending.bind_mobile.grap_num != 0 ? '：' : '')
                        }}
                        {{
                          indexData.pending.bind_mobile.grap_num != 0
                              ? indexData.pending.bind_mobile.grap_num + '钻石'
                              : ''
                        }}
                      </div>
                    </div>
                  </el-col>
                  <el-col :span="5" style="border-right: solid #cccccc 1px">
                    <div class="pointer">
                      <div class="lighter" style="font-weight: bold; color: black">微信分享（钻石）</div>
                      <div class="m-t-8 font-size-18">
                        今日新增：{{ indexData.pending.wechat_share.today_num || 0 }} 钻石
                      </div>
                      <div class="m-t-8 font-size-18">
                        昨日新增：{{ indexData.pending.wechat_share.yestoday_num || 0 }} 钻石
                      </div>
                      <div class="m-t-8 font-size-18">
                        今日相比昨日{{
                          formatInfoText(indexData.pending.wechat_share.grap_num) +
                          (indexData.pending.wechat_share.grap_num != 0 ? '：' : '')
                        }}
                        {{
                          indexData.pending.wechat_share.grap_num != 0
                              ? indexData.pending.wechat_share.grap_num + '钻石'
                              : ''
                        }}
                      </div>
                    </div>
                  </el-col>
                </el-row>
            </div>
        </div>

        <el-row :gutter="16" class="ls-home__chart m-t-16">
            <el-col :span="8">
                <div class="ls-card ls-chart--turnover">
                    <div class="title">
                        近15天充值（元）<span @click="toPages('/data/recharge')" style="color: #3967ff; cursor: pointer"
                            >更多</span
                        >
                    </div>
                    <div class="content">
                        <e-chart class="chart" :option="recharge" />
                    </div>
                </div>
            </el-col>
            <el-col :span="8">
                <div class="ls-card ls-chart--visitors">
                    <div class="title">
                        近15天访客数（人）<span
                            @click="toPages('/data/visitor')"
                            style="color: #3967ff; cursor: pointer"
                            >更多</span
                        >
                    </div>
                    <div class="content">
                        <e-chart class="chart" :option="visitor" />
                    </div>
                </div>
            </el-col>
            <el-col :span="8">
                <div class="ls-card ls-chart--turnover">
                    <div class="title">
                        近15天开房（间）<span @click="toPages('/data/room')" style="color: #3967ff; cursor: pointer"
                            >更多</span
                        >
                    </div>
                    <div class="content">
                        <e-chart class="chart" :option="room" id="myEcharts" @click="echartClick" />
                    </div>
                </div>
            </el-col>
        </el-row>
        <el-row :gutter="16" class="ls-home__rank m-t-16" v-show="false">
            <el-col :span="12">
                <div class="ls-card">
                    <div class="flex row-right m-t-16 row-right">
                        <ls-pagination v-model="pagerTopUser" @change="getWorkbenchIndexData" />
                    </div>
                </div>
            </el-col>
        </el-row>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import * as echarts from 'echarts/core'
import { BarChart, LineChart } from 'echarts/charts'
import { apiWorkbenchIndex } from '@/api/home'
import LsPagination from '@/components/ls-pagination.vue'
import { GridComponent, TitleComponent, LegendComponent, PolarComponent } from 'echarts/components'
// 注意，新的接口中默认不再包含 Canvas 渲染器，需要显示引入，如果需要使用 SVG 渲染模式则使用 SVGRenderer
import { CanvasRenderer } from 'echarts/renderers'
import { RequestPaging } from '@/utils/util'

echarts.use([BarChart, GridComponent, CanvasRenderer, TitleComponent, LegendComponent, PolarComponent, LineChart])

@Component({
    components: {
        LsPagination
    }
})
export default class Home extends Vue {
    visitor = {
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data: ['炸弹', '麻将', '标分', '跑得快', '十三水']
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
            // type: "category"
        },
        series: [
            {
                name: '炸弹',
                type: 'line',
                data: []
            },
            {
                name: '麻将',
                type: 'line',
                data: []
            },
            {
                name: '标分',
                type: 'line',
                data: []
            },
            {
                name: '跑得快',
                type: 'line',
                data: []
            },
            {
                name: '十三水',
                type: 'line',
                data: []
            }
        ]
    }

    recharge = {
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data: ['用户自助充值', '用户后台充值', '代理自助充值', '代理后台充值']
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
        },
        series: [
            {
                name: '充值金额',
                type: 'line',
                data: []
            },
            {
                name: '充值金额',
                type: 'line',
                data: []
            },
            {
                name: '充值金额',
                type: 'line',
                data: []
            },
            {
                name: '充值金额',
                type: 'line',
                data: []
            }
        ]
    }

    room = {
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data: ['房间统计']
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
        },
        series: [
            {
                name: '房间统计',
                type: 'line',
                data: []
            }
        ]
    }

    indexData: any = {
        shop_info: {
            logo: ''
        },
        today: {
            today_order_num: '',
            today_order_amount: '',
            today_new_user: '',
            today_visitor: ''
        },
        pending: {
            add_user: {
                today_num: 0,
                yestoday_num: 0,
                grap_num: -1
            },
            add_room: {
                today_num: 0,
                yestoday_num: 0,
                grap_num: -1
            },
            agent: {
                today_num: 0,
                yestoday_num: 0,
                grap_num: -1
            },
            visit: {
                today_num: 0,
                yestoday_num: 0,
                grap_num: -1
            },
            online_recharge: {
                today_num: 0,
                yestoday_num: 0,
                grap_num: -1
            },
            back_recharge: {
                today_num: 0,
                yestoday_num: 0,
                grap_num: -1
            },
            bind_mobile: {
                today_num: 0,
                yestoday_num: 0,
                grap_num: -1
            },
            auth: {
                today_num: 0,
                yestoday_num: 0,
                grap_num: -1
            },
            wechat_share: {
                today_num: 0,
                yestoday_num: 0,
                grap_num: -1
            },
            fk_recharge: {
                today_num: 0,
                yestoday_num: 0,
                grap_num: -1
            },
            gold_recharge: {
                today_num: 0,
                yestoday_num: 0,
                grap_num: -1
            },
            fk_consume: {
                today_num: 0,
                yestoday_num: 0,
                grap_num: -1
            },
            red_packet: {
                today_num: 0,
                yestoday_num: 0,
                grap_num: -1
            },
            red_packet_withdraw: {
                today_num: 0,
                yestoday_num: 0,
                grap_num: -1
            },
            wait_shipped: '',
            wait_audit: '',
            wait_reply: '',
            no_stock_goods: ''
        }
    }

    pagerTopUser: RequestPaging = new RequestPaging()

    getWorkbenchIndexData() {
        apiWorkbenchIndex({})
            .then(res => {
                this.indexData = res
                this.recharge.xAxis.data = res.business15.date
                this.visitor.xAxis.data = res.visitor15.date
                this.room.xAxis.data = res.room15.date

                res.business15.list.forEach((item: any, index: number) => {
                    this.recharge.series[index].data = item.data
                    this.recharge.series[index].name = item.name
                    this.recharge.legend.data[index] = item.name
                })

                res.visitor15.list.forEach((item: any, index: number) => {
                    this.visitor.series[index].data = item.data
                    this.visitor.series[index].name = item.name
                })

                res.room15.list.forEach((item: any, index: number) => {
                    this.room.series[index].data = item.data
                    this.room.series[index].name = item.name
                })
            })
            .catch(e => {
                this.$message.error(e)
            })
    }

    echartClick(params: any) {
        console.log(params)
        this.$router.push({
            path: '/finance/user/room',
            query: {
                dayId: params.name
            }
        })
    }

    toPages(url: any) {
        this.$router.push({
            path: url,
            query: {}
        })
    }

    formatInfoText(num: number): string {
        if (num > 0) {
            return '新增'
        }
        if (num == 0) {
            return '持平'
        }
        return '递减'
    }

    created() {
        this.getWorkbenchIndexData()
    }
}
</script>
<style lang="scss" scoped>
.ls-card {
    .title {
        font-size: 14px;
        font-weight: bold;
        padding-bottom: 20px;
        border-bottom: 1px solid $--background-color-base;
    }
    .content {
        margin-top: 20px;
    }
}
.icon {
    width: 25px;
    height: 25px;
    color: #333;
    border-radius: 2px;
    background: #f5f5f5;
    font-family: 'PingFang SC';
    font-weight: normal;
    font-size: 12px;
    line-height: 25px;
    text-align: center;
}
.ls-home {
    .ls-home__top {
        .ls-top__store {
            .store-time {
                // padding: 3px 6px;
                display: inline-block;
                margin-left: 80px;
                background: #ebf1ff;
            }
            .label {
                flex: none;
                color: $--color-text-secondary;
            }
        }
        .ls-top__data {
            height: 100%;
            box-sizing: border-box;
        }
    }
    .ls-home_todo {
        margin-right: 0;
    }
    .ls-home__chart {
        .ls-chart--turnover,
        .ls-chart--visitors {
            height: 460px;
            // min-width: 800px;
            .chart {
                // width: 900px !important;
                height: 400px;
            }
        }
    }

    .lighter {
        font-weight: bold;
    }
}
</style>
