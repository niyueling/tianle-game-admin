<!-- 用户管理 -->
<template>
    <div class="user-management">
        <div class="ls-User__centent ls-card m-t-16">
            <div class="list-header">
                <div class="flex">
                    <ls-qian-add @refresh="getUserList" action="add">
                        <el-button type="text" slot="trigger" size="small">新增</el-button>
                    </ls-qian-add>

                    <el-upload class="file-upload" :action="actionUrl" :accept="'.xls,.xlsx'"
                               :before-upload="beforeUpload"
                               :on-success="getUserList"
                               :show-file-list="false" ref="uploadFile">
                        <el-button style="margin-left: 20px" type="text" size="small">一键导入</el-button>
                    </el-upload>
                </div>
            </div>
            <div class="list-table m-t-16">
                <el-table
                    :data="pager.lists"
                    style="width: 100%"
                    size="mini"
                    v-loading="pager.loading"
                    :header-cell-style="{ background: '#f5f8ff' }"
                >
                    <el-table-column prop="qianId" label="编号" width="80px"></el-table-column>
                    <el-table-column prop="name" label="名称" width="150px"></el-table-column>
                    <el-table-column prop="position" label="位置" width="150px"></el-table-column>
                    <el-table-column prop="bless" label="运势" width="80px"></el-table-column>
                    <el-table-column prop="luckyLevel" label="吉凶级别" width="130px"></el-table-column>
                    <el-table-column prop="contentStr" label="签文"></el-table-column>
                    <el-table-column prop="summaryStr" label="签语"></el-table-column>
                    <el-table-column fixed="right" label="操作"  width="100px">
                        <template slot-scope="scope">
                            <ls-qian-add
                                class="m-l-10 inline"
                                title="编辑"
                                action="edit"
                                :qian="scope.row"
                                @refresh="getUserList"
                            >
                                <el-button type="text" slot="trigger" size="small">编辑</el-button>
                            </ls-qian-add>

                            <ls-dialog
                                class="m-l-10 inline"
                                content="是否删除此条数据吗？删除后，操作将无法回退"
                                @confirm="deleteGame(scope.row)"
                            >
                                <el-button slot="trigger" type="text" size="small">删除</el-button>
                            </ls-dialog>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
            <!-- 底部分页栏  -->
            <div class="flex row-right m-t-16 row-right">
                <ls-pagination v-model="pager" @change="getUserList()" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { apiQianList, apiDelQian } from "@/api/marketing";
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
import LsDialog from '../../components/ls-dialog.vue'
import LsGameAdd from '@/components/ls-game-add.vue'
import LsGameCategoryAdd from '@/components/ls-game-category-add.vue'
import LsConversionAdd from '@/components/ls-conversion-add.vue'
import LsCheerAdd from "@/components/ls-cheer-add.vue";
import LsQianAdd from "@/components/ls-qian-add.vue";
@Component({
    components: {
      LsQianAdd,
      LsCheerAdd,
        LsConversionAdd,
        LsGameCategoryAdd,
        LsGameAdd,
        LsPagination,
        LsDialog
    }
})
export default class UserManagement extends Vue {
    form = {
        title: '',
        category: ''
    }
    // 分页查询
    pager: RequestPaging = new RequestPaging()

    actionUrl = "https://phpadmin.tianle.fanmengonline.com/adminapi/marketing.qian/upload";
    fileType = [ "xls", "xlsx"];

    // 查询按钮
    query() {
        this.pager.page = 1
        this.getUserList()
    }

    //上传文件之前
    beforeUpload(file: any){
        if (file.type != "" || file.type != null || file.type != undefined){
            //截取文件的后缀，判断文件类型
            const FileExt = file.name.replace(/.+\./, "").toLowerCase();
            //计算文件的大小
            const isLt5M = file.size / 1024 / 1024 < 50; //这里做文件大小限制
            //如果大于50M
            if (!isLt5M) {
                this.$message.error('上传文件大小不能超过 50MB!');
                return false;
            }
            //如果文件类型不在允许上传的范围内
            if(this.fileType.includes(FileExt)){
                return true;
            }
            else {
                this.$message.error("上传文件格式不正确!");
                return false;
            }
        }
    }

    //获取用户列表数据
    getUserList() {
        this.pager.request({
            callback: apiQianList,
            params: {
                ...this.form
            }
        })
    }

    // 重置按钮
    onReset() {
        this.form = {
            title: '',
            category: ''
        }
        this.pager.page = 1
        this.getUserList()
    }

    deleteGame(good: any) {
      apiDelQian({
            _id: good._id
        }).then(res => {
            this.pager.page = 1
            this.getUserList()
        })
    }

    created() {
        this.getUserList()
    }
}
</script>

<style lang="scss" scoped>
.user-management {
    .ls-user__top {
        padding: 16px 24px;
    }
}
</style>
