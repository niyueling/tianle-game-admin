<template>
    <div class="material flex col-stretch" v-loading="pager.loading">
        <div class="material__left">
            <el-scrollbar class="ls-scrollbar" style="height: calc(100% - 80px)">
                <div class="material-left__content p-t-16">
                    <el-tree
                        node-key="id"
                        :data="groupeLists"
                        empty-text
                        :highlight-current="true"
                        :expand-on-click-node="false"
                        icon-class="el-icon-arrow-right"
                        :current-node-key="currentId"
                        @node-click="currentChange"
                    >
                        <template slot-scope="{ data }">
                            <div class="flex flex-1">
                                <img
                                    style="width: 20px; height: 16px"
                                    src="@/assets/images/icon_folder.png"
                                    alt
                                    class="m-r-10"
                                />
                                <span class="flex-1 line-1 m-r-10" style="max-width: 120px">{{ data.name }}</span>
                                <el-dropdown v-if="data.id > 0">
                                    <span class="muted m-r-10">···</span>
                                    <el-dropdown-menu slot="dropdown">
                                        <div>
                                            <popover-input
                                                type="text"
                                                tips="分类名称"
                                                @confirm="editGroupe($event, data.id)"
                                            >
                                                <el-dropdown-item>命名分组</el-dropdown-item>
                                            </popover-input>
                                        </div>
                                        <div @click="delGroupe(data.id)">
                                            <el-dropdown-item>删除分组</el-dropdown-item>
                                        </div>
                                    </el-dropdown-menu>
                                </el-dropdown>
                            </div>
                        </template>
                    </el-tree>
                </div>
            </el-scrollbar>
            <div class="flex row-center p-16">
                <popover-input tips="分类名称" type="text" @confirm="addGroupe">
                    <el-button size="small">添加分组</el-button>
                </popover-input>
            </div>
        </div>
        <div class="material__center flex-1 p-16 flex-col">
            <div class="material-center__btn flex row-between">
                <div class="flex">
                    <ls-upload
                        class="m-r-10"
                        :data="{ cid: currentId }"
                        :type="type"
                        :show-progress="true"
                        @change="getList(1)"
                    >
                        <el-button size="small" type="primary">本地上传</el-button>
                    </ls-upload>
                    <template v-if="mode == 'pages'">
                        <ls-dialog
                            class="m-r-10 inline"
                            content="确定删除选中的文件？"
                            :disabled="!selectList.length"
                            @confirm="batchFileDel"
                        >
                            <el-button slot="trigger" size="small" :disabled="!selectList.length">删除</el-button>
                        </ls-dialog>
                        <ls-dialog
                            class="m-r-10 inline"
                            @confirm="batchFileMove"
                            :disabled="!selectList.length"
                            title="移动文件"
                        >
                            <el-button slot="trigger" size="small" :disabled="!selectList.length">移动</el-button>
                            <div>
                                <span class="m-r-20">移动文件至</span>
                                <el-select v-model="moveId" placeholder="请选择">
                                    <el-option
                                        v-for="item in groupeLists"
                                        :key="item.id"
                                        :label="item.name"
                                        :value="item.id"
                                    ></el-option>
                                </el-select>
                            </div>
                        </ls-dialog>
                    </template>
                </div>
                <el-input
                    size="small"
                    placeholder="请输入名字"
                    style="width: 280px"
                    v-model="name"
                    @keyup.enter.native="getList(1)"
                >
                    <el-button slot="append" icon="el-icon-search" @click="getList(1)"></el-button>
                </el-input>
            </div>
            <div class="material-center__content flex-col flex-1">
                <ul class="file-list flex flex-wrap m-t-16">
                    <li
                        class="file-item-wrap ls-del-wrap"
                        v-for="item in pager.lists"
                        :key="item.id"
                        :style="{ width: `${size}px` }"
                        @click="selectFile(item)"
                    >
                        <file-item :type="type" :item="item" :size="size">
                            <div class="item-selected" v-if="selectStatus(item.id)">
                                <i class="el-icon-check white icon-selected"></i>
                            </div>
                        </file-item>

                        <div class="item-name line-1 xs p-t-10">{{ item.name }}</div>
                        <div v-if="mode == 'pages'" class="operation-btns" @click.stop>
                            <ls-dialog
                                class="m-r-10 inline"
                                content="确定删除该文件？"
                                @confirm="batchFileDel([item.id])"
                            >
                                <el-button slot="trigger" size="small" type="text">删除</el-button>
                            </ls-dialog>
                            <popover-input :value="item.name" type="text" @confirm="fileRename($event, item.id)">
                                <el-button size="small" type="text">重命名</el-button>
                            </popover-input>
                            <a class="m-l-10" :href="item.uri" target="_blank">
                                <el-button size="small" type="text">查看</el-button>
                            </a>
                        </div>
                        <i
                            v-if="!selectStatus(item.id) && mode == 'popup'"
                            class="el-icon-close ls-icon-del"
                            @click.stop="batchFileDel([item.id])"
                        ></i>
                    </li>
                </ul>
                <div class="flex flex-1 row-center col-center" v-if="!pager.loading && !pager.lists.length">
                    暂无数据~
                </div>
            </div>
            <div class="material-center__footer flex row-between flex-wrap p-b-16">
                <div class="btn" v-if="mode == 'pages'">
                    <span class="m-r-10">
                        <el-checkbox
                            :disabled="!pager.lists.length"
                            v-model="checkAll"
                            @change="selectAll"
                            :indeterminate="isIndeterminate"
                            >当页全选</el-checkbox
                        >
                    </span>
                    <ls-dialog
                        class="m-r-10 inline"
                        content="确定删除选中的图片？"
                        :disabled="!selectList.length"
                        @confirm="batchFileDel"
                    >
                        <el-button slot="trigger" size="small" :disabled="!selectList.length">删除</el-button>
                    </ls-dialog>
                    <ls-dialog
                        class="m-r-10 inline"
                        @confirm="batchFileMove"
                        :disabled="!selectList.length"
                        title="移动图片"
                    >
                        <el-button slot="trigger" size="small" :disabled="!selectList.length">移动</el-button>
                        <div>
                            <span class="m-r-20">移动图片至</span>
                            <el-select v-model="moveId" placeholder="请选择">
                                <el-option
                                    v-for="item in groupeLists"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.id"
                                ></el-option>
                            </el-select>
                        </div>
                    </ls-dialog>
                </div>
                <ls-pagination v-model="pager" @change="getList()" layout="total, prev, pager, next, jumper" />
            </div>
        </div>
        <div class="material__right" v-if="mode == 'popup'">
            <div class="flex row-between">
                <div class="sm">
                    已选择 {{ selectList.length }}
                    <span v-if="limit">/{{ limit }}</span>
                </div>
                <el-button type="text" size="small" @click="clearSelectList">清空</el-button>
            </div>

            <el-scrollbar class="ls-scrollbar" style="height: calc(100% - 32px)">
                <ul class="flex-col p-t-10">
                    <li class="m-b-16" v-for="item in selectList" :key="item.id">
                        <div class="ls-del-wrap">
                            <file-item :type="type" :item="item"></file-item>
                            <i class="el-icon-close ls-icon-del" @click="delImage(item.id)"></i>
                        </div>
                    </li>
                </ul>
            </el-scrollbar>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator'
