<div class="bvh-site-header" id="bvh-site-header">

   <!-- 1. TOP BAR -->
   <div class="bvh-topbar">
       <div class="bvh-wrap">
           <div class="bvh-tb-left">
               <span class="bvh-hide-sm">Giỏ hàng cập nhật mỗi ngày &middot; 100% xác thực</span>
           </div>
           <div class="bvh-tb-right">
               <a href="{{route('lienHe')}}">Liên hệ</a>
               <span class="bvh-sep">|</span>
               <span class="bvh-langs gt-lang notranslate">
                   <a href="#" class="bvh-lang is-active" data-gt-lang="vi" title="Tiếng Việt" aria-label="Tiếng Việt"><img
                           src="/frontend/img/flag-vn.svg" alt=""></a>
                   <a href="#" class="bvh-lang" data-gt-lang="en" title="English" aria-label="English"><img src="/frontend/img/flag-gb.svg"
                           alt=""></a>
                   <a href="#" class="bvh-lang" data-gt-lang="zh-CN" title="中文" aria-label="中文"><img src="/frontend/img/flag-cn.svg" alt=""></a>
                   <a href="#" class="bvh-lang" data-gt-lang="ko" title="한국어" aria-label="한국어"><img src="/frontend/img/flag-kr.svg" alt=""></a>
               </span>
               <span class="bvh-sep">|</span>
               <a class="bvh-tb-phone" href="">{{$setting->phone1}} (Zalo)</a>
           </div>
       </div>
   </div>

   <!-- 2. MAIN NAV -->
   <header class="bvh-nav">
       <div class="bvh-wrap">
           <a class="bvh-wordmark notranslate" href="{{ route('home') }}">
               @if (!empty($setting->logo))
               <img src="{{ url($setting->logo) }}" alt="{{ $setting->company ?? '' }}">
               @endif
           </a>
           <nav>
               <ul class="bvh-nav-links">
                   <li>
                       <a href="">Mặt bằng</a>
                       <div class="bvh-dropdown">
                           <div class="bvh-dropdown-inner">
                               @foreach ($floorPlanCategories ?? [] as $floorCate)
                               <div class="bvh-dd-col">
                                   <div class="bvh-dd-head">{{ $floorCate->name }}</div>
                                   <ul>
                                       @foreach ($floorCate->posts ?? [] as $floorPost)
                                       <li><a href="{{ route('floorPlan', ['slug' => $floorPost->slug]) }}">{{ $floorPost->name }}</a></li>
                                       @endforeach
                                   </ul>
                               </div>
                               @endforeach
                           </div>
                       </div>
                   </li>
                   @foreach (collect($categoryhome ?? [])->whereIn('slug', ['mua', 'thue']) as $tradeCate)
                   <li>
                       <a href="{{ route('allListProCate', ['danhmuc' => $tradeCate->slug]) }}">{{ languageName($tradeCate->name) }}</a>
                       @if ($tradeCate->typeCate->count())
                       <div class="bvh-dropdown">
                           <div class="bvh-dropdown-inner">
                               @foreach ($tradeCate->typeCate as $tradeType)
                               <div class="bvh-dd-col">
                                   @if ($tradeType->typetwo->count())
                                   <div class="bvh-dd-head">{{ languageName($tradeType->name) }}</div>
                                   <ul>
                                       @foreach ($tradeType->typetwo as $tradeChild)
                                       <li><a href="{{ route('allListTypeTwo', ['danhmuc' => $tradeCate->slug, 'loaidanhmuc' => $tradeType->slug, 'loai2' => $tradeChild->slug]) }}">{{ languageName($tradeChild->name) }}</a></li>
                                       @endforeach
                                   </ul>
                                   @else
                                   <ul>
                                       <li><a href="{{ route('allListType', ['danhmuc' => $tradeCate->slug, 'loaidanhmuc' => $tradeType->slug]) }}">{{ languageName($tradeType->name) }}</a></li>
                                   </ul>
                                   @endif
                               </div>
                               @endforeach
                           </div>
                       </div>
                       @endif
                   </li>
                   @endforeach
                   <li>
                       <a href="{{ route('consignment') }}">Ký gửi</a>
                   </li>
               </ul>
           </nav>
           <div class="bvh-nav-tools">
               <span class="bvh-langs gt-lang notranslate">
                   <a href="#" class="bvh-lang is-active" data-gt-lang="vi" title="Tiếng Việt" aria-label="Tiếng Việt"><img
                           src="/frontend/img/flag-vn.svg" alt=""></a>
                   <a href="#" class="bvh-lang" data-gt-lang="en" title="English" aria-label="English"><img src="/frontend/img/flag-gb.svg"
                           alt=""></a>
                   <a href="#" class="bvh-lang" data-gt-lang="zh-CN" title="中文" aria-label="中文"><img src="/frontend/img/flag-cn.svg" alt=""></a>
                   <a href="#" class="bvh-lang" data-gt-lang="ko" title="한국어" aria-label="한국어"><img src="/frontend/img/flag-kr.svg" alt=""></a>
               </span>
               <div class="bvh-nav-burger" id="bvh-nav-burger" role="button" aria-label="Menu"
                   aria-expanded="false" aria-controls="bvh-mobile-menu">
                   <span></span><span></span>
               </div>
           </div>
       </div>
   </header>

   <!-- 3. FULL-SCREEN MOBILE MENU -->
   <div class="bvh-mobile-menu" id="bvh-mobile-menu">
       <div class="bvh-mm-inner">
           <ul id="bvh-mobile-menu-list">
               <li class="bvh-mm-item bvh-mm-l1">
                   <button class="bvh-mm-row" type="button" aria-expanded="false">
                       <span class="bvh-mm-label">Mặt bằng</span>
                       <span class="bvh-mm-chev"></span>
                   </button>
                   <div class="bvh-mm-children">
                       <div class="bvh-mm-children-inner">
                           <ul>
                               @foreach ($floorPlanCategories ?? [] as $floorCate)
                               <li class="bvh-mm-item bvh-mm-l2{{ ($floorCate->posts ?? collect())->count() ? '' : ' bvh-mm-leaf' }}">
                                   @if (($floorCate->posts ?? collect())->count())
                                   <button class="bvh-mm-row" type="button" aria-expanded="false">
                                       <span class="bvh-mm-label">{{ $floorCate->name }}</span>
                                       <span class="bvh-mm-chev"></span>
                                   </button>
                                   <div class="bvh-mm-children">
                                       <div class="bvh-mm-children-inner">
                                           <ul>
                                               @foreach ($floorCate->posts as $floorPost)
                                               <li class="bvh-mm-item bvh-mm-l3 bvh-mm-leaf">
                                                   <a class="bvh-mm-row" href="{{ route('floorPlan', ['slug' => $floorPost->slug]) }}">
                                                       <span class="bvh-mm-label">{{ $floorPost->name }}</span>
                                                   </a>
                                               </li>
                                               @endforeach
                                           </ul>
                                       </div>
                                   </div>
                                   @else
                                   <span class="bvh-mm-row">
                                       <span class="bvh-mm-label">{{ $floorCate->name }}</span>
                                   </span>
                                   @endif
                               </li>
                               @endforeach
                           </ul>
                       </div>
                   </div>
               </li>
               @foreach (collect($categoryhome ?? [])->whereIn('slug', ['mua', 'thue']) as $tradeCate)
               <li class="bvh-mm-item bvh-mm-l1{{ $tradeCate->typeCate->count() ? '' : ' bvh-mm-leaf' }}">
                   @if ($tradeCate->typeCate->count())
                   <button class="bvh-mm-row" type="button" aria-expanded="false">
                       <span class="bvh-mm-label">{{ languageName($tradeCate->name) }}</span>
                       <span class="bvh-mm-chev"></span>
                   </button>
                   <div class="bvh-mm-children">
                       <div class="bvh-mm-children-inner">
                           <ul>
                               @foreach ($tradeCate->typeCate as $tradeType)
                               <li class="bvh-mm-item bvh-mm-l2{{ $tradeType->typetwo->count() ? '' : ' bvh-mm-leaf' }}">
                                   @if ($tradeType->typetwo->count())
                                   <button class="bvh-mm-row" type="button" aria-expanded="false">
                                       <span class="bvh-mm-label">{{ languageName($tradeType->name) }}</span>
                                       <span class="bvh-mm-chev"></span>
                                   </button>
                                   <div class="bvh-mm-children">
                                       <div class="bvh-mm-children-inner">
                                           <ul>
                                               @foreach ($tradeType->typetwo as $tradeChild)
                                               <li class="bvh-mm-item bvh-mm-l3 bvh-mm-leaf">
                                                   <a class="bvh-mm-row" href="{{ route('allListTypeTwo', ['danhmuc' => $tradeCate->slug, 'loaidanhmuc' => $tradeType->slug, 'loai2' => $tradeChild->slug]) }}">
                                                       <span class="bvh-mm-label">{{ languageName($tradeChild->name) }}</span>
                                                   </a>
                                               </li>
                                               @endforeach
                                           </ul>
                                       </div>
                                   </div>
                                   @else
                                   <a class="bvh-mm-row" href="{{ route('allListType', ['danhmuc' => $tradeCate->slug, 'loaidanhmuc' => $tradeType->slug]) }}">
                                       <span class="bvh-mm-label">{{ languageName($tradeType->name) }}</span>
                                   </a>
                                   @endif
                               </li>
                               @endforeach
                           </ul>
                       </div>
                   </div>
                   @else
                   <a class="bvh-mm-row" href="{{ route('allListProCate', ['danhmuc' => $tradeCate->slug]) }}">
                       <span class="bvh-mm-label">{{ languageName($tradeCate->name) }}</span>
                   </a>
                   @endif
               </li>
               @endforeach
               <li class="bvh-mm-item bvh-mm-l1 bvh-mm-leaf">
                   <a class="bvh-mm-row" href="{{ route('consignment') }}">
                       <span class="bvh-mm-label">Ký gửi</span>
                   </a>
               </li>
           </ul>
       </div>
   </div>

</div>