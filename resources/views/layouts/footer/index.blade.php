<footer class="bvh-footer">
   <div class="bvh-footer__wrap">
       <div class="bvh-footer__main">
           <div class="bvh-footer__brand">
               <a class="bvh-footer__wordmark" href=""><img width="200" src="{{$setting->logo}}" alt=""></a>
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
               @php
                   $fbPage = trim((string) ($setting->facebook ?? ''));
                   if ($fbPage !== '' && !preg_match('#^https?://#i', $fbPage)) {
                       $fbPage = 'https://' . ltrim($fbPage, '/');
                   }
               @endphp
               @if ($fbPage !== '')
               <div class="bvh-footer__fb">
                   <div class="fb-page"
                       data-href="{{ $fbPage }}"
                       data-tabs=""
                       data-width="340"
                       data-hide-cover="true"
                       data-show-facepile="false"
                       data-small-header="false"
                       data-adapt-container-width="true"
                       data-lazy="false">
                       <blockquote cite="{{ $fbPage }}" class="fb-xfbml-parse-ignore">
                           <a href="{{ $fbPage }}" target="_blank" rel="noopener">Facebook</a>
                       </blockquote>
                   </div>
               </div>
               <div id="fb-root"></div>
               <script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v21.0"></script>
               @endif
           </div>
       </div>
       <div class="bvh-footer__bottom">© {{ date('Y') }}. Giỏ hàng cập nhật mỗi ngày.</div>
   </div>
</footer>