<template>
    <ls-dialog
        class="material-select"
        :title="`选择${tipsText}`"
        width="1050px"
        top="15vh"
        ref="materialDialog"
        @confirm="handleConfirm"
    >
        <div v-if="!hiddenTrigger" class="material-select__trigger clearfix" slot="trigger" @click.stop="">
            <draggable
                class="ls-draggable"
                v-model="fileList"
                animation="300"
                :disabled="disabled || dragDisabled"
                @update="handleChange"
            >
                <div
                    v-for="(item, index) in fileList"
                    :key="item + index"
                    class="material-preview ls-del-wrap"
                    :class="{ 'is-disabled': disabled, 'is-one': limit == 1 }"
                    @click="showDialog(false, index)"
                >
                    <div v-if="$scopedSlots.preview">
                        <slot name="preview" :item="imageUri(item)"></slot>
                    </div>
                    <file-item v-else :type="type" :item="{ uri: imageUri(item) }" :size="size"> </file-item>
                    <i v-if="enableDelete" class="el-icon-close ls-icon-del" @click.stop="delImage(index)"></i>
                </div>
            </draggable>
            <div
                class="material-upload"
                @click="showDialog(true)"
                v-show="showUpload"
                :class="{ 'is-disabled': disabled, 'is-one': limit == 1 }"
            >
                <div v-if="$slots.upload">
                    <slot name="upload"></slot>
                </div>
                <div
                    v-else
                    class="upload-btn flex row-center"
                    :style="{
                        width: `${size}px`,
                        height: `${size}px`,
                        background: uploadBg
                    }"
                >
                    <slot></slot>
                    <span v-if="!$slots.default">添加{{ tipsText }}</span>
                </div>
            </div>
        </div>
        <div class="material-wrap">
            <ls-material ref="material" :page-size="15" :type="type" :limit="meterialLimit" @change="selectChange" />
        </div>
    </ls-dialog>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator'
import LsDialog from '@/components/ls-dialog.vue'
import LsMaterial from './material.vue'
import FileItem from './file-item.vue'
import Draggable from 'vuedraggable'

@Component({
    components: {
        LsDialog,
        LsMaterial,
        Draggable,
        FileItem
    }
})
export default class MediaSelect extends Vue {
    $refs!: { materialDialog: any; material: any }
    select!: any[]
    currentIndex!: number
    // 双向数据绑定的值
    @Prop({ default: () => [] }) value!: [] | string
    // 图片张数限制
    @Prop({ default: 1 }) limit!: number
    @Prop({ default: '100' }) size!: string
    @Prop({ default: false }) disabled!: boolean //禁用图片选择
    @Prop({ default: false }) dragDisabled!: boolean //禁用图片拖拽
    @Prop({ default: false }) hiddenTrigger!: boolean //是否隐藏
    @Prop({ default: 'image' }) type!: 'image' | 'video' //是否隐藏
    @Prop({ default: 'transparent' }) uploadBg!: string
    @Prop({ default: true }) enableDomain!: boolean
    @Prop({ default: true }) enableDelete!: boolean
    isAdd = true
    fileList: any[] = []

    get showUpload() {
        const { fileList, limit } = this
        return limit - fileList.length > 0
    }

    get meterialLimit() {
        if (!this.isAdd) {
            return 1
        }
        if (!this.limit) {
            return null
        }
        return this.limit - this.fileList.length
    }

    get tipsText() {
        switch (this.type) {
            case 'image':
                return '图片'
            case 'video':
                return '视频'
        }
    }
    get imageUri() {
        return (item: string) => {
            return this.enableDomain ? item : this.$getImageUri(item)
        }
    }
    @Watch('value', { immediate: true })
    valueChange(val: any[] | string) {
        this.fileList = Array.isArray(val) ? val : val == '' ? [] : [val]
    }
    showDialog(isAdd = true, index: number) {
        if (this.disabled) {
            return
        }
        this.isAdd = isAdd
        if (index !== undefined) {
            this.currentIndex = index
        }
        this.$refs.materialDialog?.onTrigger()
    }
    selectChange(val: any[]) {
        this.select = val
    }
    handleConfirm() {
        this.$refs.material.clearSelectList()
        const selectUri = this.select.map(item => (this.enableDomain ? item.uri : item.url))
        if (!this.isAdd) {
            this.fileList.splice(this.currentIndex, 1, selectUri.shift())
        } else {
            this.fileList = this.fileList.concat(selectUri)
        }
        this.handleChange()
    }

    delImage(index: number) {
        this.fileList.splice(index, 1)
        this.handleChange()
    }
    handleChange() {
        const valueImg = this.limit != 1 ? this.fileList : this.fileList[0] || ''
        this.$emit('input', valueImg)
        this.$emit('change', valueImg)
        this.fileList = []
    }
}
</script>

<style scoped lang="scss">
.material-select {
  ::v-deep.el-dialog__body {
        padding: 0;
    }
    .material-upload,
    .material-preview {
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        color: $--color-text-secondary;
        margin-right: 8px;
        margin-bottom: 8px;
        box-sizing: border-box;
        float: left;
        &.is-disabled {
            cursor: not-allowed;
        }
        &.is-one {
            margin-bottom: 0;
        }
    }
    .material-upload {
        .upload-btn {
            box-sizing: border-box;
            border: 1px dashed $--border-color-base;
        }
    }
    .material-preview {
        &:hover {
            .ls-icon-del {
                display: block;
            }
        }
    }
}
.material-wrap {
    height: 530px;
    border-top: 1px solid $--border-color-base;
    border-bottom: 1px solid $--border-color-base;
}
</style>
