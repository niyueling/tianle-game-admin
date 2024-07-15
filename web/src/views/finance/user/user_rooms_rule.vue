<!-- 用户详情 -->
<template>
    <div class="user-details">
        <!-- 导航头部 -->
        <div class="ls-card">
            <el-page-header @back="$router.go(-1)" content="对局详情" />
        </div>

        <div class="m-t-16 flex col-top">
            <!-- 基本资料 -->
            <div class="ls-card card-height">
                <div class="card-title">对局详情</div>
                <div v-for="(item, index) in user_info.events" :key="index">
                    <div style="text-align: left; margin: 60px" v-if="actionType.includes(item.type)">
                        <div style="float: left">
                            <div style="height: 170px; width: 170px">
                              <div v-if="!['liuJu', 'resetGold'].includes(item.type)">
                                <el-image :src="user_info.playersInfo[item.index].model.avatar" class="avatar" />
                                <div class="userinfo">
                                    <span
                                    >{{ user_info.playersInfo[item.index].model.nickname }}({{
                                        user_info.playersInfo[item.index].model.shortId
                                      }})</span
                                    >
                                </div>
                                <div class="base" v-if="['zhadan', 'ddz'].includes(user_info.type)">
                                  <span>💣 {{ user_info.states[item.index].detail.bomb || 0 }}</span>
                                  <span>🃏 {{ user_info.states[item.index].detail.joker || 0 }}</span>
                                  <span>⚾ {{ user_info.states[item.index].detail.base || 0 }}</span>
                                </div>
                                <div style="clear: both"></div>
                              </div>

                            </div>
                        </div>

                        <div style="float: left" v-if="['zhadan', 'ddz'].includes(user_info.type)">
                            <div style="display: inline-block">
                                <div>
                                    <div class="card-item">
                                        <img
                                            class="card-image"
                                            v-for="(item1, index1) in item.info.cards"
                                            :key="index1"
                                            :src="formatImagePath(item1)"
                                            :style="cardImageStyle(index1)"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div style="float: left">
                                    <div class="action-text">
                                        <span class="action-text-span">{{ actionMap[item.type] }}</span>
                                    </div>
                                </div>
                                <div style="float: left">
                                    <div>
                                        <div class="card-item">
                                            <img
                                                :src="formatImagePath(item2)"
                                                class="card-image"
                                                v-for="(item2, index2) in item.info.actionCards"
                                                :key="index2"
                                                :style="cardImageStyle(index2)"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="float: left" v-if="['majiang', 'xmmajiang', 'xueliu', 'guobiao', 'pcmj'].includes(user_info.type)">
                            <div>
                                <div style="float: left">
                                    <div class="action-text">
                                        <span class="action-text-span">{{ actionMap[item.type] }}</span>
                                    </div>
                                </div>
                                <div style="float: left">
                                    <div>
                                        <div class="card-item">
                                            <img
                                                v-if="item.info.card"
                                                v-for="(item1, index1) in item.info.card"
                                                :key="index1"
                                                :src="formatMahjongImagePath(item1)"
                                                class="card-image"
                                                :style="cardMahjongImageStyle(index1)"
                                            />
                                            <img
                                                class="card-image"
                                                style="margin-left: 90px"
                                                v-for="(item1, index1) in item.info.cards"
                                                :key="index1"
                                                :src="formatMahjongImagePath(item1)"
                                                :style="cardMahjongImageStyle1(index1, item.info.card ? item.info.card.length : 0)"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { apiUserRoomRuleDetail } from '@/api/finance/finance'
@Component({
    components: {}
})
export default class UserDetails extends Vue {
    game_id = ''
    index = 0

    // 用户信息
    user_info = {}

    player = {}

    account = {
        realName: ''
    }

    actionType = [
        'shuffle',
        'moPai',
        'da',
        'chi',
        'jiePao',
        'ziMo',
        'youJin',
        'shuangYou',
        'sanYou',
        'sanJinDao',
        'qiangJin',
        'resetGold',
        'liuJu',
        'tianHu',
        'peng',
        'jieGang',
        'buGang',
        'anGang',
        'guo',
        'qiaoXiang',
        'rejectQiao',
        'choice',
        'buHua'
    ]

    actionMap = {
        shuffle: '发牌',
        moPai: '摸牌',
        da: '打牌',
        chi: '吃牌',
        jiePao: '接炮',
        ziMo: '自摸',
        youJin: '游金',
        shuangYou: '双游',
        sanYou: '三游',
        sanJinDao: '三金倒',
        qiangJin: '抢金',
        resetGold: '开金',
        tianHu: '天胡',
        liuJu: '流局',
        peng: '碰牌',
        jieGang: '接杠',
        buGang: '补杠',
        anGang: '暗杠',
        guo: '过牌',
        qiaoXiang: '敲响',
        rejectQiao: '不敲',
        choice: '选项',
        buHua: '补花'
    }

    cardImageStyle(index: number) {
        const left = 100 + 30 * index
        return { left: `${left}px` }
    }

    formatImagePath(item: any) {
        return require(`@/assets/images/thirteen/${item.type}_${item.value}.png`)
    }

    formatMahjongImagePath(value: any) {
      if (value == 10) {
        console.log(111)
      }
        return require(`@/assets/images/mahjong/${value}.png`)
    }

    cardMahjongImageStyle(index: number) {
        const left = 30 + 60 * index
        return { left: `${left}px` }
    }

  cardMahjongImageStyle1(index: number, length: number) {
    const left = 60 * (index + length)
    return { left: `${left}px` }
  }

    userDetail() {
        apiUserRoomRuleDetail({
            game_id: this.game_id
        })
            .then((res: any) => {
              console.log(res)
              this.user_info = res
            })
            .catch((res: any) => {
              console.error(res)
            })
    }

    created() {
        const query: any = this.$route.query
        if (query._id) {
            this.game_id = query._id
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

.userinfo {
    margin-top: 8px;
    margin-left: 15px;
    width: 100%;
}

.action-text-span {
    font-size: 2em;
    color: red;
    display: block;
}

.action-text {
    height: 100%;
    width: 80px;
    padding: 16px;
    margin: 0px;
}

.card-item {
    width: 810px;
    position: relative;
    display: inline-block;
    height: 80px;
}

.action-card-image {
    width: 180px;
    position: relative;
    display: inline-block;
    height: 80px;
}

.card-image {
    display: block;
    left: 0px;
    margin: 0px;
    padding: 0px;
    position: absolute;
    width: 60px;
}

.base {
    margin-left: 15px;
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

.avatar {
    -webkit-text-size-adjust: 100%;
    -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
    --antd-wave-shadow-color: #1890ff;
    font-variant: tabular-nums;
    line-height: 1.5;
    font-feature-settings: 'tnum';
    font-family: lucida grande, lucida sans unicode, lucida, helvetica, Hiragino Sans GB, Microsoft YaHei,
        WenQuanYi Micro Hei, sans-serif;
    text-align: left;
    box-sizing: inherit;
    vertical-align: middle;
    border-style: none;
    color: rgb(255, 255, 255);
    background-color: rgb(188, 188, 188);
    user-select: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 60px;
    border-radius: 50%;
    height: 120px;
    width: 120px;
}

.user-details {
    min-height: calc(100vh - #{$--header-height} - 92px);
    margin-bottom: 60px;

    &__header {
        flex: none;
    }
}
</style>