import PopoverInput from '@/components/popover-input.vue'
import LsPagination from '@/components/ls-pagination.vue'
import LsDialog from '@/components/ls-dialog.vue'
import LsUpload from '@/components/ls-upload.vue'
import FileItem from './file-item.vue'
import {
    apiFileAddCate,
    apiFileEditCate,
    apiFileDelCate,
    apiFileList,
    apiFileListCate,
    apiFileDel,
    apiFileMove,
    apiFileRename
} from '@/api/shop'
import { RequestPaging } from '@/utils/util'
@Component({
    components: {
        LsPagination,
        PopoverInput,
        LsDialog,
        LsUpload,
        FileItem
    }
})
export default class Material extends Vue {
    @Prop({ default: 'image' }) type!: 'image' | 'video' | 'file'
    @Prop({ default: '100' }) size!: string
    @Prop({ default: 'popup' }) mode!: 'pages' | 'popup'
    @Prop({ default: 20 }) pageSize!: number
    @Prop() limit!: number
    // data
    name = ''
    moveId = 0
    checkAll = false
    isIndeterminate = false
    currentId = ''
    fileList = []
    groupeLists: any[] = []
    pager = new RequestPaging({ size: this.pageSize })
    selectList: any[] = []

    get typeValue() {
        switch (this.type) {
            case 'image':
                return 10
            case 'video':
                return 20
            case 'file':
                return 30
        }
    }

