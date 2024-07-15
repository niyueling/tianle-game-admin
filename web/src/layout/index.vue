<template>
    <el-scrollbar class="ls-layout ls-scrollbar">
        <div class="ls-view">
            <perm />
        </div>
    </el-scrollbar>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import Perm from '@/components/layout/perm.vue'
@Component({
    components: {
        Perm
    }
})
export default class Layout extends Vue {}
</script>

<style scoped lang="scss">
.ls-layout {
    height: calc(100vh - #{$--header-height});
    .ls-view {
        padding: 16px;
    }

    .page-enter-active {
        transition: all 0.8s ease;
    }

    .page-leave-active {
        transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
    }

    .page-enter,
    .page-leave-to {
        transform: translateX(100px);
        opacity: 0;
    }
}
</style>
