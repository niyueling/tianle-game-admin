<template>
    <div class="decorate-layout" @click="showPreview = false">
        <el-container>
            <el-header :height="styleConfig.headerHeight" class="flex bg-primary white">
                <div class="page-info">
                    <ls-dialog
                        class="inline"
                        content="确定离开此页面？系统可能不会保存您所做的更改。"
                        @confirm="$router.back(-1)"
                    >
                        <el-button slot="trigger" type="text">
                            <span class="nr">
                                <i class="el-icon-arrow-left"></i>
                                <span>返回</span>
                            </span>
                        </el-button>
                    </ls-dialog>
                </div>
                <div class="m-l-10">
                    ｜ 当前页面：{{ pagesInfo.name }}
                    <popover-input :value="pagesInfo.name" type="text" @confirm="setPagesInfoName">
                        <el-button class="ls-edit" type="text" icon="el-icon-edit"></el-button>
                    </popover-input>
                </div>
                <div class="flex-1"></div>
                <!-- 只有首页才可以选模板 -->
                <ls-dialog
                    v-if="$route.path == '/decorate/index'"
                    content="切换模板后，当前页面内容将被替换且不被保存，请谨慎操作"
                    @confirm="handleConfirm"
                >
                    <el-button size="small" type="text" slot="trigger"> 切换模版 </el-button>
                </ls-dialog>

                <!-- <el-button
                    type="text"
                    size="small"
                >
                    新手指引
                    <i class="el-icon-question"></i>
                </el-button> -->
                <el-popover v-if="showPreviewBtn" placement="bottom" width="150" trigger="manual" v-model="showPreview">
                    <el-button
                        slot="reference"
                        type="primary"
                        plain
                        class="preview-btn"
                        size="small"
                        icon="el-icon-view"
                        @click="handlePreview"
                    >
                        <span class="white">预览</span>
                    </el-button>
                    <div class="text-center">
                        <vue-qr :text="prevUrl" :size="120"></vue-qr>
                        <div>手机扫描二维码预览</div>
                    </div>
                </el-popover>

                <el-button size="small" @click="handleSave(false)">
                    <span class="primary">保存</span>
                </el-button>
            </el-header>
            <el-main>
                <perm />
            </el-main>
            <template-select ref="templateSelect" @select="handleSelect" />
        </el-container>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import PopoverInput from '@/components/popover-input.vue'
import LsDialog from '@/components/ls-dialog.vue'
import TemplateSelect from '@/components/shop/template-select/index.vue'
import VueQr from 'vue-qr'
import Perm from '@/components/layout/perm.vue'

@Component({
    components: {
        PopoverInput,
        VueQr,
        LsDialog,
        TemplateSelect,
        Perm
    }
})
export default class DecorateLayout extends Vue {
    $refs!: { templateSelect: any }
    showPreview = false

    prevUrl = ''
    /** S computed **/

    get pagesInfo() {
        const { pagesInfo } = this.$store.state.decorate
        return pagesInfo
    }

    set pagesInfo(val) {
        this.$store.commit('setPagesInfo', val)
    }

    get client() {
        return this.$store.state.decorate.client
    }

    get showPreviewBtn() {
        // 商品详情和购物车不支持预览
        const routeArr = ['/decorate/cart', '/decorate/goods_detail']
        return !routeArr.includes(this.$route.path) && this.client == 'mobile'
    }
    /** E computed **/

    /** S methods **/
    handleConfirm() {
        this.$refs.templateSelect.onTrigger()
    }
    handleSelect(data: any) {
        data.common = JSON.parse(data.common)
        data.content = JSON.parse(data.content)
        this.pagesInfo = data
        this.$store.commit('setPagesData', data.content)
    }
    setPagesInfoName(val: string) {
        this.pagesInfo.name = val
    }
    handleSave(preview = false) {
        const loading = this.$loading({
            lock: true,
            text: '保存中...',
            spinner: 'el-icon-loading'
        })
        return this.$store
            .dispatch('addPages', { preview })
            .then(res => {
                return Promise.resolve(res)
            })
            .catch(res => {
                return Promise.reject(res)
            })
            .finally(() => {
                loading.close()
            })
    }
    handlePreview() {
        if (this.showPreview) {
            this.showPreview = false
            return
        }
        this.handleSave(true).then(res => {
            this.prevUrl = res.url
            this.showPreview = true
        })
    }
    /** E methods **/

    /** S life cycle **/
    created() {
        // 初始化数据
        this.$store.commit('setPagesInfo', { common: {} })
        this.$store.commit('setPagesData', [])
        this.$store.commit('setSelectIndex', -1)
        this.$store.commit('setClient', 'mobile')
    }
    /** E life cycle **/
}
</script>

<style scoped lang="scss">
.decorate-layout {
    .el-button {
        color: #fff;
        margin-left: 10px;
        margin-right: 10px;
    }
    .preview-btn {
        border-color: #ffffff !important;
        background-color: transparent;
    }
}
</style>
