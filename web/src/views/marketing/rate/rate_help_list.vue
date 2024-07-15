<template>
    <div class="user-withdrawal">
        <div class="ls-card">
            <el-alert class="xxl" title="温馨提示： 用户触发规则列表。" type="info" :closable="false" show-icon>
            </el-alert>
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
                    <el-table-column prop="shortId" label="用户编号"> </el-table-column>
                    <el-table-column prop="username" label="用户昵称"> </el-table-column>
                    <el-table-column prop="totalJu" label="总局数"> </el-table-column>
                    <el-table-column prop="juRate" label="胜率"></el-table-column>
                    <el-table-column prop="winJu" label="赢局"> </el-table-column>
                    <el-table-column prop="failJu" label="输局"> </el-table-column>
                    <el-table-column prop="totalJuScore" label="总积分"> </el-table-column>
                    <el-table-column prop="winJuScore" label="赢局积分"> </el-table-column>
                    <el-table-column prop="failJuScore" label="输局积分"> </el-table-column>
                    <el-table-column prop="day" label="规则天数"> </el-table-column>
                    <el-table-column prop="juCount" label="规则局数"></el-table-column>
                    <el-table-column prop="juRank" label="规则胜率"> </el-table-column>
                    <el-table-column prop="scorestr" label="规则积分"> </el-table-column>
                    <el-table-column prop="levelstr" label="规则触发等级"> </el-table-column>
                    <el-table-column prop="game" label="游戏"> </el-table-column>
                    <el-table-column prop="isHelp" label="是否触发"> </el-table-column>
                    <el-table-column prop="estimateLevel" label="预计触发等级"> </el-table-column>
                </el-table>
            </div>
            <div class="flex row-right m-t-16 row-right">
                <ls-pagination v-model="pager" @change="getList()" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue, Watch } from 'vue-property-decorator'
import { apiUserCanRateRuleLists } from '@/api/marketing/index.ts'
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
@Component({
    components: {
        LsPagination
    }
})
export default class AccountLog extends Vue {
    pager: RequestPaging = new RequestPaging()

    form = {
        _id: ''
    }

    getList(page?: number): void {
        page && (this.pager.page = page)
        this.pager
            .request({
                callback: apiUserCanRateRuleLists,
                params: {
                    ...this.form
                }
            })
            .then((res: any) => {})
    }

    created() {
        const query: any = this.$route.query
        if (query._id) {
            this.form._id = query._id
        }
        this.getList()
    }
}
</script>

<style lang="scss" scoped></style>
