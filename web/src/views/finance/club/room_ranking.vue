<template>
    <div class="user-withdrawal">
        <div class="ls-card">
            <el-alert
                class="xxl"
                title="温馨提示： 俱乐部房间排行榜，可以查看每日不同子游戏各个俱乐部的参与情况。俱乐部游戏排行切换标签页后请等待3s刷新"
                type="info"
                :closable="false"
                show-icon
            />
        </div>

        <div id="root" style="height: 100%">
            <div class="center">
                <el-button @click="prev"><i class="el-icon-arrow-left"></i></el-button>
                <div
                    class="date-item inline"
                    :class="{ selected: item.selected }"
                    v-for="(item, index) in currentDate"
                    :key="index"
                    @click="selectItem(item.date)"
                >
                    <div class="date">{{ item.date }}</div>
                </div>
                <el-button @click="next"><i class="el-icon-arrow-right"></i></el-button>
            </div>

            <div style="text-align: center; width: 80%">
                <div class="center">
                    <el-collapse v-model="activeNames" @change="handleChange" style="margin-left: 25%">
                        <el-collapse-item name="zhadan">
                            <template slot="title" style="left: 10px">
                                <i class="header-icon el-icon-info" style="margin-right: 10px"></i
                                >{{ zhadanInfo.title }}
                            </template>
                            <el-row :gutter="12">
                                <el-col :span="24">
                                    <el-card shadow="always">
                                        入榜数量: {{ zhadanInfo.count }} / 开房数量: {{ zhadanInfo.sum }} / 领取数量:
                                        {{ zhadanInfo.gem }}
                                    </el-card>
                                </el-col>
                            </el-row>
                            <div
                                class="rank-content"
                                v-for="(item, index) in zhadan"
                                :key="index"
                                v-if="zhadanInfo.count > 0"
                            >
                                <img height="40" width="40" :src="rankImage + ++index" class="rank-content-image" />
                                <div style="font-size: 15px">
                                    {{ item.clubName }} | {{ item.day }} 日房间[ {{ item.sum }} ] | 已领钻石:{{
                                        item.getGem
                                    }}
                                </div>
                                <div class="rank-content-club">
                                    俱乐部ID: {{ item.clubId }} | 创建时间:{{ item.createAt }} | ⭕️主:{{
                                        item.ownerName
                                    }}
                                    | ⭕️主ID:{{ item.ownerId }}
                                </div>
                            </div>
                        </el-collapse-item>
                        <el-collapse-item name="biaofen">
                            <template slot="title" style="left: 10px">
                                <i class="header-icon el-icon-info" style="margin-right: 10px"></i
                                >{{ biaofenInfo.title }}
                            </template>
                            <el-row :gutter="12">
                                <el-col :span="24">
                                    <el-card shadow="always">
                                        入榜数量: {{ biaofenInfo.count }} / 开房数量: {{ biaofenInfo.sum }} / 领取数量:
                                        {{ biaofenInfo.gem }}
                                    </el-card>
                                </el-col>
                            </el-row>
                            <div
                                class="rank-content"
                                v-for="(item, index) in biaofen"
                                :key="index"
                                v-if="biaofenInfo.count > 0"
                            >
                                <img height="40" width="40" :src="rankImage + ++index" class="rank-content-image" />
                                <div style="font-size: 15px">
                                    {{ item.clubName }} | {{ item.day }} 日房间[ {{ item.sum }} ] | 已领钻石:{{
                                        item.getGem
                                    }}
                                </div>
                                <div class="rank-content-club">
                                    俱乐部ID: {{ item.clubId }} | 创建时间:{{ item.createAt }} | ⭕️主:{{
                                        item.ownerName
                                    }}
                                    | ⭕️主ID:{{ item.ownerId }}
                                </div>
                            </div>
                        </el-collapse-item>
                        <el-collapse-item name="majiang">
                            <template slot="title" style="left: 10px">
                                <i class="header-icon el-icon-info" style="margin-right: 10px"></i
                                >{{ majiangInfo.title }}
                            </template>
                            <el-row :gutter="12">
                                <el-col :span="24">
                                    <el-card shadow="always">
                                        入榜数量: {{ majiangInfo.count }} / 开房数量: {{ majiangInfo.sum }} / 领取数量:
                                        {{ majiangInfo.gem }}
                                    </el-card>
                                </el-col>
                            </el-row>
                            <div
                                class="rank-content"
                                v-for="(item, index) in majiang"
                                :key="index"
                                v-if="majiangInfo.count > 0"
                            >
                                <img height="40" width="40" :src="rankImage + ++index" class="rank-content-image" />
                                <div style="font-size: 15px">
                                    {{ item.clubName }} | {{ item.day }} 日房间[ {{ item.sum }} ] | 已领钻石:{{
                                        item.getGem
                                    }}
                                </div>
                                <div class="rank-content-club">
                                    俱乐部ID: {{ item.clubId }} | 创建时间:{{ item.createAt }} | ⭕️主:{{
                                        item.ownerName
                                    }}
                                    | ⭕️主ID:{{ item.ownerId }}
                                </div>
                            </div>
                        </el-collapse-item>
                        <el-collapse-item name="paodekuai">
                            <template slot="title" style="left: 10px">
                                <i class="header-icon el-icon-info" style="margin-right: 10px"></i
                                >{{ paodekuaiInfo.title }}
                            </template>
                            <el-row :gutter="12">
                                <el-col :span="24">
                                    <el-card shadow="always">
                                        入榜数量: {{ paodekuaiInfo.count }} / 开房数量: {{ paodekuaiInfo.sum }} /
                                        领取数量: {{ paodekuaiInfo.gem }}
                                    </el-card>
                                </el-col>
                            </el-row>
                            <div
                                class="rank-content"
                                v-for="(item, index) in paodekuai"
                                :key="index"
                                v-if="paodekuaiInfo.count > 0"
                            >
                                <img height="40" width="40" :src="rankImage + ++index" class="rank-content-image" />
                                <div style="font-size: 15px">
                                    {{ item.clubName }} | {{ item.day }} 日房间[ {{ item.sum }} ] | 已领钻石:{{
                                        item.getGem
                                    }}
                                </div>
                                <div class="rank-content-club">
                                    俱乐部ID: {{ item.clubId }} | 创建时间:{{ item.createAt }} | ⭕️主:{{
                                        item.ownerName
                                    }}
                                    | ⭕️主ID:{{ item.ownerId }}
                                </div>
                            </div>
                        </el-collapse-item>
                        <el-collapse-item name="shisanshui">
                            <template slot="title" style="left: 10px">
                                <i class="header-icon el-icon-info" style="margin-right: 10px"></i
                                >{{ shisanshuiInfo.title }}
                            </template>
                            <el-row :gutter="12">
                                <el-col :span="24">
                                    <el-card shadow="always">
                                        入榜数量: {{ shisanshuiInfo.count }} / 开房数量: {{ shisanshuiInfo.sum }} /
                                        领取数量: {{ shisanshuiInfo.gem }}
                                    </el-card>
                                </el-col>
                            </el-row>
                            <div
                                class="rank-content"
                                v-for="(item, index) in shisanshui"
                                :key="index"
                                v-if="shisanshuiInfo.count > 0"
                            >
                                <img height="40" width="40" :src="rankImage + ++index" class="rank-content-image" />
                                <div style="font-size: 15px">
                                    {{ item.clubName }} | {{ item.day }} 日房间[ {{ item.sum }} ] | 已领钻石:{{
                                        item.getGem
                                    }}
                                </div>
                                <div class="rank-content-club">
                                    俱乐部ID: {{ item.clubId }} | 创建时间:{{ item.createAt }} | ⭕️主:{{
                                        item.ownerName
                                    }}
                                    | ⭕️主ID:{{ item.ownerId }}
                                </div>
                            </div>
                        </el-collapse-item>
                    </el-collapse>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { apiClubRankLog } from '@/api/finance/finance'
