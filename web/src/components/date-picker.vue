<template>
    <el-date-picker
        v-model="pickerValue"
        :type="type"
        :picker-options="pickerOptions"
        range-separator="至"
        start-placeholder="开始时间"
        end-placeholder="结束时间"
        align="right"
        value-format="yyyy-MM-dd HH:mm:ss"
        @change="changeDate"
        :disabled="disabled"
    />
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator'

@Component
export default class DatePicker extends Vue {
    @Prop() startTime!: string // 日期时间点
    @Prop() endTime!: string // 日期时间点
    @Prop({ default: 'datetimerange' }) type!: string //选择器类型
    @Prop({ default: false }) disabled!: boolean //是否禁用
    // 日期选择器绑定值
    pickerValue: Array<Date | string> = []

    // 日期选择器配置
    pickerOptions: any = {
        shortcuts: [
            {
                text: '最近一周',
                onClick(picker: any) {
                    const end = new Date()
                    const start = new Date()
                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 7)
                    picker.$emit('pick', [start, end])
                }
            },
            {
                text: '最近一个月',
                onClick(picker: any) {
                    const end = new Date()
                    const start = new Date()
                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 30)
                    picker.$emit('pick', [start, end])
                }
            },
            {
                text: '最近三个月',
                onClick(picker: any) {
                    const end = new Date()
                    const start = new Date()
                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 90)
                    picker.$emit('pick', [start, end])
                }
            }
        ]
    }

    // 更改时间段
    changeDate() {
        const dateArray = this.pickerValue ? this.pickerValue : (this.pickerValue = ['', ''])

        this.$emit('update:start-time', dateArray[0])
        this.$emit('update:end-time', dateArray[1])
    }

    @Watch('startTime', { immediate: true })
    handleStartTime(value: string) {
        !this.pickerValue && (this.pickerValue = [])
        this.$set(this.pickerValue, 0, value)
    }

    @Watch('endTime', { immediate: true })
    handleEndTime(value: string) {
        !this.pickerValue && (this.pickerValue = [])
        this.$set(this.pickerValue, 1, value)
    }
}
</script>
