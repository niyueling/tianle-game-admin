(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-3b7f0c60"],{"0bb6":function(t,e,i){"use strict";i.d(e,"a",(function(){return n})),i.d(e,"d",(function(){return r})),i.d(e,"c",(function(){return s})),i.d(e,"e",(function(){return l})),i.d(e,"b",(function(){return o})),i.d(e,"f",(function(){return c})),i.d(e,"g",(function(){return u}));var a=i("f175");const n=t=>a.a.post("/marketing.seckill/add",t),r=t=>a.a.post("/marketing.seckill/edit",t),s=t=>a.a.get("/marketing.seckill/detail",{params:t}),l=t=>a.a.get("/marketing.seckill/lists",{params:t}),o=t=>a.a.post("/marketing.seckill/delete",t),c=t=>a.a.post("/marketing.seckill/open",{params:t}),u=t=>a.a.post("/marketing.seckill/stop",{params:t})},"5eed":function(t,e,i){"use strict";i.r(e),i.d(e,"FreeShippingEnum",(function(){return a}));var a,n=i("9ab4"),r=i("1b40"),s=i("8991"),l=i("6ddb"),o=i("5f8a"),c=i("3c50"),u=i("0a6d"),d=i("f244");!function(t){t.all="",t[t.wait=0]="wait",t[t.ing=1]="ing",t[t.end=2]="end"}(a||(a={}));let p=class extends r.e{constructor(){super(...arguments),this.tabs=[{label:"全部",name:"all"},{label:"未开始",name:"wait"},{label:"进行中",name:"ing"},{label:"已结束",name:"end"}],this.queryObj={name:"",end_time:"",start_time:""},this.tabCount={all:0,wait:0,ing:0,end:0},this.pager=new l.a,this.activeName="all"}getList(t){t&&(this.pager.page=t),this.pager.request({callback:d.e,params:{status:a[this.activeName],...this.queryObj}}).then(t=>{this.tabCount=null==t?void 0:t.extend})}resetQueryObj(){Object.keys(this.queryObj).map(t=>{this.$set(this.queryObj,t,"")}),this.getList()}handleDelete(t){Object(d.b)({id:t}).then(()=>{this.getList()})}handleStart(t){Object(d.f)({id:t}).then(()=>{this.getList()})}handleStop(t){Object(d.g)({id:t}).then(()=>{this.getList()})}created(){this.getList()}};p=Object(n.a)([Object(r.a)({components:{DatePicker:o.a,LsPagination:c.a,LsDialog:u.a,SeckillPane:s.a}})],p);var m=p,b=i("2877"),f=Object(b.a)(m,(function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"free-shipping"},[i("div",{staticClass:"ls-seckill__top ls-card"},[i("el-alert",{attrs:{title:"温馨提示：按场次来新增包邮活动，每一场的活动时间不能重叠（不包括已结束的活动）。",type:"info","show-icon":"",closable:!1}}),i("div",{staticClass:"seckill-search m-t-16"},[i("el-form",{ref:"form",attrs:{inline:"",model:t.queryObj,"label-width":"80px",size:"small"}},[i("el-form-item",{attrs:{label:"活动名称"}},[i("el-input",{attrs:{placeholder:"请输入活动名称"},model:{value:t.queryObj.name,callback:function(e){t.$set(t.queryObj,"name",e)},expression:"queryObj.name"}})],1),i("el-form-item",{attrs:{label:"活动时间"}},[i("date-picker",{attrs:{"start-time":t.queryObj.start_time,"end-time":t.queryObj.end_time},on:{"update:startTime":function(e){return t.$set(t.queryObj,"start_time",e)},"update:start-time":function(e){return t.$set(t.queryObj,"start_time",e)},"update:endTime":function(e){return t.$set(t.queryObj,"end_time",e)},"update:end-time":function(e){return t.$set(t.queryObj,"end_time",e)}}})],1),i("el-form-item",{staticClass:"m-l-6",attrs:{label:""}},[i("el-button",{attrs:{size:"mini",type:"primary"},on:{click:function(e){return t.getList(1)}}},[t._v("查询")]),i("el-button",{attrs:{size:"mini"},on:{click:t.resetQueryObj}},[t._v("重置")])],1)],1)],1)],1),i("div",{staticClass:"ls-seckill__content ls-card m-t-16",staticStyle:{"padding-top":"0"}},[i("el-tabs",{directives:[{name:"loading",rawName:"v-loading",value:t.pager.loading,expression:"pager.loading"}],on:{"tab-click":function(e){return t.getList(1)}},model:{value:t.activeName,callback:function(e){t.activeName=e},expression:"activeName"}},[t._l(t.tabs,(function(e,a){return i("el-tab-pane",{key:a,attrs:{label:e.label+"("+t.tabCount[e.name]+")",name:e.name}})})),i("div",{staticClass:"seckill-pane"},[i("div",{staticClass:"pane-header"},[i("el-button",{attrs:{size:"mini",type:"primary"},on:{click:function(e){return t.$router.push("/free_shipping/edit")}}},[t._v(" 新增包邮活动 ")])],1),i("div",{staticClass:"pane-table m-t-16"},[i("el-table",{ref:"paneTable",staticStyle:{width:"100%"},attrs:{data:t.pager.lists,size:"mini"}},[i("el-table-column",{attrs:{prop:"name",label:"活动名称","min-width":"120"}}),i("el-table-column",{attrs:{prop:"activity_time",label:"活动时间","min-width":"210"},scopedSlots:t._u([{key:"default",fn:function(e){var a=e.row;return[i("div",[t._v("开始时间： "+t._s(a.start_time))]),i("div",[t._v("结束时间： "+t._s(a.end_time))])]}}])}),i("el-table-column",{attrs:{prop:"order_num",label:"活动订单","min-width":"100"}}),i("el-table-column",{attrs:{label:"活动销售额","min-width":"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" ￥"+t._s(e.row.order_amount)+" ")]}}])}),i("el-table-column",{attrs:{label:"活动状态","min-width":"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[0==e.row.status?i("el-tag",{attrs:{size:"medium",type:"danger"}},[t._v("未开始")]):1==e.row.status?i("el-tag",{attrs:{size:"medium",type:"success"}},[t._v(" 进行中 ")]):i("el-tag",{attrs:{size:"medium",type:"info"}},[t._v("已结束")])]}}])}),i("el-table-column",{attrs:{prop:"create_time",label:"创建时间","min-width":"150"}}),i("el-table-column",{attrs:{fixed:"right",label:"操作",width:"200"},scopedSlots:t._u([{key:"default",fn:function(e){return[e.row.btn.detail_btn?i("el-button",{attrs:{type:"text",size:"small"},on:{click:function(i){return t.$router.push({path:"/free_shipping/edit",query:{id:e.row.id,disabled:!0}})}}},[t._v(" 详情 ")]):t._e(),e.row.btn.edit_btn?i("el-button",{attrs:{type:"text",size:"small"},on:{click:function(i){return t.$router.push({path:"/free_shipping/edit",query:{id:e.row.id}})}}},[t._v(" 编辑 ")]):t._e(),e.row.btn.start_btn?i("ls-dialog",{staticClass:"inline m-l-10",attrs:{content:"确认开始活动："+e.row.name+"？请谨慎操作。"},on:{confirm:function(i){return t.handleStart(e.row.id)}}},[i("el-button",{attrs:{slot:"trigger",type:"text",size:"small"},slot:"trigger"},[t._v("开始活动")])],1):t._e(),e.row.btn.end_btn?i("ls-dialog",{staticClass:"inline m-l-10",attrs:{content:"确定结束活动："+e.row.name+"？请谨慎操作。"},on:{confirm:function(i){return t.handleStop(e.row.id)}}},[i("el-button",{attrs:{slot:"trigger",type:"text",size:"small"},slot:"trigger"},[t._v("结束活动")])],1):t._e(),e.row.btn.delete_btn?i("ls-dialog",{staticClass:"inline m-l-10",attrs:{content:"确定删除："+e.row.name+"？请谨慎操作。"},on:{confirm:function(i){return t.handleDelete(e.row.id)}}},[i("el-button",{attrs:{slot:"trigger",type:"text",size:"small"},slot:"trigger"},[t._v("删除")])],1):t._e()]}}])})],1)],1),i("div",{staticClass:"pane-footer m-t-16 flex row-right"},[i("ls-pagination",{on:{change:function(e){return t.getList()}},model:{value:t.pager,callback:function(e){t.pager=e},expression:"pager"}})],1)])],2)],1)])}),[],!1,null,"c9e3349a",null);e.default=f.exports},"5f8a":function(t,e,i){"use strict";var a=i("9ab4"),n=i("1b40");let r=class extends n.e{constructor(){super(...arguments),this.pickerValue=[],this.pickerOptions={shortcuts:[{text:"最近一周",onClick(t){const e=new Date,i=new Date;i.setTime(i.getTime()-6048e5),t.$emit("pick",[i,e])}},{text:"最近一个月",onClick(t){const e=new Date,i=new Date;i.setTime(i.getTime()-2592e6),t.$emit("pick",[i,e])}},{text:"最近三个月",onClick(t){const e=new Date,i=new Date;i.setTime(i.getTime()-7776e6),t.$emit("pick",[i,e])}}]}}changeDate(){const t=this.pickerValue?this.pickerValue:this.pickerValue=["",""];this.$emit("update:start-time",t[0]),this.$emit("update:end-time",t[1])}handleStartTime(t){!this.pickerValue&&(this.pickerValue=[]),this.$set(this.pickerValue,0,t)}handleEndTime(t){!this.pickerValue&&(this.pickerValue=[]),this.$set(this.pickerValue,1,t)}};Object(a.a)([Object(n.c)()],r.prototype,"startTime",void 0),Object(a.a)([Object(n.c)()],r.prototype,"endTime",void 0),Object(a.a)([Object(n.c)({default:"datetimerange"})],r.prototype,"type",void 0),Object(a.a)([Object(n.c)({default:!1})],r.prototype,"disabled",void 0),Object(a.a)([Object(n.f)("startTime",{immediate:!0})],r.prototype,"handleStartTime",null),Object(a.a)([Object(n.f)("endTime",{immediate:!0})],r.prototype,"handleEndTime",null),r=Object(a.a)([n.a],r);var s=r,l=i("2877"),o=Object(l.a)(s,(function(){var t=this,e=t.$createElement;return(t._self._c||e)("el-date-picker",{attrs:{type:t.type,"picker-options":t.pickerOptions,"range-separator":"至","start-placeholder":"开始时间","end-placeholder":"结束时间",align:"right","value-format":"yyyy-MM-dd HH:mm:ss",disabled:t.disabled},on:{change:t.changeDate},model:{value:t.pickerValue,callback:function(e){t.pickerValue=e},expression:"pickerValue"}})}),[],!1,null,null,null);e.a=o.exports},8991:function(t,e,i){"use strict";var a=i("9ab4"),n=i("1b40"),r=i("0a6d"),s=i("3c50"),l=i("d455"),o=i("0bb6");let c=class extends n.e{handleDelete(t){Object(o.b)({id:t}).then(()=>{this.$emit("refresh")})}handleStart(t){Object(o.f)({id:t}).then(()=>{this.$emit("refresh")})}handleStop(t){Object(o.g)({id:t}).then(()=>{this.$emit("refresh")})}};Object(a.a)([Object(n.c)()],c.prototype,"value",void 0),Object(a.a)([Object(n.c)()],c.prototype,"pager",void 0),c=Object(a.a)([Object(n.a)({components:{LsDialog:r.a,LsPagination:s.a,PopoverInput:l.a}})],c);var u=c,d=i("2877"),p=Object(d.a)(u,(function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"seckill-pane"},[i("div",{staticClass:"pane-header"},[i("el-button",{attrs:{size:"mini",type:"primary"},on:{click:function(e){return t.$router.push("/seckill/edit")}}},[t._v("新增秒杀活动")])],1),i("div",{staticClass:"pane-table m-t-16"},[i("el-table",{ref:"paneTable",staticStyle:{width:"100%"},attrs:{data:t.value,size:"mini"}},[i("el-table-column",{attrs:{prop:"name",label:"活动名称","min-width":"100"}}),i("el-table-column",{attrs:{prop:"activity_time",label:"活动时间","min-width":"150"}}),i("el-table-column",{attrs:{prop:"closing_order",label:"秒杀订单","min-width":"100"}}),i("el-table-column",{attrs:{label:"秒杀销售额","min-width":"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" ￥"+t._s(e.row.sales_amount)+" ")]}}])}),i("el-table-column",{attrs:{prop:"sales_volume",label:"秒杀销售量","min-width":"100"}}),i("el-table-column",{attrs:{label:"活动状态","min-width":"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[1==e.row.status?i("el-tag",{attrs:{size:"medium",type:"danger"}},[t._v("未开始")]):2==e.row.status?i("el-tag",{attrs:{size:"medium",type:"success"}},[t._v("进行中")]):i("el-tag",{attrs:{size:"medium",type:"info"}},[t._v("已结束")])]}}])}),i("el-table-column",{attrs:{prop:"create_time",label:"创建时间","min-width":"120"}}),i("el-table-column",{attrs:{fixed:"right",label:"操作",width:"200"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("el-button",{attrs:{type:"text",size:"small"},on:{click:function(i){return t.$router.push({path:"/seckill/edit",query:{id:e.row.id,disabled:!0}})}}},[t._v("详情")]),3!=e.row.status?i("el-button",{attrs:{type:"text",size:"small"},on:{click:function(i){return t.$router.push({path:"/seckill/edit",query:{id:e.row.id}})}}},[t._v("编辑")]):t._e(),1==e.row.status?i("ls-dialog",{staticClass:"inline m-l-10",attrs:{content:"确认开始秒杀："+e.row.name+"？请谨慎操作。"},on:{confirm:function(i){return t.handleStart(e.row.id)}}},[i("el-button",{attrs:{slot:"trigger",type:"text",size:"small"},slot:"trigger"},[t._v("开始秒杀")])],1):t._e(),2==e.row.status?i("ls-dialog",{staticClass:"inline m-l-10",attrs:{content:"确定结束秒杀："+e.row.name+"？请谨慎操作。"},on:{confirm:function(i){return t.handleStop(e.row.id)}}},[i("el-button",{attrs:{slot:"trigger",type:"text",size:"small"},slot:"trigger"},[t._v("结束秒杀")])],1):t._e(),i("ls-dialog",{staticClass:"inline m-l-10",attrs:{content:"确定删除："+e.row.name+"？请谨慎操作。\n*秒杀活动删除后，未支付订单会被系统自动关闭。"},on:{confirm:function(i){return t.handleDelete(e.row.id)}}},[i("el-button",{attrs:{slot:"trigger",type:"text",size:"small"},slot:"trigger"},[t._v("删除")])],1)]}}])})],1)],1),i("div",{staticClass:"pane-footer m-t-16 flex row-right"},[i("ls-pagination",{on:{change:function(e){return t.$emit("refresh")}},model:{value:t.pager,callback:function(e){t.pager=e},expression:"pager"}})],1)])}),[],!1,null,"251a688a",null);e.a=p.exports},f244:function(t,e,i){"use strict";i.d(e,"a",(function(){return n})),i.d(e,"d",(function(){return r})),i.d(e,"c",(function(){return s})),i.d(e,"e",(function(){return l})),i.d(e,"b",(function(){return o})),i.d(e,"f",(function(){return c})),i.d(e,"g",(function(){return u}));var a=i("f175");const n=t=>a.a.post("/free_shipping.free_shipping/add",t),r=t=>a.a.post("/free_shipping.free_shipping/edit",t),s=t=>a.a.get("/free_shipping.free_shipping/detail",{params:t}),l=t=>a.a.get("/free_shipping.free_shipping/lists",{params:t}),o=t=>a.a.post("/free_shipping.free_shipping/delete",t),c=t=>a.a.post("/free_shipping.free_shipping/start",t),u=t=>a.a.post("/free_shipping.free_shipping/end",t)}}]);