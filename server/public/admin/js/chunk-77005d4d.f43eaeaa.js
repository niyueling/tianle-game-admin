(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-77005d4d"],{"267f":function(t,e,s){},"5be2":function(t,e,s){"use strict";s.d(e,"c",(function(){return r})),s.d(e,"d",(function(){return i})),s.d(e,"a",(function(){return o})),s.d(e,"b",(function(){return l})),s.d(e,"e",(function(){return n})),s.d(e,"f",(function(){return c}));var a=s("f175");const r=()=>a.a.get("/settings.user.user/getConfig"),i=t=>a.a.post("/settings.user.user/setConfig",t),o=()=>a.a.get("/settings.user.user/getRegisterConfig"),l=t=>a.a.post("/settings.user.user/setRegisterConfig",t),n=()=>a.a.get("/settings.user.user/getWithdrawConfig"),c=t=>a.a.post("/settings.user.user/setWithdrawConfig",t)},6143:function(t,e,s){"use strict";s.r(e);var a=s("9ab4"),r=s("1b40"),i=s("5be2");let o=class extends r.e{constructor(){super(...arguments),this.form={withdraw_way:[],withdraw_min_money:0,withdraw_max_money:0,withdraw_service_charge:0,scene:"withdraw",transfer_way:1},this.formRules={withdraw_way:[{type:"array",required:!0,message:"默认需要保留至少一种提现方法",trigger:"change"}]}}getWithdrawDeposit(){Object(i.e)().then(t=>{this.form=t}).catch(()=>{})}setWithdrawDeposit(){this.form.scene="withdraw",this.form.withdraw_min_money=Number(this.form.withdraw_min_money),this.$refs.formRef.validate(t=>{t&&Object(i.f)(this.form).then(t=>{setTimeout(()=>{this.getWithdrawDeposit()},50)}).catch(()=>{})})}created(){this.getWithdrawDeposit()}};o=Object(a.a)([Object(r.a)({components:{}})],o);var l=o,n=(s("7696"),s("2877")),c=Object(n.a)(l,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"withdraw_deposit"},[s("div",{staticClass:"ls-card"},[s("el-alert",{attrs:{title:"温馨提示：设置钱包可提现金额的提现方式，可提现金额来源于各类佣金奖励。",type:"info",closable:!1,"show-icon":""}})],1),s("el-form",{ref:"formRef",attrs:{model:t.form,rules:t.formRules,"label-width":"120px",size:"small"}},[s("div",{staticClass:"ls-card m-t-16"},[s("div",{staticClass:"card-title"},[t._v("提现设置")]),s("div",{staticClass:"card-content m-t-24"},[s("el-form-item",{attrs:{label:"提现方法",prop:"withdraw_way"}},[s("el-checkbox-group",{model:{value:t.form.withdraw_way,callback:function(e){t.$set(t.form,"withdraw_way",e)},expression:"form.withdraw_way"}},[s("el-checkbox",{attrs:{label:"1"}},[t._v("账户余额(默认)")]),s("el-checkbox",{attrs:{label:"2"}},[t._v("微信零钱")]),s("el-checkbox",{attrs:{label:"4"}},[t._v("微信收款码")]),s("el-checkbox",{attrs:{label:"5"}},[t._v("支付宝收款码")]),s("el-checkbox",{attrs:{label:"3"}},[t._v("银行卡")])],1),s("div",{staticClass:"muted xs"},[t._v("默认需要保留至少一种提现方法")])],1),s("el-form-item",{attrs:{label:"微信零钱接口",prop:"transfer_way"}},[s("el-radio-group",{staticClass:"m-r-16",model:{value:t.form.transfer_way,callback:function(e){t.$set(t.form,"transfer_way",e)},expression:"form.transfer_way"}},[s("el-radio",{attrs:{label:1}},[t._v("企业付款到零钱")]),s("el-radio",{attrs:{label:2}},[t._v("商家转账到零钱")])],1),s("div",{staticClass:"muted xs"},[t._v("选择商家转账到零钱时，运营账户必须有钱才能提现")])],1),s("el-form-item",{attrs:{label:"最低提现金额",prop:"withdraw_min_money"}},[s("el-input",{staticClass:"ls-input",attrs:{placeholder:"请输入金额"},model:{value:t.form.withdraw_min_money,callback:function(e){t.$set(t.form,"withdraw_min_money",e)},expression:"form.withdraw_min_money"}},[s("template",{slot:"append"},[t._v("元")])],2),s("div",{staticClass:"muted xs"},[t._v("会员提现需满足最低提现金额。才能提交提现申请。")])],1),s("el-form-item",{attrs:{label:"最高提现金额"}},[s("el-input",{staticClass:"ls-input",attrs:{placeholder:"请输入金额"},model:{value:t.form.withdraw_max_money,callback:function(e){t.$set(t.form,"withdraw_max_money",e)},expression:"form.withdraw_max_money"}},[s("template",{slot:"append"},[t._v("元")])],2),s("div",{staticClass:"muted xs"},[t._v("会员提现允许的最高提现金额。")])],1),s("el-form-item",{attrs:{label:"提现手续费"}},[s("el-input",{staticClass:"ls-input",attrs:{placeholder:"请输入提现手续费"},model:{value:t.form.withdraw_service_charge,callback:function(e){t.$set(t.form,"withdraw_service_charge",e)},expression:"form.withdraw_service_charge"}},[s("template",{slot:"append"},[t._v("%")])],2),s("div",{staticClass:"muted xs"},[t._v("会员提现时收取的手续费占比。")])],1)],1)])]),s("div",{staticClass:"bg-white ls-fixed-footer"},[s("div",{staticClass:"row-center flex",staticStyle:{height:"100%"}},[s("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(e){return t.setWithdrawDeposit()}}},[t._v("保存")])],1)])],1)}),[],!1,null,"397fb430",null);e.default=c.exports},7696:function(t,e,s){"use strict";s("267f")}}]);