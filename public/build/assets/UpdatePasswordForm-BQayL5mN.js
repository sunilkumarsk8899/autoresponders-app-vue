import{r as m,x as _,o as c,c as i,e as o,a as e,u as r,w,f as v,j as y,T as g,g as V}from"./app-jZI65Cpt.js";import{_ as l,a as n,b as d}from"./TextInput-B5FaQ-lJ.js";import{P as x}from"./PrimaryButton-CAC96Uv6.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const P={class:"flex items-center gap-4"},k={key:0,class:"text-sm text-gray-600"},I={__name:"UpdatePasswordForm",setup(b){const u=m(null),p=m(null),s=_({current_password:"",password:"",password_confirmation:""}),f=()=>{s.put(route("password.update"),{preserveScroll:!0,onSuccess:()=>s.reset(),onError:()=>{s.errors.password&&(s.reset("password","password_confirmation"),u.value.focus()),s.errors.current_password&&(s.reset("current_password"),p.value.focus())}})};return(S,a)=>(c(),i("section",null,[a[4]||(a[4]=o("header",null,[o("h2",{class:"text-lg font-medium text-gray-900"}," Update Password "),o("p",{class:"mt-1 text-sm text-gray-600"}," Ensure your account is using a long, random password to stay secure. ")],-1)),o("form",{onSubmit:V(f,["prevent"]),class:"mt-6 space-y-6"},[o("div",null,[e(l,{for:"current_password",value:"Current Password"}),e(n,{id:"current_password",ref_key:"currentPasswordInput",ref:p,modelValue:r(s).current_password,"onUpdate:modelValue":a[0]||(a[0]=t=>r(s).current_password=t),type:"password",class:"mt-1 block w-full",autocomplete:"current-password"},null,8,["modelValue"]),e(d,{message:r(s).errors.current_password,class:"mt-2"},null,8,["message"])]),o("div",null,[e(l,{for:"password",value:"New Password"}),e(n,{id:"password",ref_key:"passwordInput",ref:u,modelValue:r(s).password,"onUpdate:modelValue":a[1]||(a[1]=t=>r(s).password=t),type:"password",class:"mt-1 block w-full",autocomplete:"new-password"},null,8,["modelValue"]),e(d,{message:r(s).errors.password,class:"mt-2"},null,8,["message"])]),o("div",null,[e(l,{for:"password_confirmation",value:"Confirm Password"}),e(n,{id:"password_confirmation",modelValue:r(s).password_confirmation,"onUpdate:modelValue":a[2]||(a[2]=t=>r(s).password_confirmation=t),type:"password",class:"mt-1 block w-full",autocomplete:"new-password"},null,8,["modelValue"]),e(d,{message:r(s).errors.password_confirmation,class:"mt-2"},null,8,["message"])]),o("div",P,[e(x,{disabled:r(s).processing},{default:w(()=>a[3]||(a[3]=[v("Save")])),_:1},8,["disabled"]),e(g,{"enter-active-class":"transition ease-in-out","enter-from-class":"opacity-0","leave-active-class":"transition ease-in-out","leave-to-class":"opacity-0"},{default:w(()=>[r(s).recentlySuccessful?(c(),i("p",k," Saved. ")):y("",!0)]),_:1})])],32)]))}};export{I as default};
