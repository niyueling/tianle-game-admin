(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-0a0054a9"],{1954:function(t,e,i){"use strict";i("d122")},"63be":function(t,e,i){},"82bb":function(t,e,i){"use strict";i.d(e,"f",(function(){return o})),i.d(e,"e",(function(){return n})),i.d(e,"d",(function(){return r})),i.d(e,"c",(function(){return a})),i.d(e,"h",(function(){return c})),i.d(e,"g",(function(){return l})),i.d(e,"b",(function(){return d})),i.d(e,"a",(function(){return h}));var s=i("f175");const o=()=>s.a.get("/settings.shop.shop_setting/getShopInfo"),n=t=>s.a.post("/settings.shop.shop_setting/setShopInfo",t),r=()=>s.a.get("/settings.shop.shop_setting/getRecordInfo"),a=t=>s.a.post("/settings.shop.shop_setting/setRecordInfo",t),c=()=>s.a.get("/settings.shop.shop_setting/getShareSetting"),l=t=>s.a.post("/settings.shop.shop_setting/setShareSetting",t),d=()=>s.a.get("/settings.shop.shop_setting/getPolicyAgreement"),h=t=>s.a.post("/settings.shop.shop_setting/setPolicyAgreement",t)},"84f7":function(t,e,i){"use strict";i("63be")},a897:function(t,e,i){"use strict";var s=i("9ab4"),o=i("1b40"),n=i("6fad"),r=i.n(n),a=i("b3ad"),c=i("6ddb"),l=i("d70b");let d=class extends o.e{constructor(){super(...arguments),this.firstData=!0,this.identify=""}get editStyle(){return this.width?{width:this.width+"px"}:{}}valueChange(t){this.firstData&&(this.firstData=!1,this.editor.txt.html(t))}handeleChange(t){t.forEach(t=>{this.editor.cmd.do("insertHTML",`<img src="${t}" style="max-width:100%;"/>`)})}created(){this.identify="editor-"+Object(c.g)(3)}mounted(){this.editor=new r.a("#"+this.identify),this.editor.config.height=this.height,this.editor.config.menus=this.menu,this.editor.config.menuTooltipPosition="down",this.editor.config.showFullScreen=!1,this.editor.config.showLinkImg=!1,this.editor.config.uploadImgShowBase64=!0,this.editor.config.zIndex=1,this.editor.config.uploadImgFromMedia=()=>{this.$refs.materialSelect.showDialog()},this.editor.config.onchange=t=>{this.$emit("input",t)},this.editor.config.uploadVideoServer=l.a.baseURL+"/adminapi/upload/video",this.editor.config.uploadVideoHeaders={token:this.$store.getters.token,version:l.a.version},this.editor.config.uploadVideoName="file",this.editor.config.uploadVideoHooks={fail:(t,e,i)=>{this.$message.error("上传视频失败")},timeout:t=>{this.$message.error("上传视频超时")},customInsert:(t,e)=>{1==e.code?t(e.data.uri):this.$message.error(e.msg)}},this.editor.create()}beforeDestroy(){this.editor.destroy()}};Object(s.a)([Object(o.c)()],d.prototype,"value",void 0),Object(s.a)([Object(o.c)({default:()=>["head","bold","fontSize","fontName","italic","underline","strikeThrough","indent","lineHeight","foreColor","link","list","justify","quote","emoticon","image","video","undo","redo"]})],d.prototype,"menu",void 0),Object(s.a)([Object(o.c)({default:600})],d.prototype,"height",void 0),Object(s.a)([Object(o.c)()],d.prototype,"width",void 0),Object(s.a)([Object(o.f)("value")],d.prototype,"valueChange",null),d=Object(s.a)([Object(o.a)({components:{MaterialSelect:a.a}})],d);var h=d,m=(i("84f7"),i("2877")),f=Object(m.a)(h,(function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"ls-editor"},[e("div",{staticClass:"editor",style:[this.editStyle],attrs:{id:this.identify}}),e("material-select",{ref:"materialSelect",attrs:{limit:null,"hidden-trigger":!0},on:{change:this.handeleChange}})],1)}),[],!1,null,"00c8867e",null);e.a=f.exports},d122:function(t,e,i){},dc15:function(t,e,i){"use strict";i.r(e);var s=i("9ab4"),o=i("1b40"),n=i("a897"),r=i("82bb");let a=class extends o.e{constructor(){super(...arguments),this.form={service_agreement_name:"",service_agreement_content:"",privacy_policy_name:"",privacy_policy_content:""}}initFormData(){Object(r.b)().then(t=>{for(const e in t)this.form.hasOwnProperty(e)&&(this.form[e]=t[e])}).catch(()=>{this.$message.error("数据加载失败，请刷新重载")})}onResetFrom(){this.initFormData(),this.$message.info("已重置数据")}onSubmitFrom(t){this.$refs[t].validate(t=>{if(!t)return;const e=this.$loading({text:"保存中"});Object(r.a)({...this.form}).then(()=>{this.$message.success("保存成功")}).catch(()=>{this.$message.error("保存失败")}).finally(()=>{e.close()})})}created(){this.initFormData()}};a=Object(s.a)([Object(o.a)({components:{LsEditor:n.a}})],a);var c=a,l=(i("1954"),i("2877")),d=Object(l.a)(c,(function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"setting-protocol"},[i("el-form",{ref:"form",attrs:{model:t.form,"label-width":"120px",size:"small"}},[i("div",{staticClass:"ls-card"},[i("div",{staticClass:"card-title"},[i("div",[t._v("服务协议")]),i("div",{staticClass:"muted xs m-l-16"},[t._v("用户登录注册页面显示服务协议")])]),i("div",{staticClass:"card-content m-t-24"},[i("el-form-item",{attrs:{label:"协议名称"}},[i("el-input",{staticClass:"ls-input",attrs:{"show-word-limit":""},model:{value:t.form.service_agreement_name,callback:function(e){t.$set(t.form,"service_agreement_name",e)},expression:"form.service_agreement_name"}})],1),i("el-form-item",{attrs:{label:"协议内容"}},[i("ls-editor",{model:{value:t.form.service_agreement_content,callback:function(e){t.$set(t.form,"service_agreement_content",e)},expression:"form.service_agreement_content"}})],1)],1)]),i("div",{staticClass:"ls-card m-t-16"},[i("div",{staticClass:"card-title"},[i("div",[t._v("隐私协议")]),i("div",{staticClass:"muted xs m-l-16"},[t._v("用户登录注册页面显示隐私政策")])]),i("div",{staticClass:"card-content m-t-24"},[i("el-form-item",{attrs:{label:"协议名称"}},[i("el-input",{staticClass:"ls-input",attrs:{"show-word-limit":""},model:{value:t.form.privacy_policy_name,callback:function(e){t.$set(t.form,"privacy_policy_name",e)},expression:"form.privacy_policy_name"}})],1),i("el-form-item",{attrs:{label:"协议内容"}},[i("ls-editor",{model:{value:t.form.privacy_policy_content,callback:function(e){t.$set(t.form,"privacy_policy_content",e)},expression:"form.privacy_policy_content"}})],1)],1)])]),i("div",{staticClass:"bg-white ls-fixed-footer"},[i("div",{staticClass:"row-center flex",staticStyle:{height:"100%"}},[i("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(e){return t.onSubmitFrom("form")}}},[t._v("保存")])],1)])],1)}),[],!1,null,"4eba268e",null);e.default=d.exports}}]);