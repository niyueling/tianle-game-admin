<!-- 用户管理 -->
<template>
    <div class="user-management">
        <div class="ls-User__top ls-card">
            <el-alert
                class="xxl"
                title="温馨提示：1.管理游戏区域列表，可以进行游戏区域管理等操作。"
                type="info"
                :closable="false"
                show-icon
            ></el-alert>
            <div class="member-search m-t-16">
                <el-form ref="form" inline :model="form" label-width="70px" size="small">
                    <el-form-item label="城市">
                        <el-select class="ls-select" v-model="form.city" placeholder="全部">
                            <el-option
                                :label="item"
                                :value="item"
                                v-for="(item, index) in cityList"
                                key="index"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="县区">
                        <el-select class="ls-select" v-model="form.county" placeholder="全部">
                            <el-option
                                :label="item"
                                :value="item"
                                v-for="(item, index) in countyList[form.city]"
                                key="index1"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="游戏名称">
                        <div class="flex">
                            <el-select style="width: 120px" v-model="form.gameName" placeholder="全部">
                                <el-option label="全部" value></el-option>
                                <el-option label="炸弹" value="zhadan"></el-option>
                                <el-option label="标分" value="biaofen"></el-option>
                                <el-option label="麻将" value="majiang"></el-option>
                                <el-option label="跑得快" value="paodekuai"></el-option>
                                <el-option label="十三水" value="shisanshui"></el-option>
                                <el-option label="厦门麻将" value="xmMahjong"></el-option>
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
                    <ls-game-add @refresh="getUserList" action="add" :city-list="cityList" :county-list="countyList">
                        <el-button type="text" slot="trigger" size="small">新增游戏区域</el-button>
                    </ls-game-add>
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
                    <el-table-column prop="gameNameStr" label="游戏名称"></el-table-column>
                    <el-table-column prop="city" label="城市"></el-table-column>
                    <el-table-column prop="county" label="县区"></el-table-column>
                    <el-table-column fixed="right" label="操作" min-width="120">
                        <template slot-scope="scope">
                            <ls-game-add
                                class="m-l-10 inline"
                                title="编辑游戏区域"
                                action="edit"
                                :city-list="cityList"
                                :county-list="countyList"
                                :game="scope.row"
                                @refresh="getUserList"
                            >
                                <el-button type="text" slot="trigger" size="small">编辑</el-button>
                            </ls-game-add>

                            <ls-dialog
                                class="m-l-10 inline"
                                content="是否删除游戏区域吗？删除后，操作将无法回退"
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
import { apiGameList, apiDelGame, apiSearchList } from '@/api/marketing'
import { RequestPaging } from '@/utils/util'
import LsPagination from '@/components/ls-pagination.vue'
import LsDialog from '../../components/ls-dialog.vue'
import LsGameAdd from '@/components/ls-game-add.vue'
@Component({
    components: {
        LsGameAdd,
        LsPagination,
        LsDialog
    }
})
export default class UserManagement extends Vue {
    form = {
        gameName: '',
        city: '',
        county: ''
    }
    cityList = []
    countyList = []
    // 分页查询
    pager: RequestPaging = new RequestPaging()
    apiUserList = apiGameList

    // 查询按钮
    query() {
        this.pager.page = 1
        this.getUserList()
    }

    //获取用户列表数据
    getUserList() {
        this.pager.request({
            callback: apiGameList,
            params: {
                ...this.form
            }
        })
    }

    // 重置按钮
    onReset() {
        this.form = {
            gameName: '',
            city: '',
            county: ''
        }
        this.pager.page = 1
        this.getUserList()
    }

    deleteGame(good: any) {
        apiDelGame({
            _id: good._id
        }).then(res => {
            this.pager.page = 1
            this.getUserList()
        })
    }

    getSearchList() {
        apiSearchList({}).then(res => {
            this.cityList = res.city
            this.countyList = res.county
        })
    }

    created() {
        this.getUserList()
        this.getSearchList()
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
