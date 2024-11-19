import{_ as B}from"./AuthenticatedLayout-zC3V_ZqV.js";import{d as S,r as _,Q as T,b as A,A as E,s as l,o as d,c as p,a as n,u,Z as F,w as h,e as t,i as f,n as m,f as w,F as b,k as N,t as o}from"./app-jZI65Cpt.js";import{S as V}from"./sweetalert2-z6gKu1nJ.js";import"./ApplicationLogo-BHU40evo.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const U={class:"py-12"},z={class:"mx-auto max-w-7xl sm:px-6 lg:px-8"},H={class:"overflow-hidden bg-white shadow-sm sm:rounded-lg"},L={class:"p-6 text-gray-900 d-flex gap-3"},M={class:"container"},Q={class:"row mt-5"},Y={class:"col-12"},Z={class:"table table-striped"},j={scope:"row"},q={class:"text-center"},G={class:"text-center"},I=["onClick"],J={class:"text-center"},K=["onClick"],et={__name:"Dashboard",setup(O){const x=async()=>(await V.fire({title:"Are you sure?",text:"This action will permanently delete the setting!",icon:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes, delete it!"})).isConfirmed,v=S.useToast(),r=(e="success",s)=>{v.open({message:s,type:e,duration:3e3,position:"top-right"})},g=_([]),y=T().props.auth.user.id,i=_(!1);A(()=>{c()}),E([i],async()=>{await c(),console.log("refresh")});const c=async()=>{const e=await l.get(`/api/v1/get-campaigns/${y}`);g.value=e.data.campaigns},C=async e=>{try{if(await x()){const a=await l.delete(`/api/v1/delete-campaign/${e}`);r("success",a.data.message),c()}}catch(s){console.error("Error:",s.response?s.response.data:s.message),r("error",s.message)}},k=async e=>{const s=await l.put(`/api/v1/start/${e}/campaign`);i.value=!i.value,r("success",`${s.data.campaign.name} campaign ${s.data.is_start?"Start":"Stop"} successfully`)};return(e,s)=>(d(),p(b,null,[n(u(F),{title:"Dashboard"}),n(B,null,{default:h(()=>[t("div",U,[t("div",z,[t("div",H,[t("div",L,[n(u(f),{href:e.route("campaign.add"),class:m(["rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white",e.route().current("campaign.add")?"btn btn-info":"btn btn-secondary"])},{default:h(()=>s[0]||(s[0]=[w(" Campaing Add ")])),_:1},8,["href","class"])])])])]),t("div",M,[t("div",Q,[t("div",Y,[t("table",Z,[s[2]||(s[2]=t("thead",null,[t("tr",null,[t("th",{scope:"col"},"#"),t("th",{scope:"col"},"Name"),t("th",{scope:"col"},"Description"),t("th",{scope:"col",colspan:"3",class:"text-center"},"Action")])],-1)),t("tbody",null,[(d(!0),p(b,null,N(g.value,(a,$)=>(d(),p("tr",{key:$},[t("th",j,o(a.id),1),t("td",null,o(a.name),1),t("td",null,o(a.description),1),t("td",q,[n(u(f),{href:e.route("campaign.edit",{id:a.id}),class:m(["rounded-md px-3 py-2 text-white ring-1 ring-transparent transition btn btn-secondary"])},{default:h(()=>s[1]||(s[1]=[w(" Edit ")])),_:2},1032,["href"])]),t("td",G,[t("button",{class:"btn btn-danger",onClick:D=>C(a.id)},"Delete",8,I)]),t("td",J,[t("button",{class:m(`btn btn-${a.start==1?"secondary text-white":"success"} font-weight-bold`),onClick:D=>k(a.id)},o(a.start==1?"Stop":"Start"),11,K)])]))),128))])])])])])]),_:1})],64))}};export{et as default};
