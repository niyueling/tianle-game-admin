(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-8291865a"],{"050e":function(e,t,a){},"308b":function(e,t,a){"use strict";a.d(t,"f",(function(){return o})),a.d(t,"d",(function(){return i})),a.d(t,"e",(function(){return n})),a.d(t,"c",(function(){return l})),a.d(t,"a",(function(){return r})),a.d(t,"b",(function(){return c}));var s=a("f175");const o=e=>s.a.get("/live.LiveRoom/lists",{params:e}),i=e=>s.a.post("/live.LiveRoom/add",e),n=e=>s.a.post("/live.LiveRoom/del",e),l=e=>s.a.get("/live.LiveGoods/lists",{params:e}),r=e=>s.a.post("/live.LiveGoods/add",e),c=e=>s.a.post("/live.LiveGoods/del",e)},"424d":function(e,t,a){"use strict";a("050e")},e0f3:function(e,t,a){"use strict";a.r(t);var s=a("9ab4"),o=a("1b40"),i=a("6ddb"),n=a("308b"),l=a("4201"),r=a("0a6d"),c=a("3c50");let d=class extends o.e{onDelete(e){this.$emit("onDel",e)}};Object(s.a)([Object(o.c)()],d.prototype,"value",void 0),Object(s.a)([Object(o.c)()],d.prototype,"pager",void 0),Object(s.a)([Object(o.c)()],d.prototype,"type",void 0),d=Object(s.a)([Object(o.a)({components:{LsPagination:c.a,LsDialog:r.a}})],d);var p=d,u=a("2877"),g=Object(u.a)(p,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"goods-pane"},[a("div",{staticClass:"list-header"},[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(t){return e.$emit("add")}}},[e._v("添加直播商品")]),a("el-button",{attrs:{size:"small"},on:{click:function(t){return e.$emit("synchronization")}}},[e._v("同步商品库")])],1),a("div",{staticClass:"list-table m-t-16"},[a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:e.pager.loading,expression:"pager.loading"}],staticStyle:{width:"100%"},attrs:{data:e.value,size:"mini","header-cell-style":{background:"#f5f8ff"}}},[a("el-table-column",{attrs:{prop:"name",label:"商品名称"}}),a("el-table-column",{attrs:{prop:"price",label:"商品价格"}}),a("el-table-column",{attrs:{prop:"url",label:"商品链接"}}),a("el-table-column",{attrs:{prop:"live_status",label:"状态"}},[["1"==e.type?a("div",{},[e._v("审核中")]):e._e(),"2"==e.type?a("div",{},[e._v("审核通过")]):e._e(),"3"==e.type?a("div",{},[e._v("审核驳回")]):e._e()]],2),a("el-table-column",{attrs:{fixed:"right",label:"操作","min-width":"100"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("ls-dialog",{staticClass:"m-l-10 inline",attrs:{content:"确定删除直播商品："+t.row.name},on:{confirm:function(a){return e.onDelete(t.row)}}},[a("el-button",{attrs:{slot:"trigger",type:"text",size:"small"},slot:"trigger"},[e._v("删除")])],1)]}}])})],1)],1),a("div",{staticClass:"flex row-right m-t-16 row-right"},[a("ls-pagination",{on:{change:function(t){return e.$emit("refresh")}},model:{value:e.pager,callback:function(t){e.pager=t},expression:"pager"}})],1)])}),[],!1,null,"13ec0758",null).exports;let v=class extends o.e{constructor(){super(...arguments),this.pager=new i.a,this.form={type:"1"},this.extend={all:0,wait:0,ing:0,end:0}}addLBGoods(){this.$router.push({path:"/live_broadcast/add_broadcast_goods",query:{mode:l.f.ADD}})}synchronizationLBGoods(){this.getList()}onDelete(e){Object(n.b)({goods_id:e.goods_id}).then(()=>{this.getList()}).catch(()=>{})}getList(){this.pager.request({callback:n.c,params:{...this.form}}).catch(()=>{this.$message.error("数据请求失败，刷新重载")})}created(){this.getList()}};v=Object(s.a)([Object(o.a)({components:{GoodsPane:g}})],v);var b=v,m=(a("424d"),Object(u.a)(b,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"live-broadcast-goods"},[a("div",{staticClass:"ls-card"},[a("el-alert",{staticClass:"xxl",attrs:{type:"info",closable:!1,"show-icon":""}},[a("template",{slot:"title"},[a("div",{staticClass:"iconSize"},[e._v("温馨提示：")]),a("div",{staticClass:"iconSize"},[e._v("1.同步商品库每天最多可同步10000次，请合理使用同步次数；")]),a("div",{staticClass:"iconSize"},[e._v("2.直播商品每天最多可添加500次，删除商品每天最多可删除1000次。")])])],2)],1),a("div",{staticClass:"ls-card m-t-16"},[a("el-tabs",{directives:[{name:"loading",rawName:"v-loading",value:e.pager.loading,expression:"pager.loading"}],on:{"tab-click":e.getList},model:{value:e.form.type,callback:function(t){e.$set(e.form,"type",t)},expression:"form.type"}},[a("el-tab-pane",{attrs:{label:"审核中",name:"1"}},[a("goods-pane",{attrs:{pager:e.pager,type:e.form.type},on:{refresh:e.getList,onDel:e.onDelete,add:e.addLBGoods,synchronization:e.synchronizationLBGoods},model:{value:e.pager.lists,callback:function(t){e.$set(e.pager,"lists",t)},expression:"pager.lists"}})],1),a("el-tab-pane",{attrs:{label:"审核通过",name:"2"}},[a("goods-pane",{attrs:{pager:e.pager,type:e.form.type},on:{refresh:e.getList,onDel:e.onDelete,add:e.addLBGoods,synchronization:e.synchronizationLBGoods},model:{value:e.pager.lists,callback:function(t){e.$set(e.pager,"lists",t)},expression:"pager.lists"}})],1),a("el-tab-pane",{attrs:{lazy:"",label:"审核驳回",name:"3"}},[a("goods-pane",{attrs:{pager:e.pager,type:e.form.type},on:{refresh:e.getList,onDel:e.onDelete,add:e.addLBGoods,synchronization:e.synchronizationLBGoods},model:{value:e.pager.lists,callback:function(t){e.$set(e.pager,"lists",t)},expression:"pager.lists"}})],1)],1)],1)])}),[],!1,null,"6a4a26f3",null));t.default=m.exports}}]);