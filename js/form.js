!function(e){var t={};function n(d){if(t[d])return t[d].exports;var i=t[d]={i:d,l:!1,exports:{}};return e[d].call(i.exports,i,i.exports,n),i.l=!0,i.exports}n.m=e,n.c=t,n.d=function(e,t,d){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:d})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var d=Object.create(null);if(n.r(d),Object.defineProperty(d,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var i in e)n.d(d,i,function(t){return e[t]}.bind(null,i));return d},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="/js/",n(n.s=267)}({267:function(e,n){function d(e){return(d="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}!function(){"use strict";function e(){var e=document.getElementById("srgdev-ncfp_fbtn");this.checked?e.hasAttribute("shade")&&e.removeAttribute("shade"):e.hasAttribute("shade")||e.setAttribute("shade","1"),this.hasAttribute("err")&&this.removeAttribute("err"),this.hasAttribute("required")&&this.removeAttribute("required")}function n(){this.setCustomValidity(""),this.getAttribute("err")?(this.removeAttribute("err"),this.removeEventListener("focus",n,!1)):this.removeEventListener("input",n,!1)}function i(e){var d=0,i=document.getElementById("srgdev-ncfp_fbtn");if(!0===i.disabled)return e.preventDefault(),e.stopPropagation(),!1;var r,a=(i=document.getElementById("srgdev-ncfp_sel-hidden")).selectedIndex;if(-1===a||""===i.value?((i=document.getElementById("srgdev-ncfp_sel-dummy")).setAttribute("err","err"),i.addEventListener("focus",n,!1),d=1):r=i.dataRef[a].tzi,null!==(i=document.getElementById("srgdev-ncfp_talk_type"))&&1!==(a=i.selectedIndex)&&2!==a&&(i.setAttribute("err","err"),i.addEventListener("focus",n,!1),d=1),(i=document.getElementById("srgdev-ncfp_fname")).value.length<3&&(i.setCustomValidity(t("appointments","Name is required.")),i.addEventListener("input",n,!1),d=1),((i=document.getElementById("srgdev-ncfp_femail")).value.length<5||-1===i.value.indexOf("@")||i.value.indexOf("@")>i.value.lastIndexOf("."))&&(i.setCustomValidity(t("appointments","Email is required.")),i.addEventListener("input",n,!1),d=1),null!==(i=document.getElementById("srgdev-ncfp_fphone"))&&(""===i.value||i.value.length<9||!1===/^[0-9 .()\-+,/]*$/.test(i.value))&&(i.setCustomValidity(t("appointments","Phone number is required.")),i.addEventListener("input",n,!1),d=1),null!==(i=document.getElementById("appt_gdpr_id"))&&!1===i.checked&&(i.setAttribute("err","err"),i.setAttribute("required","1"),d=1),0!==d)return e.preventDefault(),e.stopPropagation(),!1;document.getElementById("srgdev-ncfp_fbtn-spinner").style.display="inline-block",(i=document.createElement("input")).type="hidden",i.name="tzi",i.value=r,this.appendChild(i)}function r(e){var t=document.getElementById("srgdev-dpu_main-cont");return null===t.getAttribute("data-open")?t.setAttribute("data-open",""):t.removeAttribute("data-open"),e.preventDefault(),!1}function a(e){var t=this.id.slice(13),n=this.parentElement.curActive;n!==t&&(document.getElementById("srgdev-dpu_dc"+n).removeAttribute("data-active"),document.getElementById("srgdev-dpu_dc"+t).setAttribute("data-active",""),this.parentElement.curActive=t,"e"===t.slice(-1)&&(t="e"),"e"===n.slice(-1)&&(n="e"),document.getElementById("srgdev-dpu_tc"+n).removeAttribute("data-active"),document.getElementById("srgdev-dpu_tc"+t).setAttribute("data-active",""),e.stopPropagation())}function s(e){var t=e.target;if(void 0!==t.parentElement.dpuClickID&&(t=t.parentElement),void 0!==t.dpuClickID){document.getElementById("srgdev-ncfp_sel-dummy").value=t.parentElement.getAttribute("data-dm")+" - "+t.timeAt;var n=document.getElementById("srgdev-ncfp_sel-hidden");n.selectedIndex=t.dpuClickID,n.value=n.dataRef[t.dpuClickID].d,document.getElementById("srgdev-dpu_main-cont").removeAttribute("data-open")}}function u(e){var t;void 0!==e.target?(t=e.target.parentElement,"srgdev-dpu_bf-back"===e.target.id?t.curDP>0&&t.curDP--:(t.curDP<t.maxDP&&t.curDP++,t.curDP===t.maxDP?e.target.setAttribute("disabled",""):e.target.removeAttribute("disabled"))):t=e,0===t.curDP?t.firstElementChild.setAttribute("disabled",""):t.firstElementChild.removeAttribute("disabled"),t.curDP===t.maxDP?t.lastElementChild.setAttribute("disabled",""):t.lastElementChild.removeAttribute("disabled"),document.getElementById("srgdev-dpu_main-date").style.left="-"+5*t.curDP*4.6+"em"}function o(e){if(void 0!==e.changedTouches&&e.changedTouches.length>0){var t=e.changedTouches[0],n=this.touchInfo;n.x=t.clientX,n.y=t.clientY,n.id=t.identifier}}function l(e){if(void 0!==e.changedTouches&&e.changedTouches.length>0){var t=e.changedTouches[0],n=this.touchInfo;if(t.identifier===n.id){var d=t.clientX-n.x|0,i=t.clientY-n.y|0,r=d>>31,a=d+r^r;a>(i+(r=i>>31)^r)&&a>50&&(d<0?this.bfNav.lastElementChild.click():this.bfNav.firstElementChild.click())}n.id=-1}}window.addEventListener("DOMContentLoaded",(function(){var t=document.getElementById("appt_gdpr_id");null!==t&&(t.addEventListener("change",e),e.apply(t));var n=document.getElementById("srgdev-ncfp_frm");n.addEventListener("submit",i),setTimeout((function(){n.autocomplete="on"}),1e3),function(e){for(var t,n="nbrWeeks",i="showEmpty",r="startFNED",c="showWeekends",m="showTZ",p="time2Cols",v="endTime",f={},g=e.split("."),h=g.length,b=0;b<h;b++)f[(t=g[b].split(":"))[0]]=+t[1];var y=7*f[n],E=document.getElementById("srgdev-ncfp_sel-hidden");if("2"!==E.getAttribute("data-state"))return void console.log("data-state: ",E.getAttribute("data-state"));var C,A,T=E.getAttribute("data-hdr"),_=E.getAttribute("data-tr-back"),I=E.getAttribute("data-tr-next"),D=E.getAttribute("data-tr-not-available");C=void 0!==window.monthNames?window.monthNames:["January","February","March","April","May","June","July","August","September","October","November","December"];A=void 0!==window.dayNames?window.dayNames:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];var x,w,N,k,B,P,S=window.Intl&&"object"===d(window.Intl),L=document.documentElement.lang;if(S){x=new Intl.DateTimeFormat([L],{hour:"numeric",minute:"2-digit"}).format}else x=function(e){return e.toLocaleTimeString()};if(S){w=new Intl.DateTimeFormat([L],{hour:"numeric",minute:"2-digit",timeZoneName:"short"}).format}else w=function(e){return e.toLocaleTimeString()};if(S){var O=new Intl.DateTimeFormat([L],{month:"long"});N=O.format}else N=function(e){return C[e.getMonth()]};if(S){var M=new Intl.DateTimeFormat([L],{weekday:"short"});k=M.format}else k=function(e){return A[e.getDay()]};if(S){B=new Intl.DateTimeFormat([L],{weekday:"short",month:"long",day:"2-digit"}).format}else B=function(e){return e.toDateString()};if(S){P=new Intl.DateTimeFormat([L],{weekday:"long",month:"long",day:"numeric",year:"numeric"}).format}else P=function(e){return e.toLocaleDateString()};var F=[],j=void 0;if(S){try{j=Intl.DateTimeFormat().resolvedOptions().timeZone}catch(e){console.log("no Intl timeZone ",e)}"string"!=typeof j&&(j=void 0)}for(var q,z,H,R,V,Z,J,W,X,Y,$=new Date,G=f[v],K=f[m],Q=E.getAttribute("data-info").split(","),U=Q.length,ee=0;ee<U;ee++){if(Z=(Y=Q[ee]).indexOf(":",8),$.setTime(1e3*+Y.substr(1,Z-1)),q=$.getTimezoneOffset(),W=0,"F"===(H=Y.charAt(0))?(W=6e4*q,$.setTime($.getTime()+W),R=V=x($)):0===K?R=V=x($):(R=V=w($),1===G&&(R=x($))),X=$.getTime(),1===G&&(J=Z+1,Z=Y.indexOf(":",J),J=1e3*+Y.substr(J,Z-J),$.setTime(X+(J+W-X)),R+="F"===H||0===K?" - "+x($):" - "+w($)),void 0!==j)z=H+j;else{var te=Math.abs(q),ne=Math.floor(te/60),de=te-60*ne;z=H+(q>0?"-":"+")+(ne<10?"0"+ne:ne)+(de<10?"0"+de:de)}Z++,J=Y.indexOf(":",Z),F[ee]={rts:X,d:Y.substr(Z,J-Z),t:Y.substr(J+2),tzi:z,time:R,timeAt:V}}F.sort((function(e,t){return e.rts>t.rts?1:-1})),F.push({rts:0,d:"",t:"",tzi:"",time:""}),E.dataRef=F;var ie=F.length,re=document.createElement("div");re.id="srgdev-dpu_main-cont",re.className="srgdev-dpu-bkr-cls";var ae=document.createElement("div");ae.id="srgdev-dpu_main-header",ae.appendChild(document.createTextNode(T));var se=document.createElement("div");se.id="srgdev-dpu_main-hdr-icon",se.className="icon-close",se.addEventListener("click",(function(){document.getElementById("srgdev-dpu_main-cont").removeAttribute("data-open")})),ae.appendChild(se),re.appendChild(ae),(se=document.createElement("div")).maxDP=0,se.curDP=0,se.id="srgdev-dpu_bf-cont",se.appendChild(document.createElement("span")),se.appendChild(document.createElement("span")),se.firstElementChild.id="srgdev-dpu_bf-back",se.firstElementChild.appendChild(document.createTextNode(_)),se.firstElementChild.addEventListener("click",u),se.firstElementChild.setAttribute("disabled",""),se.lastElementChild.id="srgdev-dpu_bf-next",se.lastElementChild.appendChild(document.createTextNode(I)),se.lastElementChild.addEventListener("click",u),re.appendChild(se),(ae=document.createElement("div")).id="srgdev-dpu_main-date",ae.className="srgdev-dpu-bkr-cls",ae.style.left="0em",function(e,t){e.touchInfo={x:0,y:0,id:-1},e.bfNav=t,e.addEventListener("touchstart",o),e.addEventListener("touchend",l)}(ae,se),re.appendChild(ae);var ue=document.createElement("div");ue.id="srgdev-dpu_main-time",re.appendChild(ue);var oe,le=0,ce=5,me=new Date,pe=-1,ve=-1,fe=!1,ge=function(e,t){var n=document.createElement("div");n.id="srgdev-dpu_dc"+le+(t?"e":""),n.className="srgdev-dpu-date-cont"+(t?" srgdev-dpu-dc-empty":"");var d=document.createElement("span");return d.className=0!==e.getDay()?"srgdev-dpu-date-wd":"srgdev-dpu-date-wd srgdev-dpu-date-wd-sunday",d.appendChild(document.createTextNode(k(e))),n.appendChild(d),(d=document.createElement("span")).className="srgdev-dpu-date-dn",d.appendChild(document.createTextNode(e.getDate())),n.appendChild(d),(d=document.createElement("span")).className="srgdev-dpu-date-md",d.appendChild(document.createTextNode(N(e))),n.appendChild(d),n.addEventListener("click",a),le===ce&&(ce+=5,se.maxDP++,le>y&&(fe=!0)),++le,n},he=new Date;if(he.setSeconds(1),he.setMinutes(0),he.setHours(0),1===f[i]&&0===f[r]){var be=F[0].rts;me.setTime(be),me.setSeconds(1),me.setMinutes(0),me.setHours(0);var ye=me.getDay();ye>0&&ye<6&&he.setTime(me.getTime()-864e5*(ye-1))}oe=0===f[p]||1===f[v]?"srgdev-dpu-time-unit"+(1===f[v]?"_tn":""):"srgdev-dpu-time-unit2";for(var Ee,Ce,Ae,Te,_e,Ie,De,xe,we=0;we<ie&&0!==(Ce=(xe=F[we]).rts);we++){me.setTime(Ce);var Ne=me.getDate();if(pe!==Ne){if(_e=he.getTime(),he.setTime(me.getTime()),he.setSeconds(1),he.setMinutes(0),he.setHours(0),Te=he.getTime(),1===f[i])for(;_e<Te&&(he.setTime(_e),0===(Ae=0===f[c]?he.getDay():1)||6===Ae||(ae.appendChild(ge(he,!0)),!fe));)_e+=864e5;if(fe){me=he;break}he.setTime(_e+864e5),Ie=ge(me,!1),-1===ve&&(ve=le-1,Ie.setAttribute("data-active","")),ae.appendChild(Ie),(Ie=document.createElement("div")).id="srgdev-dpu_tc"+(le-1),Ie.className="srgdev-dpu-time-cont",(De=document.createElement("div")).className="srgdev-dpu-tc-full-date",De.appendChild(document.createTextNode(P(me))),Ie.appendChild(De),(De=document.createElement("div")).setAttribute("data-dm",B(me)),De.className="srgdev-dpu-tc-tu-wrap",Ie.appendChild(De),ue.appendChild(Ie),pe=Ne}(Ie=document.createElement("span")).className=oe,Ie.dpuClickID=we,Ie.timeAt=xe.timeAt,Ie.appendChild(document.createTextNode(xe.time)),""!==xe.t&&((Ee=document.createElement("span")).className="srgdev-dpu-appt-title",Ee.appendChild(document.createTextNode(xe.t)),Ie.appendChild(Ee)),De.appendChild(Ie)}if(me.setSeconds(0),me.setMinutes(0),me.setHours(1),me.setTime(me.getTime()+864e5),le%5>0)for(var ke,Be=5-le%5,Pe=0;Pe<Be;Pe++)ke=me.getDay(),0!==(ke=0===f[c]?me.getDay():1)&&6!==ke?ae.appendChild(ge(me,!0)):Pe--,me.setTime(me.getTime()+864e5);var Se=document.createElement("div");Se.id="srgdev-dpu_tce",Se.className="srgdev-dpu-time-cont",Se.appendChild(document.createTextNode(D)),ue.appendChild(Se),ue.firstElementChild.setAttribute("data-active",""),ae.curActive=ve.toString(),re.addEventListener("click",s),document.getElementById("srgdev-ncfp_sel_cont").appendChild(re);var Le=Math.floor(ve/5);Le>0&&(se.curDP=Le,u(se))}(n.getAttribute("data-pps")),document.getElementById("srgdev-ncfp_sel-dummy").addEventListener("click",r),setTimeout((function(){var e,t=document.getElementById("srgdev-ncfp_fbtn");e=t.hasAttribute("data-tr-ses-to")?t.getAttribute("data-tr-ses-to"):"Session Timeout. Reload.",t.disabled=!0,t.textContent=e}),9e5)}))}()}});