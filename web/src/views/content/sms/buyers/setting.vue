<template>
    <div class="buyers p-b-60">
        <div class="ls-card">
            <el-page-header @back="$router.go(-1)" content="编辑消息通知" />
        </div>

        <div class="ls-card m-t-24">
            <div class="xxl m-b-20">通知名称</div>
            <div class="m-l-30 m-t-10">通知名称： {{ detail.scene_name }}</div>
            <div class="m-l-30 m-t-10">通知类型： 业务通知</div>
            <div class="m-l-30 m-t-10">通知业务： {{ detail.scene_desc }}</div>
        </div>

        <!-- 系统通知 -->
        <div class="ls-card m-t-24" v-if="detail.system_notice.is_show">
            <div class="xxl m-b-20">系统通知</div>

            <div class style="width: 90%">
                <el-form ref="form" label-width="135px">
                    <el-form-item label="开启状态" required>
                        <el-radio v-model="detail.system_notice.status" :label="'0'">关闭</el-radio>
                        <el-radio v-model="detail.system_notice.status" :label="'1'">开启</el-radio>
                    </el-form-item>

                    <el-form-item label="通知标题" size="mini" required>
                        <el-input v-model="detail.system_notice.title"></el-input>
                    </el-form-item>

                    <el-form-item label="通知内容" size="mini" required>
                        <el-input
                            type="textarea"
                            class="text"
                            style="width: 300px"
                            placeholder="请输入内容"
                            v-model="detail.system_notice.content"
                        ></el-input>
                    </el-form-item>
                </el-form>

                <div class="desc m-t-20" style="margin-left: 135px">
                    <div v-for="(item, index) in detail.system_notice.tips" :key="index">{{ item }}</div>
                </div>
            </div>
        </div>

        <!-- 短信通知 -->
        <div class="ls-card m-t-24" v-if="detail.sms_notice.is_show">
            <div class="xxl m-b-20">短信通知</div>

            <div style="width: 90%">
                <el-form ref="form" label-width="135px">
                    <el-form-item label="开启状态" required>
                        <el-radio v-model="detail.sms_notice.status" :label="'0'">关闭</el-radio>
                        <el-radio v-model="detail.sms_notice.status" :label="'1'">开启</el-radio>
                    </el-form-item>

                    <el-form-item label="模板ID" size="mini" required>
                        <el-input v-model="detail.sms_notice.template_id"></el-input>
                    </el-form-item>

                    <el-form-item label="短信内容" size="mini" required>
                        <el-input
                            type="textarea"
                            class="text"
                            style="width: 300px"
                            placeholder="请输入内容"
                            v-model="detail.sms_notice.content"
                        ></el-input>
                    </el-form-item>
                </el-form>

                <div class="desc m-t-20" style="margin-left: 135px">
                    <div v-for="(item, index) in detail.sms_notice.tips" :key="index">{{ item }}</div>
                </div>
            </div>
        </div>

        <!-- 微信模板消息 -->
        <div class="ls-card m-t-24" v-if="detail.oa_notice.is_show">
            <div class="xxl m-b-20">微信模板消息</div>

            <el-alert
                title="温馨提示：
