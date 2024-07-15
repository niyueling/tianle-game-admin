<!-- 用户管理 -->
<template>
    <div class="user-management">
        <div class="ls-User__top ls-card">
            <el-alert
                class="xxl"
                title="温馨提示：1.管理场次列表，可以进行游戏场次管理等操作。"
                type="info"
                :closable="false"
                show-icon
            ></el-alert>
            <div class="member-search m-t-16">
                <el-form ref="form" inline :model="form" label-width="70px" size="small">
                    <el-form-item label="名称">
                        <el-input v-model="form.title" placeholder="请输入场次名称"> </el-input>
                    </el-form-item>
                    <el-form-item label="类型">
                        <div class="flex">
                            <el-select style="width: 120px" v-model="form.category" placeholder="全部">
                                <el-option label="全部" value></el-option>
                                <el-option label="金豆场" value="ruby"></el-option>
                            </el-select>
                        </div>
                    </el-form-item>

                    <el-button size="small" type="primary" @click="query()">查询</el-button>
                    <el-button size="small" @click="onReset()">重置</el-button>
                </el-form>
            </div>
        </div>

        <div class="ls-User__centent ls-card m-t-16">
            <div class="list-header">
                <div class="flex">
                    <ls-game-category-add @refresh="getUserList" action="add">
                        <el-button type="text" slot="trigger" size="small">新增游戏场次</el-button>
                    </ls-game-category-add>
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
                    <el-table-column prop="_id" label="编号"></el-table-column>
                    <el-table-column prop="title" label="名称"></el-table-column>
                    <el-table-column prop="gameStr" label="游戏"></el-table-column>
                    <el-table-column prop="gameCategoryStr" label="游戏分类"></el-table-column>
                    <el-table-column prop="categoryStr" label="分类"></el-table-column>
                    <el-table-column prop="level" label="等级"></el-table-column>
                    <el-table-column prop="minScoreStr" label="底分"></el-table-column>
                    <el-table-column prop="minAmountStr" label="最低携带"></el-table-column>
                    <el-table-column prop="maxAmountStr" label="最高携带"></el-table-column>
                    <el-table-column prop="roomRate" label="房费"></el-table-column>
                    <el-table-column prop="playerCount" label="人数"></el-table-column>
                    <el-table-column prop="isOpenDoubleStr" label="是否免输/翻倍"></el-table-column>
                    <el-table-column prop="openStr" label="状态"></el-table-column>
                    <el-table-column prop="createAt" label="时间"></el-table-column>
                    <el-table-column fixed="right" label="操作" min-width="120">
                        <template slot-scope="scope">
                            <ls-game-category-add
                                class="m-l-10 inline"
                                title="编辑游戏区域"
                                action="edit"
                                :category="scope.row"
                                @refresh="getUserList"
                            >
                                <el-button type="text" slot="trigger" size="small">编辑</el-button>
                            </ls-game-category-add>

                            <ls-dialog
                                class="m-l-10 inline"
                                content="是否删除游戏场次吗？删除后，操作将无法回退"
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
import { apiGameCategoryList, apiDelGameCategory } from '@/api/marketing'
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
import LsDialog from '../../components/ls-dialog.vue'
import LsGameAdd from '@/components/ls-game-add.vue'
import LsGameCategoryAdd from '@/components/ls-game-category-add.vue'
@Component({
    components: {
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

    // 查询按钮
    query() {
        this.pager.page = 1
        this.getUserList()
    }

    //获取用户列表数据
    getUserList() {
        this.pager.request({
            callback: apiGameCategoryList,
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
        apiDelGameCategory({
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
