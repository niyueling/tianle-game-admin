<template>
    <div>
        <div class="ls-dialog__trigger" @click="onTrigger">
            <!-- 触发弹窗 -->
            <slot name="trigger"></slot>
        </div>
        <el-dialog
            coustom-class="ls-dialog__content"
            :title="title"
            :visible="visible"
            :width="width"
            :top="top"
            :modal-append-to-body="false"
            center
            :before-close="close"
            :close-on-click-modal="false"
            @close="close"
        >
            <!-- 弹窗主要内容-->
            <div class="">
                <el-form :rules="valueRules" ref="valueRef" :model="form" label-width="120px" size="small">
                  <el-form-item label="ID" prop="gemCount">
                    <div class="">
                      <el-input
                        class="ls-input"
                        v-model="form.qianId"
                        placeholder="请输入ID"
                        style="width: 300px"
                      >
                      </el-input>
                    </div>
                  </el-form-item>
                    <el-form-item label="名称" prop="gemCount">
                        <div class="">
                            <el-input
                                class="ls-input"
                                v-model="form.name"
                                placeholder="请输入名称"
                                style="width: 300px"
                            >
                            </el-input>
                        </div>
                    </el-form-item>
                  <el-form-item label="位置" prop="gemCount">
                    <div class="">
                      <el-input
                        class="ls-input"
                        v-model="form.position"
                        placeholder="请输入位置"
                        style="width: 300px"
                      >
                      </el-input>
                    </div>
                  </el-form-item>
                  <el-form-item label="运势" prop="gemCount">
                    <div class="">
                      <el-input
                        class="ls-input"
                        v-model="form.bless"
                        placeholder="请输入运势"
                        style="width: 300px"
                      >
                      </el-input>
                    </div>
                  </el-form-item>
                  <el-form-item label="吉凶级别" prop="luckyLevel">
                    <div class="">
                      <el-input
                        class="ls-input"
                        v-model="form.luckyLevel"
                        placeholder="请输入吉凶级别"
                        style="width: 300px"
                      >
                      </el-input>
                    </div>
                  </el-form-item>

                  <el-form-item v-for="(item, index) in form.content" :key="index" style="margin-left: -40px">
                    <el-form :model="form" inline>
                      <el-form-item label="签文">
                        <el-input
                          v-model="form.content[index]"
                          style="width: 150px"
                          placeholder="请输入签文"
                        >
                        </el-input>
                      </el-form-item>
                      <el-form-item label="签语">
                        <el-input
                          v-model="form.summary[index]"
                          style="width: 150px"
                          placeholder="请输入签语"
                        >
                        </el-input>
                      </el-form-item>
                    </el-form>
                  </el-form-item>
                </el-form>
            </div>

            <!-- 底部弹窗页脚 -->
            <div slot="footer" class="dialog-footer">
                <el-button size="small" @click="close">取消</el-button>
                <el-button size="small" @click="updateOrAddConversion" type="primary">确认</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator'
import { apiAddQian, apiSetQianInfo } from '@/api/marketing'
@Component({
    components: {}
})
export default class LsUserChange extends Vue {
    @Prop({
        default: ''
    })
    title!: string //弹窗标题
    @Prop({
        default: 'add'
    })
    action!: string //弹窗类型
    @Prop({
        default: {
          name: '',
          qianId: '',
          position: "",
          content: ['','','',''],
          bless: "",
          summary: ['','','',''],
          luckyLevel: ''
        }
    })
    qian!: object //编辑的信息
    @Prop({
        default: '1000px'
    })
    width!: string | number //弹窗的宽度
    @Prop({
        default: '20vh'
    })
    top!: string | number //弹窗的距离顶部位置
    visible = false
    $refs!: {
        valueRef: any
    }
    form = {
        name: '',
        qianId: '',
        position: "",
        content: ['','','',''],
        bless: "",
        summary: ['','','',''],
        luckyLevel: ''
    }

    // 表单验证
    valueRules = {}

    @Watch('qian', {
        immediate: true
    })
    getQian(val: any) {
        this.$set(this, 'form', val)
    }

    updateOrAddConversion() {
        if (!this.form.name || !this.form.qianId || !this.form.position || !this.form.bless || !this.form.luckyLevel) {
            return this.$message.error('请输入基本信息')
        }

        if (this.action == 'add') {
            return this.addConversion(this.form)
        }

        return this.updateConversion(this.form)
    }

    addConversion(good: any) {
      apiAddQian(good)
            .then((res: any) => {
                this.$emit('refresh')
                this.visible = false
            })
            .catch((res: any) => {
                this.visible = false
            })
    }

    updateConversion(category: any) {
      apiSetQianInfo(category)
            .then((res: any) => {
                this.$emit('refresh')
                this.visible = false
            })
            .catch((res: any) => {
                this.visible = false
            })
    }

    onTrigger() {
        this.visible = true
    }

    // 关闭弹窗
    close() {
        this.visible = false

        // 重制表单内容
        // this.$set(this, 'form', {})
    }
}
</script>

<style scoped lang="scss">
.del {
  //right: 10px;
  top: 0px;
  font-size: 24px;
  position: absolute;
}
</style>
