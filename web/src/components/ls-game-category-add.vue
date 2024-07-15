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
                        <el-select class="ls-select" v-model="form.category" placeholder="全部">
                            <el-option label="金豆场" value="ruby"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="游戏">
                        <el-select style="width: 120px" v-model="form.game" placeholder="全部" @change="getGameItem">
                            <el-option label="炸弹" value="zhadan"></el-option>
                            <el-option label="标分" value="biaofen"></el-option>
                            <el-option label="麻将" value="majiang"></el-option>
                            <el-option label="跑得快" value="paodekuai"></el-option>
                            <el-option label="十三水" value="shisanshui"></el-option>
                            <el-option label="厦门麻将" value="xmmajiang"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="游戏分类" v-if="form.game">
                        <el-select style="width: 120px" v-model="form.gameCategory" placeholder="全部" @change="getGameItem">
                            <el-option :label="item.name"
                                       :value="item._id"
                                       v-for="item in item"
                                       key="index"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="名称" prop="title">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.title"
                                placeholder="请输入场次名称"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入场次名称</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="等级" prop="level">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.level"
                                placeholder="请输入场次等级"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入场次等级</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="底分" prop="level">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.minScore"
                                placeholder="请输入场次底分"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入场次底分</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="最低携带" prop="minAmount">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.minAmount"
                                placeholder="请输入最低携带数量"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入最低携带数量</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="最高携带数量" prop="maxAmount">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.maxAmount"
                                placeholder="请输入最高携带数量"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入最高携带数量</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="房费" prop="roomRate" v-if="form.category">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.roomRate"
                                placeholder="请输入房费"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入扣除{{ this.formatRoomRate(form.category) }}数量</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="人数" prop="playerCount">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.playerCount"
                                placeholder="请输入人数"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入人数</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="是否免输/翻倍">
                        <el-select class="ls-select" v-model="form.isOpenDouble" placeholder="全部">
                            <el-option label="开启" :value="1"></el-option>
                            <el-option label="关闭" :value="0"></el-option>
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
import {apiaddGameCategory, apiGameCategoryItemLists, apiSetGameCategoryInfo} from '@/api/marketing'
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
    category!: object //编辑的信息
    @Prop({
        default: []
    })
    item!: Object
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
        category: '',
        title: '',
        level: '',
        minScore: '',
        minAmount: '',
        maxAmount: '',
        isOpen: '',
        roomRate: '',
        playerCount: '',
        game: '',
        gameCategory: '',
        isOpenDouble: ''
    }

    // 表单验证
    valueRules = {}

    @Watch('category', {
        immediate: true
    })
    getCategory(val: any) {
        this.$set(this, 'form', val)

        if(this.form.game) this.getGameItem();
    }

    getGameItem() {
        apiGameCategoryItemLists({game: this.form.game})
            .then((res: any) => {
                this.item = res;
                console.log(this.item)
            })
    }

    updateOrAddGameCategory() {
        if (!this.form.category || !this.form.level || !this.form.title || !this.form.minScore || !this.form.isOpen) {
            return this.$message.error('请输入基本信息')
        }

        if (this.action == 'add') {
            return this.addGameCategory(this.form)
        }

        return this.updateGameCategory(this.form)
    }

    formatRoomRate(category: any) {
        if (category == 'ruby') {
            return '金豆'
        }
    }

    addGameCategory(good: any) {
        apiaddGameCategory(good)
            .then((res: any) => {
                this.$emit('refresh')
                this.visible = false
            })
            .catch((res: any) => {
                this.visible = false
            })
    }

    updateGameCategory(category: any) {
        apiSetGameCategoryInfo(category)
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