    @Watch('selectList')
    selectListChange(val: any[]) {
        this.$emit('change', val)
        if (val.length == this.pager.lists.length && val.length !== 0) {
            this.isIndeterminate = false
            this.checkAll = true
            return
        }
        if (val.length > 0) {
            this.isIndeterminate = true
        } else {
            this.checkAll = false
            this.isIndeterminate = false
        }
    }

    get selectStatus() {
        return (id: any) => this.selectList.find(item => item.id == id)
    }
    async getGroupeList() {
        const res = await apiFileListCate({
            type: this.typeValue,
            page_type: 1
        })
        const item = [
            {
                name: '全部',
                id: ''
            },
            {
                name: '未分组',
                id: 0
            }
        ]
        this.groupeLists = res?.lists
        this.groupeLists.unshift(...item)
    }

    getList(page?: number): void {
        page && (this.pager.page = page)
        if (this.mode == 'pages') {
            this.clearSelectList()
        }
        this.pager.request({
            callback: apiFileList,
            params: {
                type: this.typeValue,
                cid: this.currentId,
                name: this.name
            }
        })
    }
    addGroupe(value: string, id: number) {
        if (!value) {
            return this.$message.error('请输入分组名称')
        }
        apiFileAddCate({
            type: this.typeValue,
            pid: id | 0,
            name: value
        }).then(res => {
            this.getData()
        })
    }
    editGroupe(value: string, id: number) {
        if (!value) {
            return this.$message.error('请输入分组名称')
        }
        apiFileEditCate({
            name: value,
            id
        }).then(res => {
            this.getData()
        })
    }
    delGroupe(id: number) {
        apiFileDelCate({
            id
        }).then(res => {
            this.getData()
        })
    }

    async getData() {
        this.pager.loading = true
        await this.getGroupeList()
        this.pager.loading = false
        this.getList()
    }

    currentChange({ id }: any) {
        this.name = ''
        this.currentId = id
        this.getList()
    }

    selectFile(item: any) {
        const index = this.selectList.findIndex((items: any) => items.id == item.id)
        if (index != -1) {
            this.selectList.splice(index, 1)
            return
        }
        if (this.mode == 'popup' && this.selectList.length == this.limit) {
            if (this.limit == 1) {
                this.selectList = []
                this.selectList.push(item)
                return
            }
            this.$message.warning('已达到选择上限')
            return
        }
        this.selectList.push(item)
    }

    selectAll(value: boolean) {
        this.isIndeterminate = false
        if (value) {
            this.selectList = [...this.pager.lists]
            return
        }
        this.clearSelectList()
    }

    batchFileDel(id: any[]) {
        const ids = id ? id : this.selectList.map(item => item.id)
        apiFileDel({
            ids
        }).then(res => {
            this.getList()
        })
    }

    batchFileMove() {
        const ids = this.selectList.map(item => item.id)
        apiFileMove({
            ids,
            cid: this.moveId
        }).then(res => {
            this.moveId = 0
            this.getList()
        })
    }

    fileRename(value: string, id: number) {
        apiFileRename({
            id,
            name: value
        }).then(() => {
            this.getList()
        })
    }

    delImage(id: number) {
        this.selectList = this.selectList.filter(item => item.id != id)
    }
    clearSelectList() {
        this.selectList = []
    }
    created() {
        this.getData()
    }
}
</script>

<style scoped lang="scss">
.material {
    height: 100%;
    flex: 1;
    &__left {
        width: 220px;
        ::v-deep .el-tree-node__content {
            height: 40px;
        }
    }
    &__center {
        border-left: 1px solid $--border-color-base;
        .file-list {
            .file-item-wrap {
                margin-right: 16px;
                margin-bottom: 16px;
                line-height: 1.3;
                cursor: pointer;
                .item-selected {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    border-radius: 4px;
                    background-color: rgba(0, 0, 0, 0.5);
                    box-sizing: border-box;
                    .icon-selected {
                        position: absolute;
                        left: 50%;
                        top: 50%;
                        transform: translate(-50%, -50%);
                        font-size: 22px;
                        font-weight: bold;
                        z-index: 100;
                    }
                }
                .operation-btns {
                    height: 28px;
                    visibility: hidden;
                }
                &:hover .operation-btns {
                    visibility: visible;
                }
            }
        }
    }
    &__right {
        border-left: 1px solid $--border-color-base;
        width: 150px;
        padding-left: 16px;
        box-sizing: border-box;
        .ls-del-wrap {
            width: 100px;
            height: 100px;
        }
    }
}
</style>
