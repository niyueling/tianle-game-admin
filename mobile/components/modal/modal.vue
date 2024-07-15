<template>
    <view>
        <u-popup v-model="inputValue" :zIndex="999999" mode="center">
            <view class="bg-white popup">
                <view class="title">{{title}}</view>
                <view class="content line-3" :style="{'height': height}">
                    <slot></slot>
                </view>
                <view class="footer flex row-around">
                    <button v-if="cancelShow" class="btn hollow flex row-center normal" @click="cancel">{{cancelText}}</button>
                    <button :style="{'width': cancelShow==false?'100%':'220rpx'}" class="btn solid flex row-center normal" @click="confirm">{{confirmText}}</button>
                </view>
            </view>
        </u-popup>
    </view>
</template>

<script>
    export default {
        props: {
            value: {
                type: Boolean,
                default: false
            },
            cancelShow: {
                type: Boolean,
                default: true
            },
            title: {
                type: String,
                default: '提示'
            },
            height: {
                type: String,
                default: '310rpx'
            },
            confirmText: {
                type: String,
                default: '确认'
            },
            cancelText: {
                type: String,
                default: '取消'
            },
        },
        
        watch: {
            value(val1, val2) {
                // 只有value的改变是来自外部的时候，才去同步inputVal的值，否则会造成循环错误
                if (!this.changeFromInner) {
                    if (this.inputValue == val1) return
                    this.inputValue = val1;
                    // 进行this.$nextTick延时
                    this.$nextTick(function() {
                        this.changeFromInner = false;
                    })
                }
            },
            inputValue(val1, v2) {
                if(this.isFistVal) return
                this.changeFromInner = true;
                // 一定时间内，清除changeFromInner标记，否则内部值改变后
                // 外部通过程序修改value值，将会无效
                this.innerChangeTimer = setTimeout(() => {
                    this.changeFromInner = false;
                }, 150);
                this.$emit('input', val1)
            }
        },

        computed: {
            // 弹窗Popup显示状态
            show: {
                get: function() {
                    return this.value
                },
                set: function(value) {
                    this.$emit('input', value)
                },
            }
        },

        data() {
            return {
                inputValue: false,
                changeFromInner: false, // 值发生变化，是来自内部还是外部
                isFistVal: true
            }
        },
        
        created() {
            this.inputValue = this.value;
            this.$nextTick(function() {
                this.isFistVal = false
            })
        },

        methods: {
            onTrigger() {
            },

            cancel() {
                this.inputValue = false;
                this.$emit('cancel')
            },

            confirm() {
                this.inputValue = false;
                this.$emit('confirm')
            }
        }
    }
</script>

<style lang="scss" scoped>
    .popup {
        width: 600rpx;
        border-radius: 12rpx;

        .title {
            padding: 30rpx 0;
            font-size: $-font-size-md;
            text-align: center;
            color: #101010;
            font-weight: 500;
        }

        .content {
            width: 600rpx;
            word-wrap:break-word;
            padding: 0 40rpx;
        }

        .footer {
            padding: 24rpx;
            .btn {
                width: 220rpx;
                height: 68rpx;
                border-radius: 68rpx;
                font-size: $-font-size-sm;
            }

            .solid {
                color: $-color-white;
                background-color: $-color-primary;
            }

            .hollow {
                color: $-color-lighter;
                border: 1px solid #DBDBDB;
            }
        }
    }
</style>
