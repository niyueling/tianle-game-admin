(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-20892238"],{"00e2":function(e,t,r){},"192a":function(e,t,r){"use strict";r.d(t,"v",(function(){return a})),r.d(t,"r",(function(){return i})),r.d(t,"s",(function(){return l})),r.d(t,"u",(function(){return o})),r.d(t,"k",(function(){return d})),r.d(t,"q",(function(){return n})),r.d(t,"p",(function(){return c})),r.d(t,"t",(function(){return f})),r.d(t,"j",(function(){return _})),r.d(t,"o",(function(){return m})),r.d(t,"m",(function(){return p})),r.d(t,"n",(function(){return u})),r.d(t,"l",(function(){return h})),r.d(t,"f",(function(){return v})),r.d(t,"e",(function(){return b})),r.d(t,"a",(function(){return g})),r.d(t,"g",(function(){return y})),r.d(t,"c",(function(){return w})),r.d(t,"h",(function(){return x})),r.d(t,"b",(function(){return D})),r.d(t,"i",(function(){return C})),r.d(t,"d",(function(){return k}));var s=r("f175");const a=()=>s.a.get("/order.order/otherLists"),i=e=>s.a.get("/order.order/detail",{params:e}),l=e=>s.a.get("/order.order/lists",{params:e}),o=e=>s.a.post("/order.order/orderRemarks",e),d=e=>s.a.post("/order.order/cancel",e),n=e=>s.a.get("/order.order/deliveryInfo",{params:e}),c=e=>s.a.post("/order.order/delivery",e),f=e=>s.a.get("/order.order/logistics",{params:e}),_=e=>s.a.post("/order.order/addressEdit",e),m=e=>s.a.post("/order.order/confirm",e),p=e=>s.a.post("/order.order/changeExpressPrice",e),u=e=>s.a.post("/order.order/changePrice",e),h=e=>s.a.post("/order.order/changeDelivery",e),v=e=>s.a.get("/after_sale.after_sale/lists",{params:e}),b=e=>s.a.get("/after_sale.after_sale/detail",{params:e}),g=e=>s.a.post("/after_sale.after_sale/agree",e),y=e=>s.a.post("/after_sale.after_sale/refuse",e),w=e=>s.a.post("/after_sale.after_sale/confirmGoods",e),x=e=>s.a.post("/after_sale.after_sale/refuseGoods",e),D=e=>s.a.post("/after_sale.after_sale/agreeRefund",e),C=e=>s.a.post("/after_sale.after_sale/refuseRefund",e),k=e=>s.a.post("/after_sale.after_sale/confirmRefund",e)},4690:function(e,t,r){e.exports={primary:"#4073fa",white:"#fff",asideMenuWidth:"150px",headerHeight:"64px"}},"5cf7":function(e,t,r){"use strict";var s=r("9ab4"),a=r("0463"),i=r("1b40");let l=class extends i.e{constructor(){super(...arguments),this.options=a.a}get areaValue(){return[this.province,this.city,this.district]}set areaValue(e){this.$emit("update:province",e[0]),this.$emit("update:city",e[1]),this.$emit("update:district",e[2])}};Object(s.a)([Object(i.c)()],l.prototype,"province",void 0),Object(s.a)([Object(i.c)()],l.prototype,"city",void 0),Object(s.a)([Object(i.c)()],l.prototype,"district",void 0),Object(s.a)([Object(i.c)({default:"380px"})],l.prototype,"width",void 0),l=Object(s.a)([i.a],l);var o=l,d=r("2877"),n=Object(d.a)(o,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"area-select"},[r("el-cascader",{style:{width:e.width},attrs:{options:e.options},model:{value:e.areaValue,callback:function(t){e.areaValue=t},expression:"areaValue"}})],1)}),[],!1,null,null,null);t.a=n.exports},"82fc":function(e,t,r){"use strict";r.d(t,"f",(function(){return a})),r.d(t,"b",(function(){return i})),r.d(t,"d",(function(){return l})),r.d(t,"e",(function(){return o})),r.d(t,"g",(function(){return d})),r.d(t,"c",(function(){return n})),r.d(t,"a",(function(){return c})),r.d(t,"o",(function(){return f})),r.d(t,"k",(function(){return _})),r.d(t,"m",(function(){return m})),r.d(t,"n",(function(){return p})),r.d(t,"p",(function(){return u})),r.d(t,"l",(function(){return h})),r.d(t,"h",(function(){return v})),r.d(t,"i",(function(){return b})),r.d(t,"j",(function(){return g}));var s=r("f175");const a=e=>s.a.get("/selffetch_shop.selffetch_shop/lists",{params:e}),i=e=>s.a.post("/selffetch_shop.selffetch_shop/add",e),l=e=>s.a.get("/selffetch_shop.selffetch_shop/detail",{params:e}),o=e=>s.a.post("/selffetch_shop.selffetch_shop/edit",e),d=e=>s.a.post("/selffetch_shop.selffetch_shop/status",e),n=e=>s.a.post("/selffetch_shop.selffetch_shop/del",e),c=e=>s.a.get("/selffetch_shop.selffetch_shop/regionSearch",{params:e}),f=e=>s.a.get("/selffetch_shop.selffetch_verifier/lists",{params:e}),_=e=>s.a.post("/selffetch_shop.selffetch_verifier/add",e),m=e=>s.a.get("/selffetch_shop.selffetch_verifier/detail",{params:e}),p=e=>s.a.post("/selffetch_shop.selffetch_verifier/edit",e),u=e=>s.a.post("/selffetch_shop.selffetch_verifier/status",e),h=e=>s.a.post("/selffetch_shop.selffetch_verifier/del",e),v=e=>s.a.post("/selffetch_shop.verification/verification",e),b=e=>s.a.get("selffetch_shop.verification/lists",{params:e}),g=e=>s.a.get("selffetch_shop.verification/verificationQuery",{params:e})},"995c":function(e,t,r){"use strict";r.r(t);var s=r("9ab4"),a=r("1b40"),i=r("0a6d"),l=r("5cf7"),o=r("ffae"),d=r("192a"),n=r("82fc");let c=class extends a.e{constructor(){super(...arguments),this.id=0,this.orderData={admin_order_btn:{remark_btn:1,cancel_btn:0,confirm_btn:0,logistics_btn:0,refund_btn:0,address_btn:1,price_btn:1}},this.address={province_id:"",city_id:"",district_id:"",address:""},this.remarks="",this.express_price="",this.goods_price="",this.express_id="",this.invoice_no="",this.expressList=[]}getOrderDetail(){Object(d.r)({id:this.id}).then(e=>{this.orderData=e,1==e.delivery_type&&e.order_status>1&&1==e.send_type&&this.getOrderDeliveryInfo()})}orderCancel(){Object(d.k)({id:this.id}).then(e=>{this.getOrderDetail()})}selffetch(){Object(n.h)({id:this.id}).then(()=>{this.getOrderDetail()})}orderAddressSet(){Object(d.j)({id:this.id,...this.address}).then(e=>{this.getOrderDetail()})}orderConfirm(){Object(d.o)({id:this.id}).then(e=>{this.getOrderDetail()})}postOrderRemarks(){Object(d.u)({id:[this.id],order_remarks:this.remarks}).then(e=>{this.getOrderDetail()})}orderChangeGoodsPrice(e){if(""==this.goods_price)return this.$message.error("请输入价格");Object(d.n)({order_goods_id:e,change_price:this.goods_price}).then(e=>{this.getOrderDetail(),this.goods_price=""})}orderChangeExpress(){if(""==this.express_price)return this.$message.error("请输入运费！");Object(d.m)({id:this.id,express_price:this.express_price}).then(e=>{this.getOrderDetail()})}orderChangeLogistics(){if(""==this.express_id)return this.$message.error("请输入物流公司名称！");Object(d.l)({id:this.id,express_id:this.express_id}).then(e=>{this.getOrderDetail(),this.express_id=""})}orderChangeCourierNumber(){if(""==this.invoice_no)return this.$message.error("请输入快递单号！");Object(d.l)({id:this.id,invoice_no:this.invoice_no}).then(e=>{this.getOrderDetail(),this.invoice_no=""})}getSummaries(e){const{columns:t,data:r}=e,s=[];return t.forEach((e,t)=>{if(0===t)return void(s[0]="总价");const a=r.map(t=>Number(t[e.property]));if(!a.every(e=>isNaN(e))){if(1==t)return;s[t]=a.reduce((e,t)=>{const r=Number(t);return isNaN(r)?e:e+t},0),3!==t&&("number"==typeof s[t]&&(s[t]=s[t].toFixed(2)),s[t]="¥"+s[t]),5==t&&("number"==typeof s[t]&&(s[t]=s[t].toFixed(2)),s[t]="-"+s[t]),6==t&&(s[t]="-¥"+(this.orderData.change_price||"0.00"))}}),s}toUserDetail(){this.$router.push({path:"/user/user_details",query:{id:this.orderData.user_id}})}getOrderDeliveryInfo(){Object(d.q)({id:this.id}).then(e=>{this.expressList=e.express})}created(){this.id=this.$route.query.id,this.id&&this.getOrderDetail()}};c=Object(s.a)([Object(a.a)({components:{LsDialog:i.a,AreaSelect:l.a,OrderLogistics:o.a}})],c);var f=c,_=(r("e1b9"),r("2877")),m=Object(_.a)(f,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",[r("header",[r("div",{staticClass:"ls-card"},[r("el-page-header",{attrs:{content:"订单详情"},on:{back:function(t){return e.$router.go(-1)}}})],1),r("div",{staticClass:"flex m-t-24"},[r("div",{staticClass:"ls-card flex flex-wrap col-stretch",staticStyle:{"min-height":"450px"}},[r("div",{staticStyle:{width:"100%"}},[r("div",{staticClass:"nr weight-500 m-b-20 title"},[e._v("订单信息")]),r("div",{staticClass:"flex col-top"},[r("el-form",{ref:"form",attrs:{model:e.orderData,"label-width":"120px",size:"small"}},[r("el-form-item",{attrs:{label:"订单状态"}},[e._v(" "+e._s(e.orderData.order_status_desc)+" ")]),r("el-form-item",{attrs:{label:"订单编号"}},[e._v(" "+e._s(e.orderData.sn)+" ")]),r("el-form-item",{attrs:{label:"订单类型"}},[e._v(" "+e._s(e.orderData.order_type_desc)+" ")]),r("el-form-item",{attrs:{label:"订单来源"}},[e._v(" "+e._s(e.orderData.order_terminal_desc)+" ")]),r("el-form-item",{attrs:{label:"下单时间"}},[e._v(" "+e._s(e.orderData.create_time)+" ")])],1),r("el-form",{ref:"form",staticStyle:{"margin-left":"20vw"},attrs:{model:e.orderData,"label-width":"120px",size:"small"}},[r("el-form-item",{attrs:{label:"支付状态"}},[e._v(" "+e._s(e.orderData.pay_status_desc)+" ")]),r("el-form-item",{attrs:{label:"支付方式"}},[e._v(" "+e._s(e.orderData.pay_way_desc)+" ")]),r("el-form-item",{attrs:{label:"支付时间"}},[e._v(" "+e._s(e.orderData.pay_time)+" ")]),r("el-form-item",{attrs:{label:"完成时间"}},[e._v(" "+e._s(e.orderData.confirm_take_time)+" ")]),r("el-form-item",{attrs:{label:"用户备注"}},[e._v(" "+e._s(e.orderData.user_remark)+" ")])],1)],1),r("el-form",{ref:"form",attrs:{model:e.orderData,"label-width":"120px",size:"small"}},[r("el-form-item",{staticStyle:{"word-break":"break-all"},attrs:{label:"商家备注"}},[e._v(" "+e._s(e.orderData.order_remarks)+" ")])],1)],1),r("div",{staticClass:"flex col-bottom",staticStyle:{width:"100%"}},[r("div",{staticClass:"border-top flex col-bottom row-left p-t-24",staticStyle:{width:"100%",height:"57px"}},[e.orderData.admin_order_btn.cancel_btn?r("ls-dialog",{staticClass:"inline m-l-24",attrs:{title:"取消订单",content:"确定取消订单("+e.orderData.sn+")吗?请谨慎操作"},on:{confirm:e.orderCancel}},[r("el-button",{staticStyle:{},attrs:{slot:"trigger",size:"small"},slot:"trigger"},[e._v("取消订单")])],1):e._e(),e.orderData.admin_order_btn.verification_btn?r("ls-dialog",{staticClass:"inline m-l-24",attrs:{title:"提货核销",content:"确定核销订单("+e.orderData.sn+")吗?请谨慎操作"},on:{confirm:e.selffetch}},[r("el-button",{attrs:{slot:"trigger",size:"small",type:"primary"},slot:"trigger"},[e._v("提货核销")])],1):e._e(),e.orderData.admin_order_btn.remark_btn?r("ls-dialog",{staticClass:"inline m-l-24",attrs:{title:"商家备注"},on:{confirm:e.postOrderRemarks}},[r("el-button",{staticStyle:{},attrs:{slot:"trigger",size:"small"},slot:"trigger"},[e._v("商家备注")]),r("div",[r("span",{staticClass:"m-b-10"},[e._v("商家备注")]),r("el-input",{staticClass:"m-t-10",attrs:{type:"textarea",rows:5,placeholder:"请输入内容"},model:{value:e.remarks,callback:function(t){e.remarks=t},expression:"remarks"}})],1)],1):e._e(),e.orderData.admin_order_btn.logistics_btn?r("order-logistics",{staticClass:"m-l-24",attrs:{flag:!1,id:e.id}},[r("el-button",{attrs:{slot:"trigger",size:"small",type:"primary"},slot:"trigger"},[e._v("物流查询")])],1):e._e(),e.orderData.admin_order_btn.deliver_btn?r("order-logistics",{staticClass:"m-l-24",attrs:{flag:!0,id:e.id,orderType:e.orderData.order_type},on:{update:e.getOrderDetail}},[r("el-button",{attrs:{slot:"trigger",size:"small",type:"primary"},slot:"trigger"},[e._v("发货")])],1):e._e(),e.orderData.admin_order_btn.confirm_btn?r("ls-dialog",{staticClass:"inline m-l-24",attrs:{title:"确认收货",content:"确定确认收货吗?请谨慎操作"},on:{confirm:e.orderConfirm}},[r("el-button",{attrs:{slot:"trigger",size:"small",type:"primary"},slot:"trigger"},[e._v("确认收货")])],1):e._e()],1)])])])]),r("section",[r("div",{staticClass:"ls-card m-t-24 flex flex-wrap col-stretch",staticStyle:{height:"auto"}},[r("div",{staticStyle:{width:"100%"}},[r("div",{staticClass:"nr weight-500 m-b-20 title"},[e._v("买家信息")]),r("el-form",{ref:"form",staticClass:"flex",attrs:{model:e.orderData,"label-width":"120px",size:"small"}},[r("el-form-item",{attrs:{label:"买家昵称"}},[r("div",{staticClass:"username pointer",on:{click:function(t){return e.toUserDetail()}}},[e._v(" "+e._s(e.orderData.nickname)+"（"+e._s(e.orderData.user_sn)+"） ")])])],1)],1)]),r("div",{staticClass:"ls-card m-t-24 flex flex-wrap col-stretch",staticStyle:{height:"auto"}},[r("div",{staticStyle:{width:"100%"}},[r("div",{staticClass:"nr weight-500 m-b-20 title"},[e._v(" "+e._s(2==e.orderData.delivery_type?"提货信息":"用户及收货信息")+" ")]),r("div",{staticClass:"flex col-top"},[r("el-form",{ref:"form",attrs:{model:e.orderData,"label-width":"120px",size:"small"}},[r("el-form-item",{attrs:{label:"配送方式"}},[e._v(" "+e._s(e.orderData.delivery_type_desc)+" ")]),r("el-form-item",{attrs:{label:2==e.orderData.delivery_type?"提货人":"收货人"}},[e._v(e._s(e.orderData.contact))]),r("el-form-item",{attrs:{label:"手机号码"}},[e._v(" "+e._s(e.orderData.mobile)+" ")]),r("el-form-item",{attrs:{label:2==e.orderData.delivery_type?"提货地址":"收货地址"}},[e._v(e._s(e.orderData.delivery_address))])],1),r("el-form",{ref:"form",staticStyle:{"margin-left":"15vw"},attrs:{model:e.orderData,"label-width":"120px",size:"small"}},[1==e.orderData.delivery_type?r("el-form-item",{attrs:{label:"发货状态"}},[e._v(" "+e._s(e.orderData.express_status_desc)+" ")]):e._e(),1==e.orderData.delivery_type?r("el-form-item",{attrs:{label:"物流公司"}},[e._v(" "+e._s(e.orderData.express_name)+" "),e.orderData.order_status>1&&1==e.orderData.send_type?r("el-popover",{attrs:{placement:"top",title:"",width:"300",trigger:"click"}},[r("i",{staticClass:"el-icon-edit primary m-l-30 lg",attrs:{slot:"reference"},slot:"reference"}),r("div",{staticClass:"flex"},[r("el-select",{staticClass:"m-r-24",staticStyle:{width:"188px"},attrs:{placeholder:"请输入物流公司名称"},model:{value:e.express_id,callback:function(t){e.express_id=t},expression:"express_id"}},e._l(e.expressList,(function(e,t){return r("el-option",{key:t,attrs:{label:e.name,value:e.id}})})),1),r("el-button",{staticClass:"m-l-24",attrs:{size:"small",type:"primary"},on:{click:function(t){return e.orderChangeLogistics()}}},[e._v("修改物流公司")])],1)]):e._e()],1):e._e(),1==e.orderData.delivery_type?r("el-form-item",{attrs:{label:"快递单号"}},[e._v(" "+e._s(e.orderData.invoice_no)+" "),e.orderData.order_status>1&&1==e.orderData.send_type?r("el-popover",{attrs:{placement:"top",title:"",width:"300",trigger:"hover"}},[r("i",{staticClass:"el-icon-edit primary m-l-30 lg",attrs:{slot:"reference"},slot:"reference"}),r("div",{staticClass:"flex"},[r("el-input",{staticClass:"m-r-24",staticStyle:{width:"188px"},attrs:{placeholder:"请输入快递单号"},model:{value:e.invoice_no,callback:function(t){e.invoice_no=t},expression:"invoice_no"}}),r("el-button",{staticClass:"m-l-24",attrs:{size:"small",type:"primary"},on:{click:function(t){return e.orderChangeCourierNumber()}}},[e._v("修改快递单号")])],1)]):e._e()],1):e._e(),1==e.orderData.delivery_type?r("el-form-item",{attrs:{label:"发货时间"}},[e._v(e._s(e.orderData.express_time))]):e._e(),2==e.orderData.delivery_type?r("el-form-item",{attrs:{label:"核销状态"}},[e._v(" "+e._s(null==e.orderData.verification_time?"待核销":"已核销")+" ")]):e._e(),2==e.orderData.delivery_type?r("el-form-item",{attrs:{label:"核销码"}},[e._v(e._s(e.orderData.pickup_code))]):e._e(),2==e.orderData.delivery_type?r("el-form-item",{attrs:{label:"自提门店"}},[e._v(e._s(e.orderData.shop_name))]):e._e(),2==e.orderData.delivery_type?r("el-form-item",{attrs:{label:"提货时间"}},[e._v(e._s(e.orderData.verification_time))]):e._e()],1)],1)]),e.orderData.admin_order_btn.address_btn?r("div",{staticClass:"flex col-bottom",staticStyle:{width:"100%"}},[r("div",{staticClass:"border-top flex col-bottom row-left p-t-24",staticStyle:{width:"100%",height:"57px"}},[r("ls-dialog",{staticClass:"inline m-l-24",attrs:{title:"收货地址修改",width:"35vw"},on:{confirm:function(t){return e.orderAddressSet()}}},[r("el-button",{staticStyle:{},attrs:{slot:"trigger",size:"small"},slot:"trigger"},[e._v("修改地址")]),r("div",{staticClass:"flex row-center"},[r("el-form",{ref:"address",attrs:{model:e.address,"label-width":"80px"}},[r("el-form-item",{attrs:{label:"地区",prop:"return_district"}},[r("area-select",{attrs:{width:"280px",province:e.address.province_id,city:e.address.city_id,district:e.address.district_id},on:{"update:province":function(t){return e.$set(e.address,"province_id",t)},"update:city":function(t){return e.$set(e.address,"city_id",t)},"update:district":function(t){return e.$set(e.address,"district_id",t)}}})],1),r("el-form-item",{attrs:{label:"详细地址",prop:"return_address"}},[r("el-input",{staticClass:"ls-input",attrs:{"show-word-limit":""},model:{value:e.address.address,callback:function(t){e.$set(e.address,"address",t)},expression:"address.address"}})],1)],1)],1)],1)],1)]):e._e()]),e.orderData.delivery_content?r("div",{staticClass:"ls-card m-t-24"},[r("div",{staticClass:"nr weight-500 m-b-20 title"},[e._v("发货内容")]),r("div",{staticStyle:{"word-break":"break-all"}},[e._v(e._s(e.orderData.delivery_content))])]):e._e(),r("div",{staticClass:"ls-card m-t-24"},[r("div",{staticClass:"nr weight-500 m-b-20 title"},[e._v("商品信息")]),r("el-table",{ref:"paneTable",staticStyle:{width:"100%"},attrs:{data:e.orderData.order_goods,"header-cell-style":{background:"#f5f8ff",border:"none",color:"#666666",height:"60px",width:"100%"},size:"mini","summary-method":e.getSummaries,"show-summary":!0}},[r("el-table-column",{attrs:{prop:"code",label:"商品编码","min-width":"180"}}),r("el-table-column",{attrs:{label:"商品信息","min-width":"260"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("div",{staticClass:"flex m-t-10"},[r("el-image",{staticClass:"flex-none",staticStyle:{width:"58px",height:"58px"},attrs:{src:t.row.goods_image}}),r("div",{staticClass:"m-l-8 flex-1"},[r("div",{staticClass:"line-2"},[e._v(e._s(t.row.goods_name))]),t.row.supplier_name?r("div",{staticClass:"line-2 muted"},[e._v("供应商："+e._s(t.row.supplier_name))]):e._e(),r("div",{staticClass:"xs muted"},[e._v(e._s(t.row.spec_value_str))])])],1)]}}])}),r("el-table-column",{attrs:{prop:"goods_price",label:"商品价格","min-width":"180"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("span",[e._v("¥"+e._s(t.row.goods_price))])]}}])}),r("el-table-column",{attrs:{prop:"goods_num",label:"购买数量","min-width":"180"}}),r("el-table-column",{attrs:{label:"商品总额",prop:"total_price","min-width":"180"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("span",[e._v("¥"+e._s(t.row.total_price))])]}}])}),r("el-table-column",{attrs:{label:"优惠金额",prop:"discount_price","min-width":"180"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("span",[e._v("¥"+e._s(t.row.discount_price))])]}}])}),r("el-table-column",{attrs:{label:"商品改价",prop:"change_price","min-width":"180"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("span",[e._v("-¥"+e._s(t.row.change_price))]),e.orderData.admin_order_btn.price_btn?r("el-popover",{attrs:{placement:"top",title:"",width:"300",trigger:"hover"}},[r("i",{staticClass:"el-icon-edit primary m-l-30 lg",attrs:{slot:"reference"},slot:"reference"}),r("div",{staticClass:"flex"},[r("el-input",{staticClass:"m-r-24",staticStyle:{width:"188px"},attrs:{placeholder:"请输入要减少的金额"},model:{value:e.goods_price,callback:function(t){e.goods_price=t},expression:"goods_price"}}),r("el-button",{staticClass:"m-l-24",attrs:{size:"small",type:"primary"},on:{click:function(r){return e.orderChangeGoodsPrice(t.row.id)}}},[e._v("修改价格")])],1)]):e._e()]}}])}),r("el-table-column",{attrs:{label:"商品实付总额",prop:"total_pay_price","min-width":"180"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v("¥"+e._s(t.row.total_pay_price))]}}])})],1)],1),r("div",{staticClass:"ls-card m-t-24"},[r("div",{staticClass:"nr weight-500 m-b-20 title"},[e._v("金额明细")]),r("el-form",{ref:"form",attrs:{model:e.orderData,"label-width":"160px",size:"small"}},[r("el-form-item",{attrs:{label:"商品总额"}},[e._v("¥"+e._s(e.orderData.total_goods_price))]),r("el-form-item",{attrs:{label:"商品改价"}},[e._v("-¥"+e._s(e.orderData.change_price||0))]),r("el-form-item",{attrs:{label:"优惠金额"}},[e._v("-¥"+e._s(e.orderData.discount_amount))]),r("el-form-item",{attrs:{label:"商品运费"}},[e._v(" +¥"+e._s(e.orderData.express_price)+" "),e.orderData.admin_order_btn.express_btn?r("el-popover",{attrs:{placement:"top",title:"",width:"300",trigger:"hover"}},[r("i",{staticClass:"el-icon-edit primary m-l-30 lg",attrs:{slot:"reference"},slot:"reference"}),r("div",{staticClass:"flex"},[r("el-input",{staticClass:"m-r-24",staticStyle:{width:"188px"},attrs:{placeholder:"请输入运费"},model:{value:e.express_price,callback:function(t){e.express_price=t},expression:"express_price"}}),r("el-button",{staticClass:"m-l-24",attrs:{size:"small",type:"primary"},on:{click:e.orderChangeExpress}},[e._v("修改运费")])],1)]):e._e()],1),r("el-form-item",{attrs:{label:"商品实付金额"}},[e._v("¥"+e._s(e.orderData.order_amount))])],1)],1)]),r("footer",{staticClass:"flex col-top"},[r("div",{staticClass:"ls-card m-t-24"},[r("div",{staticClass:"nr weight-500 m-b-20 title"},[e._v("订单日志")]),r("el-table",{ref:"paneTable",staticStyle:{width:"100%"},attrs:{data:e.orderData.order_log,size:"mini"}},[r("el-table-column",{attrs:{label:"操作人",prop:"operator",width:"155"}}),r("el-table-column",{attrs:{prop:"channel_desc",label:"操作事件","min-width":"220"}}),r("el-table-column",{attrs:{prop:"create_time",label:"操作时间","min-width":"180"}})],1)],1)])])}),[],!1,null,"5e62a701",null);t.default=m.exports},c7ec:function(e,t,r){"use strict";r("4690")},e1b9:function(e,t,r){"use strict";r("00e2")},ffae:function(e,t,r){"use strict";var s=r("9ab4"),a=r("1b40"),i=r("192a");let l=class extends a.e{constructor(){super(...arguments),this.visible=!1,this.fullscreenLoading=!1,this.orderData={traces:{}},this.form={send_type:1,express_id:"",invoice_no:"",remark:"",delivery_content:""}}getOrderDeliveryInfo(){Object(i.q)({id:this.id}).then(e=>{this.orderData=e,this.form.delivery_content=e.delivery_content,this.fullscreenLoading=!1})}getOrderLogistics(){Object(i.t)({id:this.id}).then(e=>{this.orderData=e,this.fullscreenLoading=!1})}orderDelivery(){4==this.orderData.order_type&&(this.form={delivery_content:this.form.delivery_content}),Object(i.p)({id:this.id,...this.form}).then(e=>{this.$emit("update","")})}getSummaries(e){const{columns:t,data:r}=e,s=[];return t.forEach((e,t)=>{if(0===t)return void(s[2]="总价");const a=r.map(t=>Number(t[e.property]));if(!a.every(e=>isNaN(e))){if(4==t)return;s[t]=a.reduce((e,t)=>{const r=Number(t);return isNaN(r)?e:e+t},0),5!==t&&3!==t&&(s[t]="¥"+s[t].toFixed(2))}}),s}handleEvent(e){if("cancel"===e&&this.close(),"confirm"===e){if(this.flag)if(4==this.orderData.order_type){if(""==this.form.delivery_content.trim())return this.$message.error("请输入发货内容")}else if(1==this.form.send_type){if(""==this.form.express_id)return this.$message.error("请选择快递公司");if(""==this.form.invoice_no)return this.$message.error("请填写快递单号")}this.orderDelivery(),this.close()}}onTrigger(){this.fullscreenLoading=!0,1==this.flag?this.getOrderDeliveryInfo():this.getOrderLogistics(),this.visible=!0}close(){this.visible=!1}handleChange(e){this.form.delivery_content=e.trim()}};Object(s.a)([Object(a.c)({default:"5vh"})],l.prototype,"top",void 0),Object(s.a)([Object(a.c)({default:"0"})],l.prototype,"id",void 0),Object(s.a)([Object(a.c)({default:!0})],l.prototype,"flag",void 0),Object(s.a)([Object(a.c)({default:""})],l.prototype,"isShow",void 0),Object(s.a)([Object(a.c)()],l.prototype,"orderType",void 0),l=Object(s.a)([a.a],l);var o=l,d=(r("c7ec"),r("2877")),n=Object(d.a)(o,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",[r("div",{staticClass:"ls-dialog__trigger",on:{click:e.onTrigger}},[e._t("trigger")],2),r("el-dialog",{attrs:{"coustom-class":"ls-dialog__content",title:1==e.flag?"发货":"物流查询",visible:e.visible,width:"70vw",top:e.top,"modal-append-to-body":!1,center:"","before-close":e.close,"close-on-click-modal":!1}},[r("div",{directives:[{name:"loading",rawName:"v-loading",value:0==e.orderData.length,expression:"orderData.length == 0"}],staticStyle:{height:"75vh","overflow-x":"hidden"}},[r("div",[r("div",{staticClass:"nr weight-500 m-b-20"},[e._v("商品信息")]),r("el-table",{ref:"paneTable1",staticStyle:{width:"100%"},attrs:{data:e.orderData.order_goods,size:"mini","summary-method":e.getSummaries,"show-summary":!0}},[r("el-table-column",{attrs:{label:"序号",prop:"id",width:"75"}}),r("el-table-column",{attrs:{label:"商品信息","min-width":"300"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("div",{staticClass:"flex m-t-10"},[r("el-image",{staticClass:"flex-none",staticStyle:{width:"58px",height:"58px"},attrs:{src:t.row.goods_image}}),r("div",{staticClass:"m-l-8 flex-1"},[r("div",{staticClass:"line-2"},[e._v(e._s(t.row.goods_name))])])],1)]}}])}),r("el-table-column",{attrs:{label:"","min-width":"150"}}),r("el-table-column",{attrs:{prop:"spec_value_str",label:"商品规格","min-width":"150"}}),r("el-table-column",{attrs:{prop:"goods_price",label:"商品价格","min-width":"80"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v("¥"+e._s(t.row.goods_price))]}}])}),r("el-table-column",{attrs:{prop:"goods_num",label:"购买数量","min-width":"120"}}),r("el-table-column",{attrs:{label:"优惠金额",prop:"discount_price","min-width":"80"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v("¥"+e._s(t.row.discount_price))]}}])}),r("el-table-column",{attrs:{label:"商品实付总额",prop:"total_pay_price","min-width":"180"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v("¥"+e._s(t.row.total_pay_price))]}}])})],1)],1),1==e.flag?r("div",{staticClass:"m-t-30"},[r("div",{staticClass:"nr weight-500 m-b-20"},[e._v("收货信息")]),r("div",{staticClass:"flex"},[r("div",{staticClass:"m-r-24"},[e._v("收货人： "+e._s(e.orderData.contact))]),r("div",{staticClass:"m-r-24"},[e._v("收货人手机号码： "+e._s(e.orderData.mobile))]),r("div",{staticClass:"m-r-24"},[e._v("收货人地址： "+e._s(e.orderData.delivery_address))])])]):e._e(),1==e.flag?r("div",{staticClass:"m-t-30"},[4==e.orderType?[r("div",{staticClass:"nr weight-500 m-b-20"},[e._v("商品发货")]),r("el-form",{ref:"form",attrs:{model:e.form,"label-width":"80px"}},[r("el-form-item",{attrs:{label:"发货内容"}},[r("el-input",{staticClass:"m-t-10",staticStyle:{width:"530px"},attrs:{type:"textarea",rows:7,placeholder:"请输入内容"},on:{change:e.handleChange},model:{value:e.form.delivery_content,callback:function(t){e.$set(e.form,"delivery_content",t)},expression:"form.delivery_content"}})],1)],1)]:[r("div",{staticClass:"nr weight-500 m-b-20"},[e._v("物流配送")]),r("div",{staticClass:"flex"},[r("el-form",{ref:"form",attrs:{model:e.form,"label-width":"80px"}},[r("el-form-item",{attrs:{label:"配送方式"}},[r("el-radio",{attrs:{label:1},model:{value:e.form.send_type,callback:function(t){e.$set(e.form,"send_type",t)},expression:"form.send_type"}},[e._v("需要物流")]),r("el-radio",{attrs:{label:2},model:{value:e.form.send_type,callback:function(t){e.$set(e.form,"send_type",t)},expression:"form.send_type"}},[e._v("无需物流")])],1),1==e.form.send_type?r("el-form-item",{attrs:{label:"物流公司"}},[r("el-input",{staticStyle:{width:"530px"},attrs:{placeholder:"请输入快递单号"},model:{value:e.form.invoice_no,callback:function(t){e.$set(e.form,"invoice_no",t)},expression:"form.invoice_no"}},[r("template",{slot:"prepend"},[r("div",[r("el-select",{staticStyle:{width:"120px"},attrs:{placeholder:"请选择"},model:{value:e.form.express_id,callback:function(t){e.$set(e.form,"express_id",t)},expression:"form.express_id"}},e._l(e.orderData.express,(function(e,t){return r("el-option",{key:t,attrs:{label:e.name,value:e.id}})})),1)],1)])],2)],1):e._e(),r("el-form-item",{attrs:{label:"发货备注"}},[r("el-input",{staticClass:"m-t-10",staticStyle:{width:"530px"},attrs:{type:"textarea",rows:7,placeholder:"请输入内容"},model:{value:e.form.remark,callback:function(t){e.$set(e.form,"remark",t)},expression:"form.remark"}})],1)],1)],1)]],2):e._e(),0==e.flag?r("div",{staticClass:"m-t-30"},[r("div",{staticClass:"nr weight-500 m-b-20"},[e._v("物流信息")]),r("div",{staticClass:"flex"},[r("div",{staticClass:"m-r-24"},[e._v("发货时间： "+e._s(e.orderData.express_time))]),r("div",{staticClass:"m-r-24"},[e._v("物流公司： "+e._s(e.orderData.express_name||"无"))]),r("div",{staticClass:"m-r-24"},[e._v("物流单号 "+e._s(e.orderData.invoice_no||"无"))])])]):e._e(),0==e.flag?r("div",{staticClass:"m-t-30"},[r("div",{staticClass:"nr weight-500 m-b-20"},[e._v("物流轨迹")]),1==e.orderData.send_type?r("div",[r("el-table",{ref:"paneTable",staticStyle:{width:"100%"},attrs:{data:e.orderData.traces,size:"mini"}},[r("el-table-column",{attrs:{label:"日期",prop:"0","min-width":"205"}}),r("el-table-column",{attrs:{label:"轨迹",prop:"1","min-width":"405"}})],1)],1):r("div",{staticClass:"nr weight-500 m-t-60 flex row-center"},[e._v("无需物流")])]):e._e()]),r("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[r("el-button",{attrs:{size:"small"},on:{click:function(t){return e.handleEvent("cancel")}}},[e._v("取消")]),1==e.flag?r("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(t){return e.handleEvent("confirm")}}},[e._v("发货")]):r("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(t){return e.handleEvent("cancel")}}},[e._v("确认")])],1)])],1)}),[],!1,null,null,null);t.a=n.exports}}]);