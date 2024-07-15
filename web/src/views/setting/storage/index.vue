<!-- 存储设置首页 -->
<template>
    <div class="storage-setting">
        <!-- 提示 -->
        <div class="ls-card">
            <el-alert
                title="温馨提示：1.切换存储方式后，需要将资源文件传输至新的存储端；2.请勿随意切换存储方式，可能导致图片无法查看。"
                type="info"
                :closable="false"
                show-icon
            />
        </div>
        <!-- 存储列表 -->
        <div class="ls-user_tag ls-card m-t-20">
            <div class="list-table m-t-16">
                <el-table :data="list" style="width: 100%" size="mini" :header-cell-style="{ background: '#f5f8ff' }">
                    <el-table-column prop="name" label="存储方式" min-width="" width=""> </el-table-column>
                    <el-table-column prop="path" label="存储位置" min-width="260" width=""> </el-table-column>
                    <el-table-column prop="status" label="状态" min-width="" width="">
                        <template slot-scope="scope">
                            <el-switch
                                v-model="scope.row.status"
                                :active-value="1"
                                :inactive-value="0"
                                :active-color="styleConfig.primary"
                                inactive-color="#f4f4f5"
                                @change="postStorageChange(scope.row)"
                            />
                        </template>
                    </el-table-column>
                    <el-table-column label="操作" min-width="" fixed="right">
                        <template slot-scope="scope">
                            <el-button type="text" size="small" @click="onStorageEdit(scope.row)">设置</el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Vue, Component } from 'vue-property-decorator'
import { apiStorageList, apiStorageChange } from '@/api/setting/storage/storage'
@Component({
    components: {}
})
export default class StorageIndex extends Vue {
    /** S Data **/
    list = []
    oldEngine = 'local' // 默认引擎
    /** E Data **/

    /** S Methods **/
    // 跳转引擎设置页面
    onStorageEdit(item: any) {
        this.$router.push({
            path: '/setting/storage/storage',
            query: {
                engine: item.engine // 存储引擎
            }
        })
    }

    // 获取存储引擎列表
    getStorageList() {
        apiStorageList()
            .then((res: any) => {
                this.list = res

                // this.$message.success("删除成功!");
            })
            .catch(() => {
                this.$message.error('获取数据失败，请重试！')
            })
    }
    // 切换引擎
    postStorageChange(item: any) {
        apiStorageChange({ engine: item.engine })
            .then((res: any) => {
                this.getStorageList()
                this.$message.success('更新成功')
            })
            .catch(() => {
                this.$message.error('更新失败')
            })
    }
    /** E Methods **/

    /** S Life Cycle **/
    created() {
        this.getStorageList()
    }
    /** E Life Cycle **/
}
</script>

<style lang="scss" scoped>
.ls-card {
    .ls-input {
        width: 280px;
    }

    .card-title {
        font-size: 16px;
    }

    .login_limit-unit {
        display: inline-block;
        width: 2em;
        text-align: center;
    }

    .item-a {
        color: #4073fa;
    }

    .ls-select {
        width: 100px;
    }
}

.storage-setting {
    min-height: calc(100vh - #{$--header-height} - 92px);
    margin-bottom: 60px;
}
</style>
