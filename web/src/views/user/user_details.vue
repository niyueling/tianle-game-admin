<!-- 用户详情 -->
<template>
    <div class="user-details">
        <!-- 导航头部 -->
        <div class="ls-card">
            <el-page-header @back="$router.go(-1)" content="用户详情" />
        </div>
        <!-- 中间用户数据 -->

        <div class="m-t-16 flex col-top">
            <!-- 基本资料 -->
            <div class="ls-card card-height">
                <div class="card-title">基本资料</div>
                <div class="content m-t-16 m-b-16">
                    <el-row :gutter="20">
                        <el-col :span="4" class="flex-col col-center">
                            <div class="lighter m-b-8">用户头像</div>
                            <img :src="user_info.avatar" width="58" height="58" class="avatar" />
                        </el-col>
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">金豆</div>
                            <div class="flex">
                                <div class="m-r-10">
                                    {{ user_info.gold }}
                                </div>
                                <ls-user-change
                                    title="金豆调整"
                                    :value="user_info.gold"
                                    :type="2"
                                    :userId="user_id"
                                    @refresh="userDetail"
                                >
                                    <el-button type="text" slot="trigger" size="small">调整</el-button>
                                </ls-user-change>
                            </div>
                        </el-col>
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">钻石</div>
                            <div class="flex">
                                <div class="m-r-10">
                                    {{ user_info.diamond }}
                                </div>
                                <ls-user-change
                                    title="钻石调整"
                                    :value="user_info.diamond"
                                    :type="1"
                                    :userId="user_id"
                                    @refresh="userDetail"
                                >
                                    <el-button type="text" slot="trigger" size="small">调整</el-button>
                                </ls-user-change>
                            </div>
                        </el-col>
                      <el-col :span="4" class="flex-col col-center item">
                        <div class="lighter m-b-8">代金券</div>
                        <div class="flex">
                          <div class="m-r-10">
                            {{ user_info.voucher }}
                          </div>
                          <ls-user-change
                              title="代金券调整"
                              :value="user_info.voucher"
                              :type="5"
                              :userId="user_id"
                              @refresh="userDetail"
                          >
                            <el-button type="text" slot="trigger" size="small">调整</el-button>
                          </ls-user-change>
                        </div>
                      </el-col>
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">红包</div>
                            <div class="flex">
                                <div class="m-r-10">
                                    {{ user_info.redPocket }}
                                </div>
                                <ls-user-change
                                    title="红包调整"
                                    :value="user_info.redPocket"
                                    :type="3"
                                    :userId="user_id"
                                    :rank="1"
                                    @refresh="userDetail"
                                >
                                    <el-button type="text" slot="trigger" size="small">调整</el-button>
                                </ls-user-change>
                            </div>
                        </el-col>
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">抽奖次数</div>
                            <div class="flex">
                                <div class="m-r-10">
                                    {{ user_info.turntableTimes }}
                                </div>
                                <ls-user-change
                                    title="抽奖次数调整"
                                    :value="user_info.turntableTimes"
                                    :type="4"
                                    :userId="user_id"
                                    @refresh="userDetail"
                                >
                                    <el-button type="text" slot="trigger" size="small">调整</el-button>
                                </ls-user-change>
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
                                {{ user_info.shortId }}
                            </div>
                        </el-form-item>
                        <el-form-item label="用户昵称" prop="">
                            <div class="flex">
                                <div class="ls-input m-r-10">
                                    {{ user_info.nickname }}
                                </div>
                                <popover-input-user
                                    :width="300"
                                    changeType="input"
                                    type="text"
                                    :value="user_info.nickname"
                                    @confirm="userSetInfo($event, 'nickname')"
                                >
                                    <el-button class="ls-edit" type="text" icon="el-icon-edit"></el-button>
                                </popover-input-user>
                            </div>
                        </el-form-item>

                        <el-form-item label="性别" prop="">
                            <div class="flex">
                                <div class="ls-input m-r-10">
                                    {{ user_info.sex == 1 ? '男' : '女' }}
                                </div>
                                <popover-input-user
                                    :width="300"
                                    type="text"
                                    :value="user_info.sex"
                                    changeType="sex"
                                    @confirm="userSetInfo($event, 'sex')"
                                >
                                    <el-button class="ls-edit" type="text" icon="el-icon-edit"></el-button>
                                </popover-input-user>
                            </div>
                        </el-form-item>
                        <el-form-item label="手机号码" prop="">
                            <div class="flex">
                                <div class="ls-input m-r-10">
                                    {{ user_info.phone || '暂未绑定' }}
                                </div>
                                <popover-input-user
                                    :width="400"
                                    type="text"
                                    changeType="input"
                                    :value="user_info.phone"
                                    @confirm="userSetInfo($event, 'phone')"
                                >
                                    <el-button class="ls-edit" type="text" icon="el-icon-edit"></el-button>
                                </popover-input-user>
                            </div>
                        </el-form-item>
                        <el-form-item label="注册时间">
                            <div>
                                {{ user_info.createAt }}
                            </div>
                        </el-form-item>
                    </div>
                </el-form>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { apiUserDetail, apiUserSetInfo } from '@/api/user/user'
import LsDialog from '@/components/ls-dialog.vue'
import LsUserChange from '@/components/user/ls-user-change.vue'
import PopoverInputUser from '@/components/user/popover-input-user.vue'
@Component({
    components: {
        LsDialog,
        LsUserChange,
        PopoverInputUser
    }
})
export default class UserDetails extends Vue {
    /** S Data **/
    user_id = ''

    // 用户信息
    user_info = {
        account: {}
    }

    // 验证规则
    userRules = {}

    // 获取用户信息
    userDetail() {
        apiUserDetail({
            user_id: this.user_id
        })
            .then((res: any) => {
                this.user_info = res
                if (!this.user_info.account) {
                    this.user_info.account = {}
                }
                console.log(this.user_info)
            })
            .catch((res: any) => {})
    }
    // 修改用户信息
    userSetInfo(val: string, type: string) {
        apiUserSetInfo({
            user_id: this.user_id,
            field: type,
            value: val
        })
            .then((res: any) => {
                this.userDetail()
            })
            .catch((res: any) => {})
    }

    navicatToUserByShortId(shortId: any) {
        this.$router.push({
            path: '/user/lists',
            query: {
                shortId
            }
        })
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