1. 请前往微信公众平台，将【主营行业：IT科技/互联网|电子商务 副营行业：消费品】类目添加至您的服务类目，否则将影响订阅通知的正常发送。
2. 查找订阅通知并选用，调整关键词的顺序后，复制模板ID，粘贴在此页面对应的模板ID输入框中"
                type="success"
                show-icon
                :closable="false"
            />

            <div class style="width: 90%">
                <el-form ref="form" label-width="135px">
                    <el-form-item label="开启状态" required>
                        <el-radio v-model="detail.oa_notice.status" :label="'0'">关闭</el-radio>
                        <el-radio v-model="detail.oa_notice.status" :label="'1'">开启</el-radio>
                    </el-form-item>

                    <el-form-item label="模板ID" size="mini" required>
                        <el-input v-model="detail.oa_notice.template_id"></el-input>
                    </el-form-item>

                    <el-form-item label="模板字段first内容" size="mini" required>
                        <el-input v-model="detail.oa_notice.first"></el-input>
                    </el-form-item>

                    <el-form-item label="模板字段remrk内容" size="mini" required>
                        <el-input v-model="detail.oa_notice.remark"></el-input>
                    </el-form-item>

                    <el-form-item label="模板内容" size="mini" required>
                        <el-button type="primary" size="mini" @click="onAddModeField">新增模板字段</el-button>

                        <el-table class="m-t-12" :data="detail.oa_notice.tpl" style="width: 100%" size="mini">
                            <el-table-column label="字段名" width="120">
                                <template slot-scope="scope">
                                    <el-input
                                        v-model="detail.oa_notice.tpl[scope.$index].tpl_name"
                                        placeholder="例如:订单编号"
                                    ></el-input>
                                </template>
                            </el-table-column>
                            <el-table-column label="字段值" width="120">
                                <template slot-scope="scope">
                                    <el-input
                                        v-model="detail.oa_notice.tpl[scope.$index].tpl_keyword"
                                        placeholder="例如:keyword1.DT"
                                    ></el-input>
                                </template>
                            </el-table-column>
                            <el-table-column label="字段内容" width="180">
                                <template slot-scope="scope">
                                    <el-input
                                        v-model="detail.oa_notice.tpl[scope.$index].tpl_content"
                                        placeholder="例如:{order.sn}"
                                    ></el-input>
                                </template>
                            </el-table-column>
                            <el-table-column label="操作" width="120">
                                <template slot-scope="scope">
                                    <el-button
                                        type="text"
                                        size="mall"
                                        @click="detail.oa_notice.tpl.splice(scope.$index, 1)"
                                        >删除</el-button
                                    >
                                </template>
                            </el-table-column>
                        </el-table>
                    </el-form-item>
                </el-form>

                <div class="desc m-t-20" style="margin-left: 135px">
                    <div v-for="(item, index) in detail.oa_notice.tips" :key="index">{{ item }}</div>
                </div>
            </div>
        </div>

        <!-- 微信小程序提醒 -->
        <div class="ls-card m-t-24" v-if="detail.mnp_notice.is_show">
            <div class="xxl m-b-20">微信小程序提醒</div>

            <el-alert
                title="温馨提示：
