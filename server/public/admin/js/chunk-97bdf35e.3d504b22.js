(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-97bdf35e"],{4072:function(t,i,r){"use strict";r.d(i,"f",(function(){return n})),r.d(i,"z",(function(){return s})),r.d(i,"A",(function(){return o})),r.d(i,"m",(function(){return a})),r.d(i,"l",(function(){return u})),r.d(i,"k",(function(){return d})),r.d(i,"n",(function(){return l})),r.d(i,"u",(function(){return b})),r.d(i,"t",(function(){return c})),r.d(i,"c",(function(){return p})),r.d(i,"d",(function(){return f})),r.d(i,"e",(function(){return g})),r.d(i,"v",(function(){return m})),r.d(i,"y",(function(){return _})),r.d(i,"b",(function(){return w})),r.d(i,"a",(function(){return v})),r.d(i,"g",(function(){return h})),r.d(i,"i",(function(){return y})),r.d(i,"j",(function(){return k})),r.d(i,"s",(function(){return C})),r.d(i,"q",(function(){return D})),r.d(i,"r",(function(){return x})),r.d(i,"o",(function(){return j})),r.d(i,"p",(function(){return z})),r.d(i,"w",(function(){return L})),r.d(i,"h",(function(){return S})),r.d(i,"x",(function(){return q}));var e=r("f175");const n=()=>e.a.get("/distribution.distribution_data/dataCenter"),s=t=>e.a.get("/distribution.distribution_data/topMemberEarnings",{params:t}),o=t=>e.a.get("/distribution.distribution_data/topMemberFans",{params:t}),a=t=>e.a.get("/distribution.distribution_goods/lists",{params:t}),u=t=>e.a.post("/distribution.distribution_goods/join",t),d=t=>e.a.get("/distribution.distribution_goods/detail",{params:t}),l=t=>e.a.post("/distribution.distribution_goods/set",t),b=t=>e.a.get("/distribution.distribution_member/lists",{params:t}),c=t=>e.a.get("/distribution.distribution_apply/detail",{params:t}),p=t=>e.a.get("/distribution.distribution_apply/lists",{params:t}),f=t=>e.a.post("/distribution.distribution_apply/pass",t),g=t=>e.a.post("/distribution.distribution_apply/refuse",t),m=t=>e.a.post("/distribution.distribution_member/open",t),_=t=>e.a.post("/distribution.distribution_member/freeze",t),w=t=>e.a.get("/distribution.distribution_member/adjustLevelInfo",{params:t}),v=t=>e.a.post("/distribution.distribution_member/adjustLevel",t),h=t=>e.a.get("/distribution.distribution_member/detail",{params:t}),y=t=>e.a.get("/distribution.distribution_member/getFans",{params:t}),k=t=>e.a.get("/distribution.distribution_member/getFansLists",{params:t}),C=t=>e.a.get("/distribution.distribution_level/lists",t),D=t=>e.a.get("/distribution.distribution_level/detail",{params:t}),x=t=>e.a.post("/distribution.distribution_level/edit",t),j=t=>e.a.post("/distribution.distribution_level/add",t),z=t=>e.a.post("/distribution.distribution_level/delete",t),L=t=>e.a.get("/distribution.distribution_order_goods/lists",{params:t}),S=t=>e.a.get("/distribution.distribution_config/getConfig",{params:t}),q=t=>e.a.post("/distribution.distribution_config/setConfig",t)},"6de2":function(t,i,r){},b703:function(t,i,r){"use strict";r("6de2")},e2ce:function(t,i,r){"use strict";r.r(i);var e=r("9ab4"),n=r("1b40"),s=r("3c50"),o=r("0a6d"),a=r("6ddb"),u=r("4072");let d=class extends n.e{constructor(){super(...arguments),this.pager=new a.a}getDistributionData(){this.pager.request({callback:u.s,params:{}})}Del(t){Object(u.p)({id:t}).then(()=>{this.getDistributionData()})}created(){this.getDistributionData()}};d=Object(e.a)([Object(n.a)({components:{LsPagination:s.a,LsDialog:o.a}})],d);var l=d,b=(r("b703"),r("2877")),c=Object(b.a)(l,(function(){var t=this,i=t.$createElement,r=t._self._c||i;return r("div",{staticClass:"ls-goods"},[r("div",{staticClass:"ls-goods__top ls-card"},[r("el-alert",{attrs:{title:"温馨提示：1.管理分销商的等级，系统默认等级不能删除；2.删除分销等级时，会重新调整分销商等级为系统默认等级，请谨慎操作。",type:"info","show-icon":"",closable:!1}})],1),r("div",{staticClass:"m-t-24 ls-card"},[r("div",{staticClass:"m-b-24"},[r("el-button",{attrs:{type:"primary",size:"mini"},on:{click:function(i){return t.$router.push("/distribution/distribution_grade_edit?mode=add&flag=0")}}},[t._v("新增分销等级 ")])],1),r("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.pager.loading,expression:"pager.loading"}],ref:"paneTable",staticStyle:{width:"100%"},attrs:{data:t.pager.lists,size:"mini"}},[r("el-table-column",{attrs:{prop:"weights_desc",label:"等级级别",width:"180"}}),r("el-table-column",{attrs:{prop:"name",label:"等级名称",width:"180"}}),r("el-table-column",{attrs:{prop:"first_ratio",label:"自购佣金比例",width:"180"},scopedSlots:t._u([{key:"default",fn:function(i){return[r("span",[t._v(t._s(i.row.self_ratio)+"%")])]}}])}),r("el-table-column",{attrs:{prop:"first_ratio",label:"一级佣金比例",width:"180"},scopedSlots:t._u([{key:"default",fn:function(i){return[r("span",[t._v(t._s(i.row.first_ratio)+"%")])]}}])}),r("el-table-column",{attrs:{prop:"second_ratio",label:"二级佣金比例",width:"180"},scopedSlots:t._u([{key:"default",fn:function(i){return[r("span",[t._v(t._s(i.row.second_ratio)+"%")])]}}])}),r("el-table-column",{attrs:{prop:"member_num",label:"分销商数",width:"180"}}),r("el-table-column",{attrs:{fixed:"right",label:"操作","min-width":"180"},scopedSlots:t._u([{key:"default",fn:function(i){return[r("el-button",{staticClass:"m-r-10",attrs:{slot:"trigger",type:"text",size:"mini"},on:{click:function(r){return t.$router.push({path:"/distribution/distribution_grade_edit",query:{id:i.row.id,mode:"edit",disabled:!0,flag:i.row.is_default}})}},slot:"trigger"},[t._v("详情")]),r("el-button",{staticClass:"m-r-10",attrs:{slot:"trigger",type:"text",size:"mini"},on:{click:function(r){return t.$router.push({path:"/distribution/distribution_grade_edit",query:{id:i.row.id,mode:"edit",flag:i.row.is_default}})}},slot:"trigger"},[t._v("编辑")]),i.row.is_default?t._e():r("ls-dialog",{staticClass:"inline m-l-10",attrs:{title:"删除等级",content:"确定将此："+i.row.name+"删除？"},on:{confirm:function(r){return t.Del([i.row.id])}}},[r("el-button",{attrs:{slot:"trigger",type:"text",size:"mini"},slot:"trigger"},[t._v("删除")])],1)]}}])})],1),r("div",{staticClass:"m-t-24 flex row-right"},[r("ls-pagination",{on:{change:function(i){return t.getDistributionData()}},model:{value:t.pager,callback:function(i){t.pager=i},expression:"pager"}})],1)],1)])}),[],!1,null,"79d4b414",null);i.default=c.exports}}]);