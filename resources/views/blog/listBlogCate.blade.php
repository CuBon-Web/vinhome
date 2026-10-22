@extends('layouts.main.master')
@section('title')
{{$title_page}} 
@endsection
@section('description')
{{$title_page}} 
@endsection
@section('image')
{{url(''.$banner[0]->image)}}
@endsection
@section('schema')
@php
    $cleanText = function ($value) {
        $text = (string) $value;
        // Remove zero-width chars that usually appear from copy/paste.
        return preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $text);
    };
    $jsonFlags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
    $currentUrl = url()->current();
    $homeUrl = route('home');
    $siteUrl = url('/');
    $categoryUrl = route('listCateBlog', ['slug' => $cate_name]);
    $pageTitle = $cleanText($title_page);
    $siteName = $cleanText($setting->webname ?? $setting->company ?? $title_page);
    $publisherName = $cleanText($setting->company ?? $siteName);
    $publisherLogo = !empty($setting->logo)
        ? url($setting->logo)
        : (!empty($banner[0]->image) ? url($banner[0]->image) : null);

    $itemListElements = [];
    foreach ($blog as $index => $item) {
        $postUrl = route('detailBlog', ['slug' => $item->slug]);
        $postImage = !empty($item->image) ? url($item->image) : null;
        $itemListElements[] = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'url' => $postUrl,
            'item' => array_filter([
                '@type' => 'BlogPosting',
                'headline' => $cleanText(languageName($item->title)),
                'description' => $cleanText(strip_tags(languageName($item->description))),
                'datePublished' => optional($item->created_at)->toIso8601String(),
                'image' => $postImage,
                'mainEntityOfPage' => $postUrl,
            ], function ($value) {
                return !is_null($value) && $value !== '';
            }),
        ];
    }

    $schemaGraph = [
        [
            '@type' => 'WebSite',
            '@id' => $siteUrl . '#website',
            'url' => $siteUrl,
            'name' => $siteName,
            'inLanguage' => 'vi-VN',
        ],
        array_filter([
            '@type' => 'Organization',
            '@id' => $siteUrl . '#organization',
            'name' => $publisherName,
            'url' => $siteUrl,
            'logo' => $publisherLogo ? [
                '@type' => 'ImageObject',
                'url' => $publisherLogo,
            ] : null,
        ], function ($value) {
            return !is_null($value) && $value !== '';
        }),
        [
            '@type' => 'BreadcrumbList',
            '@id' => $currentUrl . '#breadcrumb',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Trang chủ',
                    'item' => $homeUrl,
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => $pageTitle,
                    'item' => $categoryUrl,
                ],
            ],
        ],
        [
            '@type' => 'CollectionPage',
            '@id' => $currentUrl . '#collection',
            'url' => $currentUrl,
            'name' => $pageTitle,
            'description' => $pageTitle,
            'inLanguage' => 'vi-VN',
            'isPartOf' => [
                '@type' => 'WebSite',
                '@id' => $siteUrl . '#website',
            ],
        ],
        [
            '@type' => 'ItemList',
            '@id' => $currentUrl . '#itemlist',
            'name' => $pageTitle,
            'numberOfItems' => count($itemListElements),
            'itemListElement' => $itemListElements,
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@graph' => $schemaGraph], $jsonFlags) !!}</script>
@endsection
@section('css')
<link rel="stylesheet" href="/frontend/css/blog-list.css?v={{ filemtime(public_path('frontend/css/blog-list.css')) }}">
@endsection
@section('js')

@endsection
@section('content')
<div class="bvh-blog">
    <div class="bvh-blog-wrap">
        <ul class="bvh-blog-crumb">
            <li><a href="{{ route('home') }}">Trang chủ</a></li>
            <li>{{ $title_page }}</li>
        </ul>
        <div class="bvh-blog-head">
            <div>
                <div class="bvh-blog-eyebrow"><span></span>Tin tức</div>
                <h1>{{ $title_page }}</h1>
            </div>
            <span class="bvh-blog-count">{{ $blog->total() }} bài viết</span>
        </div>

        <div class="bvh-blog-layout">
            <div>
                @if ($blog->count())
                <div class="bvh-blog-grid">
                    @foreach ($blog as $item)
                    @php
                        $postTitle = languageName($item->title);
                        $postExcerpt = trim(preg_replace('/\s+/u', ' ', strip_tags(html_entity_decode(languageName($item->description), ENT_QUOTES, 'UTF-8'))));
                    @endphp
                    <article class="bvh-blog-card">
                        <a class="bvh-blog-media" href="{{ route('detailBlog', ['slug' => $item->slug]) }}">
                            @if (!empty($item->image))
                            <img src="{{ url($item->image) }}" alt="{{ $postTitle }}" loading="lazy">
                            @endif
                        </a>
                        <div class="bvh-blog-body">
                            <time datetime="{{ optional($item->created_at)->toDateString() }}">{{ date_format($item->created_at, 'd/m/Y') }}</time>
                            <h2><a href="{{ route('detailBlog', ['slug' => $item->slug]) }}">{{ $postTitle }}</a></h2>
                            @if ($postExcerpt !== '')
                            <p>{{ \Illuminate\Support\Str::limit($postExcerpt, 140) }}</p>
                            @endif
                        </div>
                    </article>
                    @endforeach
                </div>
                @else
                <p class="bvh-blog-empty">Chưa có bài viết trong mục này.</p>
                @endif

                @if ($blog->hasPages())
                <nav class="bvh-blog-pager" aria-label="Phân trang bài viết">
                    {{ $blog->links() }}
                </nav>
                @endif
            </div>

            @if (isset($blognew) && count($blognew))
            <aside class="bvh-blog-side">
                <h2>Bài viết mới</h2>
                @foreach ($blognew as $item)
                @php $sideTitle = languageName($item->title); @endphp
                <a class="bvh-blog-mini" href="{{ route('detailBlog', ['slug' => $item->slug]) }}">
                    <span class="bvh-blog-mini-img">
                        @if (!empty($item->image))
                        <img src="{{ url($item->image) }}" alt="" loading="lazy">
                        @endif
                    </span>
                    <span>
                        <time>{{ date_format($item->created_at, 'd/m/Y') }}</time>
                        <strong>{{ $sideTitle }}</strong>
                    </span>
                </a>
                @endforeach
            </aside>
            @endif
        </div>
    </div>
</div>
@endsection