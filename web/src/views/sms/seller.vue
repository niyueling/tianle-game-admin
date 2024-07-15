<template>
    <div class="buyers">
        <div class="ls-card">
            <div class="m-t-24">
                <el-table :data="pager.lists" v-loading="pager.loading" style="width: 100%" size="mini">
                    <el-table-column prop="scene_name" label="通知类型" min-width="180" />

                    <el-table-column label="短信通知" min-width="180">
                        <template slot-scope="scope">
                            <!-- 短信通知的当前状态 -->
                            <el-tag :type="scope.row.sms_notice.status == 0 ? 'danger' : 'success'" effect="plain">{{
                                scope.row.sms_notice.status == 0 ? '关闭' : '启用'
                            }}</el-tag>
                        </template>
                    </el-table-column>

                    <el-table-column label="操作" width="200">
                        <template slot-scope="scope">
                            <el-button type="text" size="mini" @click="goSetting(scope.row)">设置</el-button>
                        </template>
                    </el-table-column>
                </el-table>

                <!-- 分页 -->
                <div class="m-t-24 pagination">
                    <ls-pagination v-model="pager" @change="getNoticeList" />
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { apiNoticeData } from '@/api/sms'
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
@Component({
    components: {
        LsPagination
    }
})
export default class Buyers extends Vue {
    pager: RequestPaging = new RequestPaging()

    getNoticeList() {
        this.pager
            .request({
                callback: apiNoticeData,
                params: { recipient: 2 }
            })
            .catch(() => {
                this.$message.error('数据请求失败，刷新重载!')
            })
    }

    goSetting(row: any) {
        this.$router.push({
            path: '/sms/buyers/setting',
            query: {
                id: row.id
            }
        })
    }

    created() {
        this.getNoticeList()
    }
}
</script>

<style lang="scss" scoped></style>
