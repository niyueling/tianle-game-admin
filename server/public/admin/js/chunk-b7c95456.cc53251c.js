(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-b7c95456"],{"35a2":function(t,e,a){},4072:function(t,e,a){"use strict";a.d(e,"f",(function(){return s})),a.d(e,"z",(function(){return r})),a.d(e,"A",(function(){return o})),a.d(e,"m",(function(){return n})),a.d(e,"l",(function(){return l})),a.d(e,"k",(function(){return d})),a.d(e,"n",(function(){return c})),a.d(e,"u",(function(){return u})),a.d(e,"t",(function(){return p})),a.d(e,"c",(function(){return m})),a.d(e,"d",(function(){return b})),a.d(e,"e",(function(){return f})),a.d(e,"v",(function(){return h})),a.d(e,"y",(function(){return g})),a.d(e,"b",(function(){return _})),a.d(e,"a",(function(){return v})),a.d(e,"g",(function(){return D})),a.d(e,"i",(function(){return x})),a.d(e,"j",(function(){return y})),a.d(e,"s",(function(){return w})),a.d(e,"q",(function(){return k})),a.d(e,"r",(function(){return S})),a.d(e,"o",(function(){return O})),a.d(e,"p",(function(){return j})),a.d(e,"w",(function(){return C})),a.d(e,"h",(function(){return $})),a.d(e,"x",(function(){return z}));var i=a("f175");const s=()=>i.a.get("/distribution.distribution_data/dataCenter"),r=t=>i.a.get("/distribution.distribution_data/topMemberEarnings",{params:t}),o=t=>i.a.get("/distribution.distribution_data/topMemberFans",{params:t}),n=t=>i.a.get("/distribution.distribution_goods/lists",{params:t}),l=t=>i.a.post("/distribution.distribution_goods/join",t),d=t=>i.a.get("/distribution.distribution_goods/detail",{params:t}),c=t=>i.a.post("/distribution.distribution_goods/set",t),u=t=>i.a.get("/distribution.distribution_member/lists",{params:t}),p=t=>i.a.get("/distribution.distribution_apply/detail",{params:t}),m=t=>i.a.get("/distribution.distribution_apply/lists",{params:t}),b=t=>i.a.post("/distribution.distribution_apply/pass",t),f=t=>i.a.post("/distribution.distribution_apply/refuse",t),h=t=>i.a.post("/distribution.distribution_member/open",t),g=t=>i.a.post("/distribution.distribution_member/freeze",t),_=t=>i.a.get("/distribution.distribution_member/adjustLevelInfo",{params:t}),v=t=>i.a.post("/distribution.distribution_member/adjustLevel",t),D=t=>i.a.get("/distribution.distribution_member/detail",{params:t}),x=t=>i.a.get("/distribution.distribution_member/getFans",{params:t}),y=t=>i.a.get("/distribution.distribution_member/getFansLists",{params:t}),w=t=>i.a.get("/distribution.distribution_level/lists",t),k=t=>i.a.get("/distribution.distribution_level/detail",{params:t}),S=t=>i.a.post("/distribution.distribution_level/edit",t),O=t=>i.a.post("/distribution.distribution_level/add",t),j=t=>i.a.post("/distribution.distribution_level/delete",t),C=t=>i.a.get("/distribution.distribution_order_goods/lists",{params:t}),$=t=>i.a.get("/distribution.distribution_config/getConfig",{params:t}),z=t=>i.a.post("/distribution.distribution_config/setConfig",t)},"4ae1":function(t,e,a){"use strict";var i=a("9ab4"),s=a("1b40"),r=a("0a6d");let o=class extends s.e{constructor(){super(...arguments),this.exportData={},this.formData={page_type:0,page_start:1,page_end:200,file_name:""}}handleOpen(){this.getData()}handleConfirm(){const t=this.$loading({lock:!0,text:"正在导出中...",spinner:"el-icon-loading"});this.method({export:2,...this.param,...this.formData,user_id:this.userId,type:this.type,page_size:this.pageSize}).then(()=>{this.$refs.dialog.close()}).finally(()=>{t.close()})}getData(){this.method({...this.param,export:1,user_id:this.userId,type:this.type,page_size:this.pageSize}).then(t=>{this.exportData=t,this.formData.file_name=t.file_name,this.formData.page_end=t.page_end,this.formData.page_start=t.page_start})}created(){}};Object(i.a)([Object(s.c)()],o.prototype,"method",void 0),Object(i.a)([Object(s.c)()],o.prototype,"param",void 0),Object(i.a)([Object(s.c)()],o.prototype,"userId",void 0),Object(i.a)([Object(s.c)()],o.prototype,"type",void 0),Object(i.a)([Object(s.c)()],o.prototype,"pageSize",void 0),o=Object(i.a)([Object(s.a)({components:{LsDialog:r.a}})],o);var n=o,l=a("2877"),d=Object(l.a)(n,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"export-data inline"},[a("ls-dialog",{ref:"dialog",attrs:{title:"导出设置",width:"500px",top:"35vh","confirm-button-text":"确认导出",async:!0},on:{confirm:t.handleConfirm,open:t.handleOpen}},[a("div",{attrs:{slot:"trigger"},slot:"trigger"},[a("el-button",{attrs:{size:"small"}},[t._v("导出")])],1),a("div",[a("el-form",{ref:"form",attrs:{model:t.formData,"label-width":"120px",size:"small"}},[a("el-form-item",{attrs:{label:"数据量："}},[t._v(" 预计导出"+t._s(t.exportData.count)+"条数据，共"+t._s(t.exportData.sum_page)+"页，每页"+t._s(t.exportData.page_size)+"条数据 ")]),a("el-form-item",{attrs:{label:"导出限制："}},[t._v(" 每次导出最大允许"+t._s(t.exportData.max_page)+"页，共"+t._s(t.exportData.all_max_size)+"条数据 ")]),a("el-form-item",{attrs:{label:"导出范围：",required:""}},[a("el-radio-group",{model:{value:t.formData.page_type,callback:function(e){t.$set(t.formData,"page_type",e)},expression:"formData.page_type"}},[a("el-radio",{attrs:{label:0}},[t._v("全部导出")]),a("el-radio",{attrs:{label:1}},[t._v("分页导出")])],1)],1),1==t.formData.page_type?a("el-form-item",{attrs:{label:"分页范围：",required:""}},[a("div",{staticClass:"flex"},[a("el-input",{staticStyle:{width:"100px"},attrs:{placeholder:""},model:{value:t.formData.page_start,callback:function(e){t.$set(t.formData,"page_start",e)},expression:"formData.page_start"}}),a("span",{staticClass:"flex-none m-l-8 m-r-8"},[t._v("页，至")]),a("el-input",{staticStyle:{width:"100px"},attrs:{placeholder:""},model:{value:t.formData.page_end,callback:function(e){t.$set(t.formData,"page_end",e)},expression:"formData.page_end"}})],1)]):t._e(),a("el-form-item",{attrs:{label:"导出文件名称：",prop:"file_name"}},[a("el-input",{attrs:{placeholder:"请输入导出文件名称"},model:{value:t.formData.file_name,callback:function(e){t.$set(t.formData,"file_name",e)},expression:"formData.file_name"}})],1)],1)],1)])],1)}),[],!1,null,null,null);e.a=d.exports},"5f8a":function(t,e,a){"use strict";var i=a("9ab4"),s=a("1b40");let r=class extends s.e{constructor(){super(...arguments),this.pickerValue=[],this.pickerOptions={shortcuts:[{text:"最近一周",onClick(t){const e=new Date,a=new Date;a.setTime(a.getTime()-6048e5),t.$emit("pick",[a,e])}},{text:"最近一个月",onClick(t){const e=new Date,a=new Date;a.setTime(a.getTime()-2592e6),t.$emit("pick",[a,e])}},{text:"最近三个月",onClick(t){const e=new Date,a=new Date;a.setTime(a.getTime()-7776e6),t.$emit("pick",[a,e])}}]}}changeDate(){const t=this.pickerValue?this.pickerValue:this.pickerValue=["",""];this.$emit("update:start-time",t[0]),this.$emit("update:end-time",t[1])}handleStartTime(t){!this.pickerValue&&(this.pickerValue=[]),this.$set(this.pickerValue,0,t)}handleEndTime(t){!this.pickerValue&&(this.pickerValue=[]),this.$set(this.pickerValue,1,t)}};Object(i.a)([Object(s.c)()],r.prototype,"startTime",void 0),Object(i.a)([Object(s.c)()],r.prototype,"endTime",void 0),Object(i.a)([Object(s.c)({default:"datetimerange"})],r.prototype,"type",void 0),Object(i.a)([Object(s.c)({default:!1})],r.prototype,"disabled",void 0),Object(i.a)([Object(s.f)("startTime",{immediate:!0})],r.prototype,"handleStartTime",null),Object(i.a)([Object(s.f)("endTime",{immediate:!0})],r.prototype,"handleEndTime",null),r=Object(i.a)([s.a],r);var o=r,n=a("2877"),l=Object(n.a)(o,(function(){var t=this,e=t.$createElement;return(t._self._c||e)("el-date-picker",{attrs:{type:t.type,"picker-options":t.pickerOptions,"range-separator":"至","start-placeholder":"开始时间","end-placeholder":"结束时间",align:"right","value-format":"yyyy-MM-dd HH:mm:ss",disabled:t.disabled},on:{change:t.changeDate},model:{value:t.pickerValue,callback:function(e){t.pickerValue=e},expression:"pickerValue"}})}),[],!1,null,null,null);e.a=l.exports},"88d8":function(t,e,a){"use strict";a.r(e);var i=a("9ab4"),s=a("1b40"),r=a("3c50"),o=a("0a6d"),n=a("6ddb"),l=a("5f8a"),d=a("4ae1"),c=a("4072");let u=class extends s.e{constructor(){super(...arguments),this.apiDistributionOrdersLists=c.w,this.distributionList=[],this.goodsSearchData={sn:"",keyword:"",dkeyword:"",name:"",status:"",start_time:"",end_time:""},this.options=[{value:"",label:"全部"},{value:"1",label:"待结算"},{value:"2",label:"已入账"},{value:"3",label:"已失效"}],this.pager=new n.a}getDistributionData(t){t&&(this.pager.page=t),this.pager.request({callback:c.w,params:{...this.goodsSearchData}})}resetgoodsSearchData(){Object.keys(this.goodsSearchData).map(t=>{this.$set(this.goodsSearchData,t,"")}),this.getDistributionData()}created(){this.getDistributionData()}};u=Object(i.a)([Object(s.a)({components:{LsPagination:r.a,LsDialog:o.a,DatePicker:l.a,ExportData:d.a}})],u);var p=u,m=(a("afd1"),a("2877")),b=Object(m.a)(p,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"ls-goods"},[a("div",{staticClass:"ls-goods__top ls-card"},[a("el-alert",{attrs:{title:"温馨提示：1.分销订单明细。",type:"info","show-icon":"",closable:!1}}),a("div",{staticClass:"coupon-search m-t-16"},[a("el-form",{ref:"form",attrs:{inline:"",model:t.goodsSearchData,"label-width":"100px",size:"small"}},[a("el-form-item",{attrs:{label:"订单信息"}},[a("el-input",{staticStyle:{width:"280px"},attrs:{placeholder:"请输入订单编号"},model:{value:t.goodsSearchData.sn,callback:function(e){t.$set(t.goodsSearchData,"sn",e)},expression:"goodsSearchData.sn"}})],1),a("el-form-item",{attrs:{label:"买家信息"}},[a("el-input",{staticStyle:{width:"280px"},attrs:{placeholder:"用户名称/编码"},model:{value:t.goodsSearchData.keyword,callback:function(e){t.$set(t.goodsSearchData,"keyword",e)},expression:"goodsSearchData.keyword"}})],1),a("el-form-item",{attrs:{label:"分销商信息"}},[a("el-input",{staticStyle:{width:"280px"},attrs:{placeholder:"分销商名称/编码"},model:{value:t.goodsSearchData.dkeyword,callback:function(e){t.$set(t.goodsSearchData,"dkeyword",e)},expression:"goodsSearchData.dkeyword"}})],1),a("el-form-item",{attrs:{label:"商品信息"}},[a("el-input",{staticStyle:{width:"280px"},attrs:{placeholder:"商品名称/编码"},model:{value:t.goodsSearchData.name,callback:function(e){t.$set(t.goodsSearchData,"name",e)},expression:"goodsSearchData.name"}})],1),a("el-form-item",{attrs:{label:"佣金状态"}},[a("el-select",{staticClass:"header-input",attrs:{placeholder:"全部"},model:{value:t.goodsSearchData.status,callback:function(e){t.$set(t.goodsSearchData,"status",e)},expression:"goodsSearchData.status"}},t._l(t.options,(function(t){return a("el-option",{key:t.value,attrs:{label:t.label,value:t.value}})})),1)],1),a("el-form-item",{attrs:{label:"下单时间"}},[a("date-picker",{attrs:{"start-time":t.goodsSearchData.start_time,"end-time":t.goodsSearchData.end_time},on:{"update:startTime":function(e){return t.$set(t.goodsSearchData,"start_time",e)},"update:start-time":function(e){return t.$set(t.goodsSearchData,"start_time",e)},"update:endTime":function(e){return t.$set(t.goodsSearchData,"end_time",e)},"update:end-time":function(e){return t.$set(t.goodsSearchData,"end_time",e)}}})],1),a("el-form-item",{staticClass:"m-l-6",attrs:{label:""}},[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(e){return t.getDistributionData(1)}}},[t._v("查询")]),a("el-button",{attrs:{size:"small"},on:{click:t.resetgoodsSearchData}},[t._v("重置")]),a("export-data",{staticClass:"m-l-10",attrs:{pageSize:t.pager.size,method:t.apiDistributionOrdersLists,param:t.goodsSearchData}})],1)],1)],1)],1),a("div",{staticClass:"m-t-24 ls-card pane-table"},[a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.pager.loading,expression:"pager.loading"}],ref:"paneTable",staticStyle:{width:"100%"},attrs:{data:t.pager.lists,size:"mini"}},[a("el-table-column",{attrs:{prop:"name",label:"买家","min-width":"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("div",{staticClass:"flex"},[a("el-image",{staticClass:"flex-none",staticStyle:{width:"58px",height:"58px"},attrs:{src:e.row.buyer_avatar}}),a("div",{staticClass:"goods-info m-l-8"},[a("div",{staticClass:"line-2"},[t._v(t._s(e.row.buyer_nickname)+"("+t._s(e.row.buyer_sn)+")")])])],1)]}}])}),a("el-table-column",{attrs:{prop:"sn",label:"订单编号","min-width":"180"}}),a("el-table-column",{attrs:{prop:"is_distribution_desc",label:"商品信息","min-width":"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("div",{staticClass:"flex"},[a("el-image",{staticClass:"flex-none",staticStyle:{width:"58px",height:"58px"},attrs:{src:e.row.image}}),a("div",{staticClass:"goods-info m-l-8"},[a("div",{staticClass:"line-2"},[t._v(" "+t._s(e.row.goods_name)+" ")])])],1)]}}])}),a("el-table-column",{attrs:{prop:"goods_price",label:"实付单价","min-width":"80"}}),a("el-table-column",{attrs:{prop:"goods_num",label:"购买数量","min-width":"100"}}),a("el-table-column",{attrs:{prop:"total_pay_price",label:"实付金额","min-width":"80"}}),a("el-table-column",{attrs:{prop:"level_desc",label:"当前分销等级","min-width":"130"}}),a("el-table-column",{attrs:{label:"分销商信息",prop:"nickname","min-width":"120"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-popover",{attrs:{placement:"top",width:"200",trigger:"hover"}},[a("div",{staticClass:"flex"},[a("span",{staticClass:"flex-none m-r-20"},[t._v("头像：")]),a("el-image",{staticStyle:{width:"40px",height:"40px","border-radius":"50%"},attrs:{src:e.row.distribution_member_avatar}})],1),a("div",{staticClass:"flex m-t-20 col-top"},[a("span",{staticClass:"flex-none m-r-20"},[t._v("昵称：")]),a("span",[t._v(t._s(e.row.distribution_member_nickname))])]),a("div",{staticClass:"flex m-t-20 col-top"},[a("span",{staticClass:"flex-none m-r-20"},[t._v("编号：")]),a("span",[t._v(t._s(e.row.distribution_member_sn))])]),a("div",{staticClass:"pointer",attrs:{slot:"reference"},slot:"reference"},[t._v(" "+t._s(e.row.distribution_member_nickname)+" ")])])]}}])}),a("el-table-column",{attrs:{prop:"ratio",label:"当前佣金比例(%)","min-width":"120"}}),a("el-table-column",{attrs:{prop:"earnings",label:"佣金金额","min-width":"80"}}),a("el-table-column",{attrs:{prop:"status_desc",label:"佣金状态","min-width":"80"}}),a("el-table-column",{attrs:{prop:"settlement_time",label:"结算时间","min-width":"110"}}),a("el-table-column",{attrs:{prop:"order_create_time",label:"下单时间","min-width":"110"}})],1),a("div",{staticClass:"m-t-24 flex row-right"},[a("ls-pagination",{on:{change:function(e){return t.getDistributionData()}},model:{value:t.pager,callback:function(e){t.pager=e},expression:"pager"}})],1)],1)])}),[],!1,null,"6f98e1c2",null);e.default=b.exports},afd1:function(t,e,a){"use strict";a("35a2")}}]);