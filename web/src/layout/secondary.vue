<template>
    <el-scrollbar class="ls-scroll ls-scrollbar">
        <router-view />
    </el-scrollbar>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
@Component
export default class Secondary extends Vue {}
</script>

<style scoped lang="scss">
.ls-scroll {
    height: 100vh;
}
</style>
