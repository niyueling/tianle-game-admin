(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-d52fe528"],{"4fbf":function(t,e,a){"use strict";a("6f8c")},"6f8c":function(t,e,a){},b99a:function(t,e,a){"use strict";a.d(e,"f",(function(){return n})),a.d(e,"a",(function(){return i})),a.d(e,"d",(function(){return c})),a.d(e,"e",(function(){return r})),a.d(e,"c",(function(){return u})),a.d(e,"b",(function(){return o}));var s=a("f175");const n=()=>s.a.get("/config/getMarketingModule"),i=()=>s.a.get("/config/getAppModule"),c=t=>s.a.get("/common/activity",{params:t}),r=t=>s.a.get("/common/activityGoods",{params:t}),u=t=>s.a.post("award_integral/setConfig",t),o=()=>s.a.get("award_integral/getConfig")},bd5c:function(t,e,a){"use strict";a.r(e);var s=a("9ab4"),n=a("b99a"),i=a("1b40"),c=a("d70b");let r=class extends i.e{constructor(){super(...arguments),this.menuList=[]}getModule(){Object(n.f)().then(t=>{t.map(t=>t.list.map(t=>(t.image=c.a.baseURL+t.image,t))),this.menuList=t})}created(){this.getModule()}};r=Object(s.a)([i.a],r);var u=r,o=(a("4fbf"),a("2877")),l=Object(o.a)(u,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"marketing-center"},t._l(t.menuList,(function(e,s){return a("div",{key:s,staticClass:"ls-marketing ls-card m-b-16"},[a("div",{staticClass:"title flex"},[t._v(" "+t._s(e.name)+" "),a("div",{staticClass:"m-l-10 xs weight-400"},[t._v(t._s(e.introduce))])]),a("div",{staticClass:"flex menu flex-wrap"},t._l(e.list,(function(e,s){return a("router-link",{key:s,staticClass:"flex menu-item",attrs:{tag:"div",to:e.page_path}},[a("el-image",{staticClass:"m-r-18 menu-icon",attrs:{src:e.image}}),a("div",{staticClass:"menu-info"},[a("div",{staticClass:"lg"},[t._v(" "+t._s(e.name)+" "),e.is_open?t._e():a("span",{staticClass:"is-open xs"},[t._v("即将开放")])]),a("div",{staticClass:"muted m-t-4 line-2"},[t._v(" "+t._s(e.introduce)+" ")])])],1)})),1)])})),0)}),[],!1,null,"05cd0ba3",null);e.default=l.exports}}]);