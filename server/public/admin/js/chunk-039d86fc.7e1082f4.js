(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-039d86fc"],{"667c":function(e,t,r){"use strict";r("d2d2")},d2d2:function(e,t,r){},f1ab:function(e,t,r){"use strict";r.r(t);var s=r("9ab4"),a=r("1b40"),i=r("4201"),n=r("f633");let u=class extends a.e{constructor(){super(...arguments),this.mode=i.f.ADD,this.identity=null,this.form={name:"",remark:"",label_type:0},this.formRules={name:[{required:!0,message:"请输入标签名称",trigger:"blur"}],label_type:[{required:!0,message:"请选择标签类型",trigger:"change"}]}}onSubmit(){this.$refs.formRef.validate(e=>{if(e)switch(this.mode){case i.f.ADD:return this.handleUserLabelAdd();case i.f.EDIT:return this.handleUserLabelEdit()}})}handleUserLabelAdd(){const e=this.form;Object(n.f)(e).then(()=>{setTimeout(()=>this.$router.go(-1),500)}).catch(()=>{})}handleUserLabelEdit(){const e=this.form,t=this.identity;Object(n.i)({...e,id:t}).then(()=>{setTimeout(()=>this.$router.go(-1),500)}).catch(()=>{})}initFormDataForUserLabelEdit(){Object(n.h)({id:this.identity}).then(e=>{Object.keys(e).map(t=>{this.$set(this.form,t,e[t])})}).catch(()=>{})}created(){const e=this.$route.query;e.mode&&(this.mode=e.mode),this.mode===i.f.EDIT&&(this.identity=1*e.id,this.initFormDataForUserLabelEdit())}};u=Object(s.a)([Object(a.a)({components:{}})],u);var l=u,o=(r("667c"),r("2877")),c=Object(o.a)(l,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"user-tag-edit"},[r("div",{staticClass:"ls-card"},[r("el-page-header",{attrs:{content:"add"===e.mode?"新增用户标签":"编辑用户标签"},on:{back:function(t){return e.$router.go(-1)}}})],1),r("el-form",{ref:"formRef",attrs:{rules:e.formRules,model:e.form,"label-width":"120px",size:"small"}},[r("div",{staticClass:"ls-card m-t-16"},[r("div",{staticClass:"card-title"},[e._v("标签信息")]),r("div",{staticClass:"card-content m-t-24"},[r("el-form-item",{attrs:{label:"标签名称",prop:"name"}},[r("el-input",{attrs:{placeholder:"请输入标签名称"},model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}})],1),r("el-form-item",{attrs:{label:"标签描述",prop:"remark"}},[r("el-input",{staticClass:"ls-input-textarea",attrs:{placeholder:"请输入标签描述",type:"textarea",rows:3},model:{value:e.form.remark,callback:function(t){e.$set(e.form,"remark",t)},expression:"form.remark"}})],1)],1)]),r("div",{staticClass:"ls-card m-t-16"},[r("div",{staticClass:"card-title"},[e._v("标签类型")]),r("div",{staticClass:"card-content m-t-24"},[r("el-form-item",{attrs:{label:"标签类型",prop:"label_type"}},[r("el-radio-group",{staticClass:"m-r-16",model:{value:e.form.label_type,callback:function(t){e.$set(e.form,"label_type",t)},expression:"form.label_type"}},[r("el-radio",{staticClass:"m-r-16",attrs:{label:0}},[e._v("手动标签")])],1),r("div",{staticClass:"muted xs m-r-16"},[e._v("手动标签是指手动给用户打上标签")])],1)],1)])]),r("div",{staticClass:"bg-white ls-fixed-footer"},[r("div",{staticClass:"row-center flex",staticStyle:{height:"100%"}},[r("el-button",{attrs:{size:"small"},on:{click:function(t){return e.$router.go(-1)}}},[e._v("取消")]),r("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(t){return e.onSubmit()}}},[e._v("保存")])],1)])],1)}),[],!1,null,"42a2157f",null);t.default=c.exports},f633:function(e,t,r){"use strict";r.d(t,"o",(function(){return a})),r.d(t,"k",(function(){return i})),r.d(t,"m",(function(){return n})),r.d(t,"n",(function(){return u})),r.d(t,"l",(function(){return l})),r.d(t,"j",(function(){return o})),r.d(t,"f",(function(){return c})),r.d(t,"h",(function(){return d})),r.d(t,"i",(function(){return m})),r.d(t,"g",(function(){return f})),r.d(t,"p",(function(){return p})),r.d(t,"q",(function(){return b})),r.d(t,"b",(function(){return h})),r.d(t,"s",(function(){return v})),r.d(t,"t",(function(){return g})),r.d(t,"u",(function(){return _})),r.d(t,"r",(function(){return k})),r.d(t,"c",(function(){return y})),r.d(t,"d",(function(){return C})),r.d(t,"e",(function(){return x})),r.d(t,"v",(function(){return L})),r.d(t,"a",(function(){return w}));var s=r("f175");const a=e=>s.a.get("/user.user_level/lists",{params:e}),i=e=>s.a.post("/user.user_level/add",e),n=e=>s.a.get("/user.user_level/detail",{params:e}),u=e=>s.a.post("/user.user_level/edit",e),l=e=>s.a.post("/user.user_level/del",e),o=e=>s.a.get("/user.user_label/lists",{params:e}),c=e=>s.a.post("/user.user_label/add",e),d=e=>s.a.get("/user.user_label/detail",{params:e}),m=e=>s.a.post("/user.user_label/edit",e),f=e=>s.a.post("/user.user_label/del",e),p=e=>s.a.get("/user.user/lists",{params:e}),b=()=>s.a.get("/user.user/otherList"),h=e=>s.a.get("/user.user/detail",{params:e}),v=e=>s.a.post("/user.user/setInfo",e),g=e=>s.a.post("/user.user/setLabel",e),_=e=>s.a.post("/user.user/setUserLabel",e),k=e=>s.a.post("/user.user/adjustUserWallet",e),y=()=>s.a.get("/user.user/index"),C=e=>s.a.get("/user.user/info",{params:e}),x=e=>s.a.get("/user.user/userInviterLists",{params:e}),L=e=>s.a.post("/user.user/adjustFirstLeader",e),w=e=>s.a.get("/user.user/selectUserLists",{params:e})}}]);