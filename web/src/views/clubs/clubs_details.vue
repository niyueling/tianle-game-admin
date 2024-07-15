<!-- 用户详情 -->
<template>
    <div class="user-details">
        <!-- 导航头部 -->
        <div class="ls-card">
            <el-page-header @back="$router.go(-1)" content="俱乐部详情" />
        </div>

        <div class="m-t-16 flex col-top">
            <!-- 基本资料 -->
            <div class="ls-card card-height">
                <div class="card-title">基本资料</div>
                <div class="content m-t-16 m-b-16">
                    <el-row :gutter="20">
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">名称</div>
                            <div class="flex">
                                <div class="m-r-10">{{ user_info.name }}</div>
                                <ls-club-rename
                                    title="修改俱乐部名称"
                                    :id="user_info._id"
                                    :name="user_info.name"
                                    :clubId="user_info.shortId"
                                    @refresh="userDetail"
                                >
                                    <el-button type="text" slot="trigger" size="small">修改</el-button>
                                </ls-club-rename>
                            </div>
                        </el-col>
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">shortId</div>
                            <div
                                class="flex"
                                @click="navicatToUserByShortId(user_info.shortId)"
                                style="color: #3967ff; cursor: pointer"
                            >
                                {{ user_info.shortId }}
                            </div>
                        </el-col>
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">队长昵称</div>
                            <div class="flex">
                                <div class="m-r-10">
                                    {{ player.name }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="4" class="flex-col col-center item" v-if="account.realName">
                            <div class="lighter m-b-8">真实姓名</div>
                            <div class="flex">
                                <div class="m-r-10">
                                    {{ account.realName }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">队长ID</div>
                            <div class="flex">
                                <div class="m-r-10">
                                    {{ player.shortId }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">持有钻石</div>
                            <div class="flex">
                                <div class="m-r-10">
                                    {{ player.gem }}
                                </div>
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
                        <el-form-item label="俱乐部编号" prop="">
                            <div>
                                {{ user_info._id }}
                            </div>
                        </el-form-item>
                        <el-form-item label="名称" prop="">
                            <div>
                                {{ user_info.name }}
                            </div>
                        </el-form-item>
                        <el-form-item label="游戏类型" prop="" v-if="user_info.gameList">
                            <div>
                                {{ user_info.gameList }}
                            </div>
                        </el-form-item>
                        <el-form-item label="战队人数" prop="">
                            <div
                                @click="navicatToMembersByShortId(user_info.shortId)"
                                style="color: #3967ff; cursor: pointer"
                            >
                                {{ user_info.members }} 人，{{ user_info.clubGold }} 金币
                            </div>
                        </el-form-item>
                        <el-form-item label="待审批人数" prop="">
                            <div
                                @click="navicatToApplyByShortId(user_info.shortId)"
                                style="color: #3967ff; cursor: pointer"
                            >
                                {{ user_info.applys }} 人
                            </div>
                        </el-form-item>
                        <el-form-item label="今日" prop="">
                            <div>
                                <span
                                    @click="navicatToCreatorRecordByShortId(user_info.shortId, 1)"
                                    style="color: #00ced1; cursor: pointer"
                                    >圈主增加：{{ user_info.mainAddAmount }}金币，</span
                                >
                                <span
                                    @click="navicatToCreatorRecordByShortId(user_info.shortId, 2)"
                                    style="color: #3967ff; cursor: pointer"
                                    >圈主减少：{{ user_info.mainReduceAmount }}金币，</span
                                >
                                <span
                                    @click="navicatToClubRecordByShortId(user_info.shortId, 1)"
                                    style="color: #4caf50; cursor: pointer"
                                    >消耗：{{ user_info.comsumeAmount }}金币，</span
                                >
                                <span
                                    @click="navicatToClubRecordByShortId(user_info.shortId, 2)"
                                    style="color: #7b1fa2; cursor: pointer"
                                    >输赢：{{ user_info.winFailAmount }}金币</span
                                >
                            </div>
                        </el-form-item>
                        <el-form-item label="昨日" prop="">
                            <div>
                                <span
                                    @click="navicatToCreatorRecordByShortId(user_info.shortId, 1)"
                                    style="color: #00ced1; cursor: pointer"
                                    >圈主增加：{{ user_info.mainAddYestodayAmount }}金币，</span
                                >
                                <span
                                    @click="navicatToCreatorRecordByShortId(user_info.shortId, 2)"
                                    style="color: #3967ff; cursor: pointer"
                                    >圈主减少：{{ user_info.mainReduceYestodayAmount }}金币，</span
                                >
                                <span
                                    @click="navicatToClubRecordByShortId(user_info.shortId, 1)"
                                    style="color: #4caf50; cursor: pointer"
                                    >消耗：{{ user_info.comsumeYestodayAmount }}金币，</span
                                >
                                <span
                                    @click="navicatToClubRecordByShortId(user_info.shortId, 2)"
                                    style="color: #7b1fa2; cursor: pointer"
                                    >输赢：{{ user_info.winFailYestodayAmount }}金币</span
                                >
                            </div>
                        </el-form-item>
                        <el-form-item label="俱乐部状态">
                            <div>
                                {{ user_info.state == 'on' ? '正常' : '暂停' }}
                            </div>
                        </el-form-item>
                        <el-form-item label="创建时间">
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
import { apiClubDetail } from '@/api/club/club'
import LsClubRename from '@/components/user/ls-club-rename.vue'
@Component({
    components: {
        LsClubRename
    }
})
export default class UserDetails extends Vue {
    /** S Data **/
    club_id = ''

    // 用户信息
    user_info = {}

    player = {}

    account = {
        realName: ''
    }

    // 验证规则
    userRules = {}
    transactionRules = {}

    /** E Data **/

    /** S Methods **/
    // 获取用户信息
    userDetail() {
        apiClubDetail({
            club_id: this.club_id
        })
            .then((res: any) => {
                this.user_info = res.club
                if (res.player) {
                    this.player = res.player
                }
                if (res.account) {
                    this.account = res.account
                }
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

    navicatToMembersByShortId(shortId: any) {
        this.$router.push({
            path: '/clubs/members',
            query: {
                shortId
            }
        })
    }

    navicatToApplyByShortId(shortId: any) {
        this.$router.push({
            path: '/clubs/apply',
            query: {
                shortId
            }
        })
    }

    navicatToCreatorRecordByShortId(shortId: any, action: any) {
        this.$router.push({
            path: '/clubs/creator_gold',
            query: {
                shortId,
                action
            }
        })
    }

    navicatToClubRecordByShortId(shortId: any, action: any) {
        this.$router.push({
            path: '/user/gold',
            query: {
                shortId,
                keyword: action == 1 ? '游戏消耗' : '游戏输赢'
            }
        })
    }

    created() {
        const query: any = this.$route.query
        if (query.id) {
            this.club_id = query.id
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
