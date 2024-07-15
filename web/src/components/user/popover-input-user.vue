<!-- 单行修改弹出框 -->
<template>
    <el-popover placement="top" v-model="visible" :width="width" trigger="manual" class="popover-input" @hide="close">
        <div class="flex">
            <div class="popover-input__input m-r-10 flex-1">
                <!-- input输入框 -->
                <el-input
                    v-if="changeType == 'input'"
                    v-model="inputValue"
                    :type="type"
                    size="mini"
                    :placeholder="tips"
                ></el-input>
                <!-- select选择器-性别 -->
                <el-select
                    v-if="changeType == 'sex'"
                    class="ls-select"
                    v-model="inputValue"
                    placeholder="请选择性别"
                    size="mini"
                >
                    <el-option label="男" :value="1"></el-option>
                    <el-option label="女" :value="2"></el-option>
                </el-select>
                <!-- 时间选择器 -->
                <el-date-picker
                    v-if="changeType == 'time'"
                    v-model="inputValue"
                    align="right"
                    type="date"
                    placeholder="选择日期"
                    :picker-options="pickerOptions"
                >
                </el-date-picker>
            </div>
            <div class="popover-input__btns flex-none">
                <el-button type="text" size="mini" @click="close">取消</el-button>
                <el-button type="primary" size="mini" @click="handleConfirm">确定</el-button>
            </div>
        </div>
        <div class="inline" type="text" slot="reference" @click="handleOpen">
            <slot></slot>
        </div>
    </el-popover>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator'

@Component
export default class PopoverInput extends Vue {
    // props
    @Prop() changeType!: string // input-input输入框 sex-性别 time-生日/时间
    @Prop() value!: string
    @Prop({
        default: 'number'
    })
    type!: 'text' | 'number'
    @Prop({
        default: '250'
    })
    width!: number | string
    @Prop({
        default: false
    })
    disabled!: boolean
    @Prop() tips!: string

    visible = false
    inputValue = ''

    // 带快捷时间选择器数据
    pickerOptions: any = {
        disabledDate(time: any) {
            return time.getTime() > Date.now()
        },
        shortcuts: [
            {
                text: '今天',
                onClick(picker: any) {
                    picker.$emit('pick', new Date())
                }
            },
            {
                text: '昨天',
                onClick(picker: any) {
                    const date = new Date()
                    date.setTime(date.getTime() - 3600 * 1000 * 24)
                    picker.$emit('pick', date)
                }
            },
            {
                text: '一周前',
                onClick(picker: any) {
                    const date = new Date()
                    date.setTime(date.getTime() - 3600 * 1000 * 24 * 7)
                    picker.$emit('pick', date)
                }
            }
        ]
    }

    @Watch('value', {
        immediate: true
    })
    valueChange(val: string) {
        this.inputValue = val
    }

    // 确定事件
    handleConfirm() {
        this.visible = false
        if (this.changeType == 'time') {
            this.inputValue = String(new Date(this.inputValue).getTime() / 1000)
        }
        this.$emit('confirm', this.inputValue)
    }
    // 关闭事件
    close() {
        // 清空弹出框内容
        this.inputValue = ''

        this.visible = false
    }
    handleOpen() {
        if (this.disabled) {
            return
        }
        this.visible = true
    }
}
</script>

<style scoped lang="scss"></style>
