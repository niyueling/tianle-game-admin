(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-9a3546d6"],{"38fe":function(t,e,a){},"4ae1":function(t,e,a){"use strict";var s=a("9ab4"),i=a("1b40"),r=a("0a6d");let l=class extends i.e{constructor(){super(...arguments),this.exportData={},this.formData={page_type:0,page_start:1,page_end:200,file_name:""}}handleOpen(){this.getData()}handleConfirm(){const t=this.$loading({lock:!0,text:"正在导出中...",spinner:"el-icon-loading"});this.method({export:2,...this.param,...this.formData,user_id:this.userId,type:this.type,page_size:this.pageSize}).then(()=>{this.$refs.dialog.close()}).finally(()=>{t.close()})}getData(){this.method({...this.param,export:1,user_id:this.userId,type:this.type,page_size:this.pageSize}).then(t=>{this.exportData=t,this.formData.file_name=t.file_name,this.formData.page_end=t.page_end,this.formData.page_start=t.page_start})}created(){}};Object(s.a)([Object(i.c)()],l.prototype,"method",void 0),Object(s.a)([Object(i.c)()],l.prototype,"param",void 0),Object(s.a)([Object(i.c)()],l.prototype,"userId",void 0),Object(s.a)([Object(i.c)()],l.prototype,"type",void 0),Object(s.a)([Object(i.c)()],l.prototype,"pageSize",void 0),l=Object(s.a)([Object(i.a)({components:{LsDialog:r.a}})],l);var n=l,o=a("2877"),c=Object(o.a)(n,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"export-data inline"},[a("ls-dialog",{ref:"dialog",attrs:{title:"导出设置",width:"500px",top:"35vh","confirm-button-text":"确认导出",async:!0},on:{confirm:t.handleConfirm,open:t.handleOpen}},[a("div",{attrs:{slot:"trigger"},slot:"trigger"},[a("el-button",{attrs:{size:"small"}},[t._v("导出")])],1),a("div",[a("el-form",{ref:"form",attrs:{model:t.formData,"label-width":"120px",size:"small"}},[a("el-form-item",{attrs:{label:"数据量："}},[t._v(" 预计导出"+t._s(t.exportData.count)+"条数据，共"+t._s(t.exportData.sum_page)+"页，每页"+t._s(t.exportData.page_size)+"条数据 ")]),a("el-form-item",{attrs:{label:"导出限制："}},[t._v(" 每次导出最大允许"+t._s(t.exportData.max_page)+"页，共"+t._s(t.exportData.all_max_size)+"条数据 ")]),a("el-form-item",{attrs:{label:"导出范围：",required:""}},[a("el-radio-group",{model:{value:t.formData.page_type,callback:function(e){t.$set(t.formData,"page_type",e)},expression:"formData.page_type"}},[a("el-radio",{attrs:{label:0}},[t._v("全部导出")]),a("el-radio",{attrs:{label:1}},[t._v("分页导出")])],1)],1),1==t.formData.page_type?a("el-form-item",{attrs:{label:"分页范围：",required:""}},[a("div",{staticClass:"flex"},[a("el-input",{staticStyle:{width:"100px"},attrs:{placeholder:""},model:{value:t.formData.page_start,callback:function(e){t.$set(t.formData,"page_start",e)},expression:"formData.page_start"}}),a("span",{staticClass:"flex-none m-l-8 m-r-8"},[t._v("页，至")]),a("el-input",{staticStyle:{width:"100px"},attrs:{placeholder:""},model:{value:t.formData.page_end,callback:function(e){t.$set(t.formData,"page_end",e)},expression:"formData.page_end"}})],1)]):t._e(),a("el-form-item",{attrs:{label:"导出文件名称：",prop:"file_name"}},[a("el-input",{attrs:{placeholder:"请输入导出文件名称"},model:{value:t.formData.file_name,callback:function(e){t.$set(t.formData,"file_name",e)},expression:"formData.file_name"}})],1)],1)],1)])],1)}),[],!1,null,null,null);e.a=c.exports},"88f1":function(t,e,a){"use strict";a.r(e);var s=a("9ab4"),i=a("1b40"),r=a("c2a9"),l=a("6ddb"),n=a("0a6d"),o=a("3c50"),c=a("4ae1");let p=class extends i.e{constructor(){super(...arguments),this.pager=new l.a}getLists(t){t&&(this.pager.page=t),this.pager.request({callback:r.k})}handleDelete(t){Object(r.d)({id:t}).then(()=>{this.getLists()})}changeStatus(t,e){Object(r.m)({id:e,disable:t}).then(()=>{this.getLists()})}enterKefu(t){Object(r.l)({id:t}).then(t=>{window.open(t.url)})}created(){this.getLists()}};p=Object(s.a)([Object(i.a)({components:{LsDialog:n.a,LsPagination:o.a,ExportData:c.a}})],p);var u=p,d=(a("c96c"),a("2877")),f=Object(d.a)(u,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"service-lists"},[a("div",{staticClass:"ls-card"},[a("el-alert",{attrs:{title:"温馨提示：添加在线客服。",type:"info","show-icon":"",closable:!1}})],1),a("div",{staticClass:"ls-card m-t-16"},[a("router-link",{attrs:{to:"/service/edit"}},[a("el-button",{attrs:{type:"primary",size:"small"}},[t._v("新增客服")])],1),a("div",{staticClass:"m-t-24"},[a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.pager.loading,expression:"pager.loading"}],staticStyle:{width:"100%"},attrs:{data:t.pager.lists,size:"mini"}},[a("el-table-column",{attrs:{label:"客服头像","min-width":"80"},scopedSlots:t._u([{key:"default",fn:function(t){return[a("div",{staticClass:"flex"},[a("el-image",{staticStyle:{width:"58px",height:"58px"},attrs:{src:t.row.avatar,fit:"contain"}})],1)]}}])}),a("el-table-column",{attrs:{prop:"account",label:"客服账号","min-width":"100"}}),a("el-table-column",{attrs:{prop:"nickname",label:"客服昵称","min-width":"100"}}),a("el-table-column",{attrs:{prop:"sort",label:"排序","min-width":"60"}}),a("el-table-column",{attrs:{prop:"disable",label:"状态","min-width":"80"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-switch",{attrs:{"active-value":0,"inactive-value":1},on:{change:function(a){return t.changeStatus(a,e.row.id)}},model:{value:e.row.disable,callback:function(a){t.$set(e.row,"disable",a)},expression:"scope.row.disable"}})]}}])}),a("el-table-column",{attrs:{prop:"create_time",label:"创建时间","min-width":"100"}}),a("el-table-column",{attrs:{label:"操作",width:"200"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("router-link",{staticClass:"m-r-10",attrs:{to:{path:"/service/edit",query:{id:e.row.id}}}},[a("el-button",{attrs:{type:"text",size:"small"}},[t._v("编辑")])],1),a("span",{staticClass:"m-r-10",on:{click:function(a){return t.enterKefu(e.row.id)}}},[a("el-button",{attrs:{type:"text",size:"small"}},[t._v("进入工作台")])],1),a("ls-dialog",{staticClass:"inline",on:{confirm:function(a){return t.handleDelete(e.row.id)}}},[a("el-button",{attrs:{slot:"trigger",type:"text",size:"small"},slot:"trigger"},[t._v("删除")])],1)]}}])})],1),a("div",{staticClass:"m-t-24 pagination"},[a("ls-pagination",{on:{change:function(e){return t.getLists()}},model:{value:t.pager,callback:function(e){t.pager=e},expression:"pager"}})],1)],1)],1)])}),[],!1,null,"118aa467",null);e.default=f.exports},c2a9:function(t,e,a){"use strict";a.d(e,"a",(function(){return i})),a.d(e,"b",(function(){return r})),a.d(e,"k",(function(){return l})),a.d(e,"c",(function(){return n})),a.d(e,"f",(function(){return o})),a.d(e,"l",(function(){return c})),a.d(e,"e",(function(){return p})),a.d(e,"m",(function(){return u})),a.d(e,"d",(function(){return d})),a.d(e,"j",(function(){return f})),a.d(e,"g",(function(){return m})),a.d(e,"i",(function(){return g})),a.d(e,"h",(function(){return h}));var s=a("f175");const i=t=>s.a.get("/settings.service.service/getConfig",{params:t}),r=t=>s.a.post("/settings.service.service/setConfig",t),l=t=>s.a.get("/kefu.kefu/lists",{params:t}),n=t=>s.a.post("/kefu.kefu/add",t),o=t=>s.a.post("/kefu.kefu/edit",t),c=t=>s.a.post("/kefu.kefu/login",t),p=t=>s.a.get("/kefu.kefu/detail",{params:t}),u=t=>s.a.post("/kefu.kefu/status",t),d=t=>s.a.post("/kefu.kefu/del",t),f=t=>s.a.get("/kefu.kefu_lang/lists",{params:t}),m=t=>s.a.post("/kefu.kefu_lang/add",t),g=t=>s.a.post("/kefu.kefu_lang/edit",t),h=t=>s.a.post("/kefu.kefu_lang/del",t)},c96c:function(t,e,a){"use strict";a("38fe")}}]);