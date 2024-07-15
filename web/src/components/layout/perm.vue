<template>
    <div class="perm">
        <template v-if="hasPermission">
            <keep-alive v-if="$route.meta.keepAlive">
                <router-view />
            </keep-alive>
            <router-view v-else></router-view>
        </template>
        <template v-else>
            <div class="no-perm flex-col col-center row-center">
                <img src="@/assets/images/no-perm.png" alt="" />
                <div class="muted">暂无查看权限</div>
            </div>
        </template>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'

@Component
export default class Perm extends Vue {
    get hasPermission() {
        const {
            permission: { root, auth }
        } = this
        const { path, meta } = this.$route
        if (root) {
            // 为管理员
            return true
        }
        // 没有权限
        if (auth && auth.length == 0) {
            return false
        }
        const actions = auth[path]
        if (!meta?.permission) {
            // 不需要权限的页面
            return true
        }
        if (!actions) {
            // 需要权限但后台权限表路径对不上
            return false
        }

        return actions.some((item: string) => {
            return meta?.permission.includes(item)
        })
    }
    get permission() {
        return this.$store.getters.permission
    }
}
</script>

<style scoped lang="scss">
.perm {
    .no-perm {
        height: calc(100vh - #{$--header-height} - 32px);
        img {
            width: 152px;
            height: 152px;
        }
    }
}
</style>
