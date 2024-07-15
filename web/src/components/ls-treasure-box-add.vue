<!-- 用户详情·钱包调整 -->
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
          <el-form-item label="等级">
            <el-input
              class="ls-input"
              v-model="form.level"
              placeholder="请输入宝箱等级"
              style="width: 300px"
            >
            </el-input>
          </el-form-item>
          <el-form-item label="名称" prop="name">
            <div class="">
              <el-input
                class="ls-input"
                v-model="form.name"
                placeholder="请输入宝箱名称"
                style="width: 300px"
              >
              </el-input>
              <div class="muted xs m-r-16">输入宝箱名称</div>
            </div>
          </el-form-item>
          <el-form-item label="释放局数">
            <div class="">
              <el-input
                class="ls-input"
                v-model="form.juCount"
                placeholder="请输入释放局数"
                style="width: 300px"
              >
              </el-input>
              <div class="muted xs m-r-16">输入补助局数</div>
            </div>
          </el-form-item>
          <el-form-item label="冷却房间数">
            <div class="">
              <el-input
                class="ls-input"
                v-model="form.coolingCount"
                placeholder="请输入冷却房间数"
                style="width: 300px"
              >
              </el-input>
              <div class="muted xs m-r-16">输入冷却房间数</div>
            </div>
          </el-form-item>
          <el-form-item label="游戏">
            <el-select
              v-model="form.gamelists"
              @change="getGameLists"
              multiple
              placeholder="请选择"
            >
              <el-option label="炸弹" value="zhadan"></el-option>
              <el-option label="麻将" value="majiang"></el-option>
              <el-option label="标分" value="biaofen"></el-option>
              <el-option label="跑得快" value="paodekuai"></el-option>
              <el-option label="十三水" value="shisanshui"></el-option>
              <el-option label="厦门麻将" value="xmmajiang"></el-option>
            </el-select>
          </el-form-item>
            <el-form-item label="炸弹牌型" v-if="form.gamelists.includes('zhadan')">
                <div class="item">
                    <el-form :model="form" inline>
                        <el-form-item label="牌型" style="margin-left: 20px">
                            <el-select v-model="form.star">
                                <el-option label="4星" :value="4"></el-option>
                                <el-option label="5星" :value="5"></el-option>
                                <el-option label="6星" :value="6"></el-option>
                                <el-option label="7星" :value="7"></el-option>
                                <el-option label="8星" :value="8"></el-option>
                                <el-option label="9星" :value="9"></el-option>
                                <el-option label="10星" :value="10"></el-option>
                                <el-option label="不烧鸡" :value="11"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="重发牌次数" v-if="form.star == 11">
                            <el-input
                                v-model="form.zdFpCount"
                                style="width: 150px"
                                placeholder="请输入重发牌次数"
                            >
                            </el-input>
                        </el-form-item>
                    </el-form>
                </div>
            </el-form-item>
          <el-form-item v-for="(item, index) in form.mahjong.cardlists" :key="index" :label="'麻将规则' + (index + 1)"
                        v-if="form.gamelists.includes('majiang')">
            <div class="item">
              <el-form :model="form" inline>
                <el-form-item label="牌型" style="margin-left: 20px">
                  <el-select v-model="form.mahjong.cardlists[index].index" style="width: 100px">
                    <el-option :label="item1.label" :value="item1.value" v-for="(item1, i) in mahjongCatdType"
                               key="index"></el-option>
                  </el-select>
                </el-form-item>
                <el-form-item label="牌型个数" v-if="!['5'].includes(form.mahjong.cardlists[index].index)">
                  <el-input
                    v-model="form.mahjong.cardlists[index].cardCount"
                    style="width: 150px"
                    placeholder="请输入补助个数"
                  >
                  </el-input>
                </el-form-item>
                <el-form-item label="顺子数" v-if="['3'].includes(form.mahjong.cardlists[index].index)">
                  <el-input
                    class="ls-input"
                    v-model="form.mahjong.cardlists[index].shunziCount"
                    placeholder="请输入补助顺子数"
                    style="width: 150px"
                  >
                  </el-input>
                </el-form-item>
                <el-form-item v-if="index === form.mahjong.cardlists.length - 1">
                  <el-button type="primary" size="mini" style="margin-left: 20px" @click="addRule">增加</el-button>
                </el-form-item>
              </el-form>
              <div class="del m-t-5" @click="delRule(index)">
                <i class="el-icon-delete"></i>
              </div>
            </div>
          </el-form-item>
          <el-form-item label="摸牌次数" v-if="form.gamelists.includes('majiang')">
            <div class="">
              <el-input
                class="ls-input"
                v-model="form.mahjong.moCount"
                placeholder="请输入麻将摸牌次数"
                style="width: 300px"
              >
              </el-input>
              <div class="muted xs m-r-16">输入冷却房间数</div>
            </div>
          </el-form-item>
          <el-form-item
            label="跑得快牌型"
            v-for="(item1, index1) in form.pdk"
            :key="index1"
            :label="'跑得快规则' + (index1 + 1)"
            v-if="form.gamelists.includes('paodekuai')">

            <div class="item">
              <el-form :model="form" inline>
                <el-form-item label="牌型" style="margin-left: 20px">
                  <el-select v-model="form.pdk[index1].index" style="width: 100px">
                    <el-option :label="item1.label" :value="item1.value" v-for="(item1, i) in pdkCardType"
                               :key="i"></el-option>
                  </el-select>
                </el-form-item>
                <el-form-item label="牌型个数" v-if="!['7'].includes(form.pdk[index1].index)">
                  <el-input
                    v-model="form.pdk[index1].cardCount"
                    style="width: 150px"
                    placeholder="请输入补助个数"
                  >
                  </el-input>
                </el-form-item>
                <el-form-item label="顺子数" v-if="['4'].includes(form.pdk[index1].index)">
                  <el-input
                    class="ls-input"
                    v-model="form.pdk[index1].shunziCount"
                    placeholder="请输入补助顺子数"
                    style="width: 150px"
                  >
                  </el-input>
                </el-form-item>
                <el-form-item v-if="index1 === form.pdk.length - 1">
                  <el-button type="primary" size="mini" style="margin-left: 20px" @click="addRulePdk">增加</el-button>
                </el-form-item>
              </el-form>
              <div class="del m-t-5" @click="delRulePdk(index1)">
                <i class="el-icon-delete"></i>
              </div>
            </div>
          </el-form-item>
          <el-form-item
            label="十三水牌型"
            v-for="(item1, index1) in form.sss"
            :key="index1"
            :label="'十三水规则' + (index1 + 1)"
            v-if="form.gamelists.includes('shisanshui')">

            <div class="item">
              <el-form :model="form" inline>
                <el-form-item label="牌型" style="margin-left: 20px">
                  <el-select v-model="form.sss[index1].index" style="width: 100px">
                    <el-option :label="item1.label" :value="item1.value" v-for="(item1, i) in sssCardType"
                               :key="i"></el-option>
                  </el-select>
                </el-form-item>
                <el-form-item label="牌型个数" v-if="!['10'].includes(form.sss[index1].index)">
                  <el-input
                    v-model="form.sss[index1].cardCount"
                    style="width: 150px"
                    placeholder="请输入补助个数"
                  >
                  </el-input>
                </el-form-item>
                <el-form-item label="顺子数" v-if="['4', '8'].includes(form.sss[index1].index)">
                  <el-input
                    class="ls-input"
                    v-model="form.sss[index1].shunziCount"
                    placeholder="请输入补助顺子数"
                    style="width: 150px"
                  >
                  </el-input>
                </el-form-item>
                <el-form-item v-if="index1 === form.sss.length - 1">
                  <el-button type="primary" size="mini" style="margin-left: 20px" @click="addRuleSss">增加</el-button>
                </el-form-item>
              </el-form>
              <div class="del m-t-5" @click="delRuleSss(index1)">
                <i class="el-icon-delete"></i>
              </div>
            </div>
          </el-form-item>

          <el-form-item
            label="标分牌型"
            v-for="(item, index1) in form.bf"
            :key="index1"
            :label="'标分规则' + (index1 + 1)"
            v-if="form.gamelists.includes('biaofen')">

            <div class="item">
              <el-form :model="form" inline>
                <el-form-item label="牌型" style="margin-left: 20px">
                  <el-select v-model="form.bf[index1].index" style="width: 100px">
                    <el-option :label="item1.label" :value="item1.value" v-for="(item1, i) in bfCardType" :key="i"></el-option>
                  </el-select>
                </el-form-item>
                <el-form-item label="牌型个数">
                  <el-input
                    v-model="form.bf[index1].cardCount"
                    style="width: 150px"
                    placeholder="请输入补助个数"
                  >
                  </el-input>
                </el-form-item>
                <el-form-item label="同花数" v-if="['3'].includes(form.bf[index1].index)">
                  <el-input
                    class="ls-input"
                    v-model="form.bf[index1].shunziCount"
                    placeholder="请输入补助同花数"
                    style="width: 150px"
                  >
                  </el-input>
                </el-form-item>
                <el-form-item v-if="index1 === form.bf.length - 1">
                  <el-button type="primary" size="mini" style="margin-left: 20px" @click="addRuleBf">增加</el-button>
                </el-form-item>
              </el-form>
              <div class="del m-t-5" @click="delRuleBf(index1)">
                <i class="el-icon-delete"></i>
              </div>
            </div>
          </el-form-item>
          <el-form-item label="状态">
            <el-select class="ls-select" v-model="form.isOnline" placeholder="全部">
              <el-option label="上架" :value="1"></el-option>
              <el-option label="下架" :value="2"></el-option>
            </el-select>
          </el-form-item>
        </el-form>
      </div>

      <!-- 底部弹窗页脚 -->
      <div slot="footer" class="dialog-footer">
        <el-button size="small" @click="close">取消</el-button>
        <el-button size="small" @click="updateOrAdd" type="primary">确认</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from "vue-property-decorator";
