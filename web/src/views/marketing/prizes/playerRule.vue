<template>
    <div class="ls-add-admin">
        <div class="ls-card ls-coupon-edit__form m-t-10">
            <div class="nr weight-500 m-b-20">用户设置</div>
            <el-form ref="list" :model="list" inline label-width="120px" size="small">
                <el-form-item label="活动状态">
                    <el-radio v-model="list.set.player.open" :label="0">关闭</el-radio>
                    <el-radio v-model="list.set.player.open" :label="1">开启</el-radio>
                </el-form-item>
                <el-form-item label="活动时间">
                    <el-date-picker
                        v-model="tableData"
                        type="datetimerange"
                        align="right"
                        unlink-panels
                        range-separator="至"
                        start-placeholder="开始时间"
                        end-placeholder="结束时间"
                        :picker-options="pickerOptions"
                        @change="splitTime"
                        value-format="yyyy-MM-dd HH:mm:ss"
                    >
                    </el-date-picker>
                </el-form-item>
            </el-form>
        </div>

        <div class="ls-card ls-coupon-edit__form m-t-10">
            <div class="nr weight-500 m-b-20">活跃用户奖品设置</div>
            <el-form ref="list" :model="list" label-width="70px" style="width: 1300px" size="small">
                <el-form-item v-for="(item, index) in list.prizes.player" :key="index">
                    <div class="item">
                        <el-form :model="item" inline>
                            <el-form-item required label-width="120px" label="奖品名称">
                                <el-input v-model="list.prizes.player[index].name" style="width: 120px"></el-input>
                            </el-form-item>
                            <el-form-item required label-width="120px" label="价值">
                                <el-input v-model="list.prizes.player[index].price" style="width: 120px"></el-input>
                                元
                            </el-form-item>
                            <el-form-item required label-width="120px" label="数量">
                                <el-input v-model="list.prizes.player[index].quantity" style="width: 120px"></el-input>
                            </el-form-item>
                            <el-form-item required label-width="120px" label="前端库存">
                                <el-input v-model="list.prizes.player[index].num" style="width: 120px"></el-input>
                                个
                            </el-form-item>
                            <el-form-item required label-width="120px" label="实际库存">
                                <el-input
                                    v-model="list.prizes.player[index].residueNum"
                                    style="width: 120px"
                                ></el-input>
                                个
                            </el-form-item>
                            <el-form-item required label-width="120px" label="概率">
                                <el-input
                                    v-model="list.prizes.player[index].probability"
                                    style="width: 120px"
                                ></el-input>
                                %
                            </el-form-item>
                            <el-form-item required label-width="120px" label="类型">
                                <el-select
                                    style="width: 92px"
                                    v-model="list.prizes.player[index].source"
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
                                    v-model="list.prizes.player[index].playerShortId"
                                    placeholder="请输入用户ShortId"
                                    style="width: 120px"
                                ></el-input>
                            </el-form-item>
                        </el-form>

                        <div class="del m-t-5" @click="delPlayerPrizes(index)">
                            <i class="el-icon-delete"></i>
                        </div>
                    </div>
                </el-form-item>

                <el-form-item>
                    <div class="add-btn flex row-center" @click="addPlayerPrizes">
                        +添加 {{ list.prizes.player.length }}/10
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
    list: any = {
        set: {
            player: {
                open: false,
                activeStartAt: '',
                activeEndAt: ''
            }
        },
        prizes: {
            player: [
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

    pickerOptions: any = {
        shortcuts: [
            {
                text: '今日',
                onClick(picker: any) {
                    let end = new Date().setHours(23, 59, 59, 999)
                    let start = new Date().setHours(0, 0, 0, 0)
                    picker.$emit('pick', [new Date(start), new Date(end)])
                }
            },
            {
                text: '昨日',
                onClick(picker: any) {
                    let start = new Date().setHours(0, 0, 0, 0)
                    let end = new Date().setHours(0, 0, 0, 0)
                    start = start - 3600 * 1000 * 24
                    picker.$emit('pick', [new Date(start), new Date(end)])
                }
            },
            {
                text: '最近三天',
                onClick(picker: any) {
                    let start = new Date().setHours(0, 0, 0, 0)
                    let end = new Date().setHours(0, 0, 0, 0)
                    start = start - 3600 * 1000 * 24 * 3
                    picker.$emit('pick', [new Date(start), new Date(end)])
                }
            },
            {
                text: '最近一周',
                onClick(picker: any) {
                    let start = new Date().setHours(0, 0, 0, 0)
                    let end = new Date().setHours(0, 0, 0, 0)
                    start = start - 3600 * 1000 * 24 * 7
                    picker.$emit('pick', [new Date(start), new Date(end)])
                }
            },
            {
                text: '本周',
                onClick(picker: any) {
                    let day = new Date().getDay();
                    let end = new Date().setHours(0, 0, 0, 0)
                    let start = end - 3600 * 1000 * 24 * day
                    picker.$emit('pick', [new Date(start), new Date(end)])
                }
            },
            {
                text: '上周',
                onClick(picker: any) {
                    let day = new Date().getDay();
                    let end = new Date().setHours(0, 0, 0, 0) - 3600 * 1000 * 24 * day;
                    let start = end - 3600 * 1000 * 24 * 7
                    picker.$emit('pick', [new Date(start), new Date(end)])
                }
            },
            {
                text: '本月',
                onClick(picker: any) {
                    var today = new Date();
                    var month = new Date(today.getFullYear(), today.getMonth(), 1);
                    var firstDay = new Date(month.getFullYear(), month.getMonth(), 1).getTime();
                    var lastDay = new Date().setHours(0,0,0,0);

                    picker.$emit('pick', [new Date(firstDay), new Date(lastDay)])
                }
            },
            {
                text: '上月',
                onClick(picker: any) {
                    var today = new Date();
                    var lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    var firstDay = new Date(lastMonth.getFullYear(), lastMonth.getMonth(), 1).getTime();
                    var lastDay = new Date(lastMonth.getFullYear(), lastMonth.getMonth() + 1, 1).getTime();

                    picker.$emit('pick', [new Date(firstDay), new Date(lastDay)])
                }
            },
            {
                text: '最近一个月',
                onClick(picker: any) {
                    let start = new Date().setHours(0, 0, 0, 0)
                    let end = new Date().setHours(0, 0, 0, 0)
                    start = start - 3600 * 1000 * 24 * 30
                    picker.$emit('pick', [new Date(start), new Date(end)])
                }
            },
            {
                text: '最近三个月',
                onClick(picker: any) {
                    let start = new Date().setHours(0, 0, 0, 0)
                    let end = new Date().setHours(0, 0, 0, 0)
                    start = start - 3600 * 1000 * 24 * 90
                    picker.$emit('pick', [new Date(start), new Date(end)])
                }
            }
        ]
    }
    tableData: any = []

    disabled = false

    splitTime() {
        if (this.tableData != null) {
            this.list.set.player.activeStartAt = this.tableData[0]
            this.list.set.player.activeEndAt = this.tableData[1]
        }
    }

    addPlayerPrizes() {
        if (this.list.prizes.player.length >= 10) {
            return this.$message.error('不能继续添加了!')
        }
        this.list.prizes.player.push({
            name: '',
            price: '',
            probability: '',
            num: '',
            residueNum: '',
            type: 'player',
            playerShortId: 0,
            source: '',
            quantity: ''
        })
    }

    // 删除规则规格项
    delPlayerPrizes(index: number) {
        if (this.list.prizes.player.length <= 1) {
            return this.$message.error('已经是最后一个了!')
        }
        this.list.prizes.player.splice(index, 1)
    }

    onSubmit() {
        if (!this.disabled) {
            this.disabled = true

            const list = {
                prizes: this.list.prizes,
                type: 'player',
                ...this.list.set
            }
            list.prizes.player.forEach((item: any) => {
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
        apiPrizeRuleList({ type: 'player' })
            .then((res: any) => {
                this.list.set.player = res.set.player
                this.list.prizes.player = res.prizes.player
                this.tableData = [this.list.set.player.activeStartAt, this.list.set.player.activeEndAt]
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
