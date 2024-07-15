<!-- 用户详情 -->
<template>
    <div class="user-details">
        <!-- 导航头部 -->
        <div class="ls-card">
            <el-page-header @back="$router.go(-1)" content="代理详情" />
        </div>
        <!-- 中间用户数据 -->

        <div class="m-t-16 flex col-top">
            <!-- 基本资料 -->
            <div class="ls-card card-height">
                <div class="card-title">基本资料</div>
                <div class="content m-t-16 m-b-16">
                    <el-row :gutter="20">
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">代理昵称</div>
                            <div class="" style="margin: 7px 0">{{ user_info.username }}</div>
                        </el-col>
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">角色</div>
                            <div class="" style="margin: 7px 0">{{ user_info.role }}</div>
                        </el-col>
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">钻石</div>
                            <div class="flex">
                                <div class="m-r-10">
                                    {{ user_info.gem }}
                                </div>
                                <ls-agency-change
                                    title="钻石调整"
                                    :value="user_info.gem"
                                    :type="1"
                                    :userId="user_info._id"
                                    @refresh="userDetail"
                                >
                                    <el-button type="text" slot="trigger" size="small">调整</el-button>
                                </ls-agency-change>
                            </div>
                        </el-col>
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">金币</div>
                            <div class="flex">
                                <div class="m-r-10">
                                    {{ user_info.gold }}
                                </div>
                                <ls-agency-change
                                    title="金币调整"
                                    :value="user_info.gold"
                                    :type="2"
                                    :userId="user_info._id"
                                    @refresh="userDetail"
                                >
                                    <el-button type="text" slot="trigger" size="small">调整</el-button>
                                </ls-agency-change>
                            </div>
                        </el-col>
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">登录密码</div>
                            <div class="flex">
                                <div class="m-r-10">******</div>
                                <ls-agency-resetpwd
                                    title="设置密码"
                                    :userName="user_info.username"
                                    :userId="user_info._id"
                                    @refresh="userDetail"
                                >
                                    <el-button type="text" slot="trigger" size="small">修改</el-button>
                                </ls-agency-resetpwd>
                            </div>
                        </el-col>
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">绑定俱乐部</div>
                            <div class="flex">
                                <div class="m-r-10">{{ user_info.club_ids ? user_info.club_ids : '暂未绑定' }}</div>
                                <ls-agency-bindclub
                                    title="绑定俱乐部"
                                    :userName="user_info.username"
                                    :userId="user_info._id"
                                    @refresh="userDetail"
                                >
                                    <el-button type="text" slot="trigger" size="small">绑定</el-button>
                                </ls-agency-bindclub>
                            </div>
                        </el-col>
                    </el-row>
                </div>
                <el-form
                    :rules="userRules"
                    ref="userRef"
                    :model="user_info"
                    label-width="120px"
                    size="small"
                    class="flex col-top"
                >
                    <div class="">
                        <el-form-item label="用户编号" prop="">
                            <div>
                                {{ user_info._id }}
                            </div>
                        </el-form-item>
                        <el-form-item label="用户昵称" prop="">
                            <div>
                                {{ user_info.username }}
                            </div>
                        </el-form-item>
                        <el-form-item label="消耗钻石" prop="">
                            <div>
                                {{ user_info.spendGem }}
                            </div>
                        </el-form-item>
                        <el-form-item label="消耗金币" prop="">
                            <div>
                                {{ user_info.spendGold }}
                            </div>
                        </el-form-item>

                        <el-form-item label="我邀请的人数" prop="">
                            <div class="flex">
                                <div class="m-r-10">
                                    {{ user_info.num }}
                                </div>
                                <!--                                <el-button slot="trigger" type="text" size="small" @click="toInvitationList(user_info)"-->
                                <!--                                    >我邀请的人数</el-button-->
                                <!--                                >-->
                            </div>
                        </el-form-item>

                        <el-form-item label="最近登录时间" v-if="user_info.login_time">
                            <div>
                                {{ user_info.login_time }}
                            </div>
                        </el-form-item>
                        <el-form-item label="最近登录IP" v-if="user_info.last_login_ip">
                            <div>
                                {{ user_info.last_login_ip }}
                            </div>
                        </el-form-item>
                        <el-form-item label="">
                            <el-button @click="ApplyClick()">成员审核</el-button>
                            <ls-dialog
                                v-if="user_info.disable == 0"
                                class="m-l-10 inline"
                                content="是否取消冻结该账户？同意后，该代理将马上恢复代理身份"
                                @confirm="handleFrozen(1)"
                            >
                                <el-button slot="trigger">取消冻结</el-button>
                            </ls-dialog>
                            <ls-dialog
                                v-else
                                class="m-l-10 inline"
                                content="是否确认冻结该账户？同意后，该代理将马上失去代理身份"
                                @confirm="handleFrozen(1)"
                            >
                                <el-button slot="trigger">冻结账户</el-button>
                            </ls-dialog>

                            <ls-dialog
                                v-if="user_info.partner == 1"
                                class="m-l-10 inline"
                                content="是否取消该账户的伙伴关系？同意后，该代理将马上失去伙伴身份"
                                @confirm="handleFrozen(2)"
                            >
                                <el-button slot="trigger">取消伙伴</el-button>
                            </ls-dialog>
                            <ls-dialog
                                v-else
                                class="m-l-10 inline"
                                content="是否恢复该账户的伙伴身份？同意后，该代理将马上恢复代理伙伴身份"
                                @confirm="handleFrozen(2)"
                            >
                                <el-button slot="trigger">恢复伙伴</el-button>
                            </ls-dialog>
                        </el-form-item>
                    </div>
                </el-form>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { apiAgencyDetail, apiAgencySetInfo } from '@/api/agency/agency'
