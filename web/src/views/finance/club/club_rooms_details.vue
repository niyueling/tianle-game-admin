<!-- 用户详情 -->
<template>
    <div class="user-details">
        <!-- 导航头部 -->
        <div class="ls-card">
            <el-page-header @back="$router.go(-1)" content="房间详情" />
        </div>

        <div class="m-t-16 flex col-top">
            <!-- 基本资料 -->
            <div class="ls-card card-height">
                <div class="card-title">基本资料</div>
                <div class="content m-t-16 m-b-16">
                    <el-row :gutter="20">
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">房间ID</div>
                            <div class="flex">
                                <div class="m-r-10">{{ user_info.roomNum }}</div>
                            </div>
                        </el-col>
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">房主信息</div>
                            <div class="flex">
                                <div class="m-r-10">{{ user_info.player.name }} ({{ user_info.player.shortId }})</div>
                            </div>
                        </el-col>
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">俱乐部信息</div>
                            <div class="flex">
                                <div class="m-r-10">{{ user_info.club.name }} ({{ user_info.club.shortId }})</div>
                            </div>
                        </el-col>
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">游戏类型</div>
                            <div class="flex">
                                <div class="m-r-10">
                                    {{ formatGameType(user_info.category) }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">状态</div>
                            <div class="flex">
                                <div class="m-r-10">
                                    {{ formatRoomState(user_info.roomState) }}
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
                        <el-form-item label="房间编号" prop="">
                            <div>
                                {{ user_info._id }}
                            </div>
                        </el-form-item>
                        <el-form-item label="局数" prop="">
                            <div>
                                {{ user_info.rule.juShu }}
                            </div>
                        </el-form-item>
                        <el-form-item label="参与人数" prop="">
                            <div>
                                {{ user_info.rule.playerCount }}
                            </div>
                        </el-form-item>
                        <el-form-item label="明牌">
                            <div>
                                {{ !user_info.guanPai ? '是' : '否' }}
                            </div>
                        </el-form-item>
                        <el-form-item label="房间类型" prop="">
                            <div>
                                {{ user_info.roomType }}
                            </div>
                        </el-form-item>
                        <el-form-item label="创建时间">
                            <div>
                                {{ user_info.createAt }}
                            </div>
                        </el-form-item>
                    </div>
                </el-form>
                <div class="card-title">游戏结算</div>
                <div class="content m-t-16 m-b-16">
                    <el-row :gutter="20" v-for="(item, index) in user_info.scores" key="index">
                        <el-col :span="5" class="flex-col col-center item">
                            <div class="lighter m-b-8">用户ID</div>
                            <div class="flex">
                                <div class="m-r-10">{{ item.shortId }}</div>
                            </div>
                        </el-col>
                        <el-col :span="5" class="flex-col col-center item">
                            <div class="lighter m-b-8">用户名字</div>
                            <div class="flex">
                                <div class="m-r-10">
                                    {{ item.name }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="5" class="flex-col col-center item">
                            <div class="lighter m-b-8">战队金币</div>
                            <div class="flex">
                                <div class="m-r-10">
                                    {{ item.clubGold }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="5" class="flex-col col-center item">
                            <div class="lighter m-b-8">钻石</div>
                            <div class="flex">
                                <div class="m-r-10">
                                    {{ item.gem }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">累计积分</div>
                            <div class="flex">
                                <div class="m-r-10">{{ item.score }} 分</div>
                            </div>
                        </el-col>
                    </el-row>
                </div>

                <div class="card-title">小局回放</div>
                <div class="content m-t-16 m-b-16">
                    <el-row :gutter="20" v-for="(item1, index1) in user_info.games" :key="index1">
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">局数</div>
                            <div class="flex">
                                <div class="m-r-10">第 {{ item1.juShu }} 局</div>
                            </div>
                        </el-col>
                        <el-col
                            :span="4"
                            class="flex-col col-center item"
                            v-for="(item2, index2) in item1.record"
                            :key="index2"
                        >
                            <div class="lighter m-b-8">
                                {{ item2.name }} ({{ item1.playersInfo[index2].model.shortId }})
                            </div>
                            <div class="flex">
                                <div class="m-r-10">{{ item2.score }} 分</div>
                            </div>
                        </el-col>
                        <el-col :span="4" class="flex-col col-center item">
                            <div class="lighter m-b-8">时间</div>
                            <div class="flex">
                                <div class="m-r-10">{{ user_info.gameTimes[index1].createAt }}</div>
                            </div>
                        </el-col>
                    </el-row>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { apiClubRoomDetail } from '@/api/finance/finance'
@Component({
    components: {}
})
export default class UserDetails extends Vue {
    /** S Data **/
    room_id = ''
    index = 0
    index1 = 0

    // 用户信息
    user_info = {}

    player = {}

    account = {
        realName: ''
    }

    // 验证规则
    userRules = {}
    transactionRules = {}

    formatRoomState(state: string) {
        if (state == 'normal_last') {
            return '正常结束'
        }
        if (state == 'normal') {
            return '对局中'
        }
        if (state == 'zero_ju') {
            return '零局解散'
        }
        if (state == 'dissolve') {
            return '解散'
        }
        if (state == 'dissolve_last') {
            return '尾局解散'
        }
    }

    formatGameType(game: string) {
        if (game == 'paodekuai') {
            return '跑得快'
        }
        if (game == 'biaofen') {
            return '标分'
        }
        if (game == 'shisanshui') {
            return '十三水'
        }
        if (game == 'majiang') {
            return '麻将'
        }
        if (game == 'zhadan') {
            return '炸弹'
        }
        if (game == 'xmmajiang') {
            return '厦门麻将'
        }
    }

    userDetail() {
        apiClubRoomDetail({
            room_id: this.room_id
        })
            .then((res: any) => {
                res.games = JSON.parse(res.games)
                this.user_info = res

                console.log(res.games)
            })
            .catch((res: any) => {})
    }

    created() {
        const query: any = this.$route.query
        if (query.id) {
            this.room_id = query.id
        }

        this.userDetail()
    }
}
</script>

<style lang="scss" scoped>
.content {
    background: #f5f5f5;
    padding: 11px 0;
    border-bottom: 1px dotted darkgray;
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
