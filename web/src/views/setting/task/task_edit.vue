<template>
    <div class="substance_edit-help">
        <div class="ls-card">
            <el-page-header :content="this.mode == 'add' ? '新增定时任务' : '编辑定时任务'" @back="$router.go(-1)" />
        </div>

        <div class="ls-card m-t-16 form-container">
            <el-form :rules="rules" ref="form" :model="form" label-width="120px" size="small">
                <!-- 标题输入框 -->
                <el-form-item label="名称" prop="name">
                    <el-input class="ls-input" v-model="form.name" show-word-limit placeholder="请输入名称"></el-input>
                </el-form-item>
                <el-form-item label="类型">
                    <el-radio v-model="form.type" :label="1">定时任务</el-radio>
                </el-form-item>
                <el-form-item label="命令" prop="command">
                    <el-input
                        class="ls-input"
                        v-model="form.command"
                        placeholder="请输入thankphp命令，如vresion"
                    ></el-input>
                </el-form-item>
                <el-form-item label="参数">
                    <el-input
                        class="ls-input"
                        v-model="form.params"
                        placeholder="请输入参数，例:--id 8 --name 测试"
                    ></el-input>
                </el-form-item>
                <el-form-item label="状态">
                    <el-switch
                        v-model="form.status"
                        :active-value="1"
                        :inactive-value="0"
                        :active-color="styleConfig.primary"
                        inactive-color="#f4f4f5"
                    />
                </el-form-item>
                <el-form-item label="规则" prop="expression">
                    <el-input
                        class="ls-input"
                        @blur="blur"
                        v-model="form.expression"
                        placeholder="请输入crontab规则，例：59 * * *"
                    ></el-input>

                    <el-table ref="paneTable" class="m-t-24" :data="lists" style="width: 100%" size="mini">
                        <el-table-column prop="time" label="序号" min-width="80"></el-table-column>
                        <el-table-column prop="date" label="执行时间" min-width="180"></el-table-column>
                    </el-table>
                </el-form-item>
                <el-form-item label="备注">
                    <el-input
                        type="textarea"
                        class="ls-input"
                        v-model="form.remark"
                        placeholder="请输入备注"
                    ></el-input>
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
import { Component, Vue } from 'vue-property-decorator'
import {
    apiCrontabAdd,
    apiCrontabEdit,
    apiCrontabDetail,
    apiCrontabExpression
} from '@/api/setting/system_maintain/system_maintain'
import { PageMode } from '@/utils/type'

@Component
export default class HelpEdit extends Vue {
    /** S Data **/
    mode: string = PageMode.ADD // 当前页面: add-添加角色 edit-编辑角色

    // 表
    lists: Array<object> = []

    // 表单数据
    form: any = {
        name: '', //	是	string	名称
        type: 1, //	是	integer	类型
        command: '', //	是	string	命令
        expression: '', //	是	string	运行规则
        status: 1, //	是	integer	状态
        remark: '', //	否	string	备注
        params: '' //	否	string	参数
    }

    // 表单校验
    rules: object = {
        name: [{ required: true, message: '请输入名称', trigger: 'blur' }],
        command: [{ required: true, message: '请输入命令', trigger: 'blur' }],
        params: [{ required: true, message: '请输入参数', trigger: 'blur' }],
        expression: [{ required: true, message: '请输入规则', trigger: 'blur' }]
    }

    /** E Data **/

    /** S Methods **/
    // 提交表单
    onSubmit(formName: string) {
        const refs = this.$refs[formName] as HTMLFormElement
        refs.validate((valid: boolean): void => {
            if (!valid) {
                return
            }

            // 请求发送
            switch (this.mode) {
                case PageMode.ADD:
                    return this.handleNoticeAdd()
                case PageMode.EDIT:
                    return this.handleNoticeEdit()
            }
        })
    }

    blur() {
        if (this.form.expression != '') {
            this.getExpressionFun()
        }
    }

    getExpressionFun() {
        apiCrontabExpression({
            expression: this.form.expression
        }).then(res => {
            this.lists = res
        })
    }

    // 添加定时任务
    handleNoticeAdd() {
        const params = this.form

        apiCrontabAdd(params)
            .then(() => {
                setTimeout(() => this.$router.go(-1), 500)
            })
            .catch(() => {
                this.$message.error('添加失败!')
            })
    }

    // 编辑帮助文章
    handleNoticeEdit() {
        delete this.form.status_desc
        delete this.form.type_desc
        const params = this.form
        apiCrontabEdit(params)
            .then(() => {
                // this.$message.success("修改成功!");
                setTimeout(() => this.$router.go(-1), 500)
            })
            .catch(() => {
                this.$message.error('修改失败!')
            })
    }

    // 初始化表单数据
    initFormDataForNoticeEdit() {
        apiCrontabDetail({
            id: this.form.id
        })
            .then((res: any) => {
                Object.keys(res).map(item => {
                    this.$set(this.form, item, res[item])
                })
                this.getExpressionFun()
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

        if (this.mode === PageMode.EDIT) {
            this.form.id = query.id * 1
            this.initFormDataForNoticeEdit()
        }
    }

    /** E Life Cycle **/
}
</script>

<style lang="scss" scoped>
.form-container {
    display: flex;

    .form-left {
        width: 500px;
    }

    .form-right {
        width: 800px;
    }
}
</style>
