<template>
    <div class="ls-add-admin">
        <div class="ls-card ls-coupon-edit__form m-t-10">
            <div class="nr weight-500 m-b-20">奖品设置</div>
            <el-form ref="list" :model="list" label-width="70px" style="width: 1300px" size="small">
                <el-form-item v-for="(item, index) in list.prizes.gem" :key="index">
                    <div class="item">
                        <el-form :model="item" inline>
                            <el-form-item required label-width="120px" label="奖品名称">
                                <el-input v-model="list.prizes.gem[index].name" style="width: 120px"></el-input>
                            </el-form-item>
                            <el-form-item required label-width="120px" label="价值">
                                <el-input
                                    v-model="list.prizes.gem[index].price"
                                    type="number"
                                    style="width: 120px"
                                ></el-input>
                                元
                            </el-form-item>
                            <el-form-item required label-width="120px" label="数量">
                                <el-input
                                    v-model="list.prizes.gem[index].quantity"
                                    type="number"
                                    style="width: 120px"
                                ></el-input>
                            </el-form-item>
                            <el-form-item required label-width="120px" label="前端库存">
                                <el-input v-model="list.prizes.gem[index].num" style="width: 120px"></el-input>
                                个
                            </el-form-item>
                            <el-form-item required label-width="120px" label="实际库存">
                                <el-input v-model="list.prizes.gem[index].residueNum" style="width: 120px"></el-input>
                                个
                            </el-form-item>
                            <el-form-item required label-width="120px" label="概率">
                                <el-input v-model="list.prizes.gem[index].probability" style="width: 120px"></el-input>
                                %
                            </el-form-item>
                            <el-form-item required label-width="120px" label="类型">
                                <el-select
                                    style="width: 92px"
                                    v-model="list.prizes.gem[index].source"
                                    placeholder="请选择"
                                >
                                    <el-option label="手机" value="mobile"></el-option>
                                    <el-option label="钻石" value="gem"></el-option>
                                    <el-option label="红包" value="redpocket"></el-option>
                                    <el-option label="金豆" value="ruby"></el-option>
                                    <el-option label="谢谢惠顾" value="empty"></el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label-width="120px" label="指定用户">
                                <el-input
                                    v-model="list.prizes.gem[index].playerShortId"
                                    placeholder="请输入用户ShortId"
                                    style="width: 120px"
                                ></el-input>
                            </el-form-item>
                        </el-form>

                        <div class="del m-t-5" @click="delGemPrizes(index)">
                            <i class="el-icon-delete"></i>
                        </div>
                    </div>
                </el-form-item>

                <el-form-item>
                    <div class="add-btn flex row-center" @click="addGemPrizes">
                        +添加 {{ list.prizes.gem.length }}/45
                    </div>
                </el-form-item>
            </el-form>
        </div>

        <!-- 底部保存或取消 -->
        <div class="bg-white ls-fixed-footer flex row-center">
            <div class="row-center flex">
                <el-button size="small" @click="$router.go(-1)">取消</el-button>
                <el-button size="small" type="primary" @click="onSubmit('form')">保存</el-button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import LsEditor from '@/components/editor.vue'
import lsDialog from '@/components/ls-dialog.vue'
import MaterialSelect from '@/components/material-select/index.vue'
import { apiPrizeRuleList, apiSetPrizes } from '@/api/marketing/index.ts'

@Component({
    components: {
        MaterialSelect,
        lsDialog,
        LsEditor
    }
})
export default class RechargeRuleEdit extends Vue {
    rankId = ''
    list: any = {
        prizes: {
            gem: [
                {
                    name: '',
                    price: '',
                    probability: '',
                    num: '',
                    residueNum: '',
                    type: '',
                    playerShortId: 0,
                    source: '',
                    quantity: ''
                }
            ]
        }
    }

    disabled = false

    addGemPrizes() {
        if (this.list.prizes.gem.length >= 45) {
            return this.$message.error('不能继续添加了!')
        }
        this.list.prizes.gem.push({
            name: '',
            price: '',
            probability: '',
            num: '',
            residueNum: '',
            type: 'gem',
            playerShortId: 0,
            source: '',
            quantity: ''
        })
    }

    // 删除规则规格项
    delGemPrizes(index: number) {
        if (this.list.prizes.gem.length <= 1) {
            return this.$message.error('已经是最后一个了!')
        }
        this.list.prizes.gem.splice(index, 1)
    }

    onSubmit() {
        if (!this.disabled) {
            this.disabled = true

            const list = {
                prizes: this.list.prizes,
                type: 'gem'
            }
            list.prizes.gem.forEach((item: any) => {
                item.rankId = this.rankId
                delete item._id
                delete item.createAt
            })
            apiSetPrizes({ ...list })
                .then(() => {
                    this.detail()
                    this.$message.success('修改成功!')
                    this.disabled = false
                })
                .catch(() => {
                    this.disabled = false
                    this.$message.error('数据获取失败!')
                })
        }
    }

    // 详情
    detail() {
        apiPrizeRuleList({ rankId: this.rankId, type: 'gem' })
            .then((res: any) => {
                this.list.prizes.gem = res.prizes.gem
            })
            .catch(() => {
                this.$message.error('数据获取失败!')
            })
    }

    created() {
        const query: any = this.$route.query
        if (query.rankId) {
            this.rankId = query.rankId
        }
        this.detail()
    }
}
</script>

<style lang="scss" scoped>
.ls-add-admin {
    padding-bottom: 80px;

    .ls-input {
        width: 380px;
    }

    .desc {
        display: block;
        color: #999;
        font-size: 12px;
    }

    .add-btn {
        width: 830px;
        height: 40px;
        box-sizing: border-box;
        border: 2px solid #f5f5f5;
    }

    .item {
        padding: 30px 0;
        margin-bottom: 50rpx;
        position: relative;
        background-color: #f5f5f5;
        .del {
            right: 10px;
            top: 0px;
            font-size: 24px;
            position: absolute;
        }
    }
}
</style>
