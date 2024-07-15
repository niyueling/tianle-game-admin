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
            <el-col :span="5" class="flex-col col-center item">
              <div class="lighter m-b-8">房间ID</div>
              <div class="flex">
                <div class="m-r-10">{{ user_info.roomNum }}</div>
              </div>
            </el-col>
            <el-col :span="5" class="flex-col col-center item">
              <div class="lighter m-b-8">房主信息</div>
              <div class="flex">
                <div class="m-r-10">{{ user_info.player.nickname }} ({{ user_info.player.shortId }})</div>
              </div>
            </el-col>
            <el-col :span="4" class="flex-col col-center item">
              <div class="lighter m-b-8">俱乐部信息</div>
              <div class="flex">
                <div class="m-r-10" v-if="user_info.clubName !== '/'">
                  {{ user_info.clubName }} ({{ user_info.clubId }})
                </div>
                <div class="m-r-10" v-else>/</div>
              </div>
            </el-col>
            <el-col :span="5" class="flex-col col-center item">
              <div class="lighter m-b-8">游戏类型</div>
              <div class="flex">
                <div class="m-r-10">
                  {{ formatGameType(user_info.category) }}
                </div>
              </div>
            </el-col>
            <el-col :span="5" class="flex-col col-center item">
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
            <el-form-item label="房间类型" prop="">
              <div>
                {{ formatRoomType(user_info) }}
              </div>
            </el-form-item>
            <el-form-item label="钻石" prop="">
              <div v-if="user_info.clubName !== '/'">战队付</div>
              <div v-else-if="user_info.rule.isPublic">/</div>
              <div v-else-if="user_info.rule.share">AA付</div>
              <div v-else-if="user_info.rule.winnerPay">赢家付</div>
              <div v-else>房主付</div>
            </el-form-item>
            <el-form-item label="对局规则" prop="">
              <div style="color: #3967ff; cursor: pointer" @click="ruleClick">查看</div>
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
          <el-row :gutter="20">
            <el-col :span="5" class="flex-col col-center item">
              <div class="lighter m-b-8">用户ID</div>
              <div class="flex" v-for="(item, index) in user_info.scores" key="index">
                <div class="m-r-10" style="font-size: 10px; margin-top: 10px">{{ item.shortId }}</div>
              </div>
            </el-col>
            <el-col :span="5" class="flex-col col-center item">
              <div class="lighter m-b-8">用户名字</div>
              <div class="flex" v-for="(item, index) in user_info.scores" key="index">
                <div class="m-r-10" style="font-size: 10px; margin-top: 10px">
                  {{ item.name }}
                </div>
              </div>
            </el-col>
            <el-col :span="5" class="flex-col col-center item">
              <div class="lighter m-b-8">金币</div>
              <div class="flex" v-for="(item, index) in user_info.scores" key="index">
                <div class="m-r-10" style="font-size: 10px; margin-top: 10px">
                  {{ item.gold }}
                </div>
              </div>
            </el-col>
            <el-col :span="5" class="flex-col col-center item">
              <div class="lighter m-b-8">钻石</div>
              <div class="flex" v-for="(item, index) in user_info.scores" key="index">
                <div class="m-r-10" style="font-size: 10px; margin-top: 10px">
                  {{ item.gem }}
                </div>
              </div>
            </el-col>
            <el-col :span="4" class="flex-col col-center item">
              <div class="lighter m-b-8">累计积分</div>
              <div class="flex" v-for="(item, index) in user_info.scores" key="index">
                <div class="m-r-10" style="font-size: 10px; margin-top: 10px">{{ item.score }} 分</div>
              </div>
            </el-col>
          </el-row>
        </div>

        <div class="card-title" v-if="user_info.games.length > 0">小局回放</div>
        <div class="content m-t-16 m-b-16" v-if="user_info.games.length > 0">
          <el-row :gutter="20">
            <el-col :span="4" class="flex-col col-center item">
              <div class="lighter m-b-8">局数</div>
            </el-col>
            <el-col
                :span="4"
                class="flex-col col-center item"
                v-for="(item2, index2) in user_info.games[0].record"
                :key="index2"
            >
              <div class="lighter m-b-8">{{ item2.nickname }} ({{ item2.shortId }})</div>
            </el-col>
            <el-col :span="4" class="flex-col col-center item">
              <div class="lighter m-b-8">时间</div>
            </el-col>
          </el-row>
          <el-row :gutter="20" v-for="(item1, index1) in user_info.games" :key="index1">
            <el-col :span="4" class="flex-col col-center item">
              <div
                  class="lighter m-b-8"
                  style="color: #3967ff; cursor: pointer"
                  @click="
                                    DetailsClick(user_info.gameTimes[user_info.gameTimes.length - item1.juShu].gameId)
                                "
              >
                第 {{ item1.juShu }} 局
              </div>
            </el-col>
            <el-col
                :span="4"
                class="flex-col col-center item"
                v-for="(item2, index2) in user_info.games[index1].record"
                :key="index2"
            >
              <div class="lighter m-b-8">{{ item2.score }}</div>
            </el-col>
            <el-col :span="4" class="flex-col col-center item">
              <div class="lighter m-b-8">
                {{ user_info.gameTimes[user_info.gameTimes.length - item1.juShu].createAt }}
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
import { apiUserRoomDetail } from '@/api/finance/finance'
@Component({
  components: {}
})
export default class UserDetails extends Vue {
  /** S Data **/
  room_id = ''
  index = 0
  index1 = 0

  // 用户信息
  user_info: any = {
    club: {}
  }

  player = {}

  account = {
    realName: ''
  }

  // 验证规则
  userRules = {}

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

  formatRoomType(item: any) {
    if (!item.club) {
      return '个人房'
    }
    if (item.rule.useClubGold) {
      return `金币房(${item.rule.share ? "AA付" : item.rule.winnerPay ? "赢家付" : "房主付"})`
    }
    if (item.rule.isPublic) {
      return '金豆房'
    }
    return '公共房'
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
  }

  userDetail() {
    apiUserRoomDetail({
      room_id: this.room_id
    })
        .then((res: any) => {
          // res.games = JSON.parse(res.games);
          this.user_info = res
          if (!this.user_info.club) {
            this.user_info.club = {}
          }

          console.log(res.games)
        })
        .catch((res: any) => {})
  }

  DetailsClick(gameId: any) {
    console.log(this.user_info.type)
    // if (this.user_info.category == 'shisanshui') {
    //     return
    // }

    this.$router.push({
      path: '/user/room/rule',
      query: {
        _id: gameId
      }
    })
  }

  ruleClick() {
    let jsonstr: any = JSON.stringify(this.user_info.rule)
    localStorage.setItem('ruleJson', jsonstr)
    this.$router.push({
      path: '/user/room/json',
      query: {}
    })
  }

  created() {
    const query: any = this.$route.query
    if (query._id) {
      this.room_id = query._id
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
