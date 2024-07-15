<template>
    <div id="app">
        <router-view />
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { Action } from 'vuex-class'
@Component
export default class App extends Vue {
    @Action('getConfig') getConfig!: () => void
    async created() {
        const data: any = await this.getConfig()
        let favicon: any = document.querySelector('link[rel="icon"]')!
        if (favicon) {
            favicon.href = data.favicon
            return
        }
        favicon = document.createElement('link')
        favicon.rel = 'icon'
        favicon.href = data.favicon
        document.head.appendChild(favicon)
    }
}
</script>

<style lang="scss">
@import './assets/fonts/iconfont.css';
@import './styles/index.scss';
#app {
    min-width: 1180px;
}
</style>
