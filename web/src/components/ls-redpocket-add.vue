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
                    <el-form-item label="类型">
                        <el-select class="ls-select" v-model="form.type" placeholder="全部">
                            <el-option label="红包" value="lucky"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="游戏名称">
                        <div class="flex">
                            <el-select style="width: 120px" v-model="form.game" placeholder="全部">
                                <el-option label="炸弹" value="zhadan"></el-option>
                                <el-option label="标分" value="biaofen"></el-option>
                                <el-option label="麻将" value="majiang"></el-option>
                                <el-option label="跑得快" value="paodekuai"></el-option>
                                <el-option label="十三水" value="shisanshui"></el-option>
                                <el-option label="厦门麻将" value="xmmajiang"></el-option>
                            </el-select>
                        </div>
                    </el-form-item>
                    <el-form-item label="红包金额" prop="redPocket">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.redPocket"
                                placeholder="请输入红包数量"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入红包金额</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="概率" prop="probability">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.probabilitys"
                                placeholder="请输入抽中概率"
                                style="width: 300px"
                            >
                            </el-input
                            ><span style="margin-left: 5px">%</span>
                            <div class="muted xs m-r-16">输入抽中概率,请输入0.1-99的范围值</div>
                        </div>
                    </el-form-item>
                </el-form>
            </div>

            <!-- 底部弹窗页脚 -->
            <div slot="footer" class="dialog-footer">
                <el-button size="small" @click="close">取消</el-button>
                <el-button size="small" @click="updateOrAddRedPocket" type="primary">确认</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator'
import { apiaddRedPocket, apiSetRedPocketInfo } from '@/api/marketing'
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
    redpocket!: object
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
        game: '',
        type: '',
        redPocket: '',
        probabilitys: ''
    }

    // 表单验证
    valueRules = {}

    @Watch('redpocket', {
        immediate: true
    })
    getRedpocket(val: any) {
        this.$set(this, 'form', val)
    }
    updateOrAddRedPocket() {
        if (!this.form.game || !this.form.type || !this.form.redPocket || !this.form.probabilitys) {
            return this.$message.error('请输入基本信息')
        }

        if (this.action == 'add') {
            return this.addRedPocket(this.form)
        }

        return this.updateRedPocket(this.form)
    }

    addRedPocket(good: any) {
        apiaddRedPocket(good)
            .then((res: any) => {
                this.$emit('refresh')
                this.visible = false
            })
            .catch((res: any) => {
                this.visible = false
            })
    }

    updateRedPocket(good: any) {
        apiSetRedPocketInfo(good)
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
    }
}
</script>

<style scoped lang="scss"></style>
