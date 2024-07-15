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

                  <el-form-item v-for="(item, index) in form.times" :key="index" :label="'规则' + (index + 1)">
                    <div class="item">
                      <el-form :model="form" inline>
                        <el-form-item label="倍率" style="margin-left: 20px">
                          <el-input
                            v-model="form.times[index]"
                            type="number"
                            style="width: 150px"
                            placeholder="请输入倍率"
                          >
                          </el-input>
                        </el-form-item>
                        <el-form-item label="消耗钻石" style="margin-left: 20px">
                          <el-input
                            v-model="form.gem[index]"
                            style="width: 150px"
                            type="number"
                            placeholder="请输入消耗钻石"
                          >
                          </el-input>
                        </el-form-item>
                        <el-form-item label="运势" style="margin-left: 20px">
                          <el-input
                            v-model="form.bless[index]"
                            style="width: 150px"
                            type="number"
                            placeholder="请输入运势"
                          >
                          </el-input>
                        </el-form-item>
                        <el-form-item v-if="index === form.times.length - 1">
                          <el-button type="primary" size="mini" style="margin-left: 20px" @click="addRule">增加</el-button>
                        </el-form-item>
                        <el-form-item v-if="index != 0">
                          <div class="del" @click="delRule(index)">
                            <i class="el-icon-delete"></i>
                          </div>
                        </el-form-item>
                      </el-form>

                    </div>
                  </el-form-item>

                  <el-form-item label="排序" prop="gemCount">
                    <div class="">
                      <el-input
                        class="ls-input"
                        v-model="form.orderIndex"
                        placeholder="请输入排序"
                        style="width: 300px"
                      >
                      </el-input>
                    </div>
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
import { apiAddCheer, apiSetCheerInfo } from '@/api/marketing'
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
          times: [''],
          gem: [''],
          bless: [''],
          orderIndex: ''
        }
    })
    cheer!: object //编辑的信息
    @Prop({
        default: '1000px'
    })
    width!: string | number //弹窗的宽度
    @Prop({
        default: '20vh'
    })
    top!: string | number //弹窗的距离顶部位置
    /** S Data **/
    visible = false
    $refs!: {
        valueRef: any
    }
    form = {
        name: '',
        times: [''],
        gem: [''],
        bless: [''],
        orderIndex: ''
    }

    // 表单验证
    valueRules = {}

    @Watch('cheer', {
        immediate: true
    })
    getCheer(val: any) {
        this.$set(this, 'form', val)
    }

  addRule() {
    if (this.form.times.length >= 10) {
      return this.$message.error("不能继续添加了!");
    }
    this.form.times.push("");
    this.form.gem.push("");
    this.form.bless.push("");
  }

  delRule(index: number) {
    if (this.form.times.length <= 1) {
      return this.$message.error("已经是最后一个了!");
    }

    this.form.times.splice(index, 1);
    this.form.gem.splice(index, 1);
    this.form.bless.splice(index, 1);
  }

    updateOrAddConversion() {
        if (!this.form.name || !this.form.orderIndex) {
            return this.$message.error('请输入基本信息')
        }

        if (this.action == 'add') {
            return this.addConversion(this.form)
        }

        return this.updateConversion(this.form)
    }

    addConversion(good: any) {
      apiAddCheer(good)
            .then((res: any) => {
                this.$emit('refresh')
                this.visible = false
            })
            .catch((res: any) => {
                this.visible = false
            })
    }

    updateConversion(category: any) {
      apiSetCheerInfo(category)
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
