(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-653969e2"],{"0eba":function(t,e,s){"use strict";s("4cfe")},"14c6":function(t,e,s){"use strict";s.d(e,"n",(function(){return o})),s.d(e,"x",(function(){return i})),s.d(e,"y",(function(){return l})),s.d(e,"u",(function(){return n})),s.d(e,"C",(function(){return c})),s.d(e,"v",(function(){return r})),s.d(e,"B",(function(){return d})),s.d(e,"w",(function(){return u})),s.d(e,"z",(function(){return p})),s.d(e,"A",(function(){return g})),s.d(e,"a",(function(){return b})),s.d(e,"e",(function(){return f})),s.d(e,"f",(function(){return m})),s.d(e,"b",(function(){return h})),s.d(e,"c",(function(){return v})),s.d(e,"d",(function(){return y})),s.d(e,"E",(function(){return _})),s.d(e,"H",(function(){return O})),s.d(e,"F",(function(){return j})),s.d(e,"G",(function(){return x})),s.d(e,"D",(function(){return k})),s.d(e,"L",(function(){return w})),s.d(e,"I",(function(){return C})),s.d(e,"J",(function(){return D})),s.d(e,"K",(function(){return S})),s.d(e,"g",(function(){return $})),s.d(e,"l",(function(){return L})),s.d(e,"h",(function(){return E})),s.d(e,"m",(function(){return z})),s.d(e,"i",(function(){return G})),s.d(e,"k",(function(){return I})),s.d(e,"j",(function(){return P})),s.d(e,"M",(function(){return A})),s.d(e,"P",(function(){return V})),s.d(e,"N",(function(){return J})),s.d(e,"O",(function(){return N})),s.d(e,"r",(function(){return T})),s.d(e,"q",(function(){return q})),s.d(e,"s",(function(){return F})),s.d(e,"t",(function(){return B})),s.d(e,"p",(function(){return H})),s.d(e,"o",(function(){return K}));var a=s("f175");const o=t=>a.a.post("/goods.goods/add",t),i=t=>a.a.post("/goods.goods/edit",t),l=t=>a.a.get("/goods.goods/lists",{params:t}),n=t=>a.a.get("/goods.goods/commonLists",{params:t}),c=t=>a.a.post("/goods.goods/status",t),r=t=>a.a.post("/goods.goods/del",t),d=t=>a.a.post("/goods.goods/sort",t),u=t=>a.a.get("/goods.goods/detail",{params:{id:t}}),p=t=>a.a.get("/goods.goods/otherList",{params:t}),g=t=>a.a.post("goods.goods/rename ",t),b=t=>a.a.post("/goods.goods_brand/add",t),f=t=>a.a.get("/goods.goods_brand/lists",{params:t}),m=t=>a.a.post("/goods.goods_brand/status",t),h=t=>a.a.post("/goods.goods_brand/del",t),v=t=>a.a.get("/goods.goods_brand/detail",{params:{id:t}}),y=t=>a.a.post("/goods.goods_brand/edit",t),_=t=>a.a.post("/goods.goods_supplier_category/add",t),O=t=>a.a.get("goods.goods_supplier_category/lists",{params:t}),j=t=>a.a.post("goods.goods_supplier_category/del",{id:t}),x=t=>a.a.post("goods.goods_supplier_category/edit",t),k=t=>a.a.post("/goods.goods_supplier/add",t),w=t=>a.a.get("/goods.goods_supplier/lists",{params:t}),C=t=>a.a.post("goods.goods_supplier/del",{id:t}),D=t=>a.a.get("/goods.goods_supplier/detail",{params:{id:t}}),S=t=>a.a.post("/goods.goods_supplier/edit",t),$=t=>a.a.post("/goods.goods_category/add",t),L=t=>a.a.get("/goods.goods_category/lists",{params:t}),E=t=>a.a.get("/goods.goods_category/commonLists",{params:t}),z=t=>a.a.post("/goods.goods_category/status",t),G=t=>a.a.post("goods.goods_category/del",{id:t}),I=t=>a.a.post("/goods.goods_category/edit",t),P=t=>a.a.get("/goods.goods_category/detail",{params:{id:t}}),A=t=>a.a.post("/goods.goods_unit/add",t),V=t=>a.a.get("/goods.goods_unit/lists",{params:t}),J=t=>a.a.post("goods.goods_unit/del",{id:t}),N=t=>a.a.post("/goods.goods_unit/edit",t),T=t=>a.a.get("goods.goods_comment/lists",{params:t}),q=t=>a.a.post("goods.goods_comment/del",t),F=t=>a.a.post("goods.goods_comment/reply",t),B=t=>a.a.post("/goods.goods_comment/status",t),H=t=>a.a.get("goods.goods_comment_assistant/lists",{params:t}),K=t=>a.a.post("goods.goods_comment_assistant/add",t)},"4cfe":function(t,e,s){},dcb0:function(t,e,s){"use strict";s("caad");var a=s("9ab4"),o=s("1b40"),i=s("3c50"),l=s("6ddb"),n=s("14c6");let c=class extends o.e{constructor(){super(...arguments),this.name="",this.pager=new l.a,this.selectedObj={}}visibleChange(t){t.val&&this.getList()}get selectData(){return this.value}set selectData(t){this.$emit("input",t)}get selectItem(){return t=>"single"==this.type?this.selectData.id==t.id:this.selectData.some(e=>e.id==t.id)}get selectAll(){const{pager:{lists:t}}=this,e=this.selectData.map(t=>t.id);return!!t.length&&t.every(t=>e.includes(t.id))}set selectAll(t){const{pager:{lists:e}}=this;if(t)for(let t=0;t<e.length;t++){const s=e[t];if(!this.selectData.map(t=>t.id).includes(s.id)){if(this.checkLength())return;this.selectData.push(s)}}else e.forEach(t=>{this.setSelectData(t)})}getList(t){let e=void 0;this.showVirtualGoods&&(e=0),t&&(this.pager.page=t),this.pager.request({callback:n.u,params:{name:this.name,is_spec:this.isSpec,...this.params,type:e}}).then(t=>{})}handleSelect(t,e){if("single"==this.type)this.selectData=t?e:{};else if(t){if(this.checkLength())return;this.selectData.push(e)}else this.setSelectData(e)}setSelectData(t){const e=this.selectData.findIndex(e=>e.id==t.id);-1!=e&&this.selectData.splice(e,1)}checkLength(){return this.selectData.length>=this.limit&&(this.$message({message:`选多选择${this.limit}件商品`,type:"warning"}),!0)}};Object(a.a)([Object(o.b)("visible")],c.prototype,"visible",void 0),Object(a.a)([Object(o.c)()],c.prototype,"value",void 0),Object(a.a)([Object(o.c)()],c.prototype,"goods",void 0),Object(a.a)([Object(o.c)({default:"single"})],c.prototype,"type",void 0),Object(a.a)([Object(o.c)()],c.prototype,"limit",void 0),Object(a.a)([Object(o.c)({default:!1})],c.prototype,"isSpec",void 0),Object(a.a)([Object(o.c)({default:()=>{}})],c.prototype,"params",void 0),Object(a.a)([Object(o.c)({default:!1})],c.prototype,"showVirtualGoods",void 0),Object(a.a)([Object(o.f)("visible",{deep:!0,immediate:!0})],c.prototype,"visibleChange",null),c=Object(a.a)([Object(o.a)({components:{LsPagination:i.a}})],c);var r=c,d=s("2877"),u=Object(d.a)(r,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{directives:[{name:"loading",rawName:"v-loading",value:t.pager.loading,expression:"pager.loading"}],staticClass:"detail"},[s("div",{staticClass:"flex row m-b-10 m-l-10"},[s("div",{staticClass:"m-r-20"},["multiple"==t.type?s("el-checkbox",{model:{value:t.selectAll,callback:function(e){t.selectAll=e},expression:"selectAll"}},[t._v("全选")]):t._e()],1),s("div",{staticClass:"flex"},[s("div",{staticClass:"m-r-10"},[t._v("商品搜索")]),s("el-input",{staticStyle:{width:"220px"},attrs:{size:"small",placeholder:"请输入商品名称"},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.getList(1)}},model:{value:t.name,callback:function(e){t.name=e},expression:"name"}},[s("el-button",{attrs:{slot:"append",icon:"el-icon-search"},on:{click:function(e){return t.getList(1)}},slot:"append"})],1)],1)]),s("el-table",{ref:"table",staticStyle:{width:"100%"},attrs:{data:t.pager.lists,height:"370px",size:"mini","row-key":"id"}},["single"==t.type?s("el-table-column",{attrs:{width:"45"},scopedSlots:t._u([{key:"default",fn:function(e){var a=e.row;return[s("el-checkbox",{attrs:{value:t.selectItem(a)},on:{change:function(e){return t.handleSelect(e,a)}}})]}}],null,!1,3310675202)}):t._e(),"multiple"==t.type?s("el-table-column",{attrs:{width:"45"},scopedSlots:t._u([{key:"default",fn:function(e){var a=e.row;return[s("el-checkbox",{attrs:{value:t.selectItem(a)},on:{change:function(e){return t.handleSelect(e,a)}}})]}}],null,!1,3310675202)}):t._e(),s("el-table-column",{attrs:{label:"商品信息","min-width":"180"},scopedSlots:t._u([{key:"default",fn:function(e){return[s("div",{staticClass:"flex"},[s("el-image",{staticClass:"flex-none",staticStyle:{width:"48px",height:"48px"},attrs:{src:e.row.image,fit:"cover"}}),s("div",{staticClass:"goods-info m-l-8"},[s("div",{staticClass:"line-2"},[t._v(t._s(e.row.name))]),s("div",[2==e.row.spec_type?s("el-tag",{attrs:{size:"mini"}},[t._v("多规格")]):t._e()],1)])],1)]}}])}),s("el-table-column",{attrs:{prop:"price",label:"价格",width:"200"}}),s("el-table-column",{attrs:{prop:"total_stock",label:"库存",width:"80"}})],1),s("div",{staticClass:"flex row-right m-t-20"},[s("ls-pagination",{on:{change:function(e){return t.getList()}},model:{value:t.pager,callback:function(e){t.pager=e},expression:"pager"}})],1)],1)}),[],!1,null,"b5e42ec8",null);e.a=u.exports},f50c:function(t,e,s){"use strict";var a=s("9ab4"),o=s("1b40"),i=s("0a6d"),l=s("dcb0");let n=class extends o.e{constructor(){super(...arguments),this.visible=!1,this.goods=[]}valueChange(t){this.goods=JSON.parse(JSON.stringify(t))}handleConfirm(){this.$emit("input",this.goods)}};Object(a.a)([Object(o.c)({default:()=>[]})],n.prototype,"value",void 0),Object(a.a)([Object(o.c)({default:"multiple"})],n.prototype,"type",void 0),Object(a.a)([Object(o.c)({default:!1})],n.prototype,"disabled",void 0),Object(a.a)([Object(o.c)({default:50})],n.prototype,"limit",void 0),Object(a.a)([Object(o.c)({default:!1})],n.prototype,"isSpec",void 0),Object(a.a)([Object(o.c)({default:()=>{}})],n.prototype,"params",void 0),Object(a.a)([Object(o.c)({default:!1})],n.prototype,"showVirtualGoods",void 0),Object(a.a)([Object(o.f)("value",{immediate:!0})],n.prototype,"valueChange",null),n=Object(a.a)([Object(o.a)({components:{LsDialog:i.a,Detail:l.a}})],n);var c=n,r=s("2877"),d=Object(r.a)(c,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("ls-dialog",{ref:"dialog",staticClass:"goods-select",attrs:{title:"选择商品",width:"900px",top:"20vh",disabled:t.disabled},on:{confirm:t.handleConfirm}},[s("div",{staticClass:"goods-select__trigger",attrs:{slot:"trigger"},slot:"trigger"},[t._t("trigger",(function(){return[s("el-button",{attrs:{disabled:t.disabled,size:"mini",type:"primary"}},[t._v("选择商品")])]}))],2),s("div",{staticClass:"p-l-20 p-r-20"},[s("detail",{attrs:{goods:t.value,limit:t.limit,type:t.type,params:t.params,"show-virtual-goods":t.showVirtualGoods},model:{value:t.goods,callback:function(e){t.goods=e},expression:"goods"}})],1)])}),[],!1,null,"3025e3ae",null).exports,u=s("b76a"),p=s.n(u);let g=class extends o.e{get list(){return this.value}set list(t){this.$emit("input",t)}handleDelete(t){this.list.splice(t,1)}};Object(a.a)([Object(o.c)({default:()=>[]})],g.prototype,"value",void 0),g=Object(a.a)([Object(o.a)({components:{Draggable:p.a}})],g);var b=g,f=(s("0eba"),Object(r.a)(b,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"list"},[s("div",{staticClass:"goods-lists"},[s("draggable",{staticClass:"flex flex-wrap",attrs:{animation:"300"},model:{value:t.list,callback:function(e){t.list=e},expression:"list"}},t._l(t.list,(function(e,a){return s("div",{key:a,staticClass:"goods-item ls-del-wrap"},[s("el-image",{staticStyle:{width:"100%",height:"100%"},attrs:{fit:"cover",src:e.image}}),s("i",{staticClass:"el-icon-close ls-icon-del",on:{click:function(e){return t.handleDelete(a)}}})],1)})),0)],1)])}),[],!1,null,"30736e66",null).exports),m=s("d455");let h=class extends o.e{constructor(){super(...arguments),this.price=[]}valueChange(t){this.initPrice()}initPrice(){this.price=this.value.map(t=>{const e={};return this.extend.price.forEach(s=>{e[s.key]=t[s.key]||""}),e})}handleClose(){this.price=this.price.map(()=>({})),this.$refs.popoverInput.forEach(t=>{t.close()})}handleConfirm(){this.value.forEach((t,e)=>{this.extend.price.forEach(s=>{this.$set(t,s.key,this.price[e][s.key])})})}batchSetting(t,e){this.price.forEach((s,a)=>{this.$set(s,e,t)})}};Object(a.a)([Object(o.c)({default:!1})],h.prototype,"disabled",void 0),Object(a.a)([Object(o.c)({default:()=>[]})],h.prototype,"value",void 0),Object(a.a)([Object(o.c)({default:()=>[]})],h.prototype,"extend",void 0),Object(a.a)([Object(o.f)("value",{immediate:!0})],h.prototype,"valueChange",null),h=Object(a.a)([Object(o.a)({components:{PopoverInput:m.a,LsDialog:i.a}})],h);var v=h,y=Object(r.a)(v,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("ls-dialog",{ref:"dialog",attrs:{width:"800px",top:"20vh",title:"设置"+t.extend.name+"价格"},on:{close:t.handleClose,open:t.initPrice,confirm:t.handleConfirm}},[s("el-button",{attrs:{slot:"trigger",size:"small",disabled:t.disabled},slot:"trigger"},[t._v(t._s("设置"+t.extend.name+"价格"))]),s("div",{staticClass:"spec-table"},[s("div",{staticClass:"m-b-20"},t._l(t.extend.price,(function(e,a){return s("popover-input",{key:a,ref:"popoverInput",refInFor:!0,staticClass:"m-r-10",attrs:{disabled:t.disabled},on:{confirm:function(s){return t.batchSetting(s,e.key)}}},[s("el-button",{attrs:{size:"small",disabled:t.disabled}},[t._v("设置"+t._s(e.title))])],1)})),1),s("u-table",{attrs:{data:t.value,"use-virtual":"",size:"mini",height:"400","row-height":50,"tooltip-effect":"dark",border:!1}},[s("u-table-column",{attrs:{label:"规格名",prop:"spec_value_str"}}),s("u-table-column",{attrs:{label:"原价"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" ￥"+t._s(e.row.sell_price)+" ")]}}])}),s("u-table-column",{attrs:{label:"现有库存",prop:"stock"}}),t._l(t.extend.price,(function(e,a){return s("u-table-column",{key:a,attrs:{label:e.title},scopedSlots:t._u([{key:"default",fn:function(o){return[s("el-input",{key:a,staticClass:"m-r-10 m-t-5",staticStyle:{width:"100px"},attrs:{type:"number",disabled:t.disabled},model:{value:t.price[o.$index][e.key],callback:function(s){t.$set(t.price[o.$index],e.key,s)},expression:"price[scope.$index][item.key]"}})]}}],null,!0)})}))],2)],1)],1)}),[],!1,null,"9961c2c8",null).exports;let _=class extends o.e{get list(){return this.value}set list(t){this.$emit("input",t)}get extendPrice(){return this.extend.price||[]}handleDelete(t){this.list.splice(t,1)}};Object(a.a)([Object(o.c)({default:!1})],_.prototype,"disabled",void 0),Object(a.a)([Object(o.c)({default:()=>[]})],_.prototype,"value",void 0),Object(a.a)([Object(o.c)({default:!1})],_.prototype,"isSpec",void 0),Object(a.a)([Object(o.c)({default:()=>({})})],_.prototype,"extend",void 0),_=Object(a.a)([Object(o.a)({components:{LsDialog:i.a,SepcTable:y}})],_);var O=_,j=Object(r.a)(O,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"table m-t-20"},[t.list.length?s("el-table",{ref:"paneTable",attrs:{size:"mini",data:t.list,"max-height":"500"}},[s("el-table-column",{attrs:{label:"商品信息","min-width":"200"},scopedSlots:t._u([{key:"default",fn:function(e){return[s("div",{staticClass:"flex"},[s("el-image",{staticClass:"flex-none",staticStyle:{width:"58px",height:"58px"},attrs:{src:e.row.image,fit:"cover"}}),s("div",{staticClass:"goods-info m-l-8"},[s("div",{staticClass:"line-2"},[t._v(t._s(e.row.name))]),2==e.row.spec_type?s("el-tag",{attrs:{size:"mini"}},[t._v("多规格")]):t._e()],1)],1)]}}],null,!1,1517623647)}),s("el-table-column",{attrs:{label:"价格","min-width":"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" ￥"+t._s(e.row.sell_price)+" ")]}}],null,!1,2489262044)}),t.extend.name?s("el-table-column",{attrs:{label:t.extend.name+"设置","min-width":"200"},scopedSlots:t._u([{key:"default",fn:function(e){return[2==e.row.spec_type?s("sepc-table",{attrs:{extend:t.extend,disabled:t.disabled},model:{value:e.row.item,callback:function(s){t.$set(e.row,"item",s)},expression:"scope.row.item"}}):t._l(t.extendPrice,(function(a,o){return s("el-input",{key:o,staticClass:"m-r-10 m-t-5",staticStyle:{width:"150px"},attrs:{type:"number",placeholder:a.title,disabled:t.disabled},model:{value:e.row.item[0][a.key],callback:function(s){t.$set(e.row.item[0],a.key,s)},expression:"scope.row.item[0][item.key]"}})}))]}}],null,!1,3783708423)}):t._e(),s("el-table-column",{attrs:{label:"操作",width:"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[s("el-button",{attrs:{type:"text",size:"small",disabled:t.disabled},on:{click:function(s){return t.handleDelete(e.$index)}}},[t._v("移除")])]}}],null,!1,3770115447)})],1):t._e()],1)}),[],!1,null,"f96c6dda",null).exports;let x=class extends o.e{get selectData(){return this.value}set selectData(t){this.$emit("input",t),this.$emit("change",t)}};Object(a.a)([Object(o.c)({default:()=>[]})],x.prototype,"value",void 0),Object(a.a)([Object(o.c)({default:"multiple"})],x.prototype,"type",void 0),Object(a.a)([Object(o.c)({default:!1})],x.prototype,"disabled",void 0),Object(a.a)([Object(o.c)({default:50})],x.prototype,"limit",void 0),Object(a.a)([Object(o.c)({default:"list"})],x.prototype,"mode",void 0),Object(a.a)([Object(o.c)({default:!1})],x.prototype,"isSpec",void 0),Object(a.a)([Object(o.c)()],x.prototype,"extend",void 0),Object(a.a)([Object(o.c)({default:()=>{}})],x.prototype,"params",void 0),Object(a.a)([Object(o.c)({default:!1})],x.prototype,"showVirtualGoods",void 0),x=Object(a.a)([Object(o.a)({components:{GDialog:d,List:f,GTable:j}})],x);var k=x,w=Object(r.a)(k,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"select"},[s("div",{staticClass:"flex"},[s("g-dialog",{attrs:{type:t.type,disabled:t.disabled,limit:t.limit,"is-spec":t.isSpec,params:t.params,"show-virtual-goods":t.showVirtualGoods},model:{value:t.selectData,callback:function(e){t.selectData=e},expression:"selectData"}},[t._t("default")],2),s("div",{staticClass:"m-r-20"},[s("span",{staticClass:"muted m-l-20"},[t._v("最多添加"+t._s(t.limit)+"件商品")])]),t.selectData.length?s("div",{staticClass:"clear"},[s("el-button",{attrs:{size:"small",type:"text",disabled:t.disabled},on:{click:function(e){t.selectData=[]}}},[t._v("清空")])],1):t._e()],1),s("div",{staticClass:"select-content"},["list"==t.mode?s("list",{attrs:{disabled:t.disabled},model:{value:t.selectData,callback:function(e){t.selectData=e},expression:"selectData"}}):t._e(),"table"==t.mode?s("g-table",{attrs:{"is-spec":t.isSpec,extend:t.extend,disabled:t.disabled},model:{value:t.selectData,callback:function(e){t.selectData=e},expression:"selectData"}}):t._e()],1)])}),[],!1,null,"766e47f8",null);e.a=w.exports}}]);