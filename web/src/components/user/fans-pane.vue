<template>
    <div class="withdraw-pane">
        <div class="pane-table">
            <div class="list-table">
                <el-table
                    ref="valueRef"
                    :data="value"
                    style="width: 100%"
                    size="mini"
                    :header-cell-style="{ background: '#f5f8ff' }"
                >
                    <el-table-column label="用户信息" min-width="200">
                        <template slot-scope="scope">
                            <div class="flex">
                                <el-image style="width: 58px; height: 58px" class="flex-none" :src="scope.row.avatar">
                                </el-image>
                                <div class="m-l-8">
                                    <div>{{ scope.row.nickname }}</div>
                                </div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="推荐人" min-width="200">
                        <template slot-scope="scope">
                            <div class="flex">
                                <el-image
                                    style="width: 58px; height: 58px"
                                    class="flex-none"
                                    :src="scope.row.leader_avatar"
                                >
                                </el-image>
                                <div class="m-l-8">
                                    <div>{{ scope.row.leader_name }}</div>
                                </div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="name" label="用户等级"> </el-table-column>
                    <el-table-column prop="mobile" label="手机号码"> </el-table-column>
                    <el-table-column prop="create_time" label="注册时间"> </el-table-column>
                </el-table>
            </div>
            <!-- 底部分页栏  -->
            <div class="flex row-right m-t-16 row-right">
                <ls-pagination v-model="pager" @change="$emit('refresh')" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator'
import LsDialog from '@/components/ls-dialog.vue'
import LsPagination from '@/components/ls-pagination.vue'
@Component({
    components: {
        LsDialog,
        LsPagination
    }
})
export default class fansPane extends Vue {
    $refs!: {
        valueRef: any
    }
    @Prop() value: any
    @Prop() pager!: any
}
</script>

<style scoped lang="scss"></style>
