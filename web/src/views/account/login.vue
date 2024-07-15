<template>
    <div class="ls-login flex-col">
        <div class="flex-1 ls-login__content flex col-center">
            <div class="ls-content__body bg-white flex">
                <div
                    class="login-img"
                    :style="{
                        'background-image': `url(${config.admin_login_image})`
                    }"
                ></div>
                <div class="form-wrap flex-col col-center row-center">
                    <div class="font-size-24 weight-500">{{ config.name }}</div>
                    <div class="form m-t-40">
                        <el-form :model="accountObj" :rules="rules" ref="form">
                            <el-form-item required prop="username">
                                <el-input
                                    placeholder="请输入账号"
                                    v-model="accountObj.username"
                                    @keyup.enter.native="$refs.inputPwd.focus()"
                                >
                                    <i slot="prefix" class="el-input__icon el-icon-s-custom"></i>
                                </el-input>
                            </el-form-item>
                            <el-form-item required prop="password">
                                <el-input
                                    ref="inputPwd"
                                    placeholder="请输入密码"
                                    v-model="accountObj.password"
                                    show-password
                                    @keyup.enter.native="handleLogin"
                                >
                                    <i slot="prefix" class="el-input__icon el-icon-s-cooperation"></i>
                                </el-input>
                            </el-form-item>
                            <el-checkbox v-model="rememberAccount" label="记住账号"></el-checkbox>
                            <el-button type="primary" :loading="loadingLogin" @click="handleLogin">登录</el-button>
                        </el-form>
                    </div>
                </div>
            </div>
        </div>
        <ls-footer />
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import LsFooter from '@/components/layout/footer.vue'
import { apiLogin } from '@/api/app'
import { Action } from 'vuex-class'
import cache from '@/utils/cache'
@Component({
    components: {
        LsFooter
    }
})
export default class Login extends Vue {
    $refs!: { form: any }
    @Action('getPermission') getPermission!: () => void
    rememberAccount = false
    accountObj = {
        username: '',
        password: ''
    }
    rules: any = {
        username: [
            {
                required: true,
                message: '请输入账号',
                trigger: ['blur', 'change']
            }
        ],
        password: [
            {
                required: true,
                message: '请输入密码',
                trigger: ['blur', 'change']
            }
        ]
    }
    loadingLogin = false
    get config() {
        return this.$store.getters.config
    }
    // S Methods
    // 点击登录
    handleLogin() {
        this.$refs.form.validate((valid: boolean): any => {
            if (!valid) {
                return
            }
            cache.set('remember_account', {
                remember: this.rememberAccount,
                username: this.accountObj.username
            })
            this.login()
        })
    }
    //登录
    login() {
        this.loadingLogin = true
        this.$store
            .dispatch('login', this.accountObj)
            .then(data => {
                const {
                    query: { redirect }
                } = this.$route
                const path = typeof redirect === 'string' ? redirect : '/'
                this.$router.replace(path)
            })
            .finally(() => {
                this.loadingLogin = false
            })
    }
    created() {
        const value = cache.get('remember_account')
        if (value.remember) {
            this.rememberAccount = value.remember
            this.accountObj.username = value.username
        }
    }
}
</script>

<style scoped lang="scss">
.ls-login {
    min-height: 100vh;
    background-image: url('../../assets/images/login_bg.png');
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
    &__content {
        margin: 0 auto;
        width: 800px;
        .ls-content__body {
            flex: 1;
            height: 100%;
            height: 400px;
            box-shadow: 3px 0px 10px rgba(0, 0, 0, 0.08);
            border-radius: 20px;
            overflow: hidden;
            & > div {
                width: 50%;
                height: 100%;
            }
            .login-img {
                box-sizing: border-box;
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
            }
            .form-wrap {
                padding: 50px auto;
                .el-button {
                    width: 100%;
                    margin-top: 20px;
                }
            }
        }
    }
}
</style>
