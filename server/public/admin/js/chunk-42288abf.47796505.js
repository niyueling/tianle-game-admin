(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-42288abf"],{"208e":function(t,e,a){"use strict";a.r(e);var s=a("9ab4"),r=a("1b40"),i=a("6ddb"),l=a("92f3"),n=a("0a6d"),o=a("3c50"),c=a("50c0"),d=a("5f8a");let m=class extends r.e{constructor(){super(...arguments),this.tabIndex=0,this.tabLists=[{status:"",label:"全部"},{status:0,label:"待付款"},{status:1,label:"待发货"},{status:2,label:"待收货"},{status:3,label:"已完成"},{status:4,label:"已取消"}],this.formData={status:"",sn:"",goods_name:"",exchange_type:"",start_time:"",end_time:""},this.pager=new i.a}getList(t){t&&(this.pager.page=t);const e=this.tabLists[this.tabIndex].status;this.pager.request({callback:l.m,params:{...this.formData,status:e}})}handleReset(){Object.keys(this.formData).forEach(t=>{this.$set(this.formData,t,"")}),this.getList()}orderCancel(t){Object(l.b)({id:t}).then(t=>{this.getList()})}orderConfirm(t){Object(l.c)({id:t}).then(t=>{this.getList()})}created(){this.getList()}};m=Object(s.a)([Object(r.a)({components:{DatePicker:d.a,LsPagination:o.a,LsDialog:n.a,OrderLogistics:c.a}})],m);var u=m,p=a("2877"),f=Object(p.a)(u,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"exchange-order"},[a("div",{staticClass:"ls-card",staticStyle:{"padding-bottom":"8px"}},[a("el-alert",{attrs:{title:"温馨提示：1.会员在商城下单的列表；2.订单状态有待付款，待发货，待收货，已完成，已关闭；不做售后流程；3.待付款订单取消后则为已关闭、待付款订单支付后则为待发货、待发货订单发货后则为待收货、待收货订单收货后则为已完成；红包类型的订单一付款就变成已完成状态。",type:"info","show-icon":"",closable:!1}}),a("div",{staticClass:"form-data m-t-16"},[a("el-form",{attrs:{inline:"",model:t.formData,"label-width":"80px",size:"small"}},[a("el-form-item",{attrs:{label:"兑换单号"}},[a("el-input",{attrs:{placeholder:"请输入兑换单号"},model:{value:t.formData.sn,callback:function(e){t.$set(t.formData,"sn",e)},expression:"formData.sn"}})],1),a("el-form-item",{attrs:{label:"商品名称"}},[a("el-input",{attrs:{placeholder:"请输入商品名称"},model:{value:t.formData.goods_name,callback:function(e){t.$set(t.formData,"goods_name",e)},expression:"formData.goods_name"}})],1),a("el-form-item",{attrs:{label:"兑换类型"}},[a("el-select",{attrs:{placeholder:"请选择兑换类型"},model:{value:t.formData.exchange_type,callback:function(e){t.$set(t.formData,"exchange_type",e)},expression:"formData.exchange_type"}},[a("el-option",{attrs:{label:"全部",value:""}}),a("el-option",{attrs:{label:"商品",value:1}}),a("el-option",{attrs:{label:"红包",value:2}})],1)],1),a("el-form-item",{attrs:{label:"下单时间"}},[a("date-picker",{attrs:{"start-time":t.formData.start_time,"end-time":t.formData.end_time},on:{"update:startTime":function(e){return t.$set(t.formData,"start_time",e)},"update:start-time":function(e){return t.$set(t.formData,"start_time",e)},"update:endTime":function(e){return t.$set(t.formData,"end_time",e)},"update:end-time":function(e){return t.$set(t.formData,"end_time",e)}}})],1),a("el-form-item",{staticClass:"m-l-20",attrs:{label:""}},[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(e){return t.getList(1)}}},[t._v("查询")]),a("el-button",{attrs:{size:"small"},on:{click:t.handleReset}},[t._v("重置")])],1)],1)],1)],1),a("div",{staticClass:"ls-card m-t-16",staticStyle:{"padding-top":"0"}},[a("el-tabs",{on:{"tab-click":function(e){return t.getList(1)}},model:{value:t.tabIndex,callback:function(e){t.tabIndex=e},expression:"tabIndex"}},t._l(t.tabLists,(function(e,s){return a("el-tab-pane",{key:s,attrs:{label:e.label,name:""+s,lazy:""}},[a("div",{staticClass:"table-content m-t-16"},[a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.pager.loading,expression:"pager.loading"}],staticStyle:{width:"100%"},attrs:{data:t.pager.lists,size:"mini"}},[a("el-table-column",{attrs:{prop:"sn",label:"兑换单号","min-width":"120"}}),a("el-table-column",{attrs:{label:"商品信息","min-width":"250"},scopedSlots:t._u([{key:"default",fn:function(e){var s=e.row;return[a("div",{staticClass:"flex"},[a("el-image",{staticClass:"flex-none",staticStyle:{width:"58px",height:"58px"},attrs:{src:s.goods_snap.image}}),a("div",{staticClass:"m-l-10 flex-1"},[a("div",{staticClass:"line-2",staticStyle:{"max-height":"44px"}},[t._v(t._s(s.goods_snap.name))]),a("div",{staticClass:"flex flex-wrap"},[a("div",{staticClass:"m-r-20"},[a("span",{staticClass:"muted"},[t._v("积分金额：")]),t._v(" "+t._s(s.goods_snap.need_integral)+"积分 "),2==s.exchange_way?[t._v(" + "+t._s(+s.goods_snap.need_money)+"元 ")]:t._e()],2),a("div",{staticClass:"muted"},[t._v("数量："+t._s(s.total_num))])])])],1)]}}],null,!0)}),a("el-table-column",{attrs:{prop:"exchange_type_desc",label:"兑换类型"}}),a("el-table-column",{attrs:{label:"会员信息"},scopedSlots:t._u([{key:"default",fn:function(e){var s=e.row;return[a("el-popover",{attrs:{placement:"top",width:"200",trigger:"hover"}},[a("div",[a("div",{staticClass:"flex"},[a("span",{staticClass:"flex-none m-r-20"},[t._v("头像：")]),a("el-image",{staticStyle:{width:"40px",height:"40px","border-radius":"50%"},attrs:{src:s.user.avatar}})],1),a("div",{staticClass:"flex m-t-20 col-top"},[a("span",{staticClass:"flex-none m-r-20"},[t._v("昵称：")]),a("span",[t._v(t._s(s.user.nickname))])]),a("div",{staticClass:"flex m-t-20 col-top"},[a("span",{staticClass:"flex-none m-r-20"},[t._v("编号：")]),a("span",[t._v(t._s(s.user.sn))])])]),a("span",{attrs:{slot:"reference"},slot:"reference"},[t._v(t._s(s.user.nickname))])])]}}],null,!0)}),a("el-table-column",{attrs:{label:"实际支付"},scopedSlots:t._u([{key:"default",fn:function(e){var s=e.row;return[a("div",{staticClass:"flex"},[t._v(" "+t._s(s.order_integral)+"积分 "),s.order_amount>0?[t._v("+ "+t._s(+s.order_amount)+"元")]:t._e()],2)]}}],null,!0)}),a("el-table-column",{attrs:{label:"收货信息"},scopedSlots:t._u([{key:"default",fn:function(e){var s=e.row;return[a("el-popover",{attrs:{placement:"top",width:"300",trigger:"hover"}},[a("div",[t._v("收件人："+t._s(s.address.contact))]),a("div",{staticClass:"m-t-10"},[t._v("手机号："+t._s(s.address.mobile))]),a("div",{staticClass:"m-t-10"},[t._v(" 地   址： "+t._s(s.delivery_address)+" ")]),a("el-tag",{attrs:{slot:"reference",size:"medium"},slot:"reference"},[t._v(t._s(s.address.contact))])],1)]}}],null,!0)}),a("el-table-column",{attrs:{label:"订单状态"},scopedSlots:t._u([{key:"default",fn:function(e){var s=e.row;return[a("div",{},[t._v(t._s(s.order_status_desc))])]}}],null,!0)}),a("el-table-column",{attrs:{fixed:"right",label:"操作","min-width":"200"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("div",{staticClass:"flex"},[a("router-link",{staticClass:"m-r-10",attrs:{to:{path:"/integral_mall/exchange_order_detail",query:{id:e.row.id}}}},[a("el-button",{attrs:{type:"text",size:"small"}},[t._v("订单详情")])],1),e.row.admin_btns.delivery_btn?a("order-logistics",{staticClass:"m-r-12",attrs:{flag:!1,id:e.row.id}},[a("el-button",{attrs:{slot:"trigger",type:"text",size:"small"},slot:"trigger"},[t._v("物流查询")])],1):t._e(),e.row.admin_btns.to_ship_btn?a("order-logistics",{staticClass:"m-r-12",attrs:{flag:!0,id:e.row.id},on:{update:function(e){return t.getList()}}},[a("el-button",{attrs:{slot:"trigger",type:"text",size:"small"},slot:"trigger"},[t._v("发货")])],1):t._e(),e.row.admin_btns.confirm_btn?a("ls-dialog",{staticClass:"inline m-r-12",attrs:{title:"确认收货",content:"确定收货订单："+e.row.sn},on:{confirm:function(a){return t.orderConfirm(e.row.id)}}},[a("el-button",{attrs:{slot:"trigger",type:"text",size:"small"},slot:"trigger"},[t._v("确认收货")])],1):t._e(),e.row.admin_btns.cancel_btn?a("ls-dialog",{staticClass:"inline",attrs:{title:"取消订单",content:"确定取消订单("+e.row.sn+")吗?请谨慎操作"},on:{confirm:function(a){return t.orderCancel(e.row.id)}}},[a("el-button",{attrs:{slot:"trigger",type:"text",size:"small"},slot:"trigger"},[t._v("取消订单")])],1):t._e()],1)]}}],null,!0)})],1)],1),a("div",{staticClass:"flex row-right m-t-16"},[a("ls-pagination",{on:{change:function(e){return t.getList()}},model:{value:t.pager,callback:function(e){t.pager=e},expression:"pager"}})],1)])})),1)],1)])}),[],!1,null,null,null);e.default=f.exports},"50c0":function(t,e,a){"use strict";var s=a("9ab4"),r=a("1b40"),i=a("92f3");let l=class extends r.e{constructor(){super(...arguments),this.visible=!1,this.fullscreenLoading=!1,this.orderData={traces:{},address:{}},this.form={send_type:1,express_id:"",invoice_no:""}}getOrderDeliveryInfo(){Object(i.k)({id:this.id}).then(t=>{this.orderData=t,this.fullscreenLoading=!1})}getOrderLogistics(){Object(i.i)({id:this.id}).then(t=>{this.orderData=t,this.fullscreenLoading=!1})}orderDelivery(){Object(i.e)({id:this.id,...this.form}).then(t=>{this.$emit("update",""),this.getOrderLogistics()})}handleEvent(t){if("cancel"===t&&this.close(),"confirm"===t){if(this.flag&&1==this.form.send_type){if(""==this.form.express_id)return this.$message.error("请选择快递公司");if(""==this.form.invoice_no)return this.$message.error("请填写快递单号")}this.orderDelivery(),this.close()}}onTrigger(){this.fullscreenLoading=!0,1==this.flag?this.getOrderDeliveryInfo():this.getOrderLogistics(),this.visible=!0}close(){this.visible=!1}get getOrderGoods(){const{order_goods:t}=this.orderData;let e=[];return t&&(e=[t]),e}};Object(s.a)([Object(r.c)({default:"15vh"})],l.prototype,"top",void 0),Object(s.a)([Object(r.c)({default:"0"})],l.prototype,"id",void 0),Object(s.a)([Object(r.c)({default:!0})],l.prototype,"flag",void 0),Object(s.a)([Object(r.c)({default:""})],l.prototype,"isShow",void 0),l=Object(s.a)([r.a],l);var n=l,o=(a("d1c1"),a("2877")),c=Object(o.a)(n,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("div",{staticClass:"ls-dialog__trigger",on:{click:t.onTrigger}},[t._t("trigger")],2),a("el-dialog",{attrs:{"coustom-class":"ls-dialog__content",title:1==t.flag?"发货":"物流查询",visible:t.visible,width:"1050px",top:t.top,center:"","modal-append-to-body":!0,"append-to-body":!0,"before-close":t.close,"close-on-click-modal":!0}},[a("div",{directives:[{name:"loading",rawName:"v-loading",value:0==t.orderData.length,expression:"orderData.length == 0"}],staticClass:"p-l-20 p-r-20",staticStyle:{height:"50vh","overflow-x":"hidden"}},[a("div",[a("div",{staticClass:"nr weight-500 m-b-20 normal"},[t._v("商品信息")]),a("el-table",{ref:"paneTable",staticStyle:{width:"100%"},attrs:{data:t.getOrderGoods,size:"mini"}},[a("el-table-column",{attrs:{label:"商品信息","min-width":"150"},scopedSlots:t._u([{key:"default",fn:function(e){var s=e.row;return[a("div",{staticClass:"flex m-t-10"},[a("el-image",{staticClass:"flex-none",staticStyle:{width:"58px",height:"58px"},attrs:{src:s.image}}),a("div",{staticClass:"m-l-8 flex-1"},[a("div",{staticClass:"line-2"},[t._v(t._s(s.name))])])],1)]}}])}),a("el-table-column",{attrs:{label:"市场价"},scopedSlots:t._u([{key:"default",fn:function(e){var a=e.row;return[t._v("￥"+t._s(+a.market_price))]}}])}),a("el-table-column",{attrs:{label:"兑换积分"},scopedSlots:t._u([{key:"default",fn:function(e){var s=e.row;return[a("div",{staticClass:"flex"},[t._v(" "+t._s(s.need_integral)+"积分 "),2==t.orderData.exchange_way?[t._v("+ "+t._s(+s.need_money)+"元")]:t._e()],2)]}}])}),a("el-table-column",{attrs:{label:"数量",prop:"goods_num"}}),a("el-table-column",{attrs:{label:"运费"},scopedSlots:t._u([{key:"default",fn:function(e){var a=e.row;return[t._v("￥"+t._s(+a.express_price))]}}])}),a("el-table-column",{attrs:{label:"实付"},scopedSlots:t._u([{key:"default",fn:function(e){var s=e.row;return[a("div",{staticClass:"flex"},[t._v(" "+t._s(s.order_integral)+"积分 "),s.order_amount>0?[t._v("+ "+t._s(+s.order_amount)+"元")]:t._e()],2)]}}])})],1)],1),1==t.flag?a("div",{staticClass:"m-t-30"},[a("div",{staticClass:"nr weight-500 m-b-20 normal"},[t._v("收货信息")]),a("el-form",{ref:"form",attrs:{size:"mini","label-width":"120px"}},[a("el-form-item",{attrs:{label:"收货人姓名："}},[t._v(t._s(t.orderData.address.contact))]),a("el-form-item",{attrs:{label:"收货人手机号："}},[t._v(t._s(t.orderData.address.mobile))]),a("el-form-item",{attrs:{label:"收货人地址："}},[t._v(t._s(t.orderData.delivery_address))])],1)],1):t._e(),1==t.flag?a("div",{staticClass:"m-t-30"},[a("div",{staticClass:"nr weight-500 m-b-20"},[t._v("物流配送")]),a("div",{staticClass:"flex"},[a("el-form",{ref:"form",attrs:{size:"small",model:t.form,"label-width":"80px"}},[1==t.form.send_type?a("el-form-item",{attrs:{label:"物流公司"}},[a("el-select",{staticStyle:{width:"400px"},attrs:{placeholder:"请选择"},model:{value:t.form.express_id,callback:function(e){t.$set(t.form,"express_id",e)},expression:"form.express_id"}},t._l(t.orderData.express,(function(t,e){return a("el-option",{key:e,attrs:{label:t.name,value:t.id}})})),1)],1):t._e(),1==t.form.send_type?a("el-form-item",{attrs:{label:"快递单号"}},[a("el-input",{staticStyle:{width:"400px"},attrs:{placeholder:"请输入快递单号"},model:{value:t.form.invoice_no,callback:function(e){t.$set(t.form,"invoice_no",e)},expression:"form.invoice_no"}})],1):t._e()],1)],1)]):t._e(),0==t.flag?a("div",{staticClass:"m-t-30"},[a("div",{staticClass:"nr weight-500 m-b-20"},[t._v("物流信息")]),a("div",{staticClass:"flex"},[a("div",{staticClass:"m-r-24"},[t._v("发货时间： "+t._s(t.orderData.express_time))]),a("div",{staticClass:"m-r-24"},[t._v("物流公司： "+t._s(t.orderData.express_name||"无"))]),a("div",{staticClass:"m-r-24"},[t._v("物流单号 "+t._s(t.orderData.invoice_no||"无"))])])]):t._e(),0==t.flag?a("div",{staticClass:"m-t-30"},[a("div",{staticClass:"nr weight-500 m-b-20"},[t._v("物流轨迹")]),1==t.orderData.send_type?a("div",[a("el-table",{ref:"paneTable",staticStyle:{width:"100%"},attrs:{data:t.orderData.traces,size:"mini"}},[a("el-table-column",{attrs:{label:"日期",prop:"0","min-width":"205"}}),a("el-table-column",{attrs:{label:"轨迹",prop:"1","min-width":"405"}})],1)],1):a("div",{staticClass:"nr weight-500 m-t-60 flex row-center"},[t._v("无需物流")])]):t._e()]),a("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[a("el-button",{attrs:{size:"small"},on:{click:function(e){return t.handleEvent("cancel")}}},[t._v("取消")]),1==t.flag?a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(e){return t.handleEvent("confirm")}}},[t._v("发货")]):a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(e){return t.handleEvent("cancel")}}},[t._v("确认")])],1)])],1)}),[],!1,null,null,null);e.a=c.exports},"5f8a":function(t,e,a){"use strict";var s=a("9ab4"),r=a("1b40");let i=class extends r.e{constructor(){super(...arguments),this.pickerValue=[],this.pickerOptions={shortcuts:[{text:"最近一周",onClick(t){const e=new Date,a=new Date;a.setTime(a.getTime()-6048e5),t.$emit("pick",[a,e])}},{text:"最近一个月",onClick(t){const e=new Date,a=new Date;a.setTime(a.getTime()-2592e6),t.$emit("pick",[a,e])}},{text:"最近三个月",onClick(t){const e=new Date,a=new Date;a.setTime(a.getTime()-7776e6),t.$emit("pick",[a,e])}}]}}changeDate(){const t=this.pickerValue?this.pickerValue:this.pickerValue=["",""];this.$emit("update:start-time",t[0]),this.$emit("update:end-time",t[1])}handleStartTime(t){!this.pickerValue&&(this.pickerValue=[]),this.$set(this.pickerValue,0,t)}handleEndTime(t){!this.pickerValue&&(this.pickerValue=[]),this.$set(this.pickerValue,1,t)}};Object(s.a)([Object(r.c)()],i.prototype,"startTime",void 0),Object(s.a)([Object(r.c)()],i.prototype,"endTime",void 0),Object(s.a)([Object(r.c)({default:"datetimerange"})],i.prototype,"type",void 0),Object(s.a)([Object(r.c)({default:!1})],i.prototype,"disabled",void 0),Object(s.a)([Object(r.f)("startTime",{immediate:!0})],i.prototype,"handleStartTime",null),Object(s.a)([Object(r.f)("endTime",{immediate:!0})],i.prototype,"handleEndTime",null),i=Object(s.a)([r.a],i);var l=i,n=a("2877"),o=Object(n.a)(l,(function(){var t=this,e=t.$createElement;return(t._self._c||e)("el-date-picker",{attrs:{type:t.type,"picker-options":t.pickerOptions,"range-separator":"至","start-placeholder":"开始时间","end-placeholder":"结束时间",align:"right","value-format":"yyyy-MM-dd HH:mm:ss",disabled:t.disabled},on:{change:t.changeDate},model:{value:t.pickerValue,callback:function(e){t.pickerValue=e},expression:"pickerValue"}})}),[],!1,null,null,null);e.a=o.exports},"8c06":function(t,e,a){t.exports={primary:"#4073fa",white:"#fff",asideMenuWidth:"150px",headerHeight:"64px"}},"92f3":function(t,e,a){"use strict";a.d(e,"h",(function(){return r})),a.d(e,"a",(function(){return i})),a.d(e,"g",(function(){return l})),a.d(e,"d",(function(){return n})),a.d(e,"j",(function(){return o})),a.d(e,"f",(function(){return c})),a.d(e,"m",(function(){return d})),a.d(e,"l",(function(){return m})),a.d(e,"k",(function(){return u})),a.d(e,"e",(function(){return p})),a.d(e,"c",(function(){return f})),a.d(e,"b",(function(){return _})),a.d(e,"i",(function(){return g}));var s=a("f175");const r=t=>s.a.get("/integral.integral_goods/lists",{params:t}),i=t=>s.a.post("/integral.integral_goods/add",t),l=t=>s.a.post("/integral.integral_goods/edit",t),n=t=>s.a.post("/integral.integral_goods/del",t),o=t=>s.a.post("/integral.integral_goods/status",t),c=t=>s.a.get("/integral.integral_goods/detail",{params:t}),d=t=>s.a.get("/integral.integral_order/lists",{params:t}),m=t=>s.a.get("/integral.integral_order/detail",{params:t}),u=t=>s.a.get("/integral.integral_order/deliveryInfo",{params:t}),p=t=>s.a.post("/integral.integral_order/delivery",t),f=t=>s.a.post("/integral.integral_order/confirm",t),_=t=>s.a.post("/integral.integral_order/cancel",t),g=t=>s.a.get("/integral.integral_order/logistics",{params:t})},d1c1:function(t,e,a){"use strict";a("8c06")}}]);