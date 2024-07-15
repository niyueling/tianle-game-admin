<!-- 用户详情·钱包调整 -->
<template>
    <div>
        <div class="ls-dialog__trigger" @click="onTrigger">
            <!-- 触发弹窗 -->
            <slot name="trigger"></slot>
        </div>
        <el-dialog
            coustom-class="ls-dialog__content"
            :title="title"
            :visible="visible"
            :width="width"
            :top="top"
            :modal-append-to-body="false"
            center
            :before-close="close"
            :close-on-click-modal="false"
            @close="close"
        >
            <!-- 弹窗主要内容-->
            <div class="">
                <el-form :rules="valueRules" ref="valueRef" :model="form" label-width="120px" size="small">
                    <el-form-item label="邮件类型">
                        <div class="flex">
                            <el-select style="width: 120px" v-model="form.type">
                                <el-option label="普通邮件" value="notice"></el-option>
                                <el-option label="礼物邮件" value="noticeGift"></el-option>
                            </el-select>
                        </div>
                    </el-form-item>
                    <el-form-item label="邮件分类">
                        <div class="flex">
                            <el-select style="width: 120px" v-model="form.clubOwnerOnly">
                                <el-option label="公共邮件" :value="2"></el-option>
                                <el-option label="圈主邮件" :value="1"></el-option>
                            </el-select>
                        </div>
                    </el-form-item>
                    <el-form-item label="标题">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.title"
                            placeholder="请输入邮件标题"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="赠送钻石" v-if="form.type === 'noticeGift'">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.gem"
                            placeholder="请输入赠送钻石"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="赠送金豆" v-if="form.type === 'noticeGift'">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.ruby"
                            placeholder="请输入赠送金豆数量"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="邮件内容">
                        <textarea
                            class="ls-select-keyword"
                            v-model="form.content"
                            placeholder="请输入邮件内容"
                            style="width: 350px"
                            rows="15"
                        ></textarea>
                    </el-form-item>
                </el-form>
            </div>

            <!-- 底部弹窗页脚 -->
            <div slot="footer" class="dialog-footer">
                <el-button size="small" @click="close">取消</el-button>
                <el-button size="small" @click="addEmail" type="primary">确认</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator'
import { apiPublicEmailAdd } from '@/api/content/email'
@Component({
    components: {}
})
export default class LsUserChange extends Vue {
    @Prop({
        default: ''
    })
    title!: string
    @Prop({
        default: '660px'
    })
    width!: string | number //弹窗的宽度
    @Prop({
        default: '20vh'
    })
    top!: string | number //弹窗的距离顶部位置
    visible = false
    $refs!: {
        valueRef: any
    }
    form = {
        type: '',
        title: '',
        gem: 0,
        ruby: 0,
        content: '',
        clubOwnerOnly: ''
    }

    // 表单验证
    valueRules = {}

    addEmail() {
        if (!this.form.type || !this.form.title || !this.form.content || !this.form.clubOwnerOnly) {
            return this.$message.error('请输入基本信息')
        }

        apiPublicEmailAdd(this.form)
            .then((res: any) => {
                this.$emit('refresh')
                this.visible = false
            })
            .catch((res: any) => {
                this.visible = false
            })
    }

    onTrigger() {
        this.visible = true
    }

    // 关闭弹窗
    close() {
        this.visible = false

        // 重制表单内容
        this.$set(this, 'form', {})
    }
}
</script>

<style scoped lang="scss"></style>
