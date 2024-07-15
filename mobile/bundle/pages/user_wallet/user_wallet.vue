<template>
    <view class="wallet">

        <!-- Header -->
        <view class="header white flex row-between">
            <view class="white">
                <view class=" xs">可提现金额</view>
                <view class="header-price m-t-20">{{withdrawInfo.wallet||0}}</view>
            </view>
            
            <router-link to="/bundle/pages/user_withdraw/user_withdraw">
                <view class="header-btn br60 flex bg-white row-center md">
                    去提现
                </view>
            </router-link>
        </view>

        <!-- Section -->
        <view class="section m-t-40">
            <view class="title xl normal bold p-l-30">
                <view class="inline m-t-4 m-r-20"></view>
                提现记录
            </view>
            
            <view class="content">
                <mescroll-uni ref="mescrollRef" top="0rpx" :height="height+'px'" @init="mescrollInit"
                    @up="upCallback" :up="upOption" @down="downCallback">
                
                    <block v-for="(item, index) in lists" :key="index">
                        <view class="wallet-cell flex row-between">
                            <!-- Left -->
                            <view>
                                <view class="remark md">余额提现</view>
                                <view class="time m-t-10 muted sm">{{item.create_time}}</view>
                            </view>
                            
                            <!-- Right -->
                            <view class="black">
                                <view class="money lg text-right">{{item.change_amount}}</view>
                                <template v-if="item.status == 0">
                                    <view style="color: #01D739;" class="sm">申请中</view>
                                </template>
                                <template v-if="item.status == 1">
                                    <view style="color: #01D739;" class="sm">处理中</view>
                                </template>
                                <template v-if="item.status == 2">
                                    <view class="lighter sm">转账成功</view>
                                </template>
                                <template v-if="item.status == 3">
                                    <view style="color: #FF4141;" class="sm">转账失败</view>
                                </template>
                            </view>
                        </view>
                    </block>
                
                </mescroll-uni>
            </view>
        </view>
    </view>
</template>

<script>
    import MescrollMixin from "@/components/mescroll-uni/mescroll-mixins.js";
	import {
		getRect
	} from '@/utils/tools'
    import {
        apiWithdrawLog,
        apiGetWithdrawInfo
    } from "@/api/user.js"
    export default {
        mixins: [MescrollMixin],
        data() {
            return {
                height: 414,
                withdrawInfo: {},
                lists: [],
                
                upOption: {
                    empty: {
                        icon: '/static/images/empty/money.png',
                        tip: '暂无提现记录！', // 提示
                        fixed: true,
                        top: "200rpx",
                    }
                }
            }
        },
        
        updated() {
            this.getHeight();
        },
        
        onShow() {
            this.getWithdrawInfoFunc()
        },
        methods: {
            async getHeight() {
            	const content = await getRect('.content')
            	this.height = content.height
            },
            
            async getWithdrawInfoFunc() {
                const res = await apiGetWithdrawInfo();
                this.withdrawInfo = res;
            },
            
            upCallback(page) {
                const pageNum = page.num
                const pageSize = page.size
                
                console.log(page)
            
                apiWithdrawLog({
                    page_no: pageNum,
                    page_size: pageSize,
                }).then(({
                    lists,
                    count,
                }) => {
                    // 如果是第一页或是搜索时需手动置空列表
                    if (pageNum == 1||this.keyword) this.lists = []
                    // 重置列表数据
                    this.lists = [...this.lists, ...lists]
                    this.mescroll.endBySize(10, count)
                }).catch((err) => {
                    this.mescroll.endErr()
                })
            },
        }
        
    }
</script>

<style lang="scss">
    .wallet {
        .header {
            margin: 20rpx 30rpx;
            padding: 50rpx 40rpx;
            border-radius: 14rpx;
            background-color: $-color-primary;
            &-price {
                font-size: 60rpx;
            }
            &-btn {
                width: 182rpx;
                height: 72rpx;
                color: $-color-primary;
            }
        }
        
        .section {
            .title {
                view {
                    width: 8rpx;
                    height: 30rpx;
                    background-color: $-color-primary;
                }
                padding-bottom: 30rpx;
                border-bottom: $-solid-border;
            }
            .content {
                padding: 0 30rpx;
                height: calc(100vh - 380rpx - env(safe-area-inset-bottom));
                
                .wallet-cell {
                	padding: 16rpx 30rpx;
                	border-bottom: $-solid-border;
                }
            }
        }
    }
</style>
