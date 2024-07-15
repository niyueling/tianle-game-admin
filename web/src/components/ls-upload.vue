<template>
    <div>
        <el-upload
            ref="upload"
            class="ls-upload"
            :action="action"
            :multiple="multiple"
            :limit="limit"
            :show-file-list="false"
            :headers="{ token: $store.getters.token, version: version }"
            :data="data"
            :on-progress="handleProgress"
            :on-success="handleSuccess"
            :on-exceed="handleExceed"
            :on-error="handleError"
        >
            <slot></slot>
        </el-upload>
        <el-dialog
            v-if="showProgress && fileList.length"
            title="上传进度"
            :visible.sync="visible"
            top="20vh"
            :close-on-click-modal="false"
            width="500px"
            :modal="false"
            :before-close="handleClose"
        >
            <div class="file-list">
                <template v-for="(item, index) in fileList">
                    <div class="m-b-20" :key="index">
                        <div>{{ item.name }}</div>
                        <div class="flex-1">
                            <el-progress :percentage="parseInt(item.percentage)"></el-progress>
                        </div>
                    </div>
                </template>
            </div>
            <!-- <upload-list :files="fileList" list-type="picture" /> -->
        </el-dialog>
    </div>
</template>

<script lang="ts">
import config from '@/config'
import { Component, Prop, Vue, Watch } from 'vue-property-decorator'
import UploadList from 'element-ui/packages/upload/src/upload-list.vue'
import request from '@/plugins/axios'
@Component({
    components: {
        UploadList
    }
})
export default class Upload extends Vue {
    $refs!: { upload: any }
    @Prop({ default: 10 }) limit!: number
    @Prop({ default: true }) multiple!: boolean
    @Prop({ default: () => {} }) data!: any
    @Prop({ default: 'image' }) type!: 'image' | 'video'
    @Prop({ default: false }) showProgress!: boolean
    visible = false
    action = `${config.baseURL}/adminapi/upload/${this.type}`
    fileList: any[] = []
    version = config.version
    handleProgress(event: any, file: any, fileList: any[]) {
        this.visible = true
        this.fileList = fileList
    }
    handleSuccess(response: any, file: any, fileList: any[]) {
        const allSuccess = fileList.every(item => item.status == 'success')
        if (allSuccess) {
            this.$refs.upload.clearFiles()
            this.visible = false
        }
        this.$emit('change')
        if (response.code == 0 && response.show) {
            this.$message.error(response.msg)
        }
    }
    handleError(err: any, file: any) {
        this.$message.error(`${file.name}文件上传失败`)
        this.$refs.upload.abort()
        this.visible = false
        this.$emit('change')
        this.$emit('error')
    }
    handleExceed() {
        this.$message.error('超出上传上限，请重新上传')
    }
    handleClose() {
        this.$refs.upload.abort()
        this.$refs.upload.clearFiles()
        this.visible = false
    }
}
</script>

<style scoped lang="scss"></style>
