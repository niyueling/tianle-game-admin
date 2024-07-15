<template>
    <div class="ls-aside">
        <el-scrollbar class="ls-scrollbar" style="height: 100%">
            <el-menu :default-active="activePath" class="el-menu-vertical-demo" router>
                <template v-for="item in menu">
                    <div :key="item.path" v-if="!item.meta.hidden">
                        <el-submenu v-if="item.children" :index="item.path" :key="item.path">
                            <template slot="title">
                                <span class="iconfont" :class="[item.meta.icon]"></span>
                                <span>{{ item.meta.title }}</span>
                            </template>
                            <template v-for="i in item.children">
                                <el-menu-item v-if="!i.meta.hidden" :key="i.path" :index="i.path">{{
                                    i.meta.title
                                }}</el-menu-item>
                            </template>
                        </el-submenu>
                        <el-menu-item v-else :key="item.path" :index="item.path">
                            <template slot="title">
                                <span class="iconfont" :class="[item.meta.icon]"></span>
                                <span>{{ item.meta.title }}</span>
                            </template>
                        </el-menu-item>
                    </div>
                </template>
            </el-menu>
        </el-scrollbar>
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator'

@Component
export default class Aside extends Vue {
    @Prop({
        default: () => {
            return []
        }
    })
    menu!: any[]
    get activePath() {
        return this.$route.meta?.prevPath || this.$route.path
    }
}
</script>

<style scoped lang="scss">
.ls-aside {
    height: 100%;
    .el-scrollbar {
        .el-menu {
            padding: 20px 0 80px;
            width: $--aside-menu-width;
            border-right: none;
            ::v-deep.el-submenu__title,
            .el-menu-item {
                font-size: 13px;
                height: 50px;
                line-height: 50px;
                padding: 0 10px !important;
                box-sizing: border-box;
                min-width: auto;
                &.is-active {
                    background-color: $--color-primary-light-9;
                }
            }
            .el-submenu {
                .el-menu-item {
                    padding-left: 44px !important;
                    font-size: 13px;
                }
            }
            .iconfont {
                margin-right: 10px;
                margin-left: 10px;
            }
            ::v-deep.el-submenu__icon-arrow {
                margin-top: -4px;
            }
        }
    }
}
</style>
