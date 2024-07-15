<template>
    <div class="export-data inline">
        <ls-dialog
            ref="dialog"
            title="导出设置"
            width="500px"
            top="35vh"
            confirm-button-text="确认导出"
            @confirm="handleConfirm"
            :async="true"
            @open="handleOpen"
        >
            <div slot="trigger">
                <el-button size="small">导出</el-button>
            </div>
            <div>
                <el-form ref="form" :model="formData" label-width="120px" size="small">
                    <el-form-item label="数据量：">
                        预计导出{{ exportData.count }}条数据，共{{ exportData.sum_page }}页，每页{{
                            exportData.page_size
                        }}条数据
                    </el-form-item>
                    <el-form-item label="导出限制：">
                        每次导出最大允许{{ exportData.max_page }}页，共{{ exportData.all_max_size }}条数据
                    </el-form-item>
                    <el-form-item label="导出范围：" required>
                        <el-radio-group v-model="formData.page_type">
                            <el-radio :label="0">全部导出</el-radio>
                            <el-radio :label="1">分页导出</el-radio>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item label="分页范围：" required v-if="formData.page_type == 1">
                        <div class="flex">
                            <el-input style="width: 100px" v-model="formData.page_start" placeholder=""></el-input>
                            <span class="flex-none m-l-8 m-r-8">页，至</span>
                            <el-input style="width: 100px" v-model="formData.page_end" placeholder=""></el-input>
                        </div>
                    </el-form-item>
                    <el-form-item label="导出文件名称：" prop="file_name">
                        <el-input v-model="formData.file_name" placeholder="请输入导出文件名称"></el-input>
                    </el-form-item>
                </el-form>
            </div>
        </ls-dialog>
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator'
import LsDialog from '@/components/ls-dialog.vue'
@Component({
    components: {
        LsDialog
    }
})
export default class ExportData extends Vue {
    $refs!: { dialog: any }
    @Prop() method!: Function // 列表接口api
    @Prop() param!: object // 列表接口参数

    @Prop() userId?: number // 有些接口需要用户id，如用户邀请列表
    @Prop() type?: string // 有些接口是共用的，如用户余额/积分列表共用一个接口，需要一个额外参数来判断
    @Prop() pageSize?: number // 对应列表右下角的每页的数量
    // exportList:any = api
    exportData = {}
    formData = {
        page_type: 0,
        page_start: 1,
        page_end: 200,
        file_name: ''
    }
    handleOpen() {
        this.getData()
    }
    handleConfirm() {
        const loading = this.$loading({
            lock: true,
            text: '正在导出中...',
            spinner: 'el-icon-loading'
        })
        this.method({
            export: 2,
            ...this.param,
            ...this.formData,
            user_id: this.userId,
            type: this.type,
            page_size: this.pageSize
        })
            .then(() => {
                this.$refs.dialog.close()
                // loading.close()
            })
            .finally(() => {
                loading.close()
            })
    }

    getData() {
        this.method({
            ...this.param,
            export: 1,
            user_id: this.userId,
            type: this.type,
            page_size: this.pageSize
        }).then((res: any) => {
            this.exportData = res
            this.formData.file_name = res.file_name
            this.formData.page_end = res.page_end
            this.formData.page_start = res.page_start
        })
    }

    created() {}
}
</script>
