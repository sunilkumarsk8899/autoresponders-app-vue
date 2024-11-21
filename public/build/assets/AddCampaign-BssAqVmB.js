import{Q as q,r as n,d as G,b as J,o as l,c,a as m,u as _,Z as K,w as B,e as a,i as R,n as W,f as X,g as Y,h as D,v as S,t as d,j as u,F as p,k as v}from"./app-jZI65Cpt.js";import{_ as ee}from"./AuthenticatedLayout-zC3V_ZqV.js";import{s as te}from"./vue-multiselect.min-bO7X9CGe.js";import"./ApplicationLogo-BHU40evo.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const ae={class:"py-12"},se={class:"mx-auto max-w-7xl sm:px-6 lg:px-8"},oe={class:"overflow-hidden bg-white shadow-sm sm:rounded-lg"},ne={class:"p-6 text-gray-900"},le={class:"container"},ce={class:"row mt-5"},ie={class:"col"},re={class:"form-group mb-3"},de={key:0,id:"",class:"form-text text-danger"},ue={class:"form-group mb-3"},pe={key:0,id:"",class:"form-text text-danger"},me={class:"form-group mb-3"},ve=["value"],ge={key:0,class:"form-group mb-3"},ke=["value"],_e={key:1,class:"form-group mb-3"},be={class:"form-group mb-3"},ye=["value"],fe={key:2,class:"form-group mb-3"},Ce=["value"],De={__name:"AddCampaign",props:{user:Object,settings:Object},setup(V){const N=V,I=q().props.auth.user,{id:P}=I,$=P;n("");const b=n({}),y=n(!1),f=n(!1);n({});const C=n({}),L=n({}),A=n(!1),g=n(""),w=n("");n("");const x=n(""),i=n({name:"",desc:"",user_id:$,clickbank_id:"",clickbank_account_name:"",selected_clickbank_products_name:[],active_campaign_id:"",active_campaign_list_id:""}),k=n([]),r=n({name:"",desc:""}),U=G.useToast(),h=(t="success",e)=>{U.open({message:e,type:t,duration:3e3,position:"top-right"})};J(()=>{j(),E()});const T=async()=>{var e,s;const t=k.value.map(o=>o["@sku"]);i.value.selected_clickbank_products_name=t,r.value={};try{const o=await axios.post("/api/v1/store-campaign",i.value,{headers:{"Content-Type":"application/json"}});console.log(o),o.data.resp.id?h("success",o.data.message):h("error","Somtheing wen't wrong!")}catch(o){r.value.name=(e=o.response.data.errors)!=null&&e.name?o.response.data.errors.name[0]:"",r.value.desc=(s=o.response.data.errors)!=null&&s.desc?o.response.data.errors.desc[0]:""}},j=async()=>{try{const e=await axios.get("/api/v1/get-accounts/clickbank");b.value=e.data.data}catch(t){console.log(t)}},E=async()=>{try{const e=await axios.get("/api/v1/get-accounts/activecampaign");C.value=e.data.data}catch(t){console.log(t)}},H=async t=>{try{const e=await axios.get(`/api/v1/get-active-campaing/lists/${t}`);console.log(e)}catch(e){console.log(e)}},O=async()=>{try{let t=g.value;const e=await axios.get(`/api/v1/clickbank/get/${t}/accounts`);w.value=e.data.response.accountData}catch(t){console.log(t)}},F=async()=>{try{let t=g.value,e=i.value.clickbank_account_name;const s=await axios.get(`/api/v1/clickbank/get/${t}/products/${e}`);x.value=s.data.response.products.product}catch(t){console.log(t)}},M=t=>{y.value=!0,i.value.clickbank_id=t.target.value,g.value=t.target.value,O()},z=t=>{f.value=!0,i.value.clickbank_account_name=t.target.value,F()},Q=t=>{A.value=!0;const e=t.target.value;i.value.active_campaign_id=t.target.value,H(e),console.log("active campaing list id",e)},Z=t=>{i.value.active_campaign_list_id=t.target.value};return(t,e)=>(l(),c(p,null,[m(_(K),{title:N.settings.title},null,8,["title"]),m(ee,null,{default:B(()=>[a("div",ae,[a("div",se,[a("div",oe,[a("div",ne,[m(_(R),{href:t.route("dashboard"),class:W(["rounded-md px-3 py-2 text-white ring-1 ring-transparent transition",t.route().current("campaign.add")?"btn btn-info":"btn btn-secondary"])},{default:B(()=>e[3]||(e[3]=[X(" Campaign List ")])),_:1},8,["href","class"])])])])]),a("div",le,[a("div",ce,[a("div",ie,[a("form",{onSubmit:Y(T,["prevent"])},[a("div",re,[e[4]||(e[4]=a("label",{for:"name"},"Name",-1)),D(a("input",{type:"text",class:"form-control",id:"name",placeholder:"Enter Name","onUpdate:modelValue":e[0]||(e[0]=s=>i.value.name=s)},null,512),[[S,i.value.name]]),r.value.name?(l(),c("small",de,d(r.value.name),1)):u("",!0)]),a("div",ue,[e[5]||(e[5]=a("label",{for:"desc"},"Description",-1)),D(a("input",{type:"text",class:"form-control",id:"desc",placeholder:"Enter Description","onUpdate:modelValue":e[1]||(e[1]=s=>i.value.desc=s)},null,512),[[S,i.value.desc]]),r.value.desc?(l(),c("small",pe,d(r.value.desc),1)):u("",!0)]),a("div",me,[e[7]||(e[7]=a("label",{for:"desc"},"ClickBank API Accounts",-1)),a("select",{name:"",id:"",class:"w-100 rounded",style:{"border-color":"#dee2e6"},onChange:M},[e[6]||(e[6]=a("option",{value:""},"Select ClickBank API Account",-1)),(l(!0),c(p,null,v(b.value,(s,o)=>(l(),c("option",{key:o,value:s.id},d(s.name),9,ve))),128))],32)]),y.value?(l(),c("div",ge,[e[9]||(e[9]=a("label",{for:"desc"},"ClickBank User Accounts",-1)),a("select",{name:"",id:"",class:"w-100 rounded",style:{"border-color":"#dee2e6"},onChange:z},[e[8]||(e[8]=a("option",{value:""},"Select ClickBank User Account",-1)),(l(!0),c(p,null,v(w.value,(s,o)=>(l(),c("option",{key:o,value:s.nickName},d(s.nickName),9,ke))),128))],32)])):u("",!0),f.value?(l(),c("div",_e,[e[10]||(e[10]=a("label",{for:"clickbank-products"},"ClickBank Products",-1)),m(_(te),{modelValue:k.value,"onUpdate:modelValue":e[2]||(e[2]=s=>k.value=s),options:x.value,multiple:!0,"track-by":"@sku",label:"@sku",placeholder:"Select ClickBank Products",class:"w-100"},null,8,["modelValue","options"])])):u("",!0),a("div",be,[e[12]||(e[12]=a("label",{for:"desc"},"Active API Campaign",-1)),a("select",{name:"",id:"",class:"w-100 rounded",style:{"border-color":"#dee2e6"},onChange:Q},[e[11]||(e[11]=a("option",{value:""},"Select ActiveCampaign Account",-1)),(l(!0),c(p,null,v(C.value,(s,o)=>(l(),c("option",{key:s.id,value:s.id},d(s.name),9,ye))),128))],32)]),A.value?(l(),c("div",fe,[e[14]||(e[14]=a("label",{for:"desc"},"Active Campaign Lists",-1)),a("select",{name:"",id:"",class:"w-100 rounded",style:{"border-color":"#dee2e6"},onChange:Z},[e[13]||(e[13]=a("option",{value:""},"Select ActiveCampaign Account",-1)),(l(!0),c(p,null,v(L.value,(s,o)=>(l(),c("option",{key:s.id,value:s.id},d(s.name),9,Ce))),128))],32)])):u("",!0),e[15]||(e[15]=a("button",{type:"submit",class:"btn btn-primary"},"Submit",-1))],32)])])])]),_:1})],64))}};export{De as default};
