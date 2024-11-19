import{o as r,c as n,a as c,u as a,Z as h,e as t,l as u,w as o,f as l,i as d,F as g,j as x,t as f}from"./app-jZI65Cpt.js";const k={class:"bg-gray-50 text-black/50 dark:bg-black dark:text-white/50"},p={class:"relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"},m={class:"relative w-full max-w-2xl px-6 lg:max-w-7xl"},v={class:"grid items-center gap-2 py-10"},b={key:0,class:"-mx-3 flex flex-1 justify-center"},w={class:"py-16 text-center text-sm text-black dark:text-white/70"},F={__name:"Welcome",props:{canLogin:{type:Boolean},canRegister:{type:Boolean},laravelVersion:{type:String,required:!0},phpVersion:{type:String,required:!0}},setup(s){return(i,e)=>(r(),n(g,null,[c(a(h),{title:"Welcome"}),t("div",k,[e[4]||(e[4]=t("img",{id:"background",class:"absolute -left-20 top-0 max-w-[877px]",src:"https://laravel.com/assets/img/welcome/background.svg"},null,-1)),t("div",p,[t("div",m,[t("header",v,[s.canLogin?(r(),n("nav",b,[i.$page.props.auth.user?(r(),u(a(d),{key:0,href:i.route("dashboard"),class:"rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"},{default:o(()=>e[0]||(e[0]=[l(" Dashboard ")])),_:1},8,["href"])):(r(),n(g,{key:1},[c(a(d),{href:i.route("login"),class:"rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"},{default:o(()=>e[1]||(e[1]=[l(" Log in ")])),_:1},8,["href"]),s.canRegister?(r(),u(a(d),{key:0,href:i.route("register"),class:"rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"},{default:o(()=>e[2]||(e[2]=[l(" Register ")])),_:1},8,["href"])):x("",!0)],64))])):x("",!0)]),e[3]||(e[3]=t("main",{class:"mt-6"},null,-1)),t("footer",w," Laravel v"+f(s.laravelVersion)+" (PHP v"+f(s.phpVersion)+") ",1)])])])],64))}};export{F as default};