import LsPagination from '@/components/ls-pagination.vue'
@Component({
    components: {
        LsPagination
    }
})
export default class AccountLog extends Vue {
    records: any = []
    dates: any = []
    currentDate: any = []
    index = 0
    start_time = ''
    end_time = ''
    zhadan: any = []
    biaofen: any = []
    majiang: any = []
    paodekuai: any = []
    shisanshui: any = []
    activeNames: any = []
    zhadanInfo = {
        title: '俱乐部开房排行(类别：炸弹)',
        count: 0,
        sum: 0,
        gem: 0
    }
    biaofenInfo = {
        title: '俱乐部开房排行(类别：标分)',
        count: 0,
        sum: 0,
        gem: 0
    }
    majiangInfo = {
        title: '俱乐部开房排行(类别：麻将)',
        count: 0,
        sum: 0,
        gem: 0
    }
    paodekuaiInfo = {
        title: '俱乐部开房排行(类别：跑得快)',
        count: 0,
        sum: 0,
        gem: 0
    }
    shisanshuiInfo = {
        title: '俱乐部开房排行(类别：十三水)',
        count: 0,
        sum: 0,
        gem: 0
    }

    rankImage = 'https://dummyimage.com/100x100/e81178/fff.png&text='

    handleChange(val: any) {
        console.log(val)
    }

    selectItem(date: any) {
        // 先清除所有选中状态
        this.currentDate.forEach((item: any) => {
            item.selected = false
        })

        // 给当前选项添加选中状态
        const index: any = this.currentDate.findIndex((item: any) => item.date === date)
        this.currentDate[index].selected = true
        const times = this.formatInitTime(new Date(this.currentDate[index].date))
        this.start_time = times[0]
        this.end_time = times[1]
        this.reset()
        this.getList(true)
    }

    reset() {
        this.zhadan = []
        this.biaofen = []
        this.majiang = []
        this.paodekuai = []
        this.shisanshui = []
        this.activeNames = []
        this.zhadanInfo = {
            title: '俱乐部开房排行(类别：炸弹)',
            count: 0,
            sum: 0,
            gem: 0
        }
        this.biaofenInfo = {
            title: '俱乐部开房排行(类别：标分)',
            count: 0,
            sum: 0,
            gem: 0
        }
        this.majiangInfo = {
            title: '俱乐部开房排行(类别：麻将)',
            count: 0,
            sum: 0,
            gem: 0
        }
        this.paodekuaiInfo = {
            title: '俱乐部开房排行(类别：跑得快)',
            count: 0,
            sum: 0,
            gem: 0
        }
        this.shisanshuiInfo = {
            title: '俱乐部开房排行(类别：十三水)',
            count: 0,
            sum: 0,
            gem: 0
        }
    }

    prev() {
        if (this.index == 0) {
            return
        }
        this.currentDate = this.dates.slice(0, 15)
        this.currentDate = this.currentDate.map((item: any) => ({
            date: item,
            selected: false
        }))
        this.currentDate[0].selected = true
        this.index = 0
        const times = this.formatInitTime(new Date(this.currentDate[0].date))
        this.start_time = times[0]
        this.end_time = times[1]
        this.reset()
        this.getList(true)
    }

    next() {
        if (this.index == 1) {
            return
        }
        this.currentDate = this.dates.slice(15, 31)
        this.currentDate = this.currentDate.map((item: any) => ({
            date: item,
            selected: false
        }))
        this.currentDate[0].selected = true
        this.index = 1
        const times = this.formatInitTime(new Date(this.currentDate[0].date))
        this.start_time = times[0]
        this.end_time = times[1]
        this.reset()
        this.getList(true)
    }

    getList(flag: boolean): void {
        apiClubRankLog({ start_time: this.start_time, end_time: this.end_time }).then((res: any) => {
            this.records = res.records
            this.records.map((r: any) => {
                if (r.category === `zhadan`) {
                    this.zhadan.push(r)
                    this.zhadanInfo.count++
                    this.zhadanInfo.sum += r.sum
                    this.zhadanInfo.gem += r.getGem
                }

                if (r.category === `biaofen`) {
                    this.biaofen.push(r)
                    this.biaofenInfo.count++
                    this.biaofenInfo.sum += r.sum
                    this.biaofenInfo.gem += r.getGem
                }

                if (r.category === `majiang`) {
                    this.majiang.push(r)
                    this.majiangInfo.count++
                    this.majiangInfo.sum += r.sum
                    this.majiangInfo.gem += r.getGem
                }

                if (r.category === `paodekuai`) {
                    this.paodekuai.push(r)
                    this.paodekuaiInfo.count++
                    this.paodekuaiInfo.sum += r.sum
                    this.paodekuaiInfo.gem += r.getGem
                }

                if (r.category === `shisanshui`) {
                    this.shisanshui.push(r)
                    this.shisanshuiInfo.count++
                    this.shisanshuiInfo.sum += r.sum
                    this.shisanshuiInfo.gem += r.getGem
                }
            })
            if (!flag) {
                this.dates = res.date
                let currentDate = this.dates.slice(0, 15)
                this.currentDate = currentDate.map((item: any) => ({
                    date: item,
                    selected: false
                }))
                this.currentDate[0].selected = true
            }

            if (this.zhadan.length > 0) {
                this.activeNames.push('zhadan')
            }
            if (this.biaofen.length > 0) {
                this.activeNames.push('biaofen')
            }
            if (this.shisanshui.length > 0) {
                this.activeNames.push('shisanshui')
            }
            if (this.majiang.length > 0) {
                this.activeNames.push('majiang')
            }
            if (this.paodekuai.length > 0) {
                this.activeNames.push('paodekuai')
            }
        })
    }

    formatInitTime(date: any) {
        // 获取当前时间
        let today = new Date(date)

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

    created() {
        const times = this.formatInitTime(new Date())
        this.start_time = times[0]
        this.end_time = times[1]
        this.getList(false)
    }
}
</script>

<style lang="scss" scoped>
.center {
    text-align: center;
    margin-top: 10px;
}

.date {
    margin: 15px;
}

.date-item:active {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    transform: translateY(2px);
}

.date-item:active .xxl {
    color: #fff;
}

.date-item.selected {
    border: 2px solid orange;
    box-shadow: 0 0 10px 0 orange;
    background-color: #fff7e6;
}

.date-item.selected .xxl {
    color: orange;
}

.date-item:active::before {
    content: '';
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
}

.rank-content {
    margin-left: 0px;
    padding: 20px 16px 16px 72px;
    position: relative;
}

.rank-content-image {
    color: rgb(255, 255, 255);
    background-color: rgb(188, 188, 188);
    user-select: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    border-radius: 50%;
    height: 40px;
    width: 40px;
    position: absolute;
    top: 16px;
    left: 16px;
}

.rank-content-club {
    font-size: 15px;
    line-height: 16px;
    height: 16px;
    margin: 4px 0px 0px;
    color: rgba(0, 0, 0, 0.54);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