1. 请前往微信公众平台，将【主营行业：IT科技/互联网|电子商务 副营行业：消费品】类目添加至您的服务类目，否则将影响订阅通知的正常发送。
2. 查找订阅通知并选用，调整关键词的顺序后，复制模板ID，粘贴在此页面对应的模板ID输入框中"
                type="success"
                show-icon
                :closable="false"
            />

            <div class style="width: 90%">
                <el-form ref="form" label-width="135px">
                    <el-form-item label="开启状态" required>
                        <el-radio v-model="detail.mnp_notice.status" :label="'0'">关闭</el-radio>
                        <el-radio v-model="detail.mnp_notice.status" :label="'1'">开启</el-radio>
                    </el-form-item>

                    <el-form-item label="模板ID" size="mini" required>
                        <el-input v-model="detail.mnp_notice.template_id"></el-input>
                    </el-form-item>

                    <el-form-item label="模板内容" size="mini" required>
                        <el-button type="primary" size="mini" @click="onAddWeChatModeField">新增模板字段</el-button>

                        <el-table class="m-t-12" :data="detail.mnp_notice.tpl" style="width: 100%" size="mini">
                            <el-table-column label="字段名" width="120">
                                <template slot-scope="scope">
                                    <el-input
                                        v-model="detail.mnp_notice.tpl[scope.$index].tpl_name"
                                        placeholder="例如:订单编号"
                                    ></el-input>
                                </template>
                            </el-table-column>
                            <el-table-column label="字段值" width="120">
                                <template slot-scope="scope">
                                    <el-input
                                        v-model="detail.mnp_notice.tpl[scope.$index].tpl_keyword"
                                        placeholder="例如:keyword1.DT"
                                    ></el-input>
                                </template>
                            </el-table-column>
                            <el-table-column label="字段内容" width="180">
                                <template slot-scope="scope">
                                    <el-input
                                        v-model="detail.mnp_notice.tpl[scope.$index].tpl_content"
                                        placeholder="例如:{order.sn}"
                                    ></el-input>
                                </template>
                            </el-table-column>
                            <el-table-column label="操作" width="120">
                                <template slot-scope="scope">
                                    <el-button
                                        type="text"
                                        size="mall"
                                        @click="detail.mnp_notice.tpl.splice(scope.$index, 1)"
                                        >删除</el-button
                                    >
                                </template>
                            </el-table-column>
                        </el-table>
                    </el-form-item>
                </el-form>

                <div class="desc m-t-20" style="margin-left: 135px">
                    <div v-for="(item, index) in detail.mnp_notice.tips" :key="index">{{ item }}</div>
                </div>
            </div>
        </div>

        <!-- 底部保存或取消 -->
        <div class="bg-white flex row-center ls-fixed-footer">
            <div class="row-center flex">
                <el-button size="small" @click="$router.go(-1)">取消</el-button>
                <el-button size="small" type="primary" @click="onSubmit('form')">保存</el-button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { apiNoticeDetail, apiNoticeSet } from '@/api/content/sms'
@Component
export default class Buyers extends Vue {
    id: any = 1
    detail: any = {
        // 系统通知
        system_notice: {
            is_show: false,
            content: '',
            status: '',
            title: ''
        },
        // 短信通知
        sms_notice: {
            content: '',
            is_show: true,
            status: '',
            template_id: ''
        },
        // 微信模板
        oa_notice: {
            first: '',
            is_show: false,
            name: '',
            remark: '',
            status: '',
            template_id: '',
            template_sn: '',
            tpl: []
        },
        // 微信小程序
        mnp_notice: {
            is_show: false,
            name: '',
            status: '',
            template_id: '',
            template_sn: '',
            tpl: []
        }
    }

    // 提交保存
    onSubmit() {
        const params = {
            id: this.id,
            template: [
                {
                    type: 'system',
                    ...this.detail.system_notice
                },
                {
                    type: 'sms',
                    ...this.detail.sms_notice
                },
                {
                    type: 'oa',
                    ...this.detail.oa_notice
                },
                {
                    type: 'mnp',
                    ...this.detail.mnp_notice
                }
            ]
        }

        apiNoticeSet({ ...params })
            .then(res => {
                this.$router.go(-1)
                this.$message.success('设置成功!')
            })
            .catch(() => {
                this.$message.error('数据请求失败，刷新重载!')
            })
    }

    // 获取详情
    getNoticeDetail() {
        apiNoticeDetail({ id: this.id })
            .then(res => {
                this.detail = res
            })
            .catch(() => {
                this.$message.error('数据请求失败，刷新重载!')
            })
    }

    // 新增微信模板字段
    onAddModeField() {
        this.detail.oa_notice.tpl.push({
            tpl_name: '',
            tpl_keyword: '',
            tpl_content: ''
        })
    }

    // 新增微信小程序模板字段
    onAddWeChatModeField() {
        this.detail.mnp_notice.tpl.push({
            tpl_name: '',
            tpl_keyword: '',
            tpl_content: ''
        })
    }

    created() {
        this.id = this.$route.query.id
        this.id && this.getNoticeDetail()
    }
}
</script>

<style lang="scss" scoped>
::v-deep .text .el-textarea__inner {
    height: 100px !important;
}

.desc {
    // width: 400px;
    // min-height: 200px;
    // background-color: #f5f5f5;
}
</style>
