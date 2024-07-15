<!-- 存储设置编辑 -->
<template>
    <div class="storage-setting">
        <!-- 导航头部 -->
        <div class="ls-card">
            <el-page-header @back="$router.go(-1)" content="存储设置" />
        </div>

        <!-- 提示 -->
        <div v-if="engine !== 'local'" class="ls-card m-t-16">
            <el-alert
                v-if="engine == 'qiniu'"
                title="温馨提示：切换七牛云存储后，素材库需要重新上传至七牛云。"
                type="info"
                :closable="false"
                show-icon
            />
            <el-alert
                v-if="engine == 'aliyun'"
                title="温馨提示：切换阿里云OSS后，素材库需要重新上传至阿里云OSS。"
                type="info"
                :closable="false"
                show-icon
            />
            <el-alert
                v-if="engine == 'qcloud'"
                title="温馨提示：切换腾讯云OSS后，素材库需要重新上传至腾讯云OSS。"
                type="info"
                :closable="false"
                show-icon
            />
        </div>

        <el-form ref="formRef" :model="form" :rules="formRules" label-width="240px" size="small">
            <!-- 存储设置 -->
            <div class="ls-card m-t-16">
                <div class="card-content m-t-24">
                    <el-form-item label="存储方式">
                        <div v-if="engine == 'local'">本地存储</div>
                        <div v-if="engine == 'qiniu'">七牛云存储</div>
                        <div v-if="engine == 'aliyun'">阿里云OSS</div>
                        <div v-if="engine == 'qcloud'">腾讯云OSS</div>
                        <div v-if="engine == 'local'" class="muted xs m-r-16">本地存储方式不需要配置其他参数</div>
                    </el-form-item>
                </div>
                <div v-if="engine !== 'local'">
                    <el-form-item label=" 存储空间名称(Bucket)" prop="bucket">
                        <el-input v-model="form.bucket" placeholder="请输入存储空间名称(Bucket)"></el-input>
                    </el-form-item>
                    <el-form-item label="ACCESS_KEY（AK）" prop="access_key">
                        <el-input v-model="form.access_key" placeholder="请输入ACCESS_KEY"></el-input>
                    </el-form-item>
                    <el-form-item label="SECRET_KEY（SK）" prop="secret_key">
                        <el-input v-model="form.secret_key" placeholder="请输入SECRET_KEY"></el-input>
                    </el-form-item>
                    <el-form-item label="空间域名（Domain）" prop="domain">
                        <!-- <el-select class="ls-select" v-model="form" placeholder="https://">
							<el-option label="https://" value="https://"></el-option>
							<el-option label="http://" value="http://"></el-option>
						</el-select> -->
                        <el-input v-model="form.domain" placeholder="请输入空间域名"></el-input>
                        <div class="muted xs m-r-16">请补全http://或https://，例如https://static.cloud.com</div>
                    </el-form-item>
                    <el-form-item v-if="engine == 'qcloud'" label="REGION" prop="region">
                        <el-input v-model="form.region" placeholder="请输入region"></el-input>
                    </el-form-item>
                </div>
                <el-form-item label="状态" prop="status">
                    <el-radio-group class="m-r-16" v-model="form.status">
                        <el-radio :label="0">停用·</el-radio>
                        <el-radio :label="1">启用</el-radio>
                    </el-radio-group>
                </el-form-item>
            </div>
        </el-form>

        <!--  表单功能键  -->
        <div class="bg-white ls-fixed-footer">
            <div class="row-center flex" style="height: 100%">
                <el-button size="small" @click="$router.go(-1)">取消</el-button>
                <el-button size="small" type="primary" @click="onSubmit()">保存</el-button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Vue, Component } from 'vue-property-decorator'
import { apiStorageIndex, apiStorageSetup } from '@/api/setting/storage/storage'
@Component({
    components: {}
})
export default class Storage extends Vue {
    /** S Data **/
    // 存储方式
    engine = '' // 存储引擎名称：local-本地，qiniu-七牛云,aliyun-阿里云OSS,qcloud-腾讯云OS:
    form = {
        bucket: '',
        access_key: '',
        secret_key: '',
        domain: '',
        region: '', // 腾讯云需要
        status: 0
    }

    formRules = {
        bucket: [
            {
                required: true,
                message: '请输入存储空间名称',
                trigger: 'blur'
            }
        ],
        access_key: [
            {
                required: true,
                message: '请输入ACCESS_KEY',
                trigger: 'blur'
            }
        ],
        secret_key: [
            {
                required: true,
                message: '请输入SECRET_KEY',
                trigger: 'blur'
            }
        ],
        domain: [
            {
                required: true,
                message: '请输入空间域名',
                trigger: 'blur'
            }
        ],
        region: [
            {
                required: true,
                message: '请输入REGION',
                trigger: 'blur'
            }
        ]
    }
    $refs!: {
        formRef: any
    }
    /** E Data **/

    /** S Methods **/
    onSubmit() {
        // 验证表单格式是否正确
        this.$refs.formRef.validate((valid: boolean): any => {
            if (!valid) {
                return
            }
            // 请求发送
            apiStorageSetup({
                ...this.form,
                engine: this.engine
            })
                .then((res: any) => {
                    setTimeout(() => this.$router.go(-1), 500)
                    //this.$message.success('保存成功!')
                })
                .catch((res: any) => {
                    //this.$message.error('保存失败!')
                })
            // setTimeout(()=> {
            // 	this.getStorageIndex()
            // }, 100)
        })
    }

    // 获取引擎配置
    getStorageIndex() {
        apiStorageIndex({ engine: this.engine })
            .then((res: any) => {
                this.form = res
            })
            .catch((res: any) => {
                //this.$message.error('获取失败，请重试!')
            })
    }
    /** E Methods **/
    /** S Life Cycle **/
    created() {
        const query: any = this.$route.query
        if (query.engine) {
            this.engine = query.engine
        }
        this.getStorageIndex()
    }
    /** E Life Cycle **/
}
</script>

<style lang="scss" scoped>
.ls-card {
    .ls-input {
        width: 280px;
    }

    .card-title {
        font-size: 16px;
    }

    .login_limit-unit {
        display: inline-block;
        width: 2em;
        text-align: center;
    }

    .item-a {
        color: #4073fa;
    }

    .ls-select {
        width: 100px;
    }
}

.storage-setting {
    min-height: calc(100vh - #{$--header-height} - 92px);
    margin-bottom: 60px;
}
</style>
