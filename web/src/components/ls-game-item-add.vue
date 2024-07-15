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
                    <el-form-item label="名称" prop="name">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.name"
                                placeholder="请输入分类名称"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入游戏分类名称</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="游戏" prop="level">
                        <el-select style="width: 120px" v-model="form.gameType" placeholder="全部">
                            <el-option label="炸弹" value="zhadan"></el-option>
                            <el-option label="标分" value="biaofen"></el-option>
                            <el-option label="麻将" value="majiang"></el-option>
                            <el-option label="跑得快" value="paodekuai"></el-option>
                            <el-option label="十三水" value="shisanshui"></el-option>
                            <el-option label="厦门麻将" value="xmmajiang"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="状态">
                        <el-select class="ls-select" v-model="form.isOpen" placeholder="全部">
                            <el-option label="正常" :value="1"></el-option>
                            <el-option label="暂停" :value="2"></el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
            </div>

            <!-- 底部弹窗页脚 -->
            <div slot="footer" class="dialog-footer">
                <el-button size="small" @click="close">取消</el-button>
                <el-button size="small" @click="updateOrAddGameCategory" type="primary">确认</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator'
import { apiaddGameItem, apiSetGameItemInfo } from '@/api/marketing'
@Component({
    components: {}
})
export default class LsUserChange extends Vue {
    @Prop({
        default: ''
    })
    title!: string //弹窗标题
    @Prop({
        default: 'add'
    })
    action!: string //弹窗类型
    @Prop({
        default: {}
    })
    category!: object //编辑的商品信息
    @Prop({
        default: '660px'
    })
    width!: string | number //弹窗的宽度
    @Prop({
        default: '20vh'
    })
    top!: string | number //弹窗的距离顶部位置
    /** S Data **/
    visible = false
    $refs!: {
        valueRef: any
    }
    form = {
        name: '',
        gameType: '',
        isOpen: '',
    }

    // 表单验证
    valueRules = {}

    @Watch('category', {
        immediate: true
    })
    getCategory(val: any) {
        this.$set(this, 'form', val)
    }
    updateOrAddGameCategory() {
        if (!this.form.name || !this.form.gameType || !this.form.isOpen) {
            return this.$message.error('请输入基本信息')
        }

        if (this.action == 'add') {
            return this.addGameItem(this.form)
        }

        return this.updateGameItem(this.form)
    }

    addGameItem(good: any) {
        apiaddGameItem(good)
            .then((res: any) => {
                this.$emit('refresh')
                this.visible = false
            })
            .catch((res: any) => {
                this.visible = false
            })
    }

    updateGameItem(category: any) {
        apiSetGameItemInfo(category)
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
        // this.$set(this, 'form', {})
    }
}
</script>

<style scoped lang="scss"></style>
