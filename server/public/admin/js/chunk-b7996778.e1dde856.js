(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-b7996778"],{"192f":function(e,t,s){e.exports=s.p+"img/img_shili_mine_touxiang.7fb1ede1.png"},4611:function(e,t,s){"use strict";s("c9e3")},"5be2":function(e,t,s){"use strict";s.d(t,"c",(function(){return i})),s.d(t,"d",(function(){return n})),s.d(t,"a",(function(){return a})),s.d(t,"b",(function(){return u})),s.d(t,"e",(function(){return o})),s.d(t,"f",(function(){return l}));var r=s("f175");const i=()=>r.a.get("/settings.user.user/getConfig"),n=e=>r.a.post("/settings.user.user/setConfig",e),a=()=>r.a.get("/settings.user.user/getRegisterConfig"),u=e=>r.a.post("/settings.user.user/setRegisterConfig",e),o=()=>r.a.get("/settings.user.user/getWithdrawConfig"),l=e=>r.a.post("/settings.user.user/setWithdrawConfig",e)},c9e3:function(e,t,s){},eb49:function(e,t,s){"use strict";s.r(t);var r=s("9ab4"),i=s("1b40"),n=s("f633"),a=s("b3ad"),u=s("5be2");let o=class extends i.e{constructor(){super(...arguments),this.form={default_avatar:"",scene:"",invite_open:0,invite_ways:1,invite_appoint_user:[],invite_condition:1,poster:""},this.invite_appoint_user=[],this.userLevelLists=[],this.images={avater:s("192f")}}getUserSetting(){Object(u.c)().then(e=>{this.form=e,null===this.form.invite_appoint_user?this.form.invite_appoint_user=[]:this.form.invite_appoint_user[0]}).catch(()=>{this.$message.error("数据请求失败!")})}setUserSetting(){1===this.form.invite_ways&&(this.form.invite_appoint_user=[]),this.form.scene="user",Object(u.d)(this.form).then(e=>{setTimeout(()=>{this.getUserSetting()},50)})}getUserLevelLists(){Object(n.o)({}).then(e=>{this.userLevelLists=e.lists})}handleUserLevel(e){const t=this.userLevelLists.map(e=>e.id);this.form.invite_appoint_user=this.getArrEqual(t,e).map(String)}getArrEqual(e,t){const s=[];for(let r=0;r<t.length;r++)for(let i=0;i<e.length;i++)e[i]==t[r]&&s.push(e[i]);return s}created(){this.getUserSetting(),this.getUserLevelLists()}};o=Object(r.a)([Object(i.a)({components:{MaterialSelect:a.a}})],o);var l=o,c=(s("4611"),s("2877")),d=Object(c.a)(l,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"user_setting"},[s("el-form",{ref:"form",attrs:{"label-width":"120px",size:"small"}},[s("div",{staticClass:"ls-card"},[s("div",{staticClass:"card-title"},[e._v("基本设置")]),s("div",{staticClass:"card-content m-t-24"},[s("el-form-item",{attrs:{label:"用户默认头像"}},[s("material-select",{attrs:{limit:1,disabled:!1},model:{value:e.form.default_avatar,callback:function(t){e.$set(e.form,"default_avatar",t)},expression:"form.default_avatar"}}),s("div",{staticClass:"flex"},[s("div",{staticClass:"muted xs m-r-16"},[e._v("建议尺寸：400*400像素，支持jpg，jpeg，png格式")]),s("el-popover",{attrs:{placement:"right",width:"200",trigger:"hover"}},[s("el-image",{attrs:{src:e.images.avater}}),s("el-button",{attrs:{slot:"reference",type:"text"},slot:"reference"},[e._v("查看示例")])],1)],1)],1)],1)]),s("div",{staticClass:"ls-card m-t-16"},[s("div",{staticClass:"card-title"},[e._v("用户关系")]),s("div",{staticClass:"card-content m-t-24"},[s("el-form-item",{attrs:{label:"开启邀请下级",required:""}},[s("div",{staticClass:"flex"},[s("el-switch",{attrs:{"active-value":1,"inactive-value":0,"active-color":e.styleConfig.primary,"inactive-color":"#f4f4f5"},model:{value:e.form.invite_open,callback:function(t){e.$set(e.form,"invite_open",t)},expression:"form.invite_open"}}),s("span",{staticClass:"m-l-16"},[e._v(e._s(e.form.invite_open?"开启":"关闭"))])],1),s("div",{staticClass:"muted xs"},[e._v("系统是否开启邀请下级功能，关闭功能后用户之间不能建立新的上下级关系。")])])],1),s("div",{staticClass:"card-content m-t-24"},[s("el-form-item",{attrs:{label:"邀请下级资格",required:""}},[s("el-radio-group",{staticClass:"m-r-16",model:{value:e.form.invite_ways,callback:function(t){e.$set(e.form,"invite_ways",t)},expression:"form.invite_ways"}},[s("el-radio",{attrs:{label:1}},[e._v("全部用户可以邀")]),s("el-radio",{attrs:{label:2}},[e._v("指定用户")])],1),s("div",{directives:[{name:"show",rawName:"v-show",value:2==e.form.invite_ways,expression:"form.invite_ways == 2"}]},[s("el-checkbox-group",{on:{change:e.handleUserLevel},model:{value:e.form.invite_appoint_user,callback:function(t){e.$set(e.form,"invite_appoint_user",t)},expression:"form.invite_appoint_user"}},e._l(e.userLevelLists,(function(t){return s("el-checkbox",{key:t.id,attrs:{label:t.id+""}},[e._v(e._s(t.name))])})),1)],1)],1)],1),s("div",{staticClass:"card-content m-t-24"},[s("el-form-item",{attrs:{label:"成为下级条件",required:""}},[s("el-radio",{attrs:{label:1},model:{value:e.form.invite_condition,callback:function(t){e.$set(e.form,"invite_condition",t)},expression:"form.invite_condition"}},[e._v("首次绑定邀请码")]),s("div",{staticClass:"muted xs"},[e._v(" 用户登录后首次绑定邀请码建立上下级关系。包括扫码，点击分享链接，输入邀请码等场景。 ")])],1)],1)])]),s("div",{staticClass:"bg-white ls-fixed-footer"},[s("div",{staticClass:"row-center flex",staticStyle:{height:"100%"}},[s("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(t){return e.setUserSetting()}}},[e._v("保存")])],1)])],1)}),[],!1,null,"a45a643e",null);t.default=d.exports},f633:function(e,t,s){"use strict";s.d(t,"o",(function(){return i})),s.d(t,"k",(function(){return n})),s.d(t,"m",(function(){return a})),s.d(t,"n",(function(){return u})),s.d(t,"l",(function(){return o})),s.d(t,"j",(function(){return l})),s.d(t,"f",(function(){return c})),s.d(t,"h",(function(){return d})),s.d(t,"i",(function(){return f})),s.d(t,"g",(function(){return v})),s.d(t,"p",(function(){return m})),s.d(t,"q",(function(){return p})),s.d(t,"b",(function(){return _})),s.d(t,"s",(function(){return g})),s.d(t,"t",(function(){return h})),s.d(t,"u",(function(){return b})),s.d(t,"r",(function(){return C})),s.d(t,"c",(function(){return w})),s.d(t,"d",(function(){return x})),s.d(t,"e",(function(){return L})),s.d(t,"v",(function(){return y})),s.d(t,"a",(function(){return k}));var r=s("f175");const i=e=>r.a.get("/user.user_level/lists",{params:e}),n=e=>r.a.post("/user.user_level/add",e),a=e=>r.a.get("/user.user_level/detail",{params:e}),u=e=>r.a.post("/user.user_level/edit",e),o=e=>r.a.post("/user.user_level/del",e),l=e=>r.a.get("/user.user_label/lists",{params:e}),c=e=>r.a.post("/user.user_label/add",e),d=e=>r.a.get("/user.user_label/detail",{params:e}),f=e=>r.a.post("/user.user_label/edit",e),v=e=>r.a.post("/user.user_label/del",e),m=e=>r.a.get("/user.user/lists",{params:e}),p=()=>r.a.get("/user.user/otherList"),_=e=>r.a.get("/user.user/detail",{params:e}),g=e=>r.a.post("/user.user/setInfo",e),h=e=>r.a.post("/user.user/setLabel",e),b=e=>r.a.post("/user.user/setUserLabel",e),C=e=>r.a.post("/user.user/adjustUserWallet",e),w=()=>r.a.get("/user.user/index"),x=e=>r.a.get("/user.user/info",{params:e}),L=e=>r.a.get("/user.user/userInviterLists",{params:e}),y=e=>r.a.post("/user.user/adjustFirstLeader",e),k=e=>r.a.get("/user.user/selectUserLists",{params:e})}}]);