(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-069f2730"],{"0af0":function(e,t,a){"use strict";a.r(t);var r=a("9ab4"),s=a("1b40"),i=a("131b"),l=a("6ddb"),c=a("3c50"),n=a("5f8a");let o=class extends s.e{constructor(){super(...arguments),this.pager=new l.a,this.form={id:0,start_time:"",end_time:"",user_info:"",prize_type:"",status:""},this.prizeTypeList=[],this.desc={},this.create_time=""}luckyDrawDetail(){Object(i.c)({id:this.form.id}).then(e=>{this.desc=e})}luckyDrawGetPrizeType(){Object(i.f)().then(e=>{this.prizeTypeList=e}).catch(e=>{})}query(e){e&&(this.pager.page=e),this.pager.request({callback:i.h,params:{...this.form}}).catch(()=>{this.$message.error("数据请求失败，刷新重载")})}onReset(){this.form.start_time="",this.form.end_time="",this.form.user_info="",this.form.prize_type="",this.form.status="",this.query()}created(){const e=this.$route.query;this.form.id=e.id,this.query(),this.luckyDrawGetPrizeType(),this.luckyDrawDetail(),this.create_time=e.create_time}};o=Object(r.a)([Object(s.a)({components:{LsPagination:c.a,DatePicker:n.a}})],o);var u=o,d=a("2877"),m=Object(d.a)(u,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"lucky-draw-log"},[a("div",{staticClass:"ls-card"},[a("el-page-header",{attrs:{content:"抽奖记录"},on:{back:function(t){return e.$router.go(-1)}}})],1),a("el-form",{attrs:{"label-width":"120px",size:"small"}},[a("div",{staticClass:"ls-card m-t-16"},[a("div",{staticClass:"card-title"},[e._v("活动信息")]),a("div",{staticClass:"card-content m-t-24"},[a("el-form-item",{attrs:{label:"活动名称"}},[e._v(" "+e._s(e.desc.name)+" ")]),a("el-form-item",{attrs:{label:"活动时间"}},[e._v(" "+e._s(e.desc.start_time_desc)+" - "+e._s(e.desc.end_time_desc)+" ")]),a("el-form-item",{attrs:{label:"活动说明"}},[e._v(" "+e._s(e.desc.remark)+" ")]),a("el-form-item",{attrs:{label:"活动时状态"}},[e._v(" "+e._s(e.desc.status_desc)+" ")]),a("el-form-item",{attrs:{label:"创建时间"}},[e._v(" "+e._s(e.create_time)+" ")])],1)])]),a("div",{staticClass:"m-t-16 ls-card"},[a("el-form",{ref:"form",attrs:{inline:"",model:e.form,"label-width":"70px",size:"small"}},[a("el-form-item",{attrs:{label:"用户信息"}},[a("el-input",{staticClass:"ls-select-keyword",attrs:{placeholder:"请输入用户编号/昵称/手机号码"},model:{value:e.form.user_info,callback:function(t){e.$set(e.form,"user_info",t)},expression:"form.user_info"}})],1),a("el-form-item",{attrs:{label:"奖品类型"}},[a("el-select",{staticClass:"ls-select",attrs:{placeholder:"全部"},model:{value:e.form.prize_type,callback:function(t){e.$set(e.form,"prize_type",t)},expression:"form.prize_type"}},e._l(e.prizeTypeList,(function(e,t){return a("div",{key:t},[a("el-option",{attrs:{label:e.label,value:e.value}})],1)})),0)],1),a("el-form-item",{attrs:{label:"中奖状态"}},[a("el-select",{staticClass:"ls-select",attrs:{placeholder:"全部"},model:{value:e.form.status,callback:function(t){e.$set(e.form,"status",t)},expression:"form.status"}},[a("el-option",{attrs:{label:"未中奖",value:0}}),a("el-option",{attrs:{label:"中奖",value:1}})],1)],1),a("el-form-item",{attrs:{label:"中奖时间"}},[a("date-picker",{attrs:{"start-time":e.form.start_time,"end-time":e.form.end_time},on:{"update:startTime":function(t){return e.$set(e.form,"start_time",t)},"update:start-time":function(t){return e.$set(e.form,"start_time",t)},"update:endTime":function(t){return e.$set(e.form,"end_time",t)},"update:end-time":function(t){return e.$set(e.form,"end_time",t)}}})],1),a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(t){return e.query(1)}}},[e._v("查询")]),a("el-button",{attrs:{size:"small"},on:{click:function(t){return e.onReset()}}},[e._v("重置")])],1),a("div",{staticClass:"list-table m-t-16"},[a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:e.pager.loading,expression:"pager.loading"}],staticStyle:{width:"100%"},attrs:{data:e.pager.lists,size:"mini","header-cell-style":{background:"#f5f8ff"}}},[a("el-table-column",{attrs:{label:"用户信息"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",{staticClass:"flex"},[a("el-image",{staticStyle:{width:"34px",height:"34px"},attrs:{src:t.row.avatar}}),a("div",{staticClass:"m-l-10"},[e._v(" "+e._s(t.row.nickname)+" ")])],1)]}}])}),a("el-table-column",{attrs:{prop:"name",label:"奖品名称"}}),a("el-table-column",{attrs:{prop:"prize_type_desc",label:"奖品类型"}}),a("el-table-column",{attrs:{prop:"prize_content",label:"奖品内容"}}),a("el-table-column",{attrs:{prop:"status_desc",label:"中奖状态"}}),a("el-table-column",{attrs:{prop:"create_time",label:"中奖时间","min-width":"120"}})],1)],1),a("div",{staticClass:"flex row-right m-t-16 row-right"},[a("ls-pagination",{on:{change:function(t){return e.query()}},model:{value:e.pager,callback:function(t){e.pager=t},expression:"pager"}})],1)],1)],1)}),[],!1,null,"d752a6c6",null);t.default=m.exports},"131b":function(e,t,a){"use strict";a.d(t,"g",(function(){return s})),a.d(t,"b",(function(){return i})),a.d(t,"e",(function(){return l})),a.d(t,"i",(function(){return c})),a.d(t,"d",(function(){return n})),a.d(t,"c",(function(){return o})),a.d(t,"a",(function(){return u})),a.d(t,"f",(function(){return d})),a.d(t,"h",(function(){return m}));var r=a("f175");const s=e=>r.a.get("/lucky_draw.lucky_draw/lists",{params:e}),i=e=>r.a.post("/lucky_draw.lucky_draw/delete",e),l=e=>r.a.post("/lucky_draw.lucky_draw/end",e),c=e=>r.a.post("/lucky_draw.lucky_draw/start",e),n=e=>r.a.post("lucky_draw.lucky_draw/edit",e),o=e=>r.a.get("/lucky_draw.lucky_draw/detail",{params:e}),u=e=>r.a.post("lucky_draw.lucky_draw/add",e),d=()=>r.a.get("lucky_draw.lucky_draw/getPrizeType"),m=e=>r.a.get("lucky_draw.lucky_draw/record",{params:e})},"5f8a":function(e,t,a){"use strict";var r=a("9ab4"),s=a("1b40");let i=class extends s.e{constructor(){super(...arguments),this.pickerValue=[],this.pickerOptions={shortcuts:[{text:"最近一周",onClick(e){const t=new Date,a=new Date;a.setTime(a.getTime()-6048e5),e.$emit("pick",[a,t])}},{text:"最近一个月",onClick(e){const t=new Date,a=new Date;a.setTime(a.getTime()-2592e6),e.$emit("pick",[a,t])}},{text:"最近三个月",onClick(e){const t=new Date,a=new Date;a.setTime(a.getTime()-7776e6),e.$emit("pick",[a,t])}}]}}changeDate(){const e=this.pickerValue?this.pickerValue:this.pickerValue=["",""];this.$emit("update:start-time",e[0]),this.$emit("update:end-time",e[1])}handleStartTime(e){!this.pickerValue&&(this.pickerValue=[]),this.$set(this.pickerValue,0,e)}handleEndTime(e){!this.pickerValue&&(this.pickerValue=[]),this.$set(this.pickerValue,1,e)}};Object(r.a)([Object(s.c)()],i.prototype,"startTime",void 0),Object(r.a)([Object(s.c)()],i.prototype,"endTime",void 0),Object(r.a)([Object(s.c)({default:"datetimerange"})],i.prototype,"type",void 0),Object(r.a)([Object(s.c)({default:!1})],i.prototype,"disabled",void 0),Object(r.a)([Object(s.f)("startTime",{immediate:!0})],i.prototype,"handleStartTime",null),Object(r.a)([Object(s.f)("endTime",{immediate:!0})],i.prototype,"handleEndTime",null),i=Object(r.a)([s.a],i);var l=i,c=a("2877"),n=Object(c.a)(l,(function(){var e=this,t=e.$createElement;return(e._self._c||t)("el-date-picker",{attrs:{type:e.type,"picker-options":e.pickerOptions,"range-separator":"至","start-placeholder":"开始时间","end-placeholder":"结束时间",align:"right","value-format":"yyyy-MM-dd HH:mm:ss",disabled:e.disabled},on:{change:e.changeDate},model:{value:e.pickerValue,callback:function(t){e.pickerValue=t},expression:"pickerValue"}})}),[],!1,null,null,null);t.a=n.exports}}]);