import { apiAddTreasureBox, apiSetTreasureBoxInfo } from "@/api/marketing";

@Component({
  components: {}
})
export default class LsUserChange extends Vue {
  @Prop({
    default: ""
  })
  title!: string; //弹窗标题
  @Prop({
    default: "add"
  })
  action!: string; //弹窗类型
  @Prop({
    default: {
      level: "",
      name: "",
      star: "",
      juCount: "",
      day: "",
      isOnline: "",
      count: 1,
      coolingCount: "",
      gamelists: [],
      mahjong: { moCount: 0, cardlists: [{ index: "", cardName: "", cardType: "", cardCount: 0, shunziCount: 0 }] },
      pdk: [{ index: "", cardName: "", cardType: "", cardCount: "", shunziCount: "" }],
      sss: [{ index: "", cardName: "", cardType: "", cardCount: 0, shunziCount: 0 }],
      bf: [{ index: "", cardName: "", cardType: "", cardCount: 0, shunziCount: 0 }],
      zdFpCount: ""
    }
  })
  box!: object; //编辑的商品信息
  @Prop({
    default: "900px"
  })
  width!: string | number; //弹窗的宽度
  @Prop({
    default: "20vh"
  })
  top!: string | number; //弹窗的距离顶部位置
  /** S Data **/
  visible = false;
  $refs!: {
    valueRef: any
  };
  form: any = {
    level: "",
    name: "",
    star: "",
    juCount: "",
    day: "",
    isOnline: "",
    count: 1,
    coolingCount: "",
    gamelists: [],
    mahjong: { moCount: 0, cardlists: [{ index: "", cardName: "", cardType: "", cardCount: 0, shunziCount: 0 }] },
    pdk: [{ index: "", cardName: "", cardType: "", cardCount: 0, shunziCount: 0 }],
    sss: [{ index: "", cardName: "", cardType: "", cardCount: 0, shunziCount: 0 }],
    bf: [{ index: "", cardName: "", cardType: "", cardCount: 0, shunziCount: 0 }],
    zdFpCount: ""
  };

  mahjongCatdType = [
    { label: "刻子", value: "1" },
    { label: "对子", value: "2" },
    { label: "顺子", value: "3" },
    { label: "杠牌", value: "4" },
    { label: "清一色", value: "5" },
    { label: "地胡", value: "6" },
    { label: "天胡", value: "7" }
  ];

  pdkCardType = [
    { label: "对子", value: "1" },
    { label: "连三张", value: "2" },
    { label: "连对", value: "3" },
    { label: "顺子", value: "4" },
    { label: "飞机", value: "5" },
    { label: "炸弹", value: "6" },
    { label: "炸弹卡", value: "7" }
  ];

  sssCardType = [
    { label: "对子", value: "1" },
    { label: "两对", value: "2" },
    { label: "三条", value: "3" },
    { label: "顺子", value: "4" },
    { label: "同花", value: "5" },
    { label: "葫芦", value: "6" },
    { label: "炸弹", value: "7" },
    { label: "同花顺", value: "8" },
    { label: "五同", value: "9" },
    { label: "特殊牌", value: "10" }
  ];

  bfCardType = [
    { label: "对子", value: "1" },
    { label: "拖拉机", value: "2" },
    { label: "同花", value: "3" }
  ];

  // 表单验证
  valueRules = {};

  @Watch("box", {
    immediate: true
  })

  getBox(val: any) {
    if (val) this.$set(this, "form", val);
  }

  getGameLists() {
    console.log(this.form.gamelists);
  }

  addRule() {
    if (this.form.mahjong.cardlists.length >= 3) {
      return this.$message.error("不能继续添加了!");
    }
    this.form.mahjong.cardlists.push({ index: "", cardName: "", cardType: "", cardCount: 0, shunziCount: 0 });
  }

  delRule(index: number) {
    if (this.form.mahjong.cardlists.length <= 1) {
      return this.$message.error("已经是最后一个了!");
    }
    this.form.mahjong.cardlists.splice(index, 1);
  }

