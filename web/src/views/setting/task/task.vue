<template>
    <div>
        <div class="ls-card">
            <el-button size="small" type="primary" @click="goTaskAdd">添加定时任务</el-button>

            <el-table
                ref="paneTable"
                class="m-t-24"
                :data="pager.lists"
                v-loading="pager.loading"
                style="width: 100%"
                size="mini"
            >
                <el-table-column prop="name" label="名称" min-width="100"></el-table-column>
                <el-table-column prop="type_desc" label="类型" min-width="80"></el-table-column>
                <el-table-column prop="command" label="命令" min-width="120"></el-table-column>
                <el-table-column prop="params" label="参数" min-width="80"></el-table-column>
                <el-table-column prop="expression" label="规则" min-width="160"></el-table-column>
                <el-table-column prop="status_desc" label="状态" min-width="80"></el-table-column>
                <el-table-column prop="error" label="错误原因" min-width="80"></el-table-column>
                <el-table-column prop="last_time" label="最后执行时间" min-width="180"></el-table-column>
                <el-table-column prop="time" label="时长" min-width="110"></el-table-column>
                <el-table-column prop="max_time" label="最大时长" min-width="110"></el-table-column>

                <el-table-column label="操作" min-width="180">
                    <template slot-scope="scope">
                        <el-button type="text" size="small" @click="goTaskEdit(scope.row.id)">编辑</el-button>

                        <!-- 停止/开启 -->
                        <!-- <ls-dialog class="m-l-10 inline" :content="'确定要停止这个定时任务吗？请谨慎操作'" @confirm="onStop(scope.row)">
                                <el-button type="text" size="mini" slot="trigger">
                                    {{scope.row.status==1?'停止':'运行'}}
                                </el-button>
                            </ls-dialog> -->

                        <!-- 删除定时任务 -->
                        <ls-dialog
                            class="m-l-10 m-t-20 m-b-20 inline"
                            :content="'确定要停删除个定时任务吗？请谨慎操作'"
                            @confirm="onDel(scope.row.id)"
                        >
                            <el-button type="text" size="mini" slot="trigger">删除 </el-button>
                        </ls-dialog>
                    </template>
                </el-table-column>
            </el-table>

            <!-- 分页 -->
            <div class="m-t-24 flex row-right">
                <ls-pagination v-model="pager" @change="getList" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { apiCrontabLists, apiCrontabDel, apiSrontabOperate } from '@/api/setting/system_maintain/system_maintain'
import { Component, Prop, Vue } from 'vue-property-decorator'
import LsPagination from '@/components/ls-pagination.vue'
import { RequestPaging } from '@/utils/util'
import { PageMode } from '@/utils/type'
import LsDialog from '@/components/ls-dialog.vue'
@Component({
    components: {
        LsDialog,
        LsPagination
    }
})
export default class Task extends Vue {
    // 分页
    pager: RequestPaging = new RequestPaging()

    // 获取列表
    getList() {
        this.pager
            .request({
                callback: apiCrontabLists,
                params: {}
            })
            .catch(() => {
                this.$message.error('数据请求失败，刷新重载!')
            })
    }

    onStop(row: any) {
        apiSrontabOperate({
            id: row.id,
            operate: row.status == 1 ? 'stop' : 'start'
        }).then(() => {
            this.getList()
        })
    }

    // 删除这个定时任务
    onDel(id: any) {
        apiCrontabDel({ id: id })
            .then(() => {
                // 删除成功就请求新列表
                this.getList()
                this.$message.success('删除成功!')
            })
            .catch(() => {
                this.$message.error('删除失败!')
            })
    }

    // 新增
    goTaskAdd() {
        this.$router.push({
            path: '/setting/task_edit',
            query: {
                mode: PageMode.ADD
            }
        })
    }

    // 编辑
    goTaskEdit(id: any) {
        this.$router.push({
            path: '/setting/task_edit',
            query: {
                id: id,
                mode: PageMode.EDIT
            }
        })
    }

    created() {
        this.getList()
    }
}
</script>

<style lang="scss"></style>
