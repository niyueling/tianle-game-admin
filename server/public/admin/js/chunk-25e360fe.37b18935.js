(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-25e360fe"],{"025b":function(t,e,s){},"0516":function(t,e,s){},1248:function(t,e,s){"use strict";var a=s("9ab4"),o=s("1b40");let l=class extends o.e{constructor(){super(...arguments),this.active="content"}};Object(a.a)([Object(o.c)()],l.prototype,"title",void 0),l=Object(a.a)([Object(o.a)({components:{}})],l);var i=l,n=(s("6948"),s("2877")),c=Object(n.a)(i,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"attribute-tabs"},[s("div",{staticClass:"flex attribute-header"},[s("div",{staticClass:"attribute-title lg weight-500 flex-1"},[t._v(t._s(t.title))]),t.$slots.default?t._e():s("div",{staticClass:"tabs-header flex"},[s("div",{staticClass:"tabs-header__item",class:{active:"content"==t.active},on:{click:function(e){t.active="content"}}},[s("span",[t._v("内容")])]),s("div",{staticClass:"tabs-header__item",class:{active:"styles"==t.active},on:{click:function(e){t.active="styles"}}},[s("span",[t._v("样式")])])])]),t.$slots.default?s("div",{staticClass:"attribute-content"},[t._t("default")],2):s("div",{staticClass:"tabs"},[s("div",{staticClass:"tabs-content"},[s("div",{directives:[{name:"show",rawName:"v-show",value:"content"==t.active,expression:"active == 'content'"}]},[t._t("content")],2),s("div",{directives:[{name:"show",rawName:"v-show",value:"styles"==t.active,expression:"active == 'styles'"}]},[t._t("styles")],2)])])])}),[],!1,null,"5fc3ecec",null);e.a=c.exports},"14c6":function(t,e,s){"use strict";s.d(e,"n",(function(){return o})),s.d(e,"x",(function(){return l})),s.d(e,"y",(function(){return i})),s.d(e,"u",(function(){return n})),s.d(e,"C",(function(){return c})),s.d(e,"v",(function(){return r})),s.d(e,"B",(function(){return d})),s.d(e,"w",(function(){return u})),s.d(e,"z",(function(){return b})),s.d(e,"A",(function(){return p})),s.d(e,"a",(function(){return f})),s.d(e,"e",(function(){return g})),s.d(e,"f",(function(){return v})),s.d(e,"b",(function(){return m})),s.d(e,"c",(function(){return h})),s.d(e,"d",(function(){return _})),s.d(e,"E",(function(){return y})),s.d(e,"H",(function(){return x})),s.d(e,"F",(function(){return O})),s.d(e,"G",(function(){return j})),s.d(e,"D",(function(){return C})),s.d(e,"L",(function(){return k})),s.d(e,"I",(function(){return w})),s.d(e,"J",(function(){return $})),s.d(e,"K",(function(){return S})),s.d(e,"g",(function(){return F})),s.d(e,"l",(function(){return D})),s.d(e,"h",(function(){return z})),s.d(e,"m",(function(){return L})),s.d(e,"i",(function(){return E})),s.d(e,"k",(function(){return A})),s.d(e,"j",(function(){return I})),s.d(e,"M",(function(){return T})),s.d(e,"P",(function(){return M})),s.d(e,"N",(function(){return N})),s.d(e,"O",(function(){return G})),s.d(e,"r",(function(){return J})),s.d(e,"q",(function(){return q})),s.d(e,"s",(function(){return P})),s.d(e,"t",(function(){return V})),s.d(e,"p",(function(){return B})),s.d(e,"o",(function(){return H}));var a=s("f175");const o=t=>a.a.post("/goods.goods/add",t),l=t=>a.a.post("/goods.goods/edit",t),i=t=>a.a.get("/goods.goods/lists",{params:t}),n=t=>a.a.get("/goods.goods/commonLists",{params:t}),c=t=>a.a.post("/goods.goods/status",t),r=t=>a.a.post("/goods.goods/del",t),d=t=>a.a.post("/goods.goods/sort",t),u=t=>a.a.get("/goods.goods/detail",{params:{id:t}}),b=t=>a.a.get("/goods.goods/otherList",{params:t}),p=t=>a.a.post("goods.goods/rename ",t),f=t=>a.a.post("/goods.goods_brand/add",t),g=t=>a.a.get("/goods.goods_brand/lists",{params:t}),v=t=>a.a.post("/goods.goods_brand/status",t),m=t=>a.a.post("/goods.goods_brand/del",t),h=t=>a.a.get("/goods.goods_brand/detail",{params:{id:t}}),_=t=>a.a.post("/goods.goods_brand/edit",t),y=t=>a.a.post("/goods.goods_supplier_category/add",t),x=t=>a.a.get("goods.goods_supplier_category/lists",{params:t}),O=t=>a.a.post("goods.goods_supplier_category/del",{id:t}),j=t=>a.a.post("goods.goods_supplier_category/edit",t),C=t=>a.a.post("/goods.goods_supplier/add",t),k=t=>a.a.get("/goods.goods_supplier/lists",{params:t}),w=t=>a.a.post("goods.goods_supplier/del",{id:t}),$=t=>a.a.get("/goods.goods_supplier/detail",{params:{id:t}}),S=t=>a.a.post("/goods.goods_supplier/edit",t),F=t=>a.a.post("/goods.goods_category/add",t),D=t=>a.a.get("/goods.goods_category/lists",{params:t}),z=t=>a.a.get("/goods.goods_category/commonLists",{params:t}),L=t=>a.a.post("/goods.goods_category/status",t),E=t=>a.a.post("goods.goods_category/del",{id:t}),A=t=>a.a.post("/goods.goods_category/edit",t),I=t=>a.a.get("/goods.goods_category/detail",{params:{id:t}}),T=t=>a.a.post("/goods.goods_unit/add",t),M=t=>a.a.get("/goods.goods_unit/lists",{params:t}),N=t=>a.a.post("goods.goods_unit/del",{id:t}),G=t=>a.a.post("/goods.goods_unit/edit",t),J=t=>a.a.get("goods.goods_comment/lists",{params:t}),q=t=>a.a.post("goods.goods_comment/del",t),P=t=>a.a.post("goods.goods_comment/reply",t),V=t=>a.a.post("/goods.goods_comment/status",t),B=t=>a.a.get("goods.goods_comment_assistant/lists",{params:t}),H=t=>a.a.post("goods.goods_comment_assistant/add",t)},"1d71":function(t,e,s){"use strict";s("da0d")},"1e71":function(t,e,s){"use strict";s("025b")},"32d5":function(t,e,s){},"4b75":function(t,e,s){"use strict";s("0516")},"55e0":function(t,e,s){"use strict";s("32d5")},"66bb":function(t,e,s){"use strict";var a=s("9ab4"),o=s("1b40");let l=class extends o.e{};Object(a.a)([Object(o.c)()],l.prototype,"title",void 0),Object(a.a)([Object(o.c)()],l.prototype,"desc",void 0),l=Object(a.a)([o.a],l);var i=l,n=(s("b307"),s("2877")),c=Object(n.a)(i,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"attribute-item"},[t.title?s("div",{staticClass:"title flex nr"},[t._v(" "+t._s(t.title)+" "),s("span",{staticClass:"muted xs flex-1 m-l-8"},[t._v(t._s(t.desc))]),t._t("right")],2):t._e(),s("div",{staticClass:"content"},[t._t("default")],2),s("div",{staticClass:"footer"})])}),[],!1,null,"a332dc1a",null);e.a=c.exports},6948:function(t,e,s){"use strict";s("c322")},"6ae0":function(t,e,s){"use strict";var a=s("9ab4"),o=s("1b40");let l=class extends o.e{get select(){return this.value}set select(t){this.$emit("input",t)}};Object(a.a)([Object(o.c)({default:()=>[]})],l.prototype,"data",void 0),Object(a.a)([Object(o.c)()],l.prototype,"value",void 0),l=Object(a.a)([o.a],l);var i=l,n=(s("1d71"),s("2877")),c=Object(n.a)(i,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"style-chose"},[s("div",{staticClass:"chose-list flex flex-wrap"},t._l(t.data,(function(e,a){return s("div",{key:a,staticClass:"chose-item",class:{active:t.select==e.value},on:{click:function(s){t.select=e.value}}},[s("div",{staticClass:"chose-content"},[s("span",{style:{"padding-left":e.icon?"8px":"0"}},[t._v(t._s(e.name))])])])})),0)])}),[],!1,null,"7e8c0595",null);e.a=c.exports},b307:function(t,e,s){"use strict";s("c65a")},bec0:function(t,e,s){"use strict";s.r(e);var a=s("9ab4"),o=s("1b40"),l=s("1248"),i=s("ea05"),n=s("6ae0"),c=s("d6d3"),r=s("66bb"),d=s("2b1d"),u=s("b3ad"),b=s("b76a"),p=s.n(b);let f=class extends o.e{onAdd(){if(this.content.data.length>5)return this.$message.warning("最多五个导航");this.content.data.push({icon:"",select_icon:"",name:"导航",link:{}})}onDelete(t){const{data:e}=this.content;return 0==t?this.$message.warning("首页导航不能删除"):e.length<=2?this.$message.warning("最少两个导航"):void this.content.data.splice(t,1)}onMove(t){return 0!=t.relatedContext.index}};Object(a.a)([Object(o.c)()],f.prototype,"content",void 0),Object(a.a)([Object(o.c)()],f.prototype,"styles",void 0),f=Object(a.a)([Object(o.a)({components:{AttributeTabs:l.a,ColorSelect:i.a,StyleChose:n.a,Slider:c.a,AttributeItem:r.a,MaterialSelect:u.a,LinkSelect:d.a,Draggable:p.a}})],f);var g=f,v=(s("4b75"),s("2877")),m=Object(v.a)(g,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("attribute-tabs",{attrs:{title:"底部导航"}},[s("div",{attrs:{slot:"content"},slot:"content"},[s("el-form",{ref:"form",attrs:{"label-width":"80px",size:"small","label-position":"left"}},[s("attribute-item",{attrs:{title:"选择样式"}},[s("el-form-item",{attrs:{"label-width":"0"}},[s("el-radio-group",{model:{value:t.content.style,callback:function(e){t.$set(t.content,"style",e)},expression:"content.style"}},[s("el-radio",{attrs:{label:"1"}},[t._v("图片+文字")]),s("el-radio",{attrs:{label:"2"}},[t._v("图片")]),s("el-radio",{attrs:{label:"3"}},[t._v("文字")])],1)],1)],1),s("attribute-item",{attrs:{title:"导航内容"}},[s("div",{staticClass:"nav-list"},[s("draggable",{attrs:{animation:"300",draggable:".draggable",move:t.onMove},model:{value:t.content.data,callback:function(e){t.$set(t.content,"data",e)},expression:"content.data"}},t._l(t.content.data,(function(e,a){return s("div",{key:a,staticClass:"nav-item ls-del-wrap item",class:{draggable:0!=a}},[s("div",[3!=t.content.style?s("div",{staticClass:"flex m-b-10"},[s("material-select",{ref:"materialSelect",refInFor:!0,staticClass:"m-r-10",attrs:{size:48,"upload-bg":"#fff","enable-domain":!1},model:{value:e.icon,callback:function(s){t.$set(e,"icon",s)},expression:"item.icon"}},[s("div",{staticClass:"text-center"},[s("i",{staticClass:"el-icon-plus"}),s("div",[t._v("未选中")])])]),s("material-select",{ref:"materialSelect",refInFor:!0,attrs:{size:48,"upload-bg":"#fff","enable-domain":!1},model:{value:e.select_icon,callback:function(s){t.$set(e,"select_icon",s)},expression:"item.select_icon"}},[s("div",{staticClass:"text-center"},[s("i",{staticClass:"el-icon-plus"}),s("div",[t._v("选中")])])])],1):t._e(),s("div",[2!=t.content.style?s("el-form-item",{attrs:{label:"名称","label-width":"40px"}},[s("el-input",{staticStyle:{width:"200px"},attrs:{maxlength:"6","show-word-limit":"",placeholder:"请输入导航名称"},model:{value:e.name,callback:function(s){t.$set(e,"name",s)},expression:"item.name"}})],1):t._e(),s("el-form-item",{attrs:{label:"链接","label-width":"40px"}},[s("link-select",{attrs:{disabled:0==a},model:{value:e.link,callback:function(s){t.$set(e,"link",s)},expression:"item.link"}})],1)],1)]),a>0?s("i",{staticClass:"el-icon-close ls-icon-del",on:{click:function(e){return t.onDelete(a)}}}):t._e()])})),0)],1),s("el-form-item",{attrs:{"label-width":"0"}},[t.content.data.length<5?s("el-button",{staticClass:"add-nav",attrs:{size:"small"},on:{click:t.onAdd}},[t._v("+ 添加导航"+t._s(t.content.data.length)+"/5")]):t._e()],1)],1)],1)],1),s("div",{attrs:{slot:"styles"},slot:"styles"},[s("el-form",{ref:"form",attrs:{"label-width":"80px",size:"small","label-position":"left"}},[s("attribute-item",{attrs:{title:"颜色设置"}},[s("el-form-item",{attrs:{label:"背景颜色"}},[s("color-select",{attrs:{"reset-color":"#FFFFFF"},model:{value:t.styles.bg_color,callback:function(e){t.$set(t.styles,"bg_color",e)},expression:"styles.bg_color"}})],1),s("el-form-item",{attrs:{label:"字体颜色"}},[s("el-radio-group",{model:{value:t.content.color_type,callback:function(e){t.$set(t.content,"color_type",e)},expression:"content.color_type"}},[s("el-radio",{attrs:{label:1}},[t._v("跟随系统主题")]),s("el-radio",{attrs:{label:2}},[t._v("自定义")])],1)],1),2==t.content.color_type?[s("el-form-item",{attrs:{label:"默认颜色"}},[s("color-select",{attrs:{"reset-color":"#666666"},model:{value:t.styles.color,callback:function(e){t.$set(t.styles,"color",e)},expression:"styles.color"}})],1),s("el-form-item",{attrs:{label:"选中颜色"}},[s("color-select",{attrs:{"reset-color":"#FF2C3C"},model:{value:t.styles.select_color,callback:function(e){t.$set(t.styles,"select_color",e)},expression:"styles.select_color"}})],1)]:t._e()],2)],1)],1)])],1)}),[],!1,null,"c11a84d6",null).exports;let h=class extends o.e{};Object(a.a)([Object(o.c)()],h.prototype,"content",void 0),Object(a.a)([Object(o.c)()],h.prototype,"styles",void 0),h=Object(a.a)([o.a],h);var _=h,y=(s("55e0"),Object(v.a)(_,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"tabbar flex",style:{"background-color":t.styles.bg_color}},t._l(t.content.data,(function(e,a){return s("div",{key:a,staticClass:"tabbar-item flex-1 flex-col row-center col-center"},[1==t.content.style||2==t.content.style?s("div",{staticClass:"tabbar-icon"},[s("img",{attrs:{src:t.$getImageUri(e.icon)}})]):t._e(),1==t.content.style||3==t.content.style?s("div",{staticClass:"tabbar-text",style:{color:t.styles.color}},[t._v(" "+t._s(e.name)+" ")]):t._e()])})),0)}),[],!1,null,"029649cc",null).exports);let x=class extends o.e{};Object(a.a)([Object(o.c)()],x.prototype,"content",void 0),Object(a.a)([Object(o.c)()],x.prototype,"styles",void 0),x=Object(a.a)([Object(o.a)({components:{Contents:y}})],x);var O=x,j={attribute:m,contents:y,widget:Object(v.a)(O,(function(){var t=this.$createElement,e=this._self._c||t;return e("div",[e("contents",{attrs:{content:this.content,styles:this.styles}})],1)}),[],!1,null,null,null).exports},C=s("db85");let k=class extends o.e{constructor(){super(...arguments),this.tabbar={content:{style:"1",data:[]},styles:{bg_color:"#FFFFFF",color:"#666666",text_select_color:"#FF2C3C"}}}getThemeConfig(){Object(C.m)({type:2}).then(t=>{t.tabbar&&(this.tabbar=t.tabbar)})}handleSave(){Object(C.n)({type:2,content:{tabbar:this.tabbar}})}created(){this.getThemeConfig()}};k=Object(a.a)([Object(o.a)({components:{WTabbar:j.widget,ATabbar:j.attribute}})],k);var w=k,$=(s("1e71"),Object(v.a)(w,(function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"shop-tabbar flex"},[e("div",{staticClass:"tabbar-preview flex-1"},[e("el-scrollbar",{staticClass:"ls-scrollbar",staticStyle:{height:"100%"}},[e("div",{staticClass:"phone"},[e("w-tabbar",{attrs:{content:this.tabbar.content,styles:this.tabbar.styles}})],1)])],1),e("div",{staticClass:"tabbar-setting"},[e("el-scrollbar",{staticClass:"ls-scrollbar",staticStyle:{height:"100%"}},[e("a-tabbar",{attrs:{content:this.tabbar.content,styles:this.tabbar.styles}})],1)],1),e("div",{staticClass:"tabbar-footer bg-white ls-fixed-footer"},[e("div",{staticClass:"btns row-center flex",staticStyle:{height:"100%"}},[e("el-button",{attrs:{size:"small",type:"primary"},on:{click:this.handleSave}},[this._v("保存")])],1)])])}),[],!1,null,"56c8f63b",null));e.default=$.exports},c322:function(t,e,s){},c65a:function(t,e,s){},d6d3:function(t,e,s){"use strict";var a=s("9ab4"),o=s("1b40");let l=class extends o.e{get size(){return this.value}set size(t){this.$emit("input",t)}};Object(a.a)([Object(o.c)()],l.prototype,"value",void 0),Object(a.a)([Object(o.c)({default:0})],l.prototype,"min",void 0),Object(a.a)([Object(o.c)({default:40})],l.prototype,"max",void 0),l=Object(a.a)([o.a],l);var i=l,n=s("2877"),c=Object(n.a)(i,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"slider flex"},[s("el-slider",{staticClass:"flex-1 m-r-10",attrs:{min:t.min,max:t.max},model:{value:t.size,callback:function(e){t.size=e},expression:"size"}}),s("el-input-number",{staticStyle:{width:"90px"},attrs:{"controls-position":"right",min:t.min,max:t.max},model:{value:t.size,callback:function(e){t.size=e},expression:"size"}})],1)}),[],!1,null,"371d6e16",null);e.a=c.exports},da0d:function(t,e,s){},dcb0:function(t,e,s){"use strict";s("caad");var a=s("9ab4"),o=s("1b40"),l=s("3c50"),i=s("6ddb"),n=s("14c6");let c=class extends o.e{constructor(){super(...arguments),this.name="",this.pager=new i.a,this.selectedObj={}}visibleChange(t){t.val&&this.getList()}get selectData(){return this.value}set selectData(t){this.$emit("input",t)}get selectItem(){return t=>"single"==this.type?this.selectData.id==t.id:this.selectData.some(e=>e.id==t.id)}get selectAll(){const{pager:{lists:t}}=this,e=this.selectData.map(t=>t.id);return!!t.length&&t.every(t=>e.includes(t.id))}set selectAll(t){const{pager:{lists:e}}=this;if(t)for(let t=0;t<e.length;t++){const s=e[t];if(!this.selectData.map(t=>t.id).includes(s.id)){if(this.checkLength())return;this.selectData.push(s)}}else e.forEach(t=>{this.setSelectData(t)})}getList(t){let e=void 0;this.showVirtualGoods&&(e=0),t&&(this.pager.page=t),this.pager.request({callback:n.u,params:{name:this.name,is_spec:this.isSpec,...this.params,type:e}}).then(t=>{})}handleSelect(t,e){if("single"==this.type)this.selectData=t?e:{};else if(t){if(this.checkLength())return;this.selectData.push(e)}else this.setSelectData(e)}setSelectData(t){const e=this.selectData.findIndex(e=>e.id==t.id);-1!=e&&this.selectData.splice(e,1)}checkLength(){return this.selectData.length>=this.limit&&(this.$message({message:`选多选择${this.limit}件商品`,type:"warning"}),!0)}};Object(a.a)([Object(o.b)("visible")],c.prototype,"visible",void 0),Object(a.a)([Object(o.c)()],c.prototype,"value",void 0),Object(a.a)([Object(o.c)()],c.prototype,"goods",void 0),Object(a.a)([Object(o.c)({default:"single"})],c.prototype,"type",void 0),Object(a.a)([Object(o.c)()],c.prototype,"limit",void 0),Object(a.a)([Object(o.c)({default:!1})],c.prototype,"isSpec",void 0),Object(a.a)([Object(o.c)({default:()=>{}})],c.prototype,"params",void 0),Object(a.a)([Object(o.c)({default:!1})],c.prototype,"showVirtualGoods",void 0),Object(a.a)([Object(o.f)("visible",{deep:!0,immediate:!0})],c.prototype,"visibleChange",null),c=Object(a.a)([Object(o.a)({components:{LsPagination:l.a}})],c);var r=c,d=s("2877"),u=Object(d.a)(r,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{directives:[{name:"loading",rawName:"v-loading",value:t.pager.loading,expression:"pager.loading"}],staticClass:"detail"},[s("div",{staticClass:"flex row m-b-10 m-l-10"},[s("div",{staticClass:"m-r-20"},["multiple"==t.type?s("el-checkbox",{model:{value:t.selectAll,callback:function(e){t.selectAll=e},expression:"selectAll"}},[t._v("全选")]):t._e()],1),s("div",{staticClass:"flex"},[s("div",{staticClass:"m-r-10"},[t._v("商品搜索")]),s("el-input",{staticStyle:{width:"220px"},attrs:{size:"small",placeholder:"请输入商品名称"},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.getList(1)}},model:{value:t.name,callback:function(e){t.name=e},expression:"name"}},[s("el-button",{attrs:{slot:"append",icon:"el-icon-search"},on:{click:function(e){return t.getList(1)}},slot:"append"})],1)],1)]),s("el-table",{ref:"table",staticStyle:{width:"100%"},attrs:{data:t.pager.lists,height:"370px",size:"mini","row-key":"id"}},["single"==t.type?s("el-table-column",{attrs:{width:"45"},scopedSlots:t._u([{key:"default",fn:function(e){var a=e.row;return[s("el-checkbox",{attrs:{value:t.selectItem(a)},on:{change:function(e){return t.handleSelect(e,a)}}})]}}],null,!1,3310675202)}):t._e(),"multiple"==t.type?s("el-table-column",{attrs:{width:"45"},scopedSlots:t._u([{key:"default",fn:function(e){var a=e.row;return[s("el-checkbox",{attrs:{value:t.selectItem(a)},on:{change:function(e){return t.handleSelect(e,a)}}})]}}],null,!1,3310675202)}):t._e(),s("el-table-column",{attrs:{label:"商品信息","min-width":"180"},scopedSlots:t._u([{key:"default",fn:function(e){return[s("div",{staticClass:"flex"},[s("el-image",{staticClass:"flex-none",staticStyle:{width:"48px",height:"48px"},attrs:{src:e.row.image,fit:"cover"}}),s("div",{staticClass:"goods-info m-l-8"},[s("div",{staticClass:"line-2"},[t._v(t._s(e.row.name))]),s("div",[2==e.row.spec_type?s("el-tag",{attrs:{size:"mini"}},[t._v("多规格")]):t._e()],1)])],1)]}}])}),s("el-table-column",{attrs:{prop:"price",label:"价格",width:"200"}}),s("el-table-column",{attrs:{prop:"total_stock",label:"库存",width:"80"}})],1),s("div",{staticClass:"flex row-right m-t-20"},[s("ls-pagination",{on:{change:function(e){return t.getList()}},model:{value:t.pager,callback:function(e){t.pager=e},expression:"pager"}})],1)],1)}),[],!1,null,"b5e42ec8",null);e.a=u.exports},ea05:function(t,e,s){"use strict";var a=s("9ab4"),o=s("1b40");let l=class extends o.e{constructor(){super(...arguments),this.predefineColors=["#FF2C3C","#f7971e","#fa444d","#e0a356","#2f80ed","#2ec840"]}get color(){return this.value}set color(t){this.$emit("input",t)}reset(){this.color=this.resetColor}};Object(a.a)([Object(o.c)()],l.prototype,"value",void 0),Object(a.a)([Object(o.c)({default:""})],l.prototype,"resetColor",void 0),l=Object(a.a)([o.a],l);var i=l,n=s("2877"),c=Object(n.a)(i,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"color-select flex flex-1"},[s("el-color-picker",{attrs:{predefine:t.predefineColors},model:{value:t.color,callback:function(e){t.color=e},expression:"color"}}),s("el-input",{staticClass:"m-l-10 m-r-10 flex-1",attrs:{type:"text",readonly:""},model:{value:t.color,callback:function(e){t.color=e},expression:"color"}}),s("el-button",{attrs:{type:"text"},on:{click:t.reset}},[t._v("重置")])],1)}),[],!1,null,"b03bb76e",null);e.a=c.exports}}]);