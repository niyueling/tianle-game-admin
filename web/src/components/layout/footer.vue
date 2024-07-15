<template>
    <div class="ls-footer">
        <div class="flex-col col-center">
            <!-- <div class="flex lg">
                <a class="link m-r-30" :href="config.likeshop_official_website" target="_blank">官网</a>
                <a class="link m-r-30" :href="config.likeshop_community" target="_blank">社区</a>
                <a class="link" :href="config.likeshop_document" target="_blank">文档</a>
            </div> -->
            <div class="muted xs m-t-20">
                <span class="m-r-10">{{ config.copyright }}</span>
                <a class="link muted" :href="config.record_system_link" target="_blank"
                    >备案号：{{ config.record_number }}</a
                >
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator'
import { Getter } from 'vuex-class'

@Component
export default class Footer extends Vue {
    @Getter('config') config: any
}
</script>

<style scoped lang="scss">
.ls-footer {
    padding: 20px 0;

    .link,
    .link:link,
    .link:hover,
    .link:active,
    .link:visited {
        text-decoration: none;
        color: unset;
    }
}
</style>
