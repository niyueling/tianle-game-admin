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
                    <el-form-item label="用户ShortId">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.playerShortId"
                            placeholder="请输入用户ShortId"
                            @input="getUserClubLists"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="邮件类型">
                        <div class="flex">
                            <el-select style="width: 120px" v-model="form.type">
                                <el-option label="普通邮件" value="message"></el-option>
                                <el-option label="礼物邮件" value="gift"></el-option>
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
                    <el-form-item label="赠送钻石" v-if="form.type === 'gift'">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.gem"
                            placeholder="请输入赠送钻石数量"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="赠送金豆" v-if="form.type === 'gift'">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.ruby"
                            placeholder="请输入赠送金豆数量"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="赠送战队金币" v-if="form.type === 'gift'">
                        <el-input
                            class="ls-select-keyword"
                            v-model="form.gold"
                            placeholder="请输入赠送战队金币数量"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="战队" v-if="form.gold > 0">
                        <el-select class="ls-select" v-model="form.clubId" placeholder="请选择战队">
                            <el-option :label="item.clubName" :value="item.club"  v-for="(item, index) in clubLists" key="index"></el-option>
                        </el-select>
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
import { Component, Prop, Vue } from 'vue-property-decorator'
import { apiEmailAdd, apiUserClubLists } from '@/api/content/email'
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
        playerShortId: '',
        type: '',
        title: '',
        gem: 0,
        ruby: 0,
        gold: 0,
        content: '',
        clubId: ''
    }

    clubLists = []

    // 表单验证
    valueRules = {}

    getUserClubLists() {
        if(this.form.playerShortId.length < 7) return ;
        apiUserClubLists({ shortId: this.form.playerShortId })
          .then((res: any) => {
              this.clubLists = res;
              console.log(this.clubLists);
          })
    }

    addEmail() {
        if (!this.form.type || !this.form.title || !this.form.content || !this.form.playerShortId) {
            return this.$message.error('请输入基本信息')
        }

        apiEmailAdd(this.form)
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
