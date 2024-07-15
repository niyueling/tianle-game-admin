<template>
    <div class="help_manage">
        <div class="ls-card">
            <!-- 操作提示 -->
            <el-alert
                title="温馨提示：平台发布的公共邮件和圈主邮件，包含普通邮件和礼物邮件。用户在游戏中的领取记录统计；"
                type="info"
                show-icon
                :closable="false"
            />
            <div class="member-search m-t-16">
                <el-form ref="form" inline :model="form" label-width="70px" size="small">
                    <el-form-item label="用户信息">
                        <el-select v-model="isNameSN" placeholder="全部" style="width: 120px">
                            <el-option label="用户ShortId" value="1"></el-option>
                            <el-option label="用户昵称" value="2"></el-option>
                            <el-option label="手机号码" value="3"></el-option>
                            <el-option label="真实姓名" value="4"></el-option>
                        </el-select>
                        <el-input v-if="isNameSN == 1" v-model="form.playerShortId" placeholder=""> </el-input>
                        <el-input v-if="isNameSN == 2" v-model="form.name" placeholder=""></el-input>
                        <el-input v-if="isNameSN == 3" v-model="form.playerPhone" placeholder=""></el-input>
                        <el-input v-if="isNameSN == 4" v-model="form.realName" placeholder=""></el-input>
                    </el-form-item>
                    <el-form-item label="读取状态">
                        <div class="flex">
                            <el-select style="width: 120px" v-model="form.state" placeholder="全部">
                                <el-option label="全部" value></el-option>
                                <el-option label="未读" :value="1"></el-option>
                                <el-option label="已读" :value="2"></el-option>
                            </el-select>
                        </div>
                    </el-form-item>
                    <el-form-item label="礼物状态">
                        <div class="flex">
                            <el-select style="width: 120px" v-model="form.giftState" placeholder="全部">
                                <el-option label="全部" value></el-option>
                                <el-option label="未领" :value="1"></el-option>
                                <el-option label="已领" :value="2"></el-option>
                            </el-select>
                        </div>
                    </el-form-item>
                    <el-form-item label="邮件类型">
                        <div class="flex">
                            <el-select style="width: 120px" v-model="form.type" placeholder="全部">
                                <el-option label="全部" value></el-option>
                                <el-option label="普通邮件" value="notice"></el-option>
                                <el-option label="礼物邮件" value="noticeGift"></el-option>
                            </el-select>
                        </div>
                    </el-form-item>

                    <el-button size="small" type="primary" @click="query()">查询</el-button>
                    <el-button size="small" @click="onReset()">重置</el-button>
                </el-form>
            </div>
        </div>

        <div class="ls-card m-t-24">
            <div class="m-t-24">
                <el-table :data="pager.lists" v-loading="pager.loading" size="mini" style="width: 100%">
                    <el-table-column sortable prop="_id" label="ID" />
                    <el-table-column sortable prop="playerShortId" label="用户ID" />
                    <el-table-column sortable prop="username" label="用户昵称" />
                    <el-table-column sortable prop="phone" label="手机号" />
                    <el-table-column prop="title" label="标题"></el-table-column>
                    <el-table-column prop="content" label="内容" min-width="200"></el-table-column>
                    <el-table-column sortable prop="stateStr" label="读取状态" />
                    <el-table-column sortable prop="giftStateStr" label="领取状态" />
                </el-table>

                <!-- 分页 -->
                <div class="m-t-24 flex row-right">
                    <ls-pagination v-model="pager" @change="getEmailList" />
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import LsPagination from '@/components/ls-pagination.vue'
import { apiReceiveEmailLists } from '@/api/content/email'
import { RequestPaging } from '@/utils/util'

@Component({
    components: {
        LsPagination
    }
})
export default class HelpManage extends Vue {
    form = {
        state: '',
        name: '',
        playerShortId: '',
        playerPhone: '',
        realName: '',
        giftState: '',
        mail: ''
    }

    isNameSN = '' // 选择用户编号1 选择用户昵称2 手机号码3,真实姓名4

    // 分页
    pager: RequestPaging = new RequestPaging()

    // 获取公告管理列表
    getEmailList() {
        this.pager
            .request({
                callback: apiReceiveEmailLists,
                params: {
                    ...this.form
                }
            })
            .catch(() => {
                this.$message.error('数据请求失败，刷新重载!')
            })
    }

    // 重置按钮
    onReset() {
        this.form = {
            state: '',
            name: '',
            playerShortId: '',
            playerPhone: '',
            realName: '',
            giftState: '',
            mail: ''
        }
        this.getEmailList()
    }

    // 查询按钮
    query() {
        this.pager.page = 1
        this.getEmailList()
    }

    created() {
        const query: any = this.$route.query
        if (query.mail) {
            this.form.mail = query.mail
        }
        this.getEmailList()
    }
}
</script>

<style lang="scss" scoped></style>
