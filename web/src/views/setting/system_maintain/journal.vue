<!-- 系统日志 -->
<template>
    <div class="system-journal">
        <div class="ls-card ls-card-top">
            <el-alert
                class="xxl"
                title="温馨提示：记录管理后台系统日志，可用于排查事故原因。"
                type="info"
                :closable="false"
                show-icon
            >
            </el-alert>
            <div class="journal-search m-t-16">
                <el-form ref="formRef" inline :model="form" label-width="70px" size="small">
                    <el-form-item label="管理员" prop="admin_name">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.admin_name"
                            placeholder="请输入管理员名称"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="访问方式" prop="type">
                        <el-select class="ls-select" v-model="form.type" placeholder="全部">
                            <el-option label="全部" value=""></el-option>
                            <el-option label="get" value="get"></el-option>
                            <el-option label="post" value="post"></el-option>
                            <el-option label="put" value="put"></el-option>
                            <el-option label="delete" value="delete"></el-option>
                            <el-option label="option" value="option"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="来源IP" prop="ip">
                        <div class="flex">
                            <el-input class="ls-select-keyword" v-model="form.ip" placeholder="请输入"></el-input>
                        </div>
                    </el-form-item>
                    <el-form-item label="访问时间">
                        <el-date-picker
                            v-model="tableData"
                            type="daterange"
                            align="right"
                            unlink-panels
                            range-separator="至"
                            start-placeholder="开始时间"
                            end-placeholder="结束时间"
                            :picker-options="pickerOptions"
                            @change="splitTime"
                            value-format="yyyy-MM-dd"
                        >
                        </el-date-picker>
                    </el-form-item>
                    <el-form-item label="访问链接" prop="url">
                        <el-input class="ls-select-keyword" v-model="form.url" placeholder="请输入访问链接"></el-input>
                    </el-form-item>

                    <el-button size="small" type="primary" @click="getJournalList(1)">查询</el-button>
                    <el-button size="small" @click="onReset">重置</el-button>
                    <!-- 导出按钮 -->
                    <export-data
                        class="m-l-10"
                        :pageSize="pager.size"
                        :method="apiSystemlogList"
                        :param="form"
                    ></export-data>
                </el-form>
            </div>
        </div>

        <div class="ls-journal__centent ls-card m-t-16">
            <div class="list-table">
                <el-table
                    :data="pager.lists"
                    style="width: 100%"
                    size="mini"
                    v-loading="pager.loading"
                    :header-cell-style="{ background: '#f5f8ff' }"
                >
                    <el-table-column prop="id" label="记录ID"> </el-table-column>
                    <el-table-column prop="action" label="操作"> </el-table-column>
                    <el-table-column prop="admin_name" label="管理员"> </el-table-column>
                    <el-table-column prop="admin_id" label="管理员ID"> </el-table-column>
                    <el-table-column prop="url" label="访问链接" show-overflow-tooltip min-width="180">
                    </el-table-column>
                    <el-table-column prop="type" label="访问方式"> </el-table-column>
                    <el-table-column prop="params" label="访问参数" show-overflow-tooltip min-width="180">
                    </el-table-column>
                    <el-table-column prop="ip" label="来源IP"> </el-table-column>
                    <el-table-column prop="create_time" label="日志时间" min-width="180"> </el-table-column>
                </el-table>
            </div>
            <!-- 底部分页栏  -->
            <div class="flex row-right m-t-16 row-right">
                <ls-pagination v-model="pager" @change="getJournalList()" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { Journal_Req, Journal_Res } from '@/api/setting/system_maintain/system_maintain.d.ts'
import { apiSystemlogList } from '@/api/setting/system_maintain/system_maintain'
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
import ExportData from '@/components/export-data/index.vue'
@Component({
    components: {
        LsPagination,
        ExportData
    }
})
export default class Journal extends Vue {
    /** S Data **/

    pickerOptions: any = {
        shortcuts: [
            {
                text: '最近一周',
                onClick(picker: any) {
                    const end = new Date()
                    const start = new Date()
                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 7)
                    picker.$emit('pick', [start, end])
                }
            },
            {
                text: '最近一个月',
                onClick(picker: any) {
                    const end = new Date()
                    const start = new Date()
                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 30)
                    picker.$emit('pick', [start, end])
                }
            },
            {
                text: '最近三个月',
                onClick(picker: any) {
                    const end = new Date()
                    const start = new Date()
                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 90)
                    picker.$emit('pick', [start, end])
                }
            }
        ]
    }
    tableData = []

    pager: RequestPaging = new RequestPaging()
    // 查询表单
    form: Journal_Req = {
        admin_name: '', // 管理员
        url: '', // 访问链接
        ip: '', // 来源IP
        type: '', // 访问方式
        start_time: '', // 日志时间开始
        end_time: '' // 日志时间结束
    }
    $refs!: { formRef: any }

    apiSystemlogList = apiSystemlogList
    /** E Data **/

    /** S Methods **/
    //获取日志列表数据
    getJournalList(page?: number): void {
        page && (this.pager.page = page)
        // 分页请求
        this.pager
            .request({
                callback: apiSystemlogList,
                params: this.form
            })
            .catch(() => {
                // this.$message.error("数据请求失败，刷新重载")
            })
    }
    // 重置按钮
    onReset() {
        this.$nextTick(() => {
            this.$refs.formRef.resetFields()
            this.tableData = []
        })
    }
    // 导出按钮
    onExportData() {}
    // 拆分时间
    splitTime() {
        if (this.tableData != null) {
            this.form.start_time = this.tableData[0]
            this.form.end_time = this.tableData[1]
        }
    }
    /** E Methods **/

    /** S Life Cycle **/
    created() {
        this.getJournalList()
    }
    /** E Life Cycle **/
}
</script>

<style lang="scss" scoped>
.system-journal {
    // .ls-journal__top {
    // 	padding: 16px 24px;
    // }
    .ls-card-top {
        padding-bottom: 0;
    }
}
</style>
