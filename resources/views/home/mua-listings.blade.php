@php
    $muaCate = collect($categoryhome ?? [])->firstWhere('slug', 'mua');
    $muaTypeTwos = collect();
    if ($muaCate) {
        foreach ($muaCate->typeCate as $tradeType) {
            foreach ($tradeType->typetwo as $tradeChild) {
                $muaTypeTwos->push($tradeChild);
            }
        }
        $muaTypeTwos = $muaTypeTwos->sortBy('id')->values();
    }
    $homeProductsByType = collect();
    if ($muaTypeTwos->isNotEmpty()) {
        $homeProductsByType = \App\models\product\Product::where('home_status', 1)
            ->where('consignment', 0)
            ->whereIn('type_two', $muaTypeTwos->pluck('id'))
            ->orderBy('id', 'desc')
            ->get()
            ->groupBy('type_two');
        $muaTypeTwos = $muaTypeTwos->filter(function ($tradeChild) use ($homeProductsByType) {
            $products = $homeProductsByType->get($tradeChild->id);
            return $products && $products->isNotEmpty();
        })->values();
    }
@endphp
@foreach ($muaTypeTwos as $tradeChild)
    @php
        $inventory = json_decode($tradeChild->content ?? '', true);
        $inventoryLogs = (is_array($inventory) && isset($inventory['logs']) && is_array($inventory['logs'])) ? $inventory['logs'] : [];
        $tradeName = languageName($tradeChild->name);
        $tradeUrl = route('allListTypeTwo', [
            'danhmuc' => $muaCate->slug,
            'loaidanhmuc' => $tradeChild->type_slug,
            'loai2' => $tradeChild->slug,
        ]);
        $parentType = $muaCate->typeCate->firstWhere('id', $tradeChild->type_id);
        $tradeTypeName = $parentType ? languageName($parentType->name) : '';
        $homeProducts = $homeProductsByType->get($tradeChild->id, collect());
    @endphp
    <div class="bed-block" id="bvh-{{ $tradeChild->slug }}">
        <div class="bed-head">
            <div class="bh-l">
                <h3>{{ $tradeName }}</h3>
                @if (!empty($inventory['price_note']))
                <span class="count">{{ $inventory['price_note'] }}</span>
                @endif
            </div>
            <div class="bh-r">
                @if (!empty($inventory['badge']))
                <span class="bh-badge">{{ $inventory['badge'] }}</span>
                @endif
                <a class="link-more" href="{{ $tradeUrl }}">
                    <span class="lm-txt">Xem tất <span class="lm-break">cả</span></span>
                    <span class="lm-arr">&rarr;</span>
                </a>
            </div>
        </div>
        @if ($homeProducts->isNotEmpty())
        <div class="bvl-wrap bvl-grid">
            <div class="bvl-grid-sec">
                <div class="bvl-grid-inner">
                    <div class="bvl-card-grid">
                        @foreach ($homeProducts as $homeProduct)
                        @include('layouts.product.item', [
                            'pro' => $homeProduct,
                            'listingCard' => true,
                            'addressFallback' => $tradeTypeName,
                        ])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if (count($inventoryLogs))
        <div class="recent-label">
            <div class="eyebrow"><span class="dash"></span>Nhật ký giỏ hàng {{ count($inventoryLogs) }} ngày gần nhất · {{ $tradeName }}</div>
        </div>
        <div class="hp-tl-mini-feed">
            @foreach ($inventoryLogs as $day)
            <div class="hp-tl-mini-day">
                <div class="hp-tl-mini-node"></div>
                <div class="hp-tl-mini-day-hdr">
                    @if (!empty($day['dow']))
                    <span class="hp-tl-mini-day-dow">{{ $day['dow'] }}</span>
                    @endif
                    @if (!empty($day['date']))
                    <span class="hp-tl-mini-day-date">{{ $day['date'] }}</span>
                    @endif
                </div>
                <div class="hp-tl-mini-cards">
                    <article class="hp-tl-mini-card">
                        <div class="hp-tl-mini-card-top">
                            @if (!empty($day['title']))
                            <h3>{{ $day['title'] }}</h3>
                            @endif
                            @if (!empty($day['time']))
                            <span class="hp-tl-mini-card-time">{{ $day['time'] }}</span>
                            @endif
                        </div>
                        <div class="hp-tl-mini-card-body">
                            @foreach ($day['groups'] ?? [] as $group)
                            @if (!empty($group['name']))
                            <h4 style="font-size: 15px; font-weight: 700; margin: 12px 0 6px 0; color: #1e293b;">{{ $group['name'] }}</h4>
                            @endif
                            @if (!empty($group['items']))
                            <ul>
                                @foreach ($group['items'] as $item)
                                <li>
                                    @if (!empty($item['label']))
                                    <b>{{ $item['label'] }}</b> &#8211; {{ $item['text'] ?? '' }}
                                    @else
                                    {{ $item['text'] ?? '' }}
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                            @endif
                            @endforeach
                        </div>
                    </article>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="bed-more">
            <a class="btn btn-outline" href="{{ $tradeUrl }}">
                Xem thêm giỏ hàng {{ $tradeName }} <span class="arr">&rarr;</span>
            </a>
        </div>
    </div>
@endforeach
