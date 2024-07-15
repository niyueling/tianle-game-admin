<template>
    <div class="ls-editor">
        <div :id="identify" class="editor" :style="[editStyle]"></div>
        <!--        <material-select ref="materialSelect" :limit="null" :hidden-trigger="true" @change="handeleChange" />-->
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator'
import Editor from 'wangeditor'
import MaterialSelect from '@/components/material-select/index.vue'
import { getRandomString } from '@/utils/util'
import config from '@/config'
@Component({
    components: {
        MaterialSelect
    }
})
export default class LsEditor extends Vue {
    $refs!: { materialSelect: any }

    /** S Props **/
    @Prop() value: any // 编辑器内容
    // 编辑器菜单
    @Prop({
        default: () => [
            'head',
            'bold',
            'fontSize',
            'fontName',
            'italic',
            'underline',
            'strikeThrough',
            'indent',
            'lineHeight',
            'foreColor',
            'link',
            'list',
            'justify',
            'quote',
            'emoticon',
            'image',
            'video',
            'undo',
            'redo'
        ]
    })
    menu!: string[]
    @Prop({ default: 600 }) height!: number // 编辑器高度
    @Prop() width!: number
    /** E Props **/

    /** S Data **/
    firstData = true
    editor!: Editor
    identify = ''
    /** E Data **/

    // 编辑器宽度
    get editStyle() {
        return this.width ? { width: `${this.width}px` } : {}
    }

    // 监听编辑器内容
    @Watch('value')
    valueChange(val: string) {
        // 加载数据时渲染
        if (this.firstData) {
            this.firstData = false
            this.editor.txt.html(val)
        }
    }

    handeleChange(val: any[]) {
        val.forEach(val => {
            this.editor.cmd.do('insertHTML', `<img src="${val}" style="max-width:100%;"/>`)
        })
    }

    /** S Life Cycle **/
    created() {
        this.identify = 'editor' + '-' + getRandomString(3)
    }

    mounted() {
        this.editor = new Editor(`#${this.identify}`)

        this.editor.config.height = this.height
        this.editor.config.menus = this.menu
        this.editor.config.menuTooltipPosition = 'down'
        this.editor.config.showFullScreen = false
        this.editor.config.showLinkImg = false
        this.editor.config.uploadImgShowBase64 = true
        this.editor.config.zIndex = 1

        this.editor.config.uploadImgFromMedia = () => {
            this.$refs.materialSelect.showDialog()
        }
        this.editor.config.onchange = (newHtml: string) => {
            this.$emit('input', newHtml)
        }
        this.editor.config.uploadVideoServer = `${config.baseURL}/adminapi/upload/video`
        this.editor.config.uploadVideoHeaders = {
            token: this.$store.getters.token,
            version: config.version
        }
        this.editor.config.uploadVideoName = 'file'
        this.editor.config.uploadVideoHooks = {
            // 视频上传并返回了结果，但视频插入时出错了
            fail: (xhr, editor, resData) => {
                this.$message.error('上传视频失败')
            },
            // 上传视频超时
            timeout: xhr => {
                this.$message.error('上传视频超时')
            },
            customInsert: (insertVideoFn: any, result: any) => {
                if (result.code == 1) {
                    insertVideoFn(result.data.uri)
                } else {
                    this.$message.error(result.msg)
                }
            }
        }
        this.editor.create()
    }

    beforeDestroy() {
        this.editor.destroy()
    }
    /** E Life Cycle **/
}
</script>

<style scoped lang="scss">
.editor {
    min-width: 375px;
    ::v-deep img {
        vertical-align: top;
        display: inline;
        max-width: 100%;
    }
}
</style>
