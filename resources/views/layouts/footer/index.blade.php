<footer class="bvh-footer">
   <div class="bvh-footer__wrap">
       <div class="bvh-footer__main">
           <div class="bvh-footer__brand">
               <a class="bvh-footer__wordmark" href="">Bestvinhomes<span class="bvh-footer__dot">.</span></a>
               <p class="bvh-footer__about">{{$setting->webname}}</p>
           </div>
           <div class="bvh-footer__vline"></div>
           <div class="bvh-footer__contact">
               <div class="bvh-footer__eyebrow">Liên hệ tư vấn</div>
               <a class="bvh-footer__phone" href="tel:{{$setting->phone1}}">{{$setting->phone1}}</a>
               <p class="bvh-footer__hours">Gọi điện · Zalo · 8:00 – 21:00 mỗi ngày</p>
               <div class="bvh-footer__addr">
                   <span>
                       <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                           stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                           <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                           <circle cx="12" cy="10" r="3" />
                       </svg>
                       {{$setting->address1}} </span>
               </div>
           </div>
       </div>
       <div class="bvh-footer__bottom">© {{ date('Y') }}. Giỏ hàng cập nhật mỗi ngày.</div>
   </div>
</footer>