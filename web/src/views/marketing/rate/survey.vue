<template>
    <div class="ls-coupon">
        <!-- 充值数据 -->
        <div class="ls-card">
            <div class="title">统计数据(近7天)</div>
            <div class="content">
                <el-row :gutter="20">
                    <el-col :span="6" class="statistics-col">
                        <div class="lighter">累计房间数</div>
                        <div class="m-t-8 font-size-30">
                            {{ statisticsData.juCount }}
                        </div>
                    </el-col>
                    <el-col :span="6" class="statistics-col">
                        <div class="lighter">累计小局数</div>
                        <div class="m-t-8 font-size-30">
                            {{ statisticsData.minJuCount }}
                        </div>
                    </el-col>
                    <el-col :span="6" class="statistics-col">
                        <div class="lighter">新增用户</div>
                        <div class="m-t-8 font-size-30">
                            {{ statisticsData.newUserCount }}
                        </div>
                    </el-col>
                </el-row>
            </div>
        </div>

        <el-row :gutter="16" class="m-t-16">
            <el-col :span="12">
                <div class="ls-card">
                    <div class="title">级别设置</div>
                    <div class="content">
                        <el-table :data="rateLevelList" size="mini">
                            <el-table-column type="index" label="排行" width="120" />
                            <el-table-column prop="level" label="级别" />
                            <el-table-column prop="coolingCount" label="冷却房间数" />
                            <el-table-column prop="juCount" label="释放局数" />
                            <el-table-column prop="gameList" label="游戏" />
                            <el-table-column prop="star" label="星级" />
                        </el-table>
                    </div>
                </div>
            </el-col>

            <el-col :span="12">
                <div class="ls-card">
                    <div class="title">救助规则</div>
                    <div class="content">
                        <el-table :data="rateRuleList" size="mini">
                            <el-table-column type="index" label="排行" width="120" />
                            <el-table-column prop="day" label="天数" />
                            <el-table-column prop="juCount" label="房间数" />
                            <el-table-column prop="juRank" label="胜率" />
                            <el-table-column prop="juScore" label="扣分" />
                            <el-table-column prop="maxLevel" label="最高救助级别" />
                        </el-table>
                    </div>
                </div>
            </el-col>
        </el-row>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { apiRateIndex } from '@/api/marketing/index.ts'

@Component
export default class RechargeSurvey extends Vue {
    statisticsData = {} // 统计数据
    rateRuleList = [] // 规则排行榜列表
    rateRankList = [] // 游戏胜率列表
    rateLevelList = []

    created() {
        apiRateIndex({})
            .then((res: any) => {
                this.statisticsData = res.summary
                this.rateRankList = res.rateRankList
                this.rateRuleList = res.rateRuleList
                this.rateLevelList = res.rateLevelList
            })
            .catch(() => {
                this.$message.error('请求数据失败，请刷新重载!')
            })
    }
}
</script>

<style lang="scss" scoped>
.ls-card {
    .title {
        font-size: 14px;
        font-weight: 500;
    }

    .content {
        margin-top: 20px;
    }

    .statistics-col {
        text-align: center;
    }
}
</style>
