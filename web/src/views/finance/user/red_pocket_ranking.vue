<template>
    <div class="user-withdrawal">
        <div class="ls-card">
            <el-alert class="xxl" title="温馨提示： 1.会员钻石变动记录。" type="info" :closable="false" show-icon>
            </el-alert>
        </div>
        <div class="ls-withdrawal__centent ls-card m-t-16">
            <div class="list-table m-t-16">
                <el-table
                    :data="pager.lists"
                    style="width: 100%"
                    v-loading="pager.loading"
                    size="mini"
                    :header-cell-style="{ background: '#f5f8ff' }"
                >
                    <el-table-column prop="_id" label="用户编号"> </el-table-column>
                    <el-table-column prop="shortId" label="用户ID">
                        <template slot-scope="scope">
                            <div
                                @click="navicatToUserByShortId(scope.row.shortId)"
                                style="color: #3967ff; cursor: pointer"
                            >
                                {{ scope.row.shortId }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="name" label="用户昵称"> </el-table-column>
                    <el-table-column prop="realName" label="真实姓名"> </el-table-column>
                    <el-table-column prop="phone" label="手机号码"> </el-table-column>
                    <el-table-column prop="redPocket" label="红包"></el-table-column>
                    <el-table-column prop="createAt" label="注册时间"> </el-table-column>
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
import { apiClubCreatorGoldLog, apiUserRedPocketRanking } from '@/api/finance/finance'
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
@Component({
    components: {
        LsPagination
    }
})
export default class AccountLog extends Vue {
    /** S Data **/
    // 顶部查询表单
    pager: RequestPaging = new RequestPaging()

    // 资金记录
    getList(page?: number): void {
        page && (this.pager.page = page)
        this.pager
            .request({
                callback: apiUserRedPocketRanking,
                params: {}
            })
            .then((res: any) => {})
    }

    navicatToUserByShortId(shortId: any) {
        this.$router.push({
            path: '/user/lists',
            query: {
                shortId
            }
        })
    }

    created() {
        this.getList()
    }
}
</script>

<style lang="scss" scoped></style>
