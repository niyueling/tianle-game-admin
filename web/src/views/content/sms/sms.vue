<template>
    <div class="sms">
        <div class="ls-card">
            <el-table :data="smsData" style="width: 100%" size="mini">
                <el-table-column prop="name" label="短信通道" min-width="180" />
                <el-table-column label="状态" min-width="180">
                    <template slot-scope="scope">
                        <!-- 短信通知的当前状态 -->
                        <el-tag :type="scope.row.status == 0 ? 'danger' : 'success'" effect="plain">
                            {{ scope.row.status == 0 ? '关闭' : '启用' }}
                        </el-tag>
                    </template>
                </el-table-column>

                <el-table-column label="操作" width="200">
                    <template slot-scope="scope">
                        <el-button type="text" size="mini" @click="goSetting(scope.$index)">设置 </el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { apiSmsGetConfig } from '@/api/content/sms'
@Component
export default class Sms extends Vue {
    smsData: any = []

    getSmsList() {
        apiSmsGetConfig()
            .then(res => {
                const data = [
                    {
                        ...res.ali
                    },
                    {
                        ...res.tencent
                    }
                ]
                this.smsData = data
            })
            .catch(() => {
                this.$message.error('数据请求失败，刷新重载!')
            })
    }

    goSetting(row: any) {
        this.$router.push({
            path: '/sms/sms_edit',
            query: {
                id: row == 0 ? 'ali' : 'tencent'
            }
        })
    }

    created() {
        this.getSmsList()
    }
}
</script>

<style lang="scss" scoped></style>
