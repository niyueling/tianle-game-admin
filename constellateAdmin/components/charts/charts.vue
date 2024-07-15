<template>
    <view class="charts-box">
          <qiun-data-charts
            type="area"
            :chartData="chartData"
            background="none"
          />
    </view>
</template>

<script>
    export default {
        props: {
            ids: {
                type: String,
            },
            width: {
                type: String,
                default: '100%'
            },
            height: {
                type: String,
                default: '300rpx'
            },
            chartData: {
                type: Object
            }
        },

        data() {
            return {
                
            }
        },
    }
</script>

<style class="scss">
    .charts-box {
        width: 676rpx;
        height: 544rpx;
    }
</style>
