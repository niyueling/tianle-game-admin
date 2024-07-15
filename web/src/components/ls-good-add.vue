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
                    <el-form-item label="商品类别">
                        <el-select class="ls-select" v-model="form.goodsType" placeholder="全部">
                            <el-option label="钻石" :value="0"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="商品数量" prop="amount">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.amount"
                                placeholder="请输入商品数量"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入商品数量</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="安卓价格" prop="androidPrice">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.androidPrice"
                                placeholder="请输入安卓价格"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入安卓价格</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="苹果价格">
                        <el-select class="ls-select" v-model="form.applePriceId" placeholder="全部">
                            <el-option label="25元" value="tianle.majiang.pay_1"></el-option>
                            <el-option label="60元" value="tianle.majiang.pay_2"></el-option>
                            <el-option label="118元" value="tianle.majiang.pay_3"></el-option>
                            <el-option label="238元" value="tianle.majiang.pay_4"></el-option>
                            <el-option label="588元" value="tianle.majiang.pay_5"></el-option>
                            <el-option label="1198元" value="tianle.majiang.pay_6"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="首次购买赠送" prop="firstTimeAmount">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.firstTimeAmount"
                                placeholder="请输入首次购买赠送数量"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入首次购买赠送数量</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="状态">
                        <el-select class="ls-select" v-model="form.isOnline" placeholder="全部">
                            <el-option label="上架" :value="1"></el-option>
                            <el-option label="下架" :value="2"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="补助宝箱">
                        <el-select class="ls-select" v-model="form.boxId" placeholder="全部">
                            <el-option label="不选择" value=""></el-option>
                            <el-option :label="formatBoxName(item)" :value="item._id"  v-for="(item, index) in box" key="index"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="宝箱个数" v-if="form.boxId">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.boxCount"
                                placeholder="请输入补助宝箱个数"
                                style="width: 300px"
                            >
                            </el-input>
                            <div class="muted xs m-r-16">输入补助宝箱个数</div>
                        </div>
                    </el-form-item>
                </el-form>
            </div>

            <!-- 底部弹窗页脚 -->
            <div slot="footer" class="dialog-footer">
                <el-button size="small" @click="close">取消</el-button>
                <el-button size="small" @click="updateOrAddGoods" type="primary">确认</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator'
import { apiaddGoods, apiSetGoodInfo } from '@/api/marketing'
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
    good!: object //编辑的商品信息
    @Prop({
        default: {}
    })
    box!: any //选择的宝箱
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
        goodsType: '',
        amount: '',
        androidPrice: '',
        applePriceId: '',
        firstTimeAmount: '',
        isOnline: '',
        boxId: "",
        boxCount: ''
    }

    // 表单验证
    valueRules = {}

    @Watch('good', {
        immediate: true
    })
    getGood(val: any) {
        this.$set(this, 'form', val)
    }

    formatBoxName(item: any) {
        let boxName = `${item.name}:`;

        if(item.star) {
            boxName += "炸弹:";
            item["star"] != 11 ? boxName += item.star + `星(${item.count}次)` : boxName += `不烧鸡(${item.zdFpCount}次)`;
        }

        if(item.mahjong.cardlists) {
            boxName += ",麻将:";
            let cards = [];
            for (let i=0; i<item.mahjong.cardlists.length; i++)  {
                cards.push(`初始牌：${item.mahjong.cardlists[i]["cardName"]}`);
            }
            boxName += cards.join(" + ");
            boxName += `,辅助摸牌次数：${item.mahjong.moCount}次`;
        }

        if(item.pdk) {
            boxName += ",跑得快:";
            let cards = [];
            for (let i=0; i<item.pdk.length; i++)  {
                cards.push(`初始牌：${item.pdk[i]["cardName"]}`);
            }
            boxName += cards.join(" + ");
        }

        if(item.sss) {
            boxName += ",十三水:";
            let cards = [];
            for (let i=0; i<item.sss.length; i++)  {
                cards.push(`初始牌：${item.sss[i]["cardName"]}`);
            }
            boxName += cards.join(" + ");
        }

        if(item.bf) {
            boxName += ",标分:";
            let cards = [];
            for (let i=0; i<item.bf.length; i++)  {
                cards.push(`初始牌：${item.bf[i]["cardName"]}`);
            }
            boxName += cards.join(" + ");
        }

        return boxName;
    }

    updateOrAddGoods() {
        if (!this.form.amount || !this.form.androidPrice || !this.form.applePriceId || !this.form.isOnline) {
            return this.$message.error('请输入基本信息')
        }

        if (this.action == 'add') {
            return this.addGoods(this.form)
        }

        return this.updateGoods(this.form)
    }

    addGoods(good: any) {
        apiaddGoods(good)
            .then((res: any) => {
                this.$emit('refresh')
                this.visible = false
            })
            .catch((res: any) => {
                this.visible = false
            })
    }

    updateGoods(good: any) {
        apiSetGoodInfo(good)
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