  addRulePdk() {
    if (this.form.pdk.length >= 3) {
      return this.$message.error("不能继续添加了!");
    }
    this.form.pdk.push({ index: "", cardName: "", cardType: "", cardCount: 0, shunziCount: 0 });
  }

  // 删除规则规格项
  delRulePdk(index: number) {
    if (this.form.pdk.length <= 1) {
      return this.$message.error("已经是最后一个了!");
    }
    this.form.pdk.splice(index, 1);
  }

  addRuleSss() {
    if (this.form.sss.length >= 3) {
      return this.$message.error("不能继续添加了!");
    }
    this.form.sss.push({ index: "", cardName: "", cardType: "", cardCount: 0, shunziCount: 0 });
  }

  // 删除规则规格项
  delRuleSss(index: number) {
    if (this.form.sss.length <= 1) {
      return this.$message.error("已经是最后一个了!");
    }
    this.form.sss.splice(index, 1);
  }

  addRuleBf() {
    if (this.form.bf.length >= 3) {
      return this.$message.error("不能继续添加了!");
    }
    this.form.bf.push({ index: "", cardName: "", cardType: "", cardCount: 0, shunziCount: 0 });
  }

  delRuleBf(index: number) {
    if (this.form.bf.length <= 1) {
      return this.$message.error("已经是最后一个了!");
    }
    this.form.bf.splice(index, 1);
  }

  updateOrAdd() {
    if (this.form.gamelists.includes("majiang")) {
      this.form.mahjong.cardlists.map((v: any) => {
        v.cardName = this.mahjongCatdType[v.index - 1].label;
        v.cardType = this.mahjongCatdType[v.index - 1].value;
        if (v.index == 3) v.cardName = `${v.shunziCount}连顺`;
        if (v.index != 5) v.cardName += `(${v.cardCount}个)`;
      });
    }

    if (this.form.gamelists.includes("paodekuai")) {
      this.form.pdk.map((v: any) => {
        v.cardName = this.pdkCardType[v.index - 1].label;
        v.cardType = this.pdkCardType[v.index - 1].value;
        if (v.index == 4) v.cardName = `${v.shunziCount}连顺`;
        if (v.index != 7) v.cardName += `(${v.cardCount}个)`;
      });
    }

    if (this.form.gamelists.includes("shisanshui")) {
      this.form.sss.map((v: any) => {
        v.cardName = this.sssCardType[v.index - 1].label;
        v.cardType = this.sssCardType[v.index - 1].value;
        if (v.index == 4) v.cardName = `${v.shunziCount}连顺`;
        if (v.index == 8) v.cardName = `${v.shunziCount}连同花顺`;
        if (v.index != 10) v.cardName += `(${v.cardCount}个)`;
      });
    }

    if (this.form.gamelists.includes("biaofen")) {
      this.form.bf.map((v: any) => {
        v.cardName = this.bfCardType[v.index - 1].label;
        v.cardType = this.bfCardType[v.index - 1].value;
        if (v.index == 3) v.cardName = `${v.shunziCount}同花`;
        v.cardName += `(${v.cardCount}个)`;
      });
    }

    if (!this.form.level || !this.form.name || !this.form.juCount || !this.form.coolingCount || !this.form.gamelists) {
      return this.$message.error("请输入基本信息");
    }

    if (this.action == "add") {
      return this.addInfo(this.form);
    }

    return this.updateInfo(this.form);
  }

  addInfo(good: any) {
    apiAddTreasureBox(good)
      .then((res: any) => {
        this.$emit("refresh");
        this.visible = false;
      })
      .catch((res: any) => {
        this.visible = false;
      });
  }

  updateInfo(good: any) {
    apiSetTreasureBoxInfo(good)
      .then((res: any) => {
        this.$emit("refresh");
        this.visible = false;
      })
      .catch((res: any) => {
        this.visible = false;
      });
  }

  onTrigger() {
    this.visible = true;
  }

  // 关闭弹窗
  close() {
    this.visible = false;

    // 重制表单内容
    this.$set(this, "form", {
      level: "",
      name: "",
      star: "",
      juCount: "",
      day: "",
      isOnline: "",
      count: 1,
      coolingCount: "",
      gamelists: [],
      mahjong: { moCount: 0, cardlists: [{ index: "", cardName: "", cardType: "", cardCount: 0, shunziCount: 0 }] },
      sss: [{ index: "", cardName: "", cardType: "", cardCount: 0, shunziCount: 0 }],
        zdFpCount: ""
    });
  }
}
</script>

<style scoped lang="scss">
.item {
  padding: 30px 0;
  margin-bottom: 25px;
  position: relative;
  background-color: #f5f5f5;

  .del {
    right: 10px;
    top: 0px;
    font-size: 24px;
    position: absolute;
  }
}
</style>
