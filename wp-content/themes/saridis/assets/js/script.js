(()=>{"use strict";const e=e=>{function t(e){let t="+7 (___) ___-__-__",c=0,o=t.replace(/\D/g,""),r=this.value.replace(/\D/g,"");o.length>=r.length&&(r=o),this.value=t.replace(/./g,(function(e){return/[_\d]/.test(e)&&c<r.length?r.charAt(c++):c>=r.length?"":e})),"blur"===e.type?2==this.value.length&&(this.value=""):((e,t)=>{if(t.focus(),t.setSelectionRange)t.setSelectionRange(e,e);else if(t.createTextRange){let c=t.createTextRange();c.collapse(!0),c.moveEnd("character",e),c.moveStart("character",e),c.select()}})(this.value.length,this)}document.querySelectorAll(e).forEach((e=>{e.addEventListener("input",t),e.addEventListener("focus",t),e.addEventListener("blur",t)}))},t=()=>{try{const e=document.querySelectorAll('[href^="#"]'),t=.15,c=e=>{let c=document.documentElement.scrollTop,o=document.querySelector(e).getBoundingClientRect().top-50,r=null;requestAnimationFrame((function e(a){null===r&&(r=a);let s=a-r,l=o<0?Math.max(c-s/t,c+o):Math.min(c+s/t,c+o);document.documentElement.scrollTo(0,l),l!=c+o&&requestAnimationFrame(e)}))};window.location.hash&&c(window.location.hash),e.forEach((e=>{e.addEventListener("click",(function(e){e.preventDefault(),c(this.hash)}))}))}catch(e){console.log(e.stack)}},c=()=>{try{const e=document.querySelector(".profile__settings-avatar");if(e){const t=e.querySelector("input"),c=e.querySelector(".avatar-wrap img");t.addEventListener("change",(function(){if(this.files[0]){const e=new FileReader;e.addEventListener("load",(function(){c.src=e.result}),!1),e.readAsDataURL(this.files[0])}}))}}catch(e){console.log(e.stack)}try{document.querySelectorAll(".checkbox-field").forEach((e=>{const t=e.querySelectorAll(".checkbox-field-item");t.forEach((c=>{c.querySelector("input").addEventListener("change",(()=>{e.classList.contains("radio")&&t.forEach((e=>{c!=e&&(e.classList.remove("active"),e.querySelector("input").checked=!1)})),c.classList.toggle("active")}))}))}))}catch(e){console.log(e.stack)}try{const e=document.querySelectorAll("form.form, form.form-ajax"),t=document.querySelector(".modal"),c=document.querySelector(".modal__success"),o=document.querySelectorAll(".modal__item"),r=document.querySelector(".modal__auth-error");e.forEach((e=>{const a=e.querySelector(".btn");let s=e.getAttribute("data-success")?document.querySelector(".modal__success-"+e.getAttribute("data-success").trim()):"";e.addEventListener("submit",(l=>{l.preventDefault(),a.classList.add("disable");const n=new FormData(e);(async function(e,t){let c=await fetch(e,{method:"POST",body:t});return await c.text()})(e.action,n).then((e=>{a.classList.remove("disable"),t.classList.add("active"),o.forEach((e=>e.classList.remove("active"))),"auth-error"==e?r.classList.add("active"):"restart"==e?window.location.reload():s?s.classList.add("active"):c.classList.add("active"),document.querySelector("body").classList.add("fixed"),document.querySelector("html").classList.add("fixed")}))}))}))}catch(e){console.log(e.stack)}};window.addEventListener("DOMContentLoaded",(()=>{e('input[type="tel"]'),t(),(()=>{try{document.querySelectorAll(".slider").forEach((e=>{let t,c=e.querySelector(".slider-track"),o=e.querySelector(".slider-list"),r=e.querySelector(".slider-dots"),a=e.querySelectorAll(".slider-toggle"),s=e.querySelectorAll(".slide"),l=0,n=e.querySelector(".right"),i=n?n.querySelector(".counter"):"",d=e.querySelector(".left"),u=d?d.querySelector(".counter"):"",m=0,h=s.length,g=0,v=0,y=!1,f=0,L=0;if(r){r.innerHTML="";for(let e=0;e<h;e++)r.innerHTML+="<span></span>";t=r.querySelectorAll("span")}const S=()=>window.innerWidth<=576&&e.getAttribute("data-mob-vis")?+e.getAttribute("data-mob-vis"):window.innerWidth<=768&&e.getAttribute("data-stablet-vis")?+e.getAttribute("data-stablet-vis"):window.innerWidth<=992&&e.getAttribute("data-tablet-vis")?+e.getAttribute("data-tablet-vis"):window.innerWidth<=1400&&e.getAttribute("data-lap-vis")?+e.getAttribute("data-lap-vis"):window.innerWidth<2100&&e.getAttribute("data-pc-vis")?+e.getAttribute("data-pc-vis"):e.getAttribute("data-tv-vis")?+e.getAttribute("data-tv-vis"):1,q=()=>{g=-m*l,c.style.transform=`translateX(-${m*l}px)`,s.forEach((e=>e.classList.remove("active"))),s[m].classList.add("active"),i&&u&&(u.textContent=`${0===m?h:m}/${h}`,i.textContent=`${m+2>h?1:m+2}/${h}`),r&&(t.forEach((e=>e.classList.remove("active"))),t[m].classList.add("active"))},E=()=>{l=s[0].offsetWidth+ +window.getComputedStyle(s[0]).marginRight.replace("px","")},p=()=>-l*(h-S()),w=(e=150)=>{if(y){c.classList.remove("fast"),o.classList.remove("grabbing"),y=!1,g=v;let t=-Math.ceil(L/l);0===t&&(L<-e&&(t=1),L>e&&(t=-1)),m+=t,m<0&&(m=0),m+S()>=h&&(m=h-S()),q(),L=0,f=0}},b=e=>{y=!0,f=e},_=e=>{y&&(c.classList.add("fast"),o.classList.add("grabbing"),L=e-f,v=g+(e-f),v>0&&(v=0),v<p()&&(v=p()),c.style.transform=`translateX(${v}px)`)};s.forEach(((e,t)=>{e.classList.contains("active")&&(m=t+S()>=h?h-S():t)})),E(),q(),o.addEventListener("mousedown",(e=>b(e.clientX))),o.addEventListener("mousemove",(e=>_(e.clientX))),o.addEventListener("mouseup",(()=>w(150))),o.addEventListener("mouseleave",(()=>w(150))),o.addEventListener("touchstart",(e=>b(e.touches[0].clientX))),o.addEventListener("touchmove",(e=>_(e.touches[0].clientX))),o.addEventListener("touchend",(()=>w(50))),n&&n.addEventListener("click",(()=>{m+S()>=h?m=0:m++,q()})),d&&d.addEventListener("click",(()=>{m<=0?m=h-S():m--,q()})),r&&t.forEach(((e,t)=>{e.addEventListener("click",(()=>{m=t,q()}))})),a&&a.forEach((e=>{e.querySelectorAll("span").forEach(((e,t)=>{e.addEventListener("click",(()=>{m=t,q()}))}))})),window.addEventListener("resize",(()=>{E(),q()}))}))}catch(e){console.log(e.stack)}})(),(()=>{try{document.querySelectorAll(".single-catalog__left").forEach((e=>{let t=e.querySelector(".single-catalog__slider-track"),c=e.querySelector(".single-catalog__slider-list"),o=t.querySelectorAll("img"),r=e.querySelectorAll(".single-catalog__big img"),a=e.querySelector(".arrow-next"),s=e.querySelector(".arrow-prev"),l=0,n=0,i=0,d=o.length;const u=()=>{o.forEach((e=>e.classList.remove("active"))),o[i].classList.add("active"),r.forEach((e=>e.classList.remove("active"))),r[i].classList.add("active"),window.innerWidth<=768?t.style.transform=`translateX(-${i*l}px)`:t.style.transform=`translateY(-${i*n}px)`},m=()=>{i+1>=d?i=0:i++,u()},h=()=>{i<=0?i=d-1:i--,u()},g=()=>{l=o[0].offsetWidth+ +window.getComputedStyle(o[0]).marginRight.replace("px",""),n=o[0].offsetHeight+ +window.getComputedStyle(o[0]).marginBottom.replace("px","")};t.style.transition="transform 0.5s ease 0s",g();let v=0;c.addEventListener("touchstart",(e=>{window.innerWidth<=768&&(v=e.changedTouches[0].screenX)})),c.addEventListener("touchend",(e=>{window.innerWidth<=768&&(v-e.changedTouches[0].screenX>50?m():v-e.changedTouches[0].screenX<-50&&h())})),a&&a.addEventListener("click",m),s&&s.addEventListener("click",h),o.forEach(((e,t)=>{e.addEventListener("click",(()=>{i=t,u()}))})),window.addEventListener("resize",(()=>{g(),u()}))}))}catch(e){console.log(e.stack)}})(),c(),(()=>{try{const e=document.querySelectorAll(".filter-range-line input"),t=document.querySelectorAll(".filter-price .from-text, .filter-price .to-text"),c=document.querySelector(".filter-range-line .line");let o=0;const r=r=>{let a=+e[0].value,s=+e[1].value;s-a<o?"range-from"===r.target.className?e[0].value=s-o:e[1].value=a+o:(t[0].textContent=a,t[1].textContent=s,c.style.left=a/e[0].max*100+"%",c.style.right=100-s/e[1].max*100+"%")};r(),e.forEach((e=>{e.addEventListener("input",r)}))}catch(e){console.log(e.stack)}try{const e=document.querySelector(".catalog__list"),t=document.querySelector(".catalog__more");if(e&&t){const c=e.querySelectorAll(".catalog__list-item");let o=window.innerWidth<=576?8:9,r=0,a=c.length;const s=()=>{for(let e=r;e<a&&!(e>=r+o);e++)c[e].classList.remove("hide");r+=o,r>=a&&(t.style.display="none")};s(),t.addEventListener("click",s)}}catch(e){console.log(e.stack)}})(),(()=>{const e=e=>{let t=document.cookie.match(new RegExp("(?:^|; )"+e.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g,"\\$1")+"=([^;]*)"));return t?decodeURIComponent(t[1]):void 0},t=(e,t,c={})=>{(c={path:"/",...c}).expires instanceof Date&&(c.expires=c.expires.toUTCString());let o=encodeURIComponent(e)+"="+encodeURIComponent(t);for(let e in c){o+="; "+e;let t=c[e];!0!==t&&(o+="="+t)}document.cookie=o};try{const e=document.querySelector(".cart__content-side .btn"),c=document.querySelector(".cart__form"),o=document.querySelector(".cart__list"),r=document.querySelector(".modal"),a=document.querySelector(".modal__success"),s=document.querySelectorAll(".modal__item");c.addEventListener("submit",(e=>{e.preventDefault()})),e.addEventListener("click",(()=>{if(o.classList.contains("hide")){e.classList.add("disable");const o=new FormData(c);(async function(e,t){let c=await fetch(e,{method:"POST",body:t});return await c.text()})(c.action,o).then((c=>{e.classList.remove("disable"),r.classList.add("active"),s.forEach((e=>e.classList.remove("active"))),a.classList.add("active"),document.querySelector("body").classList.add("fixed"),document.querySelector("html").classList.add("fixed"),setTimeout((()=>{t("cart","",{"max-age":-1}),window.location.reload()}),2e3)}))}c.classList.remove("hide"),o.classList.add("hide")}))}catch(e){console.log(e.stack)}try{const e=document.querySelectorAll(".cart__list-row.cart-add-parent"),t=document.querySelector(".pre-price"),c=document.querySelector(".cut-price"),o=document.querySelector(".end-price"),r=()=>{setTimeout((()=>{let r=0,a=0;e.forEach((e=>{let t=+e.querySelector(".counter .counter-result").textContent.trim(),c=+e.getAttribute("data-price")*t,o=+e.getAttribute("data-cut")*t;e.querySelector(".price-result-span").textContent=o,r+=c,a+=o})),t.textContent=r,o.textContent=a,c&&(c.textContent=r-a)}),200)};e.forEach((e=>{const t=e.querySelector(".counter .counter-minus"),c=e.querySelector(".counter .counter-plus");t.addEventListener("click",r),c.addEventListener("click",r)}))}catch(e){console.log(e.stack)}try{const c=document.querySelectorAll(".cart-add-parent"),o=document.querySelectorAll(".header__cart");let r=e("cart")?JSON.parse(e("cart")):[];c.forEach((e=>{const c=e.querySelector(".cart-add"),a=e.querySelector(".cart-delete"),s=e.querySelector(".counter");let l=+e.getAttribute("data-id");const n=(c="")=>{let a=s?+s.querySelector(".counter-result").textContent.trim():1,n=[],i=0,d=!1;"delete"==c?(r.forEach((e=>{e[0]!=l&&(i+=e[1],n.push(e))})),e.remove()):"responsive"==c?r.forEach((e=>{e[0]==l&&(e[1]=a),i+=e[1],n.push(e)})):(r.forEach((e=>{e[0]==l&&(e[1]+=a,d=!0),i+=e[1],n.push(e)})),d||(n.push([l,a]),i+=a)),o.forEach((e=>{e.querySelector("span")?e.querySelector("span").textContent=i:e.innerHTML+="<span>"+i+"</span>",0==i&&e.querySelector("span").remove()})),t("cart",JSON.stringify(n),{"max-age":2678400})};s&&(s.querySelector(".counter-minus").addEventListener("click",(()=>{setTimeout((()=>{n("responsive")}),200)})),s.querySelector(".counter-plus").addEventListener("click",(()=>{setTimeout((()=>{n("responsive")}),200)}))),a&&a.addEventListener("click",(()=>n("delete"))),c&&c.addEventListener("click",n)}))}catch(e){console.log(e.stack)}try{const c=document.querySelectorAll(".wishlist-btn, .wish-delete"),o=document.querySelectorAll(".header__favorite");let r=e("favorite")?JSON.parse(e("favorite")):[];const a=e=>{let c=[],a=!1;r.forEach((t=>{t!=e?c.push(t):a=!0})),a||c.push(e),r=c,o.forEach((e=>{e.querySelector("span")?e.querySelector("span").textContent=r.length:e.innerHTML+="<span>"+r.length+"</span>",0==r.length&&e.querySelector("span").remove()})),t("favorite",JSON.stringify(c),{"max-age":2678400})};c.forEach((e=>{let t=+e.getAttribute("data-id").trim();-1==r.indexOf(t)||e.classList.contains("wish-delete")||e.classList.add("active"),e.addEventListener("click",(()=>{a(t),e.classList.contains("wish-delete")?e.closest(".wish-parent").remove():e.classList.toggle("active")}))}))}catch(e){console.log(e.stack)}})(),(()=>{try{const e=document.querySelectorAll(".body-click-content"),t=document.querySelectorAll(".body-click-target");document.body.addEventListener("click",(c=>{if(c.target.classList.contains("body-click-target")||c.target.classList.contains("body-click-close")){c.preventDefault();let o=c.target.getAttribute("data-content")?document.querySelector('.body-click-content[data-content="'+c.target.getAttribute("data-content")+'"]'):c.target.nextElementSibling?c.target.nextElementSibling:"";e.forEach((e=>o!=e&&e.classList.contains("global-hide")?e.classList.remove("active"):"")),t.forEach((e=>e.classList.contains("global-hide")&&e!=c.target?e.classList.remove("active"):"")),o.classList.contains("body-click-content")?o.classList.toggle("active"):c.target.parentElement.classList.remove("active"),!c.target.classList.contains("not-active")&&c.target.classList.toggle("active")}else c.target.closest(".body-click-content")||(e.forEach((e=>e.classList.contains("not-global")?"":e.classList.remove("active"))),t.forEach((e=>e.classList.contains("not-active")||e.classList.contains("not-global")?"":e.classList.remove("active"))))}))}catch(c){console.log(c.stack)}try{const o=document.querySelectorAll(".elem_animate"),r=document.querySelectorAll(".text_animate");function a(e){e.forEach((e=>{(window.innerWidth<=600?window.innerHeight/1.05:window.innerHeight/1.2)+window.scrollY>=e.getBoundingClientRect().y+window.scrollY&&e.classList.add("anim")}))}r.forEach((e=>{let t=e.textContent.trim(),c="",o=0;for(let e=0;e<t.length;e++)c+=`<i class="or" style="transition: 0.4s all ${o.toFixed(2)}s">${t[e]}</i>`,o+=.03;e.innerHTML=c})),a(o),a(r),window.addEventListener("scroll",(()=>{a(o),a(r)}))}catch(s){console.log(s.stack)}try{const l=document.querySelector(".modal"),n=document.querySelectorAll("[data-call-modal]"),i=document.querySelectorAll(".modal__item");n.forEach((e=>{e.getAttribute("data-call-modal")&&e.addEventListener("click",(t=>{t.preventDefault(),i.forEach((e=>e.classList.remove("active"))),l.classList.add("active"),l.querySelector('.modal__item[data-modal="'+e.getAttribute("data-call-modal")+'"]').classList.add("active"),document.querySelector("body").classList.add("fixed"),document.querySelector("html").classList.add("fixed")}))})),l.addEventListener("click",(e=>{(e.target==l||e.target.classList.contains("modal__close")||e.target.classList.contains("modal__hide"))&&(i.forEach((e=>e.classList.remove("active"))),l.classList.remove("active"),document.querySelector("body").classList.remove("fixed"),document.querySelector("html").classList.remove("fixed"))}))}catch(d){console.log(d.stack)}try{const u=document.querySelector(".header__burger"),m=document.querySelector(".header__mobile");u.addEventListener("click",(()=>{u.classList.toggle("active"),m.classList.toggle("active"),document.querySelector("body").classList.toggle("fixed"),document.querySelector("html").classList.toggle("fixed")}))}catch(h){console.log(h.stack)}try{document.querySelectorAll(".counter").forEach((e=>{const t=e.querySelector(".counter-minus"),c=e.querySelector(".counter-plus"),o=e.querySelector(".counter-result");let r=+o.textContent;const a=(e=0)=>{r+=e,r<1&&(r=1),o.textContent=r};t.addEventListener("click",(()=>a(-1))),c.addEventListener("click",(()=>a(1)))}))}catch(g){console.log(g.stack)}try{document.querySelectorAll(".showhide-field").forEach((e=>{const t=e.querySelector(".showhide-list"),c=e.querySelector(".showhide-more");if(t&&c){const o=t.querySelectorAll(".showhide-item");let r=e.getAttribute("data-vis")?+e.getAttribute("data-vis").trim():5,a=0,s=o.length;const l=()=>{for(let e=a;e<s&&!(e>=a+r);e++)o[e].classList.remove("hide");a+=r,a>=s&&(c.style.display="none")};l(),c.addEventListener("click",l)}}))}catch(v){console.log(v.stack)}})()}))})();