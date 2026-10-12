@extends('layouts.main.master')
@section('title')
    {{ $setting->company }}
@endsection
@section('description')
    {{ $setting->webname }}
@endsection
@section('image')
    @php
        $ogBanner = $banner->first();
        $ogImage = $ogBanner && $ogBanner->image ? url($ogBanner->image) : url($setting->logo ?? '');
    @endphp
    {{ $ogImage }}
@endsection
@section('css')

@endsection
@section('js')
@endsection
@section('content')
<div class="bvh-hp">
 
   <!-- ===================== HERO ===================== -->
   @php
       $heroBanner = isset($banner) ? $banner->first() : null;
       $heroTitle = $heroBanner ? trim((string) $heroBanner->title) : '';
       $heroDesc = $heroBanner ? trim((string) $heroBanner->description) : '';
       $heroLink = $heroBanner ? trim((string) $heroBanner->link) : '';
       $heroImage = $heroBanner ? trim((string) $heroBanner->image) : '';
   @endphp
   @if ($heroBanner && ($heroImage !== '' || $heroTitle !== '' || $heroDesc !== ''))
   <section class="hero">
       <div class="hero-bg">
           @if ($heroImage !== '')
           <img id="bvh-hero-img" src="{{ $heroImage }}" alt="{{ $heroTitle !== '' ? $heroTitle : 'Banner' }}">
           @endif
       </div>
       <div class="hp-wrap">
           <div class="hero-inner">
            <div class="eyebrow eyebrow-light"><span class="dash"></span>Vinhomes Central Park · Bình Thạnh
            </div>
               {{-- @if ($heroTitle !== '')
               <h1 class="serif">{!! $heroTitle !!}</h1>
               @endif
               @if ($heroDesc !== '')
               <div class="hero-sub">{!! $heroDesc !!}</div>
               @endif --}}
               <form class="hero-search" action="{{ route('search_result') }}" method="get" role="search">
                   <label class="hs-field hs-cate">
                       <span class="hs-label">Danh mục</span>
                       <select name="cate">
                           <option value="">Tất cả</option>
                           @foreach ($categoryhome ?? [] as $searchCate)
                           <option value="{{ $searchCate->slug }}">{{ languageName($searchCate->name) }}</option>
                           @endforeach
                       </select>
                   </label>
                   <label class="hs-field hs-keyword">
                       <span class="hs-label">Từ khóa</span>
                       <input type="text" name="keyword" placeholder="Tòa, số phòng ngủ, view..." autocomplete="off">
                   </label>
                   <button type="submit" class="hs-submit">Tìm kiếm <span class="arr">&rarr;</span></button>
               </form>
               {{-- <div class="hero-stats">
                   <div class="st">
                       <div class="v">43,91<small> ha</small></div>
                       <div class="l">Tổng diện tích</div>
                   </div>
                   <div class="st">
                       <div class="v">14<small> ha</small></div>
                       <div class="l">Công viên ven sông</div>
                   </div>
                   <div class="st">
                       <div class="v">18<small> tòa</small></div>
                       <div class="l">&amp; 88 biệt thự</div>
                   </div>
                   <div class="st">
                       <div class="v">Sổ hồng</div>
                       <div class="l">Pháp lý rõ ràng</div>
                   </div>
               </div> --}}
           </div>
       </div>
   </section>
   @endif

   <!-- ===================== CREDENTIAL BAND ===================== -->
   {{-- <section class="creds">
       <div class="hp-wrap">
           <div class="cd">
               <div class="k">Chủ đầu tư</div>
               <div class="v">Vingroup</div>
           </div>
           <div class="cd">
               <div class="k">Vị trí</div>
               <div class="v">Ven sông Sài Gòn</div>
           </div>
           <div class="cd">
               <div class="k">Mật độ xây dựng</div>
               <div class="v">18,6%</div>
           </div>
           <div class="cd">
               <div class="k">Bàn giao</div>
               <div class="v">2014 – 2018</div>
           </div>
           <div class="cd">
               <div class="k">Loại hình</div>
               <div class="v">Căn hộ · Penthouse · Biệt thự</div>
           </div>
       </div>
   </section> --}}

   <!-- ===================== LISTINGS ===================== -->
   <section class="hp-section listings" id="listings">
       <div class="hp-wrap">
           {{-- <div class="section-head">
               <div class="eyebrow"><span class="dash"></span>Giỏ hàng bán · Tháng {{ now()->month }}, {{ now()->year }}</div>
               <h2>Tổng hợp căn hộ đang rao bán</h2>
               <div class="rule"></div>
               <p>Chọn căn hộ theo số phòng ngủ. Mỗi căn được xác thực pháp lý và cập nhật giá mỗi ngày.</p>
           </div> --}}


           @include('home.mua-listings')

       </div><!-- /.hp-wrap -->
   </section>
   <!-- ===================== MẶT BẰNG NỔI BẬT ===================== -->
   @if (($featuredPlans ?? collect())->isNotEmpty())
   <section class="hp-section info fplans" id="mat-bang-noi-bat">
       <div class="hp-wrap">
           <div class="section-head">
               <div class="eyebrow"><span class="dash"></span>Mặt bằng nổi bật</div>
               <h2>Layout căn hộ được quan tâm nhiều nhất</h2>
               <div class="rule"></div>
               <p>Xem nhanh mặt bằng tòa, layout từng loại căn và nhật ký giỏ hàng cập nhật mỗi ngày.</p>
           </div>
           <div class="fplan-grid">
               @foreach ($featuredPlans as $fp)
               <article class="fplan-card reveal">
                   <a class="fplan-media{{ $fp->image === '' ? ' is-blank' : '' }}" href="{{ $fp->url }}">
                       @if ($fp->image !== '')
                       <img src="{{ $fp->image }}" alt="{{ $fp->name }}" loading="lazy">
                       @else
                       <span class="fplan-code">{{ $fp->code }}</span>
                       @endif
                       @if (!empty($fp->zone))
                       <span class="fplan-zone">{{ $fp->zone }}</span>
                       @endif
                   </a>
                   <div class="fplan-body">
                       <h3><a href="{{ $fp->url }}">{{ $fp->name }}</a></h3>
                       @if (!empty($fp->intro))
                       <p>{{ $fp->intro }}</p>
                       @endif
                       <div class="fplan-foot">
                           <a class="link-more" href="{{ $fp->url }}">Xem mặt bằng <span class="arr">&rarr;</span></a>
                           @if ($fp->journalDays)
                           <a class="fplan-journal" href="{{ $fp->journalUrl }}">Nhật ký · {{ $fp->journalDays }} ngày</a>
                           @endif
                       </div>
                   </div>
               </article>
               @endforeach
           </div>
       </div>
   </section>
   @endif

   <!-- ===================== SUPPORT CTA ===================== -->
   <section class="hp-section support" id="lien-he">
       <div class="hp-wrap">
           <div class="support-card reveal">
               <div class="eyebrow eyebrow-light"><span class="dash"></span>Hỗ trợ tư vấn</div>
               <h2 class="serif">Nhận ngay danh sách<br>căn hộ đang rao bán</h2>
               <p>Đội ngũ tư vấn của Bestvinhomes sẵn sàng đồng hành cùng bạn — từ chọn căn, xem nhà đến hoàn tất
                   pháp lý.</p>
               <a class="support-phone serif" href="tel:{{$setting->phone1}}">{{$setting->phone1}}</a>
               <div class="support-meta">Gọi điện · Zalo · 8:00 – 21:00 mỗi ngày</div>
               <div class="support-actions">
                   <a class="btn btn-gold" href="">Liên hệ tư vấn <span class="arr">&rarr;</span></a>
                   <a class="btn btn-ghost-light" href="https://zalo.me/{{$setting->phone1}}" target="_blank" rel="noopener">Nhắn qua Zalo</a>
               </div>
               <div class="support-trust">
                   <span>100% xác thực</span>
                   <span class="dotsep"></span>
                   <span>Cập nhật mỗi ngày</span>
                   <span class="dotsep"></span>
                   <span>Pháp lý minh bạch</span>
               </div>
           </div>
       </div>
   </section>

   <!-- ===================== PROJECT INFO ===================== -->
   <div id="duan">

       <!-- Bảng giá -->
       @if (($homePrices ?? collect())->isNotEmpty())
       <section class="hp-section info">
           <div class="hp-wrap">
               <div class="section-head">
                   @if (!empty(optional($homePriceMeta)->eyebrow))
                   <div class="eyebrow"><span class="dash"></span>{{ $homePriceMeta->eyebrow }}</div>
                   @endif
                   @if (!empty(optional($homePriceMeta)->title))
                   <h2>{{ $homePriceMeta->title }}</h2>
                   <div class="rule"></div>
                   @endif
               </div>
               <div class="price-table">
                   @foreach ($homePrices as $priceRow)
                   <div class="price-cell reveal">
                       <div class="pc-type">{{ $priceRow->type }}</div>
                       @if ($priceRow->area !== '')
                       <div class="pc-area">{{ $priceRow->area }}</div>
                       @endif
                       @if ($priceRow->price !== '')
                       <div class="pc-price">{{ $priceRow->price }}</div>
                       @endif
                   </div>
                   @endforeach
               </div>
               @if (!empty(optional($homePriceMeta)->note))
               <p class="price-note">{{ $homePriceMeta->note }}</p>
               @endif
           </div>
       </section>
       @endif

       <!-- Khám phá dự án -->
       @if ($homeProjectMeta && ($homeProjectMeta->title !== '' || ($homeProjectSpecs ?? collect())->isNotEmpty()))
       <section class="hp-section">
           <div class="hp-wrap">
               <div class="editorial">
                   <div class="ed-text">
                       @if ($homeProjectMeta->eyebrow !== '')
                       <div class="eyebrow"><span class="dash"></span>{{ $homeProjectMeta->eyebrow }}</div>
                       @endif
                       @if ($homeProjectMeta->title !== '')
                       <h2 class="serif">{{ $homeProjectMeta->title }}</h2>
                       @endif
                       @if (!empty($homeProjectMeta->paragraph_1))
                       <p>{!! $homeProjectMeta->paragraph_1 !!}</p>
                       @endif
                       @if (!empty($homeProjectMeta->paragraph_2))
                       <p>{!! $homeProjectMeta->paragraph_2 !!}</p>
                       @endif
                       @if (($homeProjectSpecs ?? collect())->isNotEmpty())
                       <div class="specs">
                           @foreach ($homeProjectSpecs as $spec)
                           <div class="spec-row">
                               <span class="sk">{{ $spec->label }}</span>
                               <span class="sv">{{ $spec->value }}</span>
                           </div>
                           @endforeach
                       </div>
                       @endif
                   </div>
                   @if (!empty($homeProjectMeta->image))
                   @php
                       $projectImage = preg_match('#^(https?:)?//#', $homeProjectMeta->image) ? $homeProjectMeta->image : url($homeProjectMeta->image);
                   @endphp
                   <figure class="ed-img" style="margin:0">
                       <img src="{{ $projectImage }}" alt="{{ $homeProjectMeta->image_alt }}" loading="lazy">
                       @if (!empty($homeProjectMeta->caption))
                       <figcaption>{{ $homeProjectMeta->caption }}</figcaption>
                       @endif
                   </figure>
                   @endif
               </div>
           </div>
       </section>
       @endif

       <!-- Mặt bằng -->
       <section class="hp-section info">
           <div class="hp-wrap">
               <div class="editorial rev">
                   <div class="ed-text">
                       <div class="eyebrow"><span class="dash"></span>Mặt bằng tổng quan</div>
                       <h2 class="serif">Mặt bằng tổng quan Vinhomes Central Park</h2>
                       <p>Tìm hiểu chi tiết mặt bằng tổng quan Vinhomes Central Park cùng layout cụ thể các tòa
                           Central, Landmark, Park và Landmark 81.</p>
                       <div style="margin-top:30px">
                           <a class="btn btn-outline" href="" target="_blank" rel="noopener">Xem thêm về mặt bằng
                               <span class="arr">&rarr;</span></a>
                       </div>
                   </div>
                   <figure class="ed-img" style="margin:0">
                       <img src="https://bestvinhomes.com/wp-content/uploads/2021/09/layout-mat-bang-vinhomes-central-park-1536x978.jpg"
                           alt="Mặt bằng Vinhomes Central Park" loading="lazy">
                       <figcaption>Mặt bằng Vinhomes Central Park (mã căn cũ)</figcaption>
                   </figure>
               </div>
           </div>
       </section>

       <!-- Lợi ích -->
       <section class="hp-section">
           <div class="hp-wrap">
               <div class="section-head">
                   <div class="eyebrow"><span class="dash"></span>Vì sao chọn Central Park</div>
                   <h2>Những lợi ích khi chọn mua căn hộ Vinhomes Central Park</h2>
                   <div class="rule"></div>
                   <p>Không chỉ là nơi ở, mà là một phong cách sống đỉnh cao đậm chất lượng cho cư dân.</p>
               </div>
               @if (($whyChoose ?? collect())->isNotEmpty())
               <div class="benefits">
                   @foreach ($whyChoose as $item)
                   <div class="benefit reveal">
                       <div class="bn">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                       <h4>{{ $item->title }}</h4>
                       @if (!empty($item->description))
                       <p>{{ $item->description }}</p>
                       @endif
                   </div>
                   @endforeach
               </div>
               @endif
           </div>
       </section>

       <!-- Tiện ích gallery -->
       <section class="hp-section info">
           <div class="hp-wrap">
               <div class="section-head left">
                   <div class="eyebrow"><span class="dash"></span>Tiện ích đẳng cấp</div>
                   <h2>Một thế giới tiện nghi ngay tại ngưỡng cửa</h2>
                   <div class="rule"></div>
               </div>
               @php
                   $albumTiles = [];
                   foreach ($gallery ?? [] as $album) {
                       $seen = [];
                       foreach (['before', 'after'] as $field) {
                           $path = trim((string) ($album->{$field} ?? ''));
                           if ($path === '' || isset($seen[$path])) {
                               continue;
                           }
                           $seen[$path] = true;
                           $albumTiles[] = [
                               'src' => preg_match('#^(https?:)?//#', $path) ? $path : url($path),
                               'title' => trim((string) ($album->title ?? '')),
                           ];
                       }
                   }
               @endphp
               @if (count($albumTiles))
               <div class="gallery">
                   @foreach ($albumTiles as $tile)
                   <a href="{{ $tile['src'] }}">
                       <img src="{{ $tile['src'] }}" alt="{{ $tile['title'] }}" loading="lazy">
                       @if ($tile['title'] !== '')
                       <span class="cap">{{ $tile['title'] }}</span>
                       @endif
                   </a>
                   @endforeach
               </div>
               @endif
           </div>
       </section>

       <!-- Vị trí -->
       <section class="hp-section">
           <div class="hp-wrap">
               <div class="editorial">
                   <figure class="ed-img" style="margin:0">
                       <img src="https://bestvinhomes.com/wp-content/uploads/2023/12/cac-loi-vao-du-an-vinhomes-central-park.jpg"
                           alt="Các lối vào dự án Vinhomes Central Park" loading="lazy">
                       <figcaption>Các lối vào dự án Vinhomes Central Park</figcaption>
                   </figure>
                   <div class="ed-text">
                       <div class="eyebrow"><span class="dash"></span>Vị trí chiến lược</div>
                       <h2 class="serif">Trung tâm kết nối Thành phố Hồ Chí Minh</h2>
                       <p>Nằm giữa Quận 1 và TP Thủ Đức, Vinhomes Central Park tận hưởng sự kết nối thuận tiện tới
                           các tuyến đường huyết mạch của thành phố.</p>
                       <div class="specs">
                           <div class="spec-row"><span class="sk">Đến Quận 1, Quận 3</span><span class="sv">5
                                   phút</span></div>
                           <div class="spec-row"><span class="sk">Đến TP Thủ Đức</span><span class="sv">2
                                   phút</span></div>
                           <div class="spec-row"><span class="sk">Địa chỉ chính</span><span class="sv">720A Điện
                                   Biên Phủ</span></div>
                           <div class="spec-row"><span class="sk">Phường</span><span class="sv">Thạnh Mỹ Tây, Bình
                                   Thạnh</span></div>
                           <div class="spec-row"><span class="sk">Số cổng vào</span><span class="sv">3 cổng linh
                                   hoạt</span></div>
                       </div>
                   </div>
               </div>
               <div class="prose loc-prose">
                   <p class="prose-lead">Vinhomes Central Park không chỉ nổi tiếng với thiết kế đẳng cấp và tiện
                       ích vượt trội, mà còn thu hút nhờ vị trí "vàng" tại trung tâm TP.HCM, làm tăng giá trị cho
                       những ai muốn mua căn hộ Vinhomes Central Park.</p>
                   <div class="pblock">
                       <h4>Địa chỉ đắc địa</h4>
                       <p>Vinhomes Central Park nằm tại vị trí thuận lợi:</p>
                       <ul>
                           <li><strong>720A Điện Biên Phủ</strong>, phường Thạnh Mỹ Tây, quận Bình Thạnh, TP.HCM.
                           </li>
                       </ul>
                   </div>
                   <div class="pblock">
                       <h4>Vị trí chiến lược</h4>
                       <p>Nằm giữa quận 1 và TP Thủ Đức (quận 2 cũ), Vinhomes Central Park tận hưởng sự kết nối
                           thuận tiện tới các tuyến đường huyết mạch của thành phố:</p>
                       <ul>
                           <li>Dễ dàng di chuyển đến quận 1, quận 3 chỉ trong 5 phút.</li>
                           <li>Chỉ mất 2 phút để đến TP Thủ Đức.</li>
                       </ul>
                   </div>
                   <div class="pblock">
                       <h4>Cổng vào linh hoạt</h4>
                       <p>Với ba cổng vào chính, cư dân có thể lựa chọn lối vào tiện lợi nhất:</p>
                       <ul>
                           <li>Từ Điện Biên Phủ (trước chân cầu Sài Gòn – 720A Điện Biên Phủ).</li>
                           <li>Từ Nguyễn Hữu Cảnh (208 Nguyễn Hữu Cảnh).</li>
                           <li>Từ Ung Văn Khiêm hoặc Võ Nguyên Giáp (dưới chân cầu Sài Gòn).</li>
                       </ul>
                   </div>
                   <div class="pblock">
                       <h4>Tình trạng giao thông thuận lợi</h4>
                       <p>Giao thông tại Vinhomes Central Park được đánh giá là rất thuận lợi. Đặc biệt:</p>
                       <ul>
                           <li>Cổng 2 và 3 thông thoáng, xe lưu thông dễ dàng.</li>
                           <li>Tại cổng 1 (Nguyễn Hữu Cảnh), giao thông có thể chậm vào giờ cao điểm, nhưng hiếm
                               khi xảy ra tình trạng kẹt xe nghiêm trọng.</li>
                       </ul>
                       <p>Vị trí chiến lược và giao thông thuận lợi làm Vinhomes Central Park trở thành một lựa
                           chọn hấp dẫn không chỉ để sinh sống mà còn là cơ hội đầu tư hấp dẫn cho những người quan
                           tâm đến việc mua căn hộ Vinhomes Central Park.</p>
                   </div>
               </div>
           </div>
       </section>

       <!-- An ninh, phí, pháp lý -->
       <section class="hp-section info">
           <div class="hp-wrap">
               <div class="section-head">
                   <div class="eyebrow"><span class="dash"></span>An tâm tuyệt đối</div>
                   <h2>An ninh · Chi phí · Pháp lý minh bạch</h2>
                   <div class="rule"></div>
               </div>
               <div class="fcols">
                   <div class="fcol reveal">
                       <h4>An toàn &amp; An ninh</h4>
                       <div class="fcol-items">
                           <div class="fitem">
                               <div class="ft">Lối thoát hiểm tối ưu</div>
                               <div class="fd">Hệ thống lối thoát hiểm thiết kế khoa học, rộng rãi và dễ tiếp cận,
                                   đảm bảo di tản nhanh chóng và an toàn.</div>
                           </div>
                           <div class="fitem">
                               <div class="ft">Báo động &amp; cứu hỏa hiện đại</div>
                               <div class="fd">Cảm biến khói, bình chữa cháy và hệ thống phun nước tự động, được
                                   kiểm tra và bảo trì định kỳ.</div>
                           </div>
                           <div class="fitem">
                               <div class="ft">Diễn tập định kỳ</div>
                               <div class="fd">Tổ chức diễn tập cứu hỏa định kỳ, giúp cư dân và nhân viên nắm rõ
                                   phương pháp xử lý tình huống khẩn cấp.</div>
                           </div>
                       </div>
                   </div>
                   <div class="fcol reveal">
                       <h4>Phí quản lý &amp; dịch vụ</h4>
                       <div class="fcol-items">
                           <div class="fitem">
                               <div class="fbig">19.000đ/m²</div>
                               <div class="fd">Phí quản lý theo m² thông thủy, đảm bảo dịch vụ và tiện ích chất
                                   lượng cho cư dân.</div>
                           </div>
                           <div class="fitem">
                               <div class="fbig">170.000đ/tháng</div>
                               <div class="fd">Phí gửi xe máy — dịch vụ an toàn và thuận tiện (2 suất/căn).</div>
                           </div>
                           <div class="fitem">
                               <div class="fbig">1.700.000đ/tháng</div>
                               <div class="fd">Phí gửi xe ô tô — dịch vụ chuyên nghiệp, đảm bảo an ninh (1
                                   suất/căn).</div>
                           </div>
                           <div class="fitem">
                               <div class="ft">Điện &amp; nước</div>
                               <div class="fd">Tính theo giá nhà nước, đảm bảo công bằng và minh bạch cho tất cả cư
                                   dân.</div>
                           </div>
                       </div>
                   </div>
                   <div class="fcol reveal">
                       <h4>Pháp lý &amp; sở hữu</h4>
                       <div class="fcol-items">
                           <div class="fitem">
                               <div class="ft">Pháp lý minh bạch</div>
                               <div class="fd">Mọi thủ tục pháp lý rõ ràng, mang lại sự an tâm tuyệt đối về quyền
                                   lợi và giá trị tài sản.</div>
                           </div>
                           <div class="fitem">
                               <div class="ft">Sổ hồng sẵn có</div>
                               <div class="fd">Căn hộ sở hữu lâu dài đã được cấp sổ hồng, đảm bảo quyền lợi và tăng
                                   giá trị đầu tư.</div>
                           </div>
                           <div class="fitem">
                               <div class="ft">Sang nhượng officetel dễ dàng</div>
                               <div class="fd">Các căn hộ OT (officetel) có thể dễ dàng sang nhượng thông qua chủ
                                   đầu tư, mang lại sự linh hoạt cho nhà đầu tư và cư dân.</div>
                           </div>
                           <div class="fitem">
                               <div class="ft">Suất bán cho người nước ngoài</div>
                               <div class="fd">Dự án mở rộng cơ hội sở hữu cho người nước ngoài với các suất bán
                                   dành riêng, đáp ứng nhu cầu sở hữu căn hộ tại Việt Nam.</div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </section>

       <!-- Tiện ích mỗi tòa nhà -->
       <section class="hp-section">
           <div class="hp-wrap">
               <div class="section-head">
                   <div class="eyebrow"><span class="dash"></span>Tiện ích nội khu</div>
                   <h2>Tiện ích đa dạng tại mỗi tòa nhà Vinhomes Central Park</h2>
                   <div class="rule"></div>
                   <p>Mỗi tòa nhà tại Vinhomes Central Park được thiết kế với sự chú trọng đặc biệt vào tiện ích và
                       sự thoải mái cho cư dân, mang đến trải nghiệm sống đẳng cấp và tiện nghi.</p>
               </div>
               <div class="prose">
                   <div class="pblock">
                       <h4>Tiện ích độc đáo trong mỗi tòa nhà</h4>
                       <p>Mỗi tòa nhà tại Vinhomes Central Park được trang bị đầy đủ các tiện ích chất lượng cao,
                           phục vụ mọi nhu cầu của cư dân. Điểm nổi bật là phòng cộng đồng – không gian lý tưởng
                           cho các hoạt động giao lưu và tổ chức sự kiện.</p>
                       <p>Ngoài ra, còn có phòng họp hiện đại, phòng đón khách sang trọng, phòng gym được trang bị
                           thiết bị tập luyện hiện đại, và một hồ bơi rộng lớn, mang lại không gian thư giãn và
                           giải trí tuyệt vời cho cư dân.</p>
                   </div>
                   <div class="pblock">
                       <h4>Quy định gửi xe tiện lợi</h4>
                       <p>Mỗi căn hộ tại Vinhomes Central Park được hưởng lợi từ quy định gửi xe thuận tiện:
                           <strong>2 suất gửi xe máy và 1 suất gửi xe ô tô</strong>. Điều này không chỉ đảm bảo sự
                           tiện lợi cho việc đi lại của cư dân mà còn giúp quản lý và tổ chức không gian gửi xe một
                           cách hiệu quả.</p>
                   </div>
               </div>
           </div>
       </section>

       <!-- Thanh khoản & đầu tư -->
       <section class="hp-section info">
           <div class="hp-wrap">
               <div class="section-head">
                   <div class="eyebrow"><span class="dash"></span>Thanh khoản & đầu tư</div>
                   <h2 class="serif">Mua bán và cho thuê tại Vinhomes Central Park</h2>
                   <div class="rule"></div>
                   <p>Thanh khoản mạnh mẽ và cơ hội đầu tư hấp dẫn — một điểm nóng trên thị trường mua bán và cho
                       thuê nhờ thanh khoản tốt và giá cho thuê hấp dẫn.</p>
               </div>
               <div class="prose">
                   <div class="pblock">
                       <h4>Thanh khoản tốt của các căn hộ</h4>
                       <p>Các căn hộ tại Vinhomes Central Park thường rao bán không lâu đã nhanh chóng tìm được
                           người mua. Điều này phản ánh sức hút mạnh mẽ của dự án, nhờ vào vị trí đắc địa, tiện ích
                           nội khu đẳng cấp, và chất lượng xây dựng hàng đầu.</p>
                       <p>Sự nhanh chóng trong việc tìm được người mua cũng làm tăng giá trị đầu tư cho các chủ sở
                           hữu, đặc biệt trong bối cảnh thị trường bất động sản sôi động.</p>
                   </div>
                   <div class="pblock">
                       <h4>Cho thuê dễ dàng với giá tốt</h4>
                       <p>Khả năng cho thuê tại Vinhomes Central Park cũng rất dễ dàng, với nhu cầu thuê cao từ cả
                           cư dân trong nước và người nước ngoài. Điều này tạo ra một cơ hội đầu tư không thể bỏ
                           qua, với dòng thu nhập ổn định và lợi nhuận hấp dẫn.</p>
                       <p>Giá cho thuê tại dự án này rất tốt, phản ánh sự cao cấp và tiện nghi của các căn hộ, cùng
                           với dịch vụ và an ninh hàng đầu, mang lại sự an tâm và thoải mái cho người thuê.</p>
                   </div>
               </div>
           </div>
       </section>

       <!-- Lưu ý khi chọn mua -->
       <section class="hp-section">
           <div class="hp-wrap">
               <div class="section-head">
                   <div class="eyebrow"><span class="dash"></span>Cẩm nang đầu tư</div>
                   <h2>Lưu ý khi chọn mua hoặc thuê</h2>
                   <div class="rule"></div>
                   <p>Khi quyết định mua hoặc thuê tại Vinhomes Central Park, có một số lưu ý quan trọng cần được
                       xem xét để đưa ra quyết định đúng đắn.</p>
               </div>
               <div class="notes-grid">
                   <div class="note reveal">
                       <div class="nn">01</div>
                       <h4>Xác định mục tiêu đầu tư hoặc nhu cầu cá nhân</h4>
                       <p>Người mua để đầu tư hãy xem xét tiềm năng sinh lời và thanh khoản của căn hộ. Người mua
                           để ở hãy xác định dự án có đáp ứng đủ nhu cầu sống và tiện ích mong muốn hay không.</p>
                   </div>
                   <div class="note reveal">
                       <div class="nn">02</div>
                       <h4>Hiểu rõ về pháp lý và quyền sở hữu</h4>
                       <p>Kiểm tra các thông tin pháp lý liên quan đến quyền sở hữu, sổ hồng và các giấy tờ pháp lý
                           khác. Đảm bảo mọi thủ tục mua bán, sang nhượng đều minh bạch và hợp pháp.</p>
                   </div>
                   <div class="note reveal">
                       <div class="nn">03</div>
                       <h4>Nghiên cứu vị trí và tiện ích của dự án</h4>
                       <p>Vị trí dự án và các tiện ích nội khu như phòng gym, hồ bơi, không gian xanh, cùng tiện
                           ích xung quanh như trường học, trung tâm thương mại ảnh hưởng lớn đến chất lượng cuộc
                           sống và giá trị bất động sản.</p>
                   </div>
                   <div class="note reveal">
                       <div class="nn">04</div>
                       <h4>Đánh giá tài chính và chi phí liên quan</h4>
                       <p>Tính toán kỹ lưỡng về ngân sách, bao gồm giá mua hoặc thuê, phí quản lý, chi phí gửi xe
                           và các chi phí phát sinh khác, giúp đưa ra quyết định phù hợp với khả năng tài chính.
                       </p>
                   </div>
                   <div class="note reveal">
                       <div class="nn">05</div>
                       <h4>Nghe lời khuyên từ những người thực sự hiểu rõ</h4>
                       <p>Hãy nói chuyện với cư dân hiện tại để có cái nhìn sâu sắc về cuộc sống tại Vinhomes
                           Central Park. Không gì quý giá hơn kinh nghiệm thực tế từ những người đang sống tại đây.
                       </p>
                   </div>
                   <div class="note reveal">
                       <div class="nn">06</div>
                       <h4>Tham khảo ý kiến từ chuyên gia</h4>
                       <p>Đừng ngần ngại tham khảo ý kiến từ các chuyên gia bất động sản hoặc những người có kinh
                           nghiệm, để có cái nhìn toàn diện hơn về dự án và quyết định một cách thông thái.</p>
                   </div>
                   <div class="note reveal">
                       <div class="nn">07</div>
                       <h4>Xem xét kỹ tình trạng căn hộ</h4>
                       <p>Đừng quên xem xét kỹ lưỡng tình trạng thực tế của căn hộ. Một bước không thể bỏ qua để
                           đảm bảo căn hộ phù hợp với mong đợi của bạn về chất lượng và thiết kế.</p>
                   </div>
               </div>
           </div>
       </section>

       <!-- Checklist -->
       <section class="hp-section info">
           <div class="hp-wrap">
               <div class="section-head">
                   <div class="eyebrow"><span class="dash"></span>Cẩm nang</div>
                   <h2>Checklist khi đi xem căn hộ</h2>
                   <div class="rule"></div>
                   <p>10 điều cần kiểm tra để đưa ra quyết định mua hoặc thuê đúng đắn tại Vinhomes Central Park.
                   </p>
               </div>
               <div class="checklist">
                   <div class="chk reveal">
                       <div class="cn">01</div>
                       <h5>Vị trí &amp; Cảnh quan</h5>
                       <ul>
                           <li>Tầm nhìn và hướng view — hướng nắng hay hướng mát</li>
                           <li>Mức độ yên tĩnh hoặc tiếng ồn xung quanh</li>
                           <li>Gần tiện ích nội khu: trường học, bệnh viện, mua sắm</li>
                       </ul>
                   </div>
                   <div class="chk reveal">
                       <div class="cn">02</div>
                       <h5>Pháp lý &amp; Quyền sở hữu</h5>
                       <ul>
                           <li>Sổ hồng, giấy tờ pháp lý liên quan</li>
                           <li>Quyền sở hữu — lâu dài hoặc có thời hạn</li>
                       </ul>
                   </div>
                   <div class="chk reveal">
                       <div class="cn">03</div>
                       <h5>Tình trạng &amp; Thiết kế</h5>
                       <ul>
                           <li>Tình trạng bảo dưỡng, sửa chữa cần thiết</li>
                           <li>Căn hộ có bị thấm dột không</li>
                           <li>Thiết bị điện còn hoạt động tốt</li>
                           <li>Chất lượng hoàn thiện: sơn, gạch, trần, cửa</li>
                       </ul>
                   </div>
                   <div class="chk reveal">
                       <div class="cn">04</div>
                       <h5>Tiện ích &amp; Dịch vụ</h5>
                       <ul>
                           <li>Hồ bơi, phòng gym, khu vui chơi trẻ em</li>
                           <li>Dịch vụ an ninh, vệ sinh, bảo trì</li>
                       </ul>
                   </div>
                   <div class="chk reveal">
                       <div class="cn">05</div>
                       <h5>Hệ thống điện &amp; nước</h5>
                       <ul>
                           <li>Kiểm tra đèn, ổ cắm, công tắc</li>
                           <li>Áp lực nước, nước nóng, hệ thống xử lý nước</li>
                       </ul>
                   </div>
                   <div class="chk reveal">
                       <div class="cn">06</div>
                       <h5>Kết nối giao thông</h5>
                       <ul>
                           <li>Đường xá dễ tiếp cận, giao thông công cộng</li>
                           <li>Tình trạng giao thông giờ cao điểm</li>
                       </ul>
                   </div>
                   <div class="chk reveal">
                       <div class="cn">07</div>
                       <h5>An ninh &amp; An toàn</h5>
                       <ul>
                           <li>Hệ thống an ninh, camera giám sát</li>
                           <li>Phòng cháy chữa cháy, đường thoát hiểm</li>
                       </ul>
                   </div>
                   <div class="chk reveal">
                       <div class="cn">08</div>
                       <h5>Cộng đồng &amp; Hàng xóm</h5>
                       <ul>
                           <li>Môi trường cộng đồng, văn hóa khu vực</li>
                           <li>Phản hồi từ hàng xóm, cư dân hiện tại</li>
                       </ul>
                   </div>
                   <div class="chk reveal">
                       <div class="cn">09</div>
                       <h5>Chi phí phát sinh</h5>
                       <ul>
                           <li>Phí quản lý, phí gửi xe</li>
                           <li>Các khoản phí ban quản lý đã thanh toán</li>
                           <li>Chi phí dịch vụ khác như điện, nước, internet</li>
                       </ul>
                   </div>
                   <div class="chk reveal">
                       <div class="cn">10</div>
                       <h5>Tài chính &amp; Giá cả</h5>
                       <ul>
                           <li>So sánh giá với các căn hộ tương tự trong khu vực</li>
                           <li>Các tùy chọn tài chính, hỗ trợ vay ngân hàng</li>
                       </ul>
                   </div>
               </div>
           </div>
       </section>

   </div><!-- /#duan -->

   <!-- ===================== ABOUT US ===================== -->
   <section class="hp-section">
       <div class="hp-wrap">
           <div class="section-head">
               <div class="eyebrow"><span class="dash"></span>Về chúng tôi</div>
               <h2 class="serif">Chuyên mua bán căn hộ Vinhomes Central Park</h2>
               <div class="rule"></div>
               <p>Chúng tôi tập trung mọi nguồn lực để trao giá trị nhiều nhất đến cho khách hàng của mình.</p>
           </div>
           <div class="about-grid">
               <div class="about-card reveal">
                   <img class="ac-icon"
                       src="https://bestvinhomes.com/wp-content/uploads/2023/05/222.Exploring.webp"
                       alt="Tìm kiếm căn hộ" loading="lazy">
                   <h4>Tìm kiếm căn hộ</h4>
                   <p>Hơn 60 chuyên viên tìm kiếm căn hộ cùng 300 cộng tác viên ở Vinhomes, chúng tôi tự tin có căn
                       hộ bạn mong muốn.</p>
               </div>
               <div class="about-card reveal">
                   <img class="ac-icon" src="https://bestvinhomes.com/wp-content/uploads/2023/05/205.OK_.webp"
                       alt="Môi giới giàu kinh nghiệm" loading="lazy">
                   <h4>Môi giới giàu kinh nghiệm</h4>
                   <p>Luôn đào tạo đội ngũ môi giới chuyên bán căn hộ Vinhomes Central Park, yêu cầu ít nhất 2 năm
                       kinh nghiệm làm tại dự án.</p>
               </div>
               <div class="about-card reveal">
                   <img class="ac-icon" src="https://bestvinhomes.com/wp-content/uploads/2023/05/169.Check_.webp"
                       alt="Thủ tục và pháp lý" loading="lazy">
                   <h4>Thủ tục và pháp lý</h4>
                   <p>Trải qua rất nhiều giao dịch, chúng tôi đã hoàn thiện quy trình thủ tục và pháp lý. Nhanh
                       chóng, chính xác, đúng luật và an toàn là cam kết của chúng tôi.</p>
               </div>
               <div class="about-card reveal">
                   <img class="ac-icon" src="https://bestvinhomes.com/wp-content/uploads/2023/05/174.Project.webp"
                       alt="Văn phòng trong dự án" loading="lazy">
                   <h4>Văn phòng trong dự án</h4>
                   <p>Trụ sở chính ngay tại Vinhomes, chúng tôi am hiểu sâu sắc dự án từ điều nhỏ nhất đến chuyện
                       quan trọng nhất.</p>
               </div>
           </div>
       </div>
   </section>

</div><!-- /.bvh-hp -->
@endsection
