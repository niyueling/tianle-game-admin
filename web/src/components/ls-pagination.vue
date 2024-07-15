<template>
    <div class="ls-pagination">
        <el-pagination
            :current-page="value.page"
            :page-sizes="pageSizes"
            :page-size="value.size"
            :layout="layout"
            :total="value.count"
            @current-change="pageChange"
            @size-change="sizeChange"
        />
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator'

@Component
export default class Pagination extends Vue {
    // props
    @Prop({ default: () => ({}) }) value: any //pager对象
    @Prop({ default: () => [10, 20, 30] }) pageSizes!: number[] //页数列表
    @Prop({ default: 'total, sizes, prev, pager, next, jumper' })
    layout!: string
    // 页码改变
    pageChange(page: number) {
        this.value.page = page
        this.$emit('change', this.value)
    }

    // 页数改变
    sizeChange(size: number) {
        this.value.size = size
        this.value.page = 1
        this.$emit('change', this.value)
    }
}
</script>

<style scoped lang="scss"></style>
