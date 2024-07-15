<template>
    <el-container class="ls-layout">
        <el-header class="bg-primary" :height="styleConfig.headerHeight">
            <ls-header />
        </el-header>
        <el-container class="ls-container">
            <el-aside :width="styleConfig.asideMenuWidth" class="bg-white" v-if="!hideAsideMenu">
                <ls-aside :menu="asideMenu" />
            </el-aside>
            <el-main class="ls-main">
                <layout />
            </el-main>
        </el-container>
    </el-container>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator'
import LsHeader from '@/components/layout/header.vue'
import Layout from '@/layout/index.vue'
import LsAside from '@/components/layout/aside.vue'
import { asyncRoutes } from '@/router'
@Component({
    components: {
        LsHeader,
        Layout,
        LsAside
    }
})
export default class Aside extends Vue {
    get asideMenu() {
        const { meta } = this.$route
        const item = meta?.moduleName
            ? asyncRoutes.find(item => item.meta?.moduleName === meta?.moduleName)
            : asyncRoutes.find(item => item.path === meta?.parentPath)
        return item?.children
    }
    get hideAsideMenu() {
        return this.asideMenu?.every((item: any) => item.meta?.hidden)
    }
}
</script>

<style scoped lang="scss">
.el-aside {
    height: calc(100vh - #{$--header-height});
}
</style>
