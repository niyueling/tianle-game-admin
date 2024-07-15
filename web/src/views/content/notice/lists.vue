<template>
    <div class="help_manage">
        <div class="ls-card">
            <!-- 操作提示 -->
            <el-alert
                title="温馨提示：1、平台发布的操作说明，公告文档，常见问题。用户可在游戏的公告功能查看；"
                type="info"
                show-icon
                :closable="false"
            />
        </div>

        <div class="ls-card m-t-24">
            <!-- 新增公告按钮 -->
            <div class="add-btn">
                <ls-notice-add @refresh="getNoticeList" title="新增公告" action="add">
                    <el-button type="primary" slot="trigger" size="mini">新增公告</el-button>
                </ls-notice-add>
            </div>

            <!-- 公告数据列表 -->
            <div class="m-t-24">
                <el-table :data="pager.lists" v-loading="pager.loading" size="mini" style="width: 100%">
                    <el-table-column sortable prop="_id" label="ID" min-width="70" />
                    <el-table-column prop="message" label="内容" min-width="280"></el-table-column>
                    <el-table-column sortable prop="createAt" label="创建时间" min-width="200" />
                    <el-table-column label="操作" min-width="200">
                        <template slot-scope="scope">
                            <ls-notice-add
                                @refresh="getNoticeList"
                                class="m-l-10 inline"
                                title="编辑公告"
                                action="edit"
                                :notice="scope.row"
                            >
                                <el-button type="text" slot="trigger" size="mini">编辑</el-button>
                            </ls-notice-add>
                            <ls-dialog title="删除公告" class="m-l-10 inline" @confirm="onNoticeDel(scope.row)">
                                <el-button type="text" size="mini" slot="trigger">删除 </el-button>
                            </ls-dialog>
                        </template>
                    </el-table-column>
                </el-table>

                <!-- 分页 -->
                <div class="m-t-24 flex row-right">
                    <ls-pagination v-model="pager" @change="getNoticeList" />
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import LsDialog from '@/components/ls-dialog.vue'
import LsPagination from '@/components/ls-pagination.vue'
import ExportData from '@/components/export-data/index.vue'
import { apiNoticeLists, apiNoticeDel } from '@/api/content/notice'
import { RequestPaging } from '@/utils/util'
import { PageMode } from '@/utils/type'
import LsNoticeAdd from '@/components/ls-notice-add.vue'

@Component({
    components: {
        LsNoticeAdd,
        LsDialog,
        LsPagination,
        ExportData
    }
})
export default class HelpManage extends Vue {
    // 分页
    pager: RequestPaging = new RequestPaging()

    // 获取公告管理列表
    getNoticeList() {
        this.pager
            .request({
                callback: apiNoticeLists,
                params: {}
            })
            .catch(() => {
                this.$message.error('数据请求失败，刷新重载!')
            })
    }

    // 删除公告文章
    onNoticeDel(data: any) {
        apiNoticeDel({ _id: data._id }).then(() => {
            this.getNoticeList()
        })
    }

    created() {
        this.getNoticeList()
    }
}
</script>

<style lang="scss" scoped></style>