import LsDialog from '@/components/ls-dialog.vue'
import LsAgencyResetpwd from '@/components/user/ls-agency-resetpwd.vue'
import LsAgencyBindclub from '@/components/user/ls-agency-bindclub.vue'
import LsAgencyChange from '@/components/user/ls-agency-change.vue'
@Component({
    components: {
        LsAgencyChange,
        LsAgencyBindclub,
        LsAgencyResetpwd,
        LsDialog
    }
})
export default class UserDetails extends Vue {
    /** S Data **/
    user_id = ''

    // 用户信息
    user_info = {
        _id: '', // 用户id
        username: '', // 用户昵称
        login_time: '', // 最后的登录时间
        disable: '', // 是否禁用：1-是；0-否（可用于显示加入黑名单和放出黑名单按钮）
        fans: '', // 团队人数
        gem: '', // 钻石
        gold: '', // 金币
        num: 0,
        spendGem: 0,
        spendGold: 0,
        last_login_ip: '',
        club_ids: '',
        partner: ''
    }

    // 验证规则
    userRules = {}
    transactionRules = {}

    // 获取用户信息
    userDetail() {
        apiAgencyDetail({
            user_id: this.user_id
        })
            .then((res: any) => {
                this.user_info = res.user_info
            })
            .catch((res: any) => {})
    }
    // 修改用户信息
    userSetInfo(val: any, type: string) {
        apiAgencySetInfo({
            user_id: this.user_id,
            field: type,
            value: val
        })
            .then((res: any) => {
                this.userDetail()
            })
            .catch((res: any) => {})
    }

    // 成员审核
    ApplyClick() {
        this.$router.push({
            path: '/agency/apply',
            query: {
                id: this.user_info._id
            }
        })
    }

    handleFrozen(action: any) {
        if (action == 1) {
            this.userSetInfo(this.user_info.disable == '1' ? '0' : '1', 'disable')
        }
        if (action == 2) {
            this.userSetInfo(this.user_info.partner == '1' ? '0' : '1', 'partner')
        }
    }

    created() {
        const query: any = this.$route.query
        if (query.id) {
            this.user_id = query.id
        }

        this.userDetail()
    }
}
</script>

<style lang="scss" scoped>
.content {
    background: #f5f5f5;
    padding: 11px 0;
}

.item {
    margin-top: 18px;
}

.avatar {
    border-radius: 29px;
}

.ls-card {
    // .ls-input {
    // 	width: 133px;
    // }

    // .ls-input-textarea {
    // 	width: 300px;
    // }
    .card-title {
        font-size: 14px;
        font-weight: 500;
        padding-bottom: 20px;
        border-bottom: 1px solid $--background-color-base;
    }
}

.card-height {
    // height: 600px;
    //box-sizing: border-box;
}

.user-details {
    min-height: calc(100vh - #{$--header-height} - 92px);
    margin-bottom: 60px;

    &__header {
        flex: none;
    }
}
</style>
