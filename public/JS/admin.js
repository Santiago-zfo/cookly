(() => {
'use strict';
const button=document.getElementById('ca-print'),reports=document.getElementById('reportes');
if(button){button.hidden=false;button.addEventListener('click',()=>{if(reports)reports.open=true;window.print();});}
if(location.hash==='#reportes'&&reports)reports.open=true;
document.querySelectorAll('a[href="#reportes"]').forEach(link=>link.addEventListener('click',()=>{if(reports)reports.open=true;}));
const confirmation=document.querySelector('.ca-confirm');if(confirmation)confirmation.scrollIntoView({block:'start',behavior:matchMedia('(prefers-reduced-motion:reduce)').matches?'instant':'smooth'});
let wasOpen=false;window.addEventListener('beforeprint',()=>{if(reports){wasOpen=reports.open;reports.open=true;}});window.addEventListener('afterprint',()=>{if(reports)reports.open=wasOpen;});
})();
