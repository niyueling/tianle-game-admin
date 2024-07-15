<template>
    <div class="ls-add-admin">
        <div class="ls-card">
            <el-page-header @back="$router.go(-1)" :content="identity ? '编辑管理员' : '添加管理员'" />
        </div>

        <div class="ls-card m-t-16">
            <el-form :rules="rules" ref="form" :model="form" label-width="120px" size="small">
                <!-- 账号输入框 -->
                <el-form-item label="账号" prop="account">
                    <el-input
                        class="ls-input"
                        v-model="form.account"
                        placeholder="请输入账号"
                        :disabled="form.root == 1"
                    ></el-input>
                </el-form-item>
                <!-- 名称输入框 -->
                <el-form-item label="名称" prop="name">
                    <el-input class="ls-input" v-model="form.name" placeholder="请输入名称"></el-input>
                </el-form-item>
                <!-- 角色选择框 -->
                <el-form-item label="角色" prop="role_id">
                    <el-select v-model="form.role_id" placeholder="请选择角色" :disabled="form.root == 1">
                        <el-option v-if="form.root == 1" label="超级管理员" :value="0"></el-option>
                        <el-option
                            v-for="(item, index) in roleList"
                            :key="index"
                            :label="item.name"
                            :value="item.id"
                        ></el-option>
                    </el-select>
                </el-form-item>
                <!-- 密码输入框 -->
                <el-form-item label="密码" prop="password">
                    <el-input
                        class="ls-input"
                        v-model="form.password"
                        show-word-limit
                        placeholder="请输入密码"
                    ></el-input>
                </el-form-item>
                <!-- 确认密码输入框 -->
                <el-form-item label="确认密码" prop="password_confirm">
                    <el-input
                        class="ls-input"
                        v-model="form.password_confirm"
                        show-word-limit
                        placeholder="请输入确认密码"
                    ></el-input>
                </el-form-item>
                <!-- 管理员状态 -->
                <el-form-item label="管理员状态">
                    <el-switch
                        v-model="form.disable"
                        :active-value="0"
                        :inactive-value="1"
                        :active-color="styleConfig.primary"
                        inactive-color="#f4f4f5"
                        :disabled="form.root == 1"
                        @change="value => this.$set(this.form, 'disable', value)"
                    />
                    <div class="muted" v-if="form.root == 1">系统管理员状态不允许关闭</div>
                </el-form-item>
                <!-- 管理员状态 -->
                <el-form-item label="支持多处登录">
                    <el-switch
                        v-model="form.multipoint_login"
                        :active-value="1"
                        :inactive-value="0"
                        :active-color="styleConfig.primary"
                        inactive-color="#f4f4f5"
                        @change="value => this.$set(this.form, 'multipoint_login', value)"
                    />
                </el-form-item>
                <el-form-item label="管理员头像">
                    <material-select :limit="1" v-model="form.avatar" />
                </el-form-item>
            </el-form>
        </div>

        <!-- 底部保存或取消 -->
        <div class="bg-white ls-fixed-footer">
            <div class="row-center flex" style="height: 100%">
                <el-button size="small" @click="$router.go(-1)">取消</el-button>
                <el-button size="small" type="primary" @click="onSubmit('form')">保存</el-button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue, Watch } from 'vue-property-decorator'
import { apiAdminAdd, apiAdminDetail, apiAdminEdit, apiRoleList } from '@/api/setting/permissions'
import { AdminAdd_Req, AdminEdit_Req } from '@/api/setting/permissions.d'
import { PageMode } from '@/utils/type'
import MaterialSelect from '@/components/material-select/index.vue'
import Delivery from '../delivery/index.vue'

@Component({
    components: {
        MaterialSelect
    }
})
export default class AdminEdit extends Vue {
    /** S Data **/
    mode: string = PageMode.ADD // 当前页面【add: 添加管理员 | edit: 编辑管理员】
    identity: number | null = null // 当前编辑用户的身份ID  valid: mode = 'edit'
    roleList: Array<object> = [] // 角色的数据

