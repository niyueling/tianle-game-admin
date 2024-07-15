<template>
    <div class="ls-add-admin">
        <div class="ls-card ls-coupon-edit__form m-t-10">
            <div class="nr weight-500 m-b-20">游戏设置</div>
            <el-form ref="list" :model="list" inline label-width="120px" size="small">
                <el-form-item label="炸弹状态">
                    <el-radio v-model="list.set.zhadan" :label="0">关闭</el-radio>
                    <el-radio v-model="list.set.zhadan" :label="1">开启</el-radio>
                </el-form-item>

                <el-form-item label="麻将状态">
                    <el-radio v-model="list.set.majiang" :label="0">关闭</el-radio>
                    <el-radio v-model="list.set.majiang" :label="1">开启</el-radio>
                </el-form-item>

                <el-form-item label="标分状态">
                    <el-radio v-model="list.set.biaofen" :label="0">关闭</el-radio>
                    <el-radio v-model="list.set.biaofen" :label="1">开启</el-radio>
                </el-form-item>

                <el-form-item label="跑得快状态">
                    <el-radio v-model="list.set.paodekuai" :label="0">关闭</el-radio>
                    <el-radio v-model="list.set.paodekuai" :label="1">开启</el-radio>
                </el-form-item>

                <el-form-item label="十三水状态">
                    <el-radio v-model="list.set.shisanshui" :label="0">关闭</el-radio>
                    <el-radio v-model="list.set.shisanshui" :label="1">开启</el-radio>
                </el-form-item>
            </el-form>
        </div>

        <div class="ls-card ls-coupon-edit__form m-t-10">
            <div class="nr weight-500 m-b-20">救助规则</div>
            <el-form ref="list" :model="list" label-width="120px" style="width: 1300px" size="small">
                <el-form-item v-for="(item, index) in list.rule" :key="index" :label="'规则' + (index + 1)">
                    <div class="item">
                        <el-form :model="item" inline label-width="120px">
                            <el-form-item label="游戏">
                                <div class="flex">
                                    <el-select
                                        style="width: 120px"
                                        v-model="list.rule[index].game"
                                        placeholder="请选择"
                                    >
                                        <el-option label="炸弹" value="zhadan"></el-option>
                                        <el-option label="标分" value="biaofen"></el-option>
                                        <el-option label="麻将" value="majiang"></el-option>
                                        <el-option label="跑得快" value="paodekuai"></el-option>
                                        <el-option label="十三水" value="shisanshui"></el-option>
                                        <el-option label="厦门麻将" value="xmmajiang"></el-option>
                                    </el-select>
                                </div>
                            </el-form-item>
                            <el-form-item required label="天数">
                                <el-input class="m-r-10" v-model="list.rule[index].day" style="width: 120px"></el-input>
                                天
                            </el-form-item>
                            <el-form-item required class="m-t-10" label="房间数">
                                <el-input
                                    class="m-r-10"
                                    v-model="list.rule[index].juCount"
                                    style="width: 120px"
                                ></el-input>
                                间
                            </el-form-item>
                            <el-form-item required class="m-t-10" label="胜率">
                                <el-input
                                    class="m-r-10"
                                    v-model="list.rule[index].juRank"
                                    style="width: 120px"
                                ></el-input>
                                %
                            </el-form-item>
                            <el-form-item required class="m-t-10" label="最低输局积分">
                                <el-input
                                    class="m-r-10"
                                    v-model="list.rule[index].juMinScore"
                                    style="width: 120px"
                                ></el-input>
                                积分
                            </el-form-item>
                            <el-form-item required class="m-t-10" label="最高输局积分">
                                <el-input
                                    class="m-r-10"
                                    v-model="list.rule[index].juMaxScore"
                                    style="width: 120px"
                                ></el-input>
                                积分
                            </el-form-item>
                            <el-form-item required class="m-t-10" label="最低救助级别">
                                <el-select style="width: 120px" v-model="list.rule[index].minLevel">
                                    <el-option label="1级" value="1"></el-option>
                                    <el-option label="2级" value="2"></el-option>
                                    <el-option label="3级" value="3"></el-option>
                                    <el-option label="4级" value="4"></el-option>
                                    <el-option label="5级" value="5"></el-option>
                                    <el-option label="6级" value="6"></el-option>
                                    <el-option label="7级" value="7"></el-option>
                                    <el-option label="8级" value="8"></el-option>
                                    <el-option label="9级" value="9"></el-option>
                                    <el-option label="10级" value="10"></el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item required class="m-t-10" label="最高救助级别">
                                <el-select style="width: 120px" v-model="list.rule[index].maxLevel">
                                    <el-option label="1级" value="1"></el-option>
                                    <el-option label="2级" value="2"></el-option>
                                    <el-option label="3级" value="3"></el-option>
                                    <el-option label="4级" value="4"></el-option>
                                    <el-option label="5级" value="5"></el-option>
                                    <el-option label="6级" value="6"></el-option>
                                    <el-option label="7级" value="7"></el-option>
                                    <el-option label="8级" value="8"></el-option>
                                    <el-option label="9级" value="9"></el-option>
                                    <el-option label="10级" value="10"></el-option>
                                </el-select>
                            </el-form-item>
                        </el-form>

                        <div class="del m-t-5" @click="delRule(index)">
                            <i class="el-icon-delete"></i>
                        </div>
                    </div>
                </el-form-item>

                <el-form-item>
                    <div class="add-btn flex row-center" @click="addRule">+添加 {{ list.rule.length }}/30</div>
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
import { apiRateRuleList, apiSetRateRuleList } from '@/api/marketing/index.ts'

@Component({
    components: {
        MaterialSelect,
        lsDialog,
        LsEditor
    }
})
export default class RechargeRuleEdit extends Vue {
    list: any = {
        set: {
            majiang: 0,
            zhadan: 0,
            biaofen: 0,
            paodekuai: 0,
            shisanshui: 0
        },
        rule: [
            {
                minLevel: '',
                day: '',
                juCount: '',
                juRank: '',
                juMinScore: '',
                juMaxScore: '',
                game: '',
                maxLevel: ''
            }
        ]
    }

    disabled = false

    addRule() {
        if (this.list.rule.length >= 30) {
            return this.$message.error('不能继续添加了!')
        }
        this.list.rule.push({
            day: '',
            juCount: '',
            juRank: '',
            juMinScore: '',
            juMaxScore: '',
            coolingcycle: '',
            maxLevel: ''
        })
    }

    // 删除规则规格项
    delRule(index: number) {
        if (this.list.rule.length <= 1) {
            return this.$message.error('已经是最后一个了!')
        }
        this.list.rule.splice(index, 1)
    }

    onSubmit() {
        if (!this.disabled) {
            this.disabled = true

            const list = {
                rule: this.list.rule,
                ...this.list.set
            }
            try {
                list.rule.forEach((item: any) => {
                    delete item._id
                    delete item.createAt
                })
            } catch (error) {}
            apiSetRateRuleList({ ...list })
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
        apiRateRuleList({})
            .then((res: any) => {
                this.list = res
                console.log(this.list)
            })
            .catch(() => {
                this.$message.error('数据获取失败!')
            })
    }

    created() {
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
