<template>
    <div class="file-item" :style="{ height: `${size}px`, width: `${size}px` }">
        <el-image class="image" v-if="type == 'image'" fit="contain" :src="item.uri"></el-image>
        <video class="video" v-else-if="type == 'video'" :src="item.uri"></video>
        <slot></slot>
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator'
@Component({})
export default class FileItem extends Vue {
    @Prop({ default: () => {} }) item!: any
    @Prop({ default: '100' }) size!: string
    @Prop({ default: 'image' }) type!: string
}
</script>

<style scoped lang="scss">
.file-item {
    background-color: $--border-color-light;
    border: 1px solid $--border-color-light;
    box-sizing: border-box;
    position: relative;
    .image,
    .video {
        width: 100%;
        height: 100%;
    }
}
</style>