    // 添加管理员表单数据
    form: any = {
        account: '', // 账号
        name: '', // 名称
        password: '', // 密码
        password_confirm: '', // 确认密码
        role_id: '', // 角色id
        disable: 0, // 禁用：0-否；1-是
        multipoint_login: 1, // N端登录：0-否；1-是
        avatar: '' // 头像路径
    }

    // 校验密码
    validatePassword = [
        { required: true, message: '请输入密码', trigger: 'blur' },
        {
            validator: (rule: object, value: string, callback: any) => {
                !value ? callback(new Error('请输入密码')) : callback()
            },
            trigger: 'blur'
        }
    ]

    // 校验确认密码
    validatePasswordConfirm = [
        { required: true, message: '请再次输入密码', trigger: 'blur' },
        {
            validator: (rule: object, value: string, callback: any) => {
                if (this.form.password) {
                    if (!value) {
                        callback(new Error('请再次输入密码'))
                    }
                    if (value !== this.form.password) {
                        callback(new Error('两次输入密码不一致!'))
                    }
                }
                callback()
            },
            trigger: 'blur'
        }
    ]

    // 表单校验
    rules = {
        account: [{ required: true, message: '请输入账号', trigger: 'blur' }],
        name: [{ required: true, message: '请输入名称', trigger: 'blur' }],
        role_id: [{ required: true, message: '请选择角色', trigger: 'change' }],
        password: [],
        password_confirm: []
    }

    /** E Data **/

    /** S Methods **/
    // 点击表单提交
    onSubmit(formName: string) {
        // 验证表单格式是否正确
        const refs = this.$refs[formName] as HTMLFormElement
        refs.validate((valid: boolean): any => {
            if (!valid) {
                return
            }

            // 请求发送
            switch (this.mode) {
                case PageMode.ADD:
                    return this.handleAdminAdd()
                case PageMode.EDIT:
                    return this.handleAdminEdit()
            }
        })
    }

    // 添加管理员
    handleAdminAdd() {
        const form = this.form as AdminAdd_Req
        apiAdminAdd(form)
            .then(() => {
                this.$message.success('添加成功!')
                setTimeout(() => this.$router.go(-1), 500)
            })
            .catch(() => {
                this.$message.error('保存失败!')
            })
    }

    // 编辑管理员
    handleAdminEdit() {
        const params = this.form
        const id: number = this.identity as number
        apiAdminEdit({ ...params, id } as AdminEdit_Req)
            .then(() => {
                this.$message.success('修改成功!')
                setTimeout(() => this.$router.go(-1), 500)
            })
            .catch(() => {
                this.$message.error('保存失败!')
            })
    }

    // 获取角色列表
    geRoleList() {
        apiRoleList({
            page_type: 1
        }).then(res => {
            this.roleList = res.lists
        })
    }

    // 表单初始化数据 [编辑模式] mode => edit
    initFormDataForAdminEdit() {
        apiAdminDetail({
            id: this.identity as number
        })
            .then(res => {
                Object.keys(res).map(key => {
                    this.$set(this.form, key, res[key])
                })
            })
            .catch(() => {
                this.$message.error('数据初始化失败，请刷新重载！')
            })
    }

    /** E Methods **/

    /** S Life Cycle **/
    created() {
        const query: any = this.$route.query

        if (query.mode) {
            this.mode = query.mode
        }

        // 编辑模式：初始化数据
        if (this.mode === PageMode.EDIT) {
            this.identity = query.id * 1
            this.initFormDataForAdminEdit()
        }

        if (this.mode === PageMode.ADD) {
            this.$set(this.rules, 'password', this.validatePassword)
            this.$set(this.rules, 'password_confirm', this.validatePasswordConfirm)
        }

        // 获取角色列表
        this.geRoleList()
    }

    @Watch('form.password')
    changePasswordInput(value: string) {
        if (this.mode === PageMode.EDIT) {
            value
                ? this.$set(this.rules, 'password_confirm', this.validatePasswordConfirm)
                : this.$set(this.rules, 'password_confirm', [])
        }
    }

    /** E Life Cycle **/
}
</script>

<style lang="scss" scoped>
.ls-add-admin {
    padding-bottom: 80px;

    .ls-input {
        width: 380px;
    }
}
</style>
