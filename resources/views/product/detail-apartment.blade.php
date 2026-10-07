@php
    $imageRows = json_decode($product->images ?? '[]', true);
    $galleryUrls = [];
    if (is_array($imageRows)) {
        foreach ($imageRows as $image) {
            $path = is_string($image) ? $image : (is_array($image) ? ($image['url'] ?? $image['image'] ?? '') : '');
            $path = trim((string) $path);
            if ($path === '') {
                continue;
            }
            $galleryUrls[] = preg_match('#^(https?:)?//#', $path) ? $path : url($path);
        }
    }
    $productTitle = languageName($product->name);
    $listing = json_decode($product->listing ?? '', true);
    if (!is_array($listing)) {
        $listing = [];
    }
    $listingText = function ($key) use ($listing) {
        return trim((string) ($listing[$key] ?? ''));
    };
    $mediaUrl = function ($path) {
        $path = trim((string) $path);
        if ($path === '') {
            return '';
        }
        return preg_match('#^(https?:)?//#', $path) ? $path : url($path);
    };
    $technicalSpecs = [];
    if (!empty($product->size)) {
        $decodedSpecs = json_decode($product->size, true);
        if (is_array($decodedSpecs)) {
            foreach ($decodedSpecs as $item) {
                $title = trim((string) data_get($item, 'title', ''));
                $detail = trim((string) data_get($item, 'detail', ''));
                if ($title === '' && $detail === '') {
                    continue;
                }
                $technicalSpecs[] = ['title' => $title, 'detail' => $detail, 'accent' => false];
            }
        }
    }
    $specOf = function ($needles) use ($technicalSpecs) {
        foreach ($technicalSpecs as $spec) {
            $title = mb_strtolower($spec['title']);
            foreach ((array) $needles as $needle) {
                if ($needle !== '' && mb_strpos($title, mb_strtolower($needle)) !== false) {
                    return $spec['detail'];
                }
            }
        }
        return '';
    };
    $amount = (float) $product->discount > 0 ? (float) $product->discount : (float) $product->price;
    $priceNum = '';
    $priceUnit = 'tỷ đồng';
    if ($amount > 0) {
        $priceNum = rtrim(rtrim(number_format($amount, 2, ',', '.'), '0'), ',');
        if (strpos($priceNum, ',') === false) {
            $priceNum .= ',0';
        }
    }
    $priceNote = $listingText('price_note');
    $unitPriceNote = $listingText('unit_price_note');
    $hasPriceSpec = false;
    foreach ($technicalSpecs as $spec) {
        if (preg_match('/giá/iu', $spec['title'])) {
            $hasPriceSpec = true;
            break;
        }
    }
    if (!$hasPriceSpec && $priceNum !== '') {
        $priceDetail = trim($priceNum.' '.$priceUnit);
        if ($priceNote !== '' && mb_stripos($priceNote, 'thuế') !== false) {
            $priceDetail .= ' · bao thuế phí';
        }
        $technicalSpecs[] = ['title' => 'Mức giá', 'detail' => $priceDetail, 'accent' => true];
    }
    foreach ($technicalSpecs as $index => $spec) {
        $technicalSpecs[$index]['accent'] = !empty($spec['accent']) || (bool) preg_match('/giá/iu', $spec['title']);
    }
    $half = (int) ceil(count($technicalSpecs) / 2);
    $leftSpecs = array_slice($technicalSpecs, 0, $half);
    $rightSpecs = array_slice($technicalSpecs, $half);
    $building = $specOf(['toà nhà', 'toa nha', 'tòa nhà']);
    $bedDetail = $specOf(['phòng ngủ', 'phong ngu']);
    $bathDetail = $specOf(['phòng tắm', 'phong tam', 'wc', 'vệ sinh']);
    $areaDetail = $specOf(['diện tích', 'dien tich']);
    $viewDetail = $specOf(['hướng', 'huong', 'view']);
    $offer = ($product->cate_slug ?? '') === 'thue' ? 'CHO THUÊ' : 'BÁN';
    $bedName = $product->typecatetwo ? languageName($product->typecatetwo->name) : '';
    $projectName = $product->typecate ? languageName($product->typecate->name) : '';
    $eyebrow = trim($offer.($bedName !== '' ? ' '.mb_strtoupper($bedName) : ''));
    $subtitleParts = [];
    if ($building !== '') {
        $subtitleParts[] = 'Toà '.$building;
    }
    if ($projectName !== '') {
        $subtitleParts[] = $projectName;
    }
    $subtitle = implode(' · ', $subtitleParts);
    $viewPill = $viewDetail;
    if ($viewPill !== '' && !preg_match('/^view/iu', $viewPill)) {
        $viewPill = 'View '.$viewPill;
    }
    $viewPills = [];
    if (!empty($listing['pills']) && is_array($listing['pills'])) {
        foreach ($listing['pills'] as $pill) {
            $pill = trim((string) $pill);
            if ($pill !== '') {
                $viewPills[] = $pill;
            }
        }
    }
    if (!count($viewPills) && $viewPill !== '') {
        $viewPills[] = $viewPill;
    }
    $statNumber = function ($text) {
        if (preg_match('/\d+(?:[.,]\d+)?/', (string) $text, $match)) {
            return $match[0];
        }
        return trim((string) $text);
    };
    $descHtml = trim((string) languageName($product->description));
    $contentHtml = trim((string) languageName($product->content));
    if (trim(strip_tags($descHtml)) === '') {
        $descHtml = $contentHtml;
    }
    $unitCode = $listingText('unit_code');
    $unitLabel = $unitCode;
    if ($unitLabel !== '' && !preg_match('/căn/iu', $unitLabel)) {
        $unitLabel = 'CĂN SỐ '.$unitLabel;
    }
    $grossArea = $listingText('gross_area');
    $netArea = $listingText('net_area');
    $floorPlan = $mediaUrl($listingText('floor_plan'));
    $showLayout = $unitLabel !== '' || $grossArea !== '' || $netArea !== '' || $floorPlan !== '';
    $agentName = $listingText('agent_name');
    $agentRole = $listingText('agent_role');
    $agentPhone = $listingText('agent_phone');
    if ($agentPhone === '') {
        $agentPhone = trim((string) ($setting->phone1 ?? ''));
    }
    $agentImage = $mediaUrl($listingText('agent_image'));
    $phoneDigits = preg_replace('/[^0-9]/', '', $agentPhone);
    $zaloUrl = $phoneDigits !== '' ? 'https://zalo.me/'.$phoneDigits : '';
    $sideImages = array_slice($galleryUrls, 1, 4);
    $hiddenCount = max(0, count($galleryUrls) - 5);
    $galleryJson = json_encode($galleryUrls, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
@endphp
<div class="apt-page">
    <div class="apt-wrap">
        <header class="apt-hero">
            <div>
                @if ($eyebrow !== '')
                <p class="apt-kicker">— {{ $eyebrow }}</p>
                @endif
                <h1>{{ $productTitle }}</h1>
                @if ($subtitle !== '')
                <p class="apt-sub">{{ $subtitle }}</p>
                @endif
            </div>
            @if ($priceNum !== '')
            <div class="apt-hero-price">
                <span>MỨC GIÁ {{ $offer }}</span>
                <b>{{ $priceNum }} <small>{{ $priceUnit }}</small></b>
                @if ($priceNote !== '')
                <p>{{ $priceNote }}</p>
                @endif
            </div>
            @endif
        </header>

        @if (count($galleryUrls))
        <div class="apt-gallery" data-apt-gallery data-images="{{ $galleryJson }}">
            <button type="button" class="apt-shot apt-gallery-main" data-apt-open="0">
                <img src="{{ $galleryUrls[0] }}" alt="{{ $productTitle }}">
                @if (count($galleryUrls) > 1)
                <span class="apt-photo-count">{{ count($galleryUrls) }} ẢNH</span>
                @endif
            </button>
            @if (count($sideImages))
            <div class="apt-gallery-side">
                @foreach ($sideImages as $image)
                <button type="button" class="apt-shot" data-apt-open="{{ $loop->iteration }}">
                    <img src="{{ $image }}" alt="" loading="lazy">
                    @if ($loop->last && $hiddenCount > 0)
                    <span class="apt-more">
                        <b>+{{ $hiddenCount }}</b>
                        <span>Xem tất cả {{ count($galleryUrls) }} ảnh</span>
                    </span>
                    @endif
                </button>
                @endforeach
            </div>
            @endif
        </div>
        <div class="apt-lightbox" id="apt-lightbox" hidden>
            <button type="button" class="apt-lightbox-close" data-apt-close aria-label="Đóng">×</button>
            <button type="button" class="apt-lightbox-nav apt-lightbox-prev" data-apt-prev aria-label="Ảnh trước">‹</button>
            <img src="" alt="">
            <button type="button" class="apt-lightbox-nav apt-lightbox-next" data-apt-next aria-label="Ảnh sau">›</button>
            <span class="apt-lightbox-count" data-apt-count></span>
        </div>
        @endif

        <div class="apt-layout">
            <div>
                @if ($descHtml !== '')
                <p class="apt-label">MÔ TẢ</p>
                <div class="apt-copy">{!! $descHtml !!}</div>
                @endif

                @if (count($viewPills))
                <div class="apt-pills">
                    @foreach ($viewPills as $pill)
                    <span class="apt-pill"><i></i> {{ $pill }}</span>
                    @endforeach
                </div>
                @endif

                @if (count($technicalSpecs))
                <p class="apt-label">THÔNG TIN CHI TIẾT</p>
                <div class="apt-specs">
                    <div>
                        @foreach ($leftSpecs as $spec)
                        <div class="apt-spec">
                            <span>{{ $spec['title'] !== '' ? $spec['title'] : 'Thông tin' }}</span>
                            <b class="{{ !empty($spec['accent']) ? 'is-price' : '' }}">{{ $spec['detail'] !== '' ? $spec['detail'] : '—' }}</b>
                        </div>
                        @endforeach
                    </div>
                    <div>
                        @foreach ($rightSpecs as $spec)
                        <div class="apt-spec">
                            <span>{{ $spec['title'] !== '' ? $spec['title'] : 'Thông tin' }}</span>
                            <b class="{{ !empty($spec['accent']) ? 'is-price' : '' }}">{{ $spec['detail'] !== '' ? $spec['detail'] : '—' }}</b>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if ($showLayout)
                <p class="apt-label">MẶT BẰNG LAYOUT</p>
                <div class="apt-plan">
                    @if ($floorPlan !== '')
                    <div class="apt-plan-figure">
                        <img src="{{ $floorPlan }}" alt="Mặt bằng {{ $building }}">
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <aside class="apt-side">
                <div class="apt-card">
                    @if ($priceNum !== '')
                    <div class="apt-card-head">
                        <span class="apt-card-kicker">MỨC GIÁ {{ $offer }}</span>
                        <div class="apt-card-price"><b>{{ $priceNum }} <small>tỷ</small></b></div>
                        @if ($unitPriceNote !== '')
                        <p class="apt-card-note">≈ {{ ltrim($unitPriceNote, '≈ ') }}</p>
                        @endif
                    </div>
                    @endif

                    @if ($bedDetail !== '' || $bathDetail !== '' || $areaDetail !== '')
                    <div class="apt-stats">
                        <div>
                            <b>{{ $statNumber($bedDetail) ?: '—' }}</b>
                            <span>PHÒNG NGỦ</span>
                        </div>
                        <div>
                            <b>{{ $statNumber($bathDetail) ?: '—' }}</b>
                            <span>PHÒNG TẮM</span>
                        </div>
                        <div>
                            <b>{{ $areaDetail !== '' ? $areaDetail : '—' }}</b>
                            <span>DIỆN TÍCH</span>
                        </div>
                    </div>
                    @endif

                    @if ($agentName !== '' || $agentPhone !== '')
                    <div class="apt-agent">
                        @if ($agentImage !== '')
                        <img src="{{ $agentImage }}" alt="{{ $agentName }}">
                        @else
                        <i></i>
                        @endif
                        <div>
                            @if ($agentName !== '')
                            <b>{{ $agentName }}</b>
                            @endif
                            @if ($agentRole !== '')
                            <span>{{ $agentRole }}</span>
                            @endif
                            @if ($agentPhone !== '')
                            <a href="tel:{{ $phoneDigits }}">{{ $agentPhone }}</a>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if ($phoneDigits !== '')
                    <a class="apt-call" href="tel:{{ $phoneDigits }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.2 3.8h2.4c.4 0 .8.3.9.7l.8 2.6c.1.4 0 .8-.3 1.1L9.6 9.6a12.4 12.4 0 005.8 5.8l1.4-1.4c.3-.3.7-.4 1.1-.3l2.6.8c.4.1.7.5.7.9v2.4c0 .6-.4 1.1-1 1.2A15.2 15.2 0 016 5c.1-.6.6-1 1.2-1.2z"/></svg>
                        Gọi ngay
                    </a>
                    @endif
                    @if ($zaloUrl !== '')
                    <a class="apt-zalo" href="{{ $zaloUrl }}" target="_blank" rel="noopener noreferrer">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M7.5 3h9A2.5 2.5 0 0 1 19 5.5v8A2.5 2.5 0 0 1 16.5 16H10l-3.6 2.4c-.5.3-1.1-.1-1.1-.7V16A2.5 2.5 0 0 1 5 13.5v-8A2.5 2.5 0 0 1 7.5 3zM9 10.2a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg>
                        Chat Zalo
                    </a>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</div>
