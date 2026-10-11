@extends('layouts.main.master')
@section('title')
Kết quả tìm kiếm: {{ $keyword }}
@endsection
@section('description')
Tìm thấy {{ $product->total() }} căn hộ cho từ khóa "{{ $keyword }}"
@endsection
@section('image')
{{ url($setting->logo ?? '') }}
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('frontend/css/trade-list.css') }}" />
@endsection
@section('content')
<div class="trade-list">
    <div class="trade-list-wrap">
        <ul class="trade-crumb">
            <li><a href="{{ route('home') }}">Trang chủ</a></li>
            <li>Tìm kiếm</li>
        </ul>

        <h1>Kết quả tìm kiếm “{{ $keyword }}”</h1>
        <p class="trade-lead">
            Có {{ $product->total() }} căn hộ phù hợp{{ $cateName !== '' ? ' trong mục '.$cateName : '' }}.
        </p>

        <form class="hero-search hero-search--light" action="{{ route('search_result') }}" method="get" role="search">
            <label class="hs-field hs-cate">
                <span class="hs-label">Danh mục</span>
                <select name="cate">
                    <option value="">Tất cả</option>
                    @foreach ($searchCategories as $searchCate)
                    <option value="{{ $searchCate->slug }}" @if ($cate === $searchCate->slug) selected @endif>{{ languageName($searchCate->name) }}</option>
                    @endforeach
                </select>
            </label>
            <label class="hs-field hs-keyword">
                <span class="hs-label">Từ khóa</span>
                <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Tòa, số phòng ngủ, view..." autocomplete="off">
            </label>
            <button type="submit" class="hs-submit">Tìm kiếm <span class="arr">&rarr;</span></button>
        </form>

        <div class="trade-head">
            <h2>Căn hộ phù hợp</h2>
            <span class="trade-badge">{{ $product->total() }} căn</span>
        </div>

        @if ($product->count())
        <div class="bvl-wrap bvl-grid">
            <div class="bvl-grid-sec">
                <div class="bvl-grid-inner">
                    <div class="bvl-card-grid">
                        @foreach ($product as $item)
                        @include('layouts.product.item', [
                            'pro' => $item,
                            'listingCard' => true,
                            'cateFallback' => $item->cate_slug ?: 'mua',
                        ])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @else
        <p class="trade-empty">Không tìm thấy căn hộ phù hợp. Thử từ khóa khác hoặc chọn danh mục “Tất cả”.</p>
        @endif

        @if ($product->hasPages())
        <nav class="trade-pager" aria-label="Phân trang kết quả">
            {{ $product->links() }}
        </nav>
        @endif
    </div>
</div>
@endsection
