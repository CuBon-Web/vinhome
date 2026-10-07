@extends('layouts.main.master')
@section('title')
{{ $title }}
@endsection
@section('description')
Danh sách {{ $title }}
@endsection
@section('image')
@php
    $listOgImage = isset($banner[0]) && !empty($banner[0]->image)
        ? url($banner[0]->image)
        : url($setting->logo ?? '');
@endphp
{{ $listOgImage }}
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('frontend/css/trade-list.css') }}" />
@endsection
@section('js')
<script>
    function initTradeLogPager() {
        var pageSize = 8;
        var days = Array.prototype.slice.call(document.querySelectorAll('[data-trade-day]'));
        var pager = document.getElementById('tl-log-pager');
        var section = document.querySelector('.trade-journal');
        if (!pager || !days.length) return;
        var page = 0;
        var pageCount = Math.ceil(days.length / pageSize);
        if (pageCount < 2) return;

        function showPage(nextPage, scroll) {
            page = nextPage;
            var start = page * pageSize;
            var end = start + pageSize;
            days.forEach(function (day, index) {
                day.hidden = index < start || index >= end;
            });
            pager.innerHTML = '';
            for (var index = 0; index < pageCount; index++) {
                var button = document.createElement('button');
                button.type = 'button';
                button.textContent = String(index + 1);
                if (index === page) button.className = 'is-on';
                button.addEventListener('click', function (event) {
                    showPage(Number(event.currentTarget.textContent) - 1, true);
                });
                pager.appendChild(button);
            }
            if (page < pageCount - 1) {
                var next = document.createElement('button');
                next.type = 'button';
                next.textContent = 'Sau';
                next.addEventListener('click', function () {
                    showPage(page + 1, true);
                });
                pager.appendChild(next);
            }
            if (scroll && section) {
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        showPage(0, false);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTradeLogPager);
    } else {
        initTradeLogPager();
    }
</script>
@endsection
@section('content')
@php
    $tradeLabel = 'Giỏ hàng';
    if (($cate_slug ?? '') === 'mua') {
        $tradeLabel = 'Giỏ hàng bán';
    } elseif (($cate_slug ?? '') === 'thue') {
        $tradeLabel = 'Giỏ hàng cho thuê';
    } elseif (!empty($cate_name ?? '')) {
        $tradeLabel = $cate_name;
    }
    $offerWord = ($cate_slug ?? '') === 'thue' ? 'cho thuê' : 'bán';
    $pageTitle = $title;
    if (!empty($type_two_name ?? '') && !empty($type_name ?? '')) {
        $pageTitle = $tradeLabel.' '.mb_strtolower($type_two_name).' tại '.$type_name;
    } elseif (!empty($type_name ?? '')) {
        $pageTitle = $tradeLabel.' tại '.$type_name;
    }
    $lead = 'Tổng hợp căn hộ đang rao và nhật ký biến động giỏ hàng — cập nhật liên tục.';
    if (!empty($type_two_name ?? '') && !empty($type_name ?? '')) {
        $lead = 'Tổng hợp căn hộ '.mb_strtolower($type_two_name).' đang '.$offerWord.' và nhật ký biến động giỏ hàng tại '.$type_name.' — cập nhật liên tục.';
    }
    $inventory = json_decode($content ?? '', true);
    $inventoryLogs = (is_array($inventory) && isset($inventory['logs']) && is_array($inventory['logs'])) ? array_values($inventory['logs']) : [];
    $priceChip = '';
    if (!empty($inventory['price_note'])) {
        $priceChip = trim(explode('·', $inventory['price_note'])[0]);
    }
    $shortBed = $type_two_name ?? '';
    if (preg_match('/(\d+)\s*ph[oò]ng\s*ng[uủ]/iu', $shortBed, $bedMatch)) {
        $shortBed = $bedMatch[1].'PN';
    }
    $sectionTitle = $shortBed !== '' ? 'Căn hộ '.$shortBed.' đang '.$offerWord : 'Căn hộ đang '.$offerWord;
    $countBadge = !empty($inventory['badge']) ? $inventory['badge'] : ($list->total().' căn');
    $crumbs = [];
    if (!empty($cate_slug ?? '')) {
        $crumbs[] = ['label' => $tradeLabel, 'url' => route('allListProCate', ['danhmuc' => $cate_slug])];
    }
    if (!empty($type_two_name ?? '') && !empty($type_slug ?? '') && !empty($type_two_slug ?? '')) {
        $crumbs[] = ['label' => $type_two_name, 'url' => route('allListTypeTwo', ['danhmuc' => $cate_slug, 'loaidanhmuc' => $type_slug, 'loai2' => $type_two_slug])];
    }
    if (!empty($type_name ?? '') && !empty($type_slug ?? '')) {
        $crumbs[] = ['label' => $type_name, 'url' => route('allListType', ['danhmuc' => $cate_slug ?: 'danh-muc', 'loaidanhmuc' => $type_slug])];
    }
@endphp
<div class="trade-list">
    <div class="trade-list-wrap">
        @if (count($crumbs))
        <ul class="trade-crumb">
            @foreach ($crumbs as $crumb)
            <li><a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a></li>
            @endforeach
        </ul>
        @endif

        <h1>{{ $pageTitle }}</h1>
        <p class="trade-lead">{{ $lead }}</p>

        <ul class="trade-chips">
            @if ($shortBed !== '')
            <li>{{ $shortBed === ($type_two_name ?? '') ? $shortBed : str_replace('PN', ' Phòng Ngủ', $shortBed) }}</li>
            @endif
            @if ($priceChip !== '')
            <li>{{ $priceChip }}</li>
            @endif
            <li>Cập nhật mỗi ngày</li>
            @if (!empty($type_name ?? ''))
            <li>{{ $type_name }}</li>
            @endif
        </ul>

        <div class="trade-head">
            <h2>{{ $sectionTitle }}</h2>
            <span class="trade-badge">{{ $countBadge }}</span>
        </div>

        @if ($list->count())
        <div class="bvl-wrap bvl-grid">
            <div class="bvl-grid-sec">
                <div class="bvl-grid-inner">
                    <div class="bvl-card-grid">
                        @foreach ($list as $item)
                        @include('layouts.product.item', [
                            'pro' => $item,
                            'listingCard' => true,
                            'addressFallback' => $type_name ?? '',
                            'cateFallback' => $cate_slug ?? 'mua',
                        ])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @else
        <p class="trade-empty">Chưa có căn hộ trong mục này.</p>
        @endif

        @if ($list->hasPages())
        <nav class="trade-pager" aria-label="Phân trang căn hộ">
            {{ $list->links() }}
        </nav>
        @endif

        @if (count($inventoryLogs))
        <section class="trade-journal">
            <div class="trade-head">
                <h2>Nhật ký &amp; Biến động giỏ hàng {{ $type_two_name ?? '' }}</h2>
                <span class="trade-badge">{{ count($inventoryLogs) }} ngày gần nhất</span>
            </div>
            <p class="trade-journal-lead">Dòng thời gian cập nhật giá {{ $offerWord }}, căn hộ mới ra và biến động thị trường trong {{ count($inventoryLogs) }} ngày gần nhất.</p>
            @foreach ($inventoryLogs as $day)
            <article class="tl-day" data-trade-day>
                <div class="tl-day-date">
                    <i></i>
                    @if (!empty($day['dow']))
                    <b>{{ $day['dow'] }}</b>
                    @endif
                    @if (!empty($day['date']))
                    <span>{{ $day['date'] }}</span>
                    @endif
                </div>
                <div class="tl-day-card">
                    <div class="tl-day-top">
                        @if (!empty($day['title']))
                        <h3>{{ $day['title'] }}</h3>
                        @endif
                        @if (!empty($day['time']))
                        <time>{{ $day['time'] }}</time>
                        @endif
                    </div>
                    @foreach ($day['groups'] ?? [] as $group)
                    <div data-trade-group>
                        @if (!empty($group['name']))
                        <h4>{{ $group['name'] }}</h4>
                        @endif
                        @if (!empty($group['items']))
                        <ul>
                            @foreach ($group['items'] as $logItem)
                            <li data-trade-item>
                                @if (!empty($logItem['label']))
                                <b>{{ $logItem['label'] }}</b> &#8211; {{ $logItem['text'] ?? '' }}
                                @else
                                {{ $logItem['text'] ?? '' }}
                                @endif
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    @endforeach
                </div>
            </article>
            @endforeach
            <div class="tl-log-pager" id="tl-log-pager"></div>
        </section>
        @endif
    </div>
</div>
@endsection
