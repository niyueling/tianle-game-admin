<template>
    <view>
        <view class="withdraw">

            <!-- Header -->
            <view class="withdraw-header bg-white flex row-between"
                @click="goPage('/bundle/pages/bank_list/bank_list')">
                <view class="black">提现账户</view>
                <view>
                    <template v-if="!bank.id">
                        <view class="muted">请选择<u-icon name="arrow-right" class="m-l-12"></u-icon>
                        </view>
                    </template>
                    <template v-else>
                        <view class="black">{{bank.name}}({{bank.account.substring(bank.account.length-4)}})<u-icon name="arrow-right" class="m-l-12">
                            </u-icon>
                        </view>
                    </template>
                </view>
            </view>

            <!-- Section -->
            <view class="withdraw-content m-t-20">
                <view class="black nr bold m-b-20">提现金额</view>

                <view class="input flex">
                    <text class="black bold">¥</text>
                    <input type="text" v-model="money" :placeholder="'最低提现'+ withdrawInfo.min_withdrawal_money+'元'" />
                </view>

                <view class="flex row-between m-t-30 m-b-30">
                    <view class="normal sm m-t-10">可提现余额￥{{withdrawInfo.wallet||0}}</view>
                    <text class="all sm flex row-right"
                        @click="money = withdrawInfo.wallet">全部提现</text>
                </view>
            </view>

            <view class="m-t-30 m-l-24 xs black">
                提示：
            </view>

            <view class="m-t-20 m-l-24 xs black">
                1. 最高可提现：¥{{withdrawInfo.max_withdrawal_money||0}}
            </view>
            <view class="m-t-20 m-l-24 xs black">
                2. 最低可提现：¥{{withdrawInfo.min_withdrawal_money||0}}
            </view>
            <view class="m-t-20 m-l-24 xs black" style="margin-bottom: 80rpx;">
                3. 提现手续费需扣除：{{withdrawInfo.withdrawal_service_charge||0}}%
            </view>
        </view>

        <view class="withdraw-btn flex row-center br60 md white" @click="withdrawFunc">
            确认提现
        </view>
        
        <u-toast ref="uToast" />
    </view>
</template>

<script>
    import {
        apiGetWithdrawInfo,
        apiWithdrawApply
    } from "@/api/user.js"
    import {
        prepay
    } from "@/api/app.js";
    export default {
        data() {
            return {
                money: '',
                bank: {},
                withdrawInfo: {}
            }
        },

        onShow() {
            this.getWithdrawInfoFunc();
            uni.$on('getBank', (res) => {
                this.bank = res;
            })
        },

        onUnload() {
            // 移除监听事件 优化性能   
            uni.$off('getBank');
        },

        methods: {
            async getWithdrawInfoFunc() {
                const res = await apiGetWithdrawInfo();
                this.withdrawInfo = res;
            },

            // 提现
            async withdrawFunc() {
                if (!this.bank.id) return this.$toast({
                    title: '请选择提现的账户'
                })
                if (this.money == '') return this.$toast({
                    title: '请输入提现金额'
                })
                const params = {
                    money: this.money,
                    bank_id: this.bank.id
                }
                const res = await apiWithdrawApply({
                    ...params
                })

                this.$refs.uToast.show({
                    title: '提现成功',
                    type: 'success',
                })
                setTimeout(() => {
                    this.$Router.back()
                }, 1000)
            },

            goPage(url) {
                this.$Router.push(url)
            }
        }
    }
</script>

<style lang="scss">
    // 第一层
    .withdraw {
        padding-top: 20rpx;

        &-header {
            padding: 30rpx;
        }

        &-content {
            width: 100%;
            padding: 30rpx;
            background-color: #FFFFFF;

            .input {
                font-size: 46rpx;
                border-bottom: 1rpx solid #E5E5E5;

                input {
                    padding-left: 30rpx;
                    font-size: 40rpx;
                    height: 80rpx;
                }
            }

            .all {
                color: $-color-primary;
            }
        }

        &-btn {
            width: 690rpx;
            height: 88rpx;
            margin: 0 30rpx;
            margin-top: 40rpx;
            box-sizing: border-box;
            background-color: $-color-primary;
        }
    }
</style>
