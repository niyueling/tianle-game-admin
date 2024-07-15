<template>
    <div class="ls-header flex">
        <div class="ls-header__logo">
            <el-image v-if="logo" style="width: auto; height: 40px" :src="logo" fit="contain"> </el-image>
        </div>
        <div class="ls-header__menu flex-1">
            <el-menu :default-active="activePath" mode="horizontal" :background-color="styleConfig.primary" router>
                <template v-for="(item, index) in menuList">
                    <el-menu-item :index="item.path" :key="index" v-if="!item.meta.hidden">
                        <div class="item-text">{{ item.meta.title }}</div>
                    </el-menu-item>
                </template>
            </el-menu>
        </div>
        <el-dropdown @command="handleCommand">
            <el-button type="text">
                <div class="white nr">{{ userInfo.username }}</div>
            </el-button>
            <el-dropdown-menu slot="dropdown">
                <el-dropdown-item command="resetPassword"> 修改密码 </el-dropdown-item>
                <el-dropdown-item command="logout">退出登录</el-dropdown-item>
            </el-dropdown-menu>
        </el-dropdown>
        <ls-dialog
            :async="true"
            width="500px"
            top="30vh"
            title="修改密码"
            confirmButtonText="保存"
            ref="lsDialog"
            @confirm="resetPassword"
        >
            <div>
                <el-form ref="form" :model="formData" label-width="120px" size="small">
                    <el-form-item label="当前密码" required>
                        <el-input
                            v-model="formData.origin_password"
                            placeholder="请输入当前密码"
                            type="password"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="新的密码" required>
                        <el-input v-model="formData.password" placeholder="请输入新的密码" type="password"></el-input>
                    </el-form-item>
                    <el-form-item label="确认密码" required>
                        <el-input
                            v-model="formData.password_confirm"
                            placeholder="请输入确认密码"
                            type="password"
                        ></el-input>
                    </el-form-item>
                </el-form>
            </div>
        </ls-dialog>
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator'
import { asyncRoutes } from '@/router'
import { State } from 'vuex-class'
import { apiAdminResetPassword } from '@/api/app'
import LsDialog from '@/components/ls-dialog.vue'
@Component({
    components: {
        LsDialog
    }
})
export default class Header extends Vue {
    $refs!: { lsDialog: any; form: any }
    @State('user') user: any
    formData = {
        origin_password: '',
        password: '',
        password_confirm: ''
    }
    get activePath() {
        return this.$route.meta?.parentPath
    }

    get menuList() {
        return asyncRoutes
    }

    get userInfo() {
        return this.user.userInfo
    }

    get logo() {
        return this.$store.getters.config.logo
    }
    handleCommand(command: string) {
        switch (command) {
            case 'logout':
                this.logout()
                break
            case 'resetPassword':
                this.$refs.lsDialog.open()
        }
    }
    logout() {
        this.$store.dispatch('logout').then(res => {
            this.$store.commit('setPermission', {})
            this.$router.push('/account/login')
        })
    }
    resetPassword() {
        apiAdminResetPassword(this.formData).then(res => {
            this.$refs.lsDialog.close()
            this.logout()
        })
    }
}
</script>

<style scoped lang="scss">
.ls-header {
    height: 100%;
    &__logo {
        width: 110px;
        height: 40px;
        margin-right: 20px;
        overflow: hidden;
        .logo {
            height: 40px;
            width: auto;
        }
    }
    &__menu {
        height: 100%;
        overflow: hidden;
        .el-menu {
            .el-menu-item {
                color: $--color-white;
                border: none;
                height: $--header-height;
                line-height: $--header-height;
                &:not(.is-disabled):focus,
                &:not(.is-disabled):hover {
                    color: $--color-white;
                }
                & .item-text {
                    border-bottom: 2px solid transparent;
                    height: calc(100% - 4px);
                    font-size: 14px;
                }
                &.is-active {
                    .item-text {
                        border-color: $--color-white;
                        color: $--color-white;
                    }
                }
            }
        }
    }
}
</style>
