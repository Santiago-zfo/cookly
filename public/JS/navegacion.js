(() => {
'use strict';
const header=document.querySelector('.cookly-nav');if(!header)return;
const menu=document.getElementById('cookly-menu'),account=document.getElementById('cookly-account');
const mobile=matchMedia('(max-width:1050px)');
const resize=()=>{if(menu)menu.open=!mobile.matches;document.body.style.setProperty('--cookly-header-space',`${Math.ceil(header.getBoundingClientRect().height)+24}px`);};
mobile.addEventListener('change',resize);resize();
if(window.ResizeObserver)new ResizeObserver(()=>document.body.style.setProperty('--cookly-header-space',`${Math.ceil(header.getBoundingClientRect().height)+24}px`)).observe(header);
if(menu)menu.addEventListener('toggle',()=>{if(!mobile.matches&&!menu.open)menu.open=true;if(menu.open&&mobile.matches&&account)account.open=false;});
if(account)account.addEventListener('toggle',()=>{if(account.open&&mobile.matches&&menu)menu.open=false;});
document.addEventListener('click',event=>{if(account&&!account.contains(event.target))account.open=false;if(menu&&mobile.matches&&!menu.contains(event.target))menu.open=false;});
document.addEventListener('keydown',event=>{if(event.key==='Escape'){if(account?.open){account.open=false;account.querySelector('summary').focus();}else if(menu?.open&&mobile.matches){menu.open=false;menu.querySelector('summary').focus();}}});
if(menu)menu.querySelectorAll('a').forEach(a=>a.addEventListener('click',()=>{if(mobile.matches)menu.open=false;}));
})();
