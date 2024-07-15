<template>
    <div class="ls-add-role">
        <div class="ls-card">
            <el-page-header @back="$router.go(-1)" content="编辑角色" />
        </div>

        <div class="ls-card m-t-10">
            <el-form ref="form" :model="form" :rules="rules" label-width="120px" size="small">
                <!--    名称    -->
                <el-form-item label="名称" required prop="name">
                    <el-input
                        class="ls-input"
                        v-model="form.name"
                        maxlength="8"
                        show-word-limit
                        placeholder="请输入名称"
                    />
                </el-form-item>

                <!--   说明     -->
                <el-form-item label="描述">
                    <el-input
                        class="ls-input"
                        v-model="form.desc"
                        type="textarea"
                        :autosize="{ minRows: 3, maxRows: 4 }"
                        placeholder="请描述一下该角色"
                    />
                </el-form-item>

                <!--   权限     -->
                <el-form-item label="权限">
                    <div>
                        <el-button type="text" @click="allSelect">全选</el-button>
                        <el-button type="text" @click="cancelAllSelect">不全选</el-button>
                    </div>
                    <el-tree
                        ref="permissionsTree"
                        :data="permissionsTree"
                        node-key="auth_key"
                        icon-class="el-icon-arrow-right"
                        :props="{
                            children: 'sons',
                            label: 'name'
                        }"
                        empty-text
                        show-checkbox
                        default-expand-all
                        @check-change="handlePermissionsCheckChange"
                    ></el-tree>
                </el-form-item>
            </el-form>
        </div>

        <!--  表单功能键  -->
        <div class="bg-white ls-fixed-footer">
            <div class="row-center flex" style="height: 100%">
                <el-button size="small" @click="$router.go(-1)">取消</el-button>
                <el-button size="small" type="primary" @click="submitForm('form')">保存</el-button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { apiRoleAdd, apiRoleEdit, apiRoleDetail, apiAuthMenu } from '@/api/setting/permissions'
import { RoleAdd_Req } from '@/api/setting/permissions.d'
import { PageMode } from '@/utils/type'

@Component
export default class RoleEdit extends Vue {
    /** S Data **/
    mode: string = PageMode.ADD // 当前页面【add: 添加角色 | edit: 编辑角色】
    identity: number | null = null // 当前编辑用户的身份ID  valid: mode = 'edit'

    // 表单数据
    form: RoleAdd_Req = {
        name: '',
        auth_keys: [],
        desc: ''
    }

    // 权限树
    permissionsTree: any[] = []

    // 表单验证
    rules: object = {
        name: [{ required: true, message: '必填项不可为空', trigger: 'blur' }]
    }
    allAuthKey!: any[]

    /** E Data **/

    /** S Methods **/
    handlePermissionsCheckChange(data: any, checked: boolean) {
        console.log(data)
        if (!data.auth_key) {
            return
        }
        const index = this.form.auth_keys.findIndex(item => item == data.auth_key)
        if (checked) {
            index == -1 && this.form.auth_keys.push(data.auth_key)
            return
        }

        if (index != -1) {
            this.form.auth_keys.splice(index, 1)
        }
    }

    // 表单提交
    submitForm(formName: string) {
        const ref = this.$refs[formName] as HTMLFormElement
        ref.validate((valid: boolean) => {
            if (!valid) {
                return this.$message.error('请完善信息')
            }

            // 请求发送
            switch (this.mode) {
                case PageMode.ADD:
                    return this.handleRoleAdd()
                case PageMode.EDIT:
                    return this.handleRoleEdit()
            }
        })
    }

    // 添加角色
    handleRoleAdd() {
        const params = this.form
        apiRoleAdd(params)
            .then(() => {
                setTimeout(() => this.$router.go(-1), 500)
            })
            .catch(() => {})
    }

    // 编辑角色
    handleRoleEdit() {
        const params = this.form
        const id: number = this.identity as number
        apiRoleEdit({ ...params, id })
            .then(() => {
                setTimeout(() => this.$router.go(-1), 500)
            })
            .catch(() => {})
    }

    // 初始化表单数据: 角色编辑
    initFormDataForRoleEdit() {
        const id: number = this.identity as number
        apiRoleDetail({ id })
            .then(res => {
                Object.keys(res).map(item => {
                    this.$set(this.form, item, res[item])
                })
                this.form.auth_keys = []
                const ref = this.$refs.permissionsTree as any
                ref.setCheckedKeys(res.auth_keys)
            })
            .catch(() => {})
    }

    // 获取权限菜单
    getAuthMenu() {
        apiAuthMenu().then(res => {
            this.permissionsTree = res
            this.allAuthKey = this.getAuthKey()
        })
    }

    //全选
    allSelect() {
        const ref = this.$refs.permissionsTree as any
        ref.setCheckedKeys(this.allAuthKey)
    }
    cancelAllSelect() {
        const ref = this.$refs.permissionsTree as any
        ref.setCheckedKeys([])
    }

    getAuthKey(arr: any[] = this.permissionsTree) {
        return arr.reduce((prev, item) => {
            if (item.auth_key) {
                prev.push(item.auth_key)
            }
            if (item.sons) {
                prev.push(...this.getAuthKey(item.sons))
            }
            return prev
        }, [])
    }
    /** E Methods **/
    /** S Life Cycle **/
    created() {
        const query: any = this.$route.query
        if (query.mode) {
            this.mode = query.mode
        }

        if (this.mode === PageMode.EDIT) {
            this.identity = query.id * 1
            this.initFormDataForRoleEdit()
        }
        this.getAuthMenu()
    }

    /** S Life Cycle **/
}
</script>

<style lang="scss" scoped>
.ls-add-role {
    padding-bottom: 120px;
    .ls-input {
        width: 380px;
    }
    .el-tree {
        ::v-deep .el-tree-node__content {
            height: 40px;
            background-color: transparent !important;
        }
    }
}
</style>
