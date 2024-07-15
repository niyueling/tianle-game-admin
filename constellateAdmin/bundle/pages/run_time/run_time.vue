<template>
    <view>
        <view class="run-time bg-white m-t-20">
            <view class="black nr m-b-20">工作日: </view>
            <view class="days flex row-between">
                <block v-for="(item, index) in weekdaysText" :key="index">
                    <template>
                        <view @click="selectDayFunc(index)" class="item" :class="weekdays[index] == null ? 'not-select' : 'select'">{{item}}</view>
                    </template>
                </block>
            </view>
            
            <view class="black nr m-t-40 m-b-20">营业时间: </view>
            
            <view class="time flex row-between">
                <view class="flex row-between" @click="openTimePickerFunc(1)">
                    {{start[0]}}:{{start[1]}}
                    <u-icon name="arrow-down" size="20"></u-icon>
                </view>                
                -
                <view class="flex row-between" @click="openTimePickerFunc(2)">
                    {{end[0]}}:{{end[1]}}
                    <u-icon name="arrow-down" size="20"></u-icon>
                </view>
            </view>
        </view>

        <button class="my-btn white md flex br60 row-center" @click="onSubmit">完成</button>
        
        <!-- 时间选择组件 -->
        <u-picker v-model="show" @confirm="confirm" mode="time" :params="params"></u-picker>
        
        <u-toast ref="uToast" />
    </view>
</template>

<script>
    import {
        apiSetShopInfo
    } from '@/api/store'
    export default {
        data() {
            return {
                weekdays: [],
                weekdaysText: ['日',"一","二",'三','四','五','六'],
                
                type: 1, //1 = 开始时间 2=结束时间
                show: false,
                params: {
                	year: false,
                	month: false,
                	day: false,
                	hour: true,
                	minute: true,
                	second: false,
                	timestamp: true, // 1.3.7版本提供
                },
                start: ['00','00'],//时  分
                end: ['00','00'],//时  分
                startTimestamp: '',//开始时间戳
                endTimestamp: ''//结束时间戳
            }
        },
        onLoad() {
            // 工作日计算
            let arr = [null, null, null, null, null, null, null];
            let weekdays = this.shopInfo.weekdays;
            let weekdaysText = [0, 1, 2, 3, 4, 5, 6]
            for (let i = 0; i < weekdaysText.length; i++) {
                for (let j = 0; j < weekdays.length; j++) {
                    if (weekdaysText[i] == weekdays[j]) {
                        arr[i] = weekdays[j];
                    }
                }
            }
            this.weekdays = arr;
            
            // 营业时间计算
            let start = this.shopInfo.run_start_time;
            let end = this.shopInfo.run_end_time;
            this.start = start.split(':');
            this.end = end.split(':');
            this.startTimestamp = ((start.split(':')[0])*60*60) + ((start.split(':')[1])*60) + (Number(new Date(new Date().toLocaleDateString()).getTime())/1000)
            this.endTimestamp = ((end.split(':')[0])*60*60) + ((end.split(':')[1])*60) + (Number(new Date(new Date().toLocaleDateString()).getTime())/1000)
        },
        methods: {
            // 选择营业工作日
            selectDayFunc(index) {
                let weekdaysText = [0, 1, 2, 3, 4, 5, 6]
                weekdaysText.forEach(item => {
                    if(item == index) {
                        this.weekdays[index] = this.weekdays[index] == null ? item : null
                    }
                }) 
                this.weekdaysText = [...this.weekdaysText]
            },
            
            openTimePickerFunc(type) {
                this.type = type;
                this.show = true;
            },
            
            confirm(time) {
                if(this.type == 1) {
                    this.start = [time.hour, time.minute]
                    this.startTimestamp = time.timestamp;
                } else {
                    this.end = [time.hour, time.minute]
                    this.endTimestamp = time.timestamp;
                }
            },
            
            async onSubmit() {
                const params = {
                    run_start_time:  Number(this.startTimestamp),
                    run_end_time: Number(this.endTimestamp),
                    weekdays: this.weekdays.filter(item => typeof item == 'string' || typeof item == 'number')
                }
                
                await apiSetShopInfo({...params})
                this.$refs.uToast.show({
                    title: '设置成功',
                    type: 'success'
                })
                setTimeout(() => {
                    this.$Router.back()
                }, 1000)
            }
        }
    }
</script>

<style lang="scss">
    .run-time {
        padding: 30rpx;

        .days {
            .item {
                width: 76rpx;
                height: 76rpx;
                border-radius: 50%;
                line-height: 76rpx;
                text-align: center;
                color: $-color-white;
                font-size: $-font-size-nr;
            }
            .select {
                background-color: $-color-primary;
            }
            .not-select {
                background-color: #EEEEEE;
            }
        }
        .time {
            padding-bottom: 20rpx;
            >view {
                width: 322rpx;
                height: 64rpx;
                padding: 20rpx;
                font-size: $-font-size-nr;
                border: 1rpx solid #dbdbdb;
            }
        }
    }

    .my-btn {
        height: 88rpx;
        margin: 30rpx 26rpx;
        margin-top: 40rpx;
        text-align: center;
        background-color: $-color-primary;
    }
</style>
