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
                    <el-form-item label="城市">
                        <el-select class="ls-select" v-model="form.city" placeholder="全部">
                            <el-option
                                :label="item"
                                :value="item"
                                v-for="(item, index) in cityList"
                                key="index"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="区县">
                        <el-select class="ls-select" v-model="form.county" placeholder="全部">
                            <el-option
                                :label="item"
                                :value="item"
                                v-for="(item, index1) in countyList[form.city]"
                                key="index1"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="游戏名称">
                        <div class="flex">
                            <el-select style="width: 120px" v-model="form.gameName" placeholder="全部">
                                <el-option label="炸弹" value="zhadan"></el-option>
                                <el-option label="标分" value="biaofen"></el-option>
                                <el-option label="麻将" value="majiang"></el-option>
                                <el-option label="厦门麻将" value="xmmajiang"></el-option>
                                <el-option label="跑得快" value="paodekuai"></el-option>
                                <el-option label="十三水" value="shisanshui"></el-option>
                            </el-select>
                        </div>
                    </el-form-item>
                </el-form>
            </div>

            <!-- 底部弹窗页脚 -->
            <div slot="footer" class="dialog-footer">
                <el-button size="small" @click="close">取消</el-button>
                <el-button size="small" @click="updateOrAddGame" type="primary">确认</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator'
import { apiaddGame, apiSearchList, apiSetGameInfo } from '@/api/marketing'
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
    game!: object
    @Prop({
        default: {}
    })
    cityList!: object
    @Prop({
        default: {}
    })
    countyList!: object
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
        city: '',
        county: '',
        gameName: ''
    }

    // 表单验证
    valueRules = {}

    @Watch('game', {
        immediate: true
    })
    getGame(val: any) {
        this.$set(this, 'form', val)
    }

    updateOrAddGame() {
        if (!this.form.city || !this.form.county || !this.form.gameName) {
            return this.$message.error('请输入基本信息')
        }

        if (this.action == 'add') {
            return this.addGame(this.form)
        }

        return this.updateGame(this.form)
    }

    addGame(good: any) {
        apiaddGame(good)
            .then((res: any) => {
                this.$emit('refresh')
                this.visible = false
            })
            .catch((res: any) => {
                this.visible = false
            })
    }

    updateGame(good: any) {
        apiSetGameInfo(good)
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
