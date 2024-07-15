<!-- 用户详情 -->
<template>
    <div class="user-details">
        <!-- 导航头部 -->
        <div class="ls-card">
            <el-page-header @back="$router.go(-1)" content="对局规则" />
        </div>

        <div class="m-t-16 flex col-top">
            <!-- 基本资料 -->
            <div class="ls-card card-height">
                <div class="card-title">规则</div>
                <div class="content m-t-16 m-b-16">
                    <pre style="margin-left: 50px; font-size: 16px" v-html="codedatatwo"></pre>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
@Component({
    components: {}
})
export default class UserDetails extends Vue {
    codedatatwo = ''

    syntaxHighlight(json: any) {
        if (typeof json != 'string') {
            json = JSON.stringify(json, undefined, 2)
        }
        json = json.replace(/&/g, '&').replace(/</g, '<').replace(/>/g, '>')
        return json.replace(
            /("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g,
            function (match: any) {
                let cls = 'number'
                if (/^"/.test(match)) {
                    if (/:$/.test(match)) {
                        cls = 'key'
                    } else {
                        cls = 'string'
                    }
                } else if (/true|false/.test(match)) {
                    cls = 'boolean'
                } else if (/null/.test(match)) {
                    cls = 'null'
                }
                return '<span class="' + cls + '">' + match + '</span>'
            }
        )
    }

    created() {
        let json: any = localStorage.getItem('ruleJson')
        this.codedatatwo = this.syntaxHighlight(JSON.parse(json))
    }
}
</script>

<style lang="scss" scoped>
.content {
    background: #f5f5f5;
    padding: 11px 0;
    border-bottom: 1px dotted darkgray;
}

.item {
    margin-top: 18px;
}

.avatar {
    border-radius: 29px;
}

.userinfo {
    margin-top: 8px;
    margin-left: 15px;
    width: 100%;
}

.action-text-span {
    font-size: 2em;
    color: red;
    display: block;
}

.action-text {
    height: 100%;
    width: 80px;
    padding: 16px;
    margin: 0px;
}

.card-item {
    width: 810px;
    position: relative;
    display: inline-block;
    height: 80px;
}

.action-card-image {
    width: 180px;
    position: relative;
    display: inline-block;
    height: 80px;
}

.card-image {
    display: block;
    left: 0px;
    margin: 0px;
    padding: 0px;
    position: absolute;
    width: 60px;
}

.base {
    margin-left: 15px;
}

.ls-card {
    .card-title {
        font-size: 14px;
        font-weight: 500;
        padding-bottom: 20px;
        border-bottom: 1px solid $--background-color-base;
    }
}

.card-height {
    // height: 600px;
    //box-sizing: border-box;
}

.avatar {
    -webkit-text-size-adjust: 100%;
    -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
    --antd-wave-shadow-color: #1890ff;
    font-variant: tabular-nums;
    line-height: 1.5;
    font-feature-settings: 'tnum';
    font-family: lucida grande, lucida sans unicode, lucida, helvetica, Hiragino Sans GB, Microsoft YaHei,
        WenQuanYi Micro Hei, sans-serif;
    text-align: left;
    box-sizing: inherit;
    vertical-align: middle;
    border-style: none;
    color: rgb(255, 255, 255);
    background-color: rgb(188, 188, 188);
    user-select: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 60px;
    border-radius: 50%;
    height: 120px;
    width: 120px;
}

.user-details {
    min-height: calc(100vh - #{$--header-height} - 92px);
    margin-bottom: 60px;

    &__header {
        flex: none;
    }
}
</style>
