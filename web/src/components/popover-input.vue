<template>
    <el-popover placement="top" v-model="visible" :width="width" trigger="manual" class="popover-input">
        <div class="flex">
            <div class="popover-input__input m-r-10 flex-1">
                <el-input v-model="inputValue" :type="type" size="mini" :placeholder="tips"></el-input>
            </div>
            <div class="popover-input__btns flex-none">
                <el-button type="text" size="mini" @click="visible = false">取消</el-button>
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
    @Prop() value!: string
    @Prop({ default: 'number' }) type!: 'text' | 'number'
    @Prop({ default: '250' }) width!: number | string
    @Prop({ default: false }) disabled!: boolean
    @Prop() tips!: string
    visible = false
    inputValue = ''
    @Watch('value', { immediate: true })
    valueChange(val: string) {
        this.inputValue = val
    }
    handleConfirm() {
        this.close()
        this.$emit('confirm', this.inputValue)
    }
    handleOpen() {
        if (this.disabled) {
            return
        }
        this.visible = true
    }
    close() {
        this.visible = false
    }
}
</script>

<style scoped lang="scss"></style>
