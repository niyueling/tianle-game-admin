<template>
    <view class="switch" @tap="onClick" :style="[switchStyle]">

        <!-- 背景 -->
        <view class="switch--bg flex br60 row-around">
            <!-- 文字 -->
            <view class="switch--text br60 sm flex row-left p-l-20">
                <view :class="value == 1 ? 'ani2':'ani1'" class="primary">{{firstText}}</view>
                <view :class="value == 0 ? 'ani2':'ani1'" class="lighter">{{lastText}}</view>
            </view>

            <!-- 滑块 -->
            <view class="switch--slider br60" :class="value == 1 ? 'open':'close'"></view>
        </view>

    </view>
</template>

<script>
    /**
     * switch 开关选择器
     */
    export default {
        name: "u-switch",
        props: {
            // 是否为加载中状态
            loading: {
                type: Boolean,
                default: false
            },
            firstText: {
                type: [Number, String],
                default: '营业中'
            },
            lastText: {
                type: [Number, String],
                default: '暂停营业'
            },
            // 是否为禁用装填
            disabled: {
                type: Boolean,
                default: false
            },
            // 打开时的背景颜色
            activeColor: {
                type: String,
                default: '#f2f2f2'
            },
            // 关闭时的背景颜色
            inactiveColor: {
                type: String,
                default: '#ffffff'
            },
            // 通过v-model双向绑定的值
            value: {
                type: [Number, String],
                default: 1
            },
            // 是否使手机发生短促震动，目前只在iOS的微信小程序有效(2020-05-06)
            vibrateShort: {
                type: Boolean,
                default: true
            },
            // 打开选择器时的值
            activeValue: {
                type: [Number, String, Boolean],
                default: true
            },
            // 关闭选择器时的值
            inactiveValue: {
                type: [Number, String, Boolean],
                default: false
            },
        },
        data() {
            return {

            }
        },
        computed: {
            switchStyle() {
                let style = {};
                style.fontSize = this.size + 'rpx';
                style.backgroundColor = this.value ? this.activeColor : this.inactiveColor;
                return style;
            },
            loadingColor() {
                return this.value ? this.activeColor : null;
            }
        },
        methods: {
            onClick() {
                if (!this.disabled && !this.loading) {
                    // 使手机产生短促震动，微信小程序有效，APP(HX 2.6.8)和H5无效
                    if (this.vibrateShort) uni.vibrateShort();
                    this.$emit('input', this.value == 1 ? 0 : 1);
                    // 放到下一个生命周期，因为双向绑定的value修改父组件状态需要时间，且是异步的
                    this.$nextTick(() => {
                        this.$emit('change', {
                            value: this.value == 1 ? this.activeValue : this.inactiveValue,
                            text: this.value
                        });
                    })
                }
            }
        }
    };
</script>

<style lang="scss" scoped>
    .switch {
        width: 150rpx;
        height: 40rpx;
        border-radius: 60rpx;
        position: relative;
        border-radius: 40rpx;

        &--slider {
            width: 24rpx;
            height: 24rpx;
            border-radius: 50%;
            right: 12rpx;
            top: 50%;
            z-index: 101;
            transform: translateY(-50%);
            transition: .2s linear;
            position: absolute;
        }

        .open {
            background-color: #01D739;
        }

        .close {
            background-color: #999;
        }

        .ani1 {
            transition: all .3s;
            position: absolute;
            left: -20rpx;
            opacity: 0;
        }

        .ani2 {
            transition: all .5s;
            position: absolute;
            left: 20rpx;
            opacity: 1;
        }

        &--bg {
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: 100;
            position: relative;
            position: absolute;
            background-color: $-color-white;
        }

        &--text {
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: 1002;
            font-size: 22rpx;
            position: relative;
            position: absolute;

            .primary {
                color: $-color-primary;
            }
        }
    }
</style>
