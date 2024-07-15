<template>
    <view class="page-body">
        <web-view :src="url"></web-view>
    </view>
</template>

<script>
export default {
    data() {
        return {
            url: 'https://www.baidu.com'
        }
    },
    onLoad: function (options) {
        // this.url = this.$Route.query.url
        // console.log(this.url)
    },
    methods: {}
}
</script>
<style></style>
