<!-- 用户详情 -->
<template>
    <div class="user-details">
        <!-- 导航头部 -->
        <div class="ls-card">
            <el-page-header @back="$router.go(-1)" content="对局详情" />
        </div>

        <div class="m-t-16 flex col-top">
            <el-table :data="cards" style="width: 100%" size="mini" :header-cell-style="{ background: '#f5f8ff' }">
                <el-table-column type="index" label="序号" width="120" />
                <el-table-column prop="type_arr" label="类型" width="120" />
                <el-table-column prop="value" label="牌型" width="120" />
                <el-table-column prop="point" label="点数" width="120" />
            </el-table>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { apiFourJokerGameDetail } from '@/api/finance/finance'
@Component({
    components: {}
})
export default class UserDetails extends Vue {
    _id = ''
    cards = []

    userDetail() {
        apiFourJokerGameDetail({
            _id: this._id
        })
            .then((res: any) => {
                this.cards = res
            })
            .catch((res: any) => {})
    }

    created() {
        const query: any = this.$route.query
        if (query._id) {
            this._id = query._id
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
