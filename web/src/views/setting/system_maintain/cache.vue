<!-- 系统缓存 -->
<template>
    <div class="system-cache">
        <div class="ls-card">
            <el-alert
                class="xxl"
                title="温馨提示：管理系统运行过程中产生的缓存。"
                type="info"
                :closable="false"
                show-icon
            >
            </el-alert>
        </div>
        <div class="ls-card m-t-16">
            <el-table
                :data="lists"
                style="width: 100%"
                size="mini"
                v-loading="false"
                :header-cell-style="{ background: '#f5f8ff' }"
            >
                <el-table-column prop="content" label="管理内容"> </el-table-column>
                <el-table-column prop="description" label="内容说明"> </el-table-column>
                <el-table-column label="操作">
                    <template slot-scope="scope">
                        <ls-dialog
                            class="inline"
                            :content="`确定清除：${scope.row.content}`"
                            @confirm="onSystemCacheClear"
                        >
                            <el-button type="text" size="small" slot="trigger">清除系统缓存</el-button>
                        </ls-dialog>
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { apiSystemCacheClear } from '@/api/setting/system_maintain/system_maintain'
import LsDialog from '@/components/ls-dialog.vue'
import { RequestPaging } from '@/utils/util'
@Component({
    components: {
        LsDialog
    }
})
export default class SystemCache extends Vue {
    /** S Data **/
    lists = [
        {
            content: '系统缓存',
            description: '系统运行过程中产生的各类缓存数据'
        }
    ]
    // pager: RequestPaging = new RequestPaging()
    /** E Data **/

    /** S Methods **/
    onSystemCacheClear() {
        apiSystemCacheClear()
            .then(res => {})
            .catch(res => {})
    }
    /** E Methods **/

    /** S Life Cycle **/
    created() {}
    /** E Life Cycle **/
}
</script>

<style lang="scss" scoped></style>
