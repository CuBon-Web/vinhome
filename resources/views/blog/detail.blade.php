@extends('layouts.main.master')
@section('title')
    {{ $blog_detail->seo_title ? $blog_detail->seo_title : languageName($blog_detail->title) }}
@endsection
@section('description')
    {{ $blog_detail->meta_description ? $blog_detail->meta_description : languageName($blog_detail->description) }}
@endsection
@section('image')
    {{ url('' . $blog_detail->image) }}
@endsection
@section('schema')
    @php
        $cleanText = function ($value) {
            $text = (string) $value;
            // Remove zero-width chars that usually appear from copy/paste.
            return preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $text);
        };
        $jsonFlags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
        $postTitle = $cleanText(languageName($blog_detail->title));
        $postDescription = $cleanText(
            $blog_detail->meta_description ?: strip_tags(languageName($blog_detail->description))
        );
        $postContentText = trim($cleanText(strip_tags(languageName($blog_detail->content))));
        preg_match_all('/[\p{L}\p{N}]+/u', $postContentText, $wordMatches);
        $postWordCount = count($wordMatches[0]);
        $postUrl = url()->current();
        $homeUrl = route('home');
        $categoryUrl = route('listCateBlog', ['slug' => $blog_detail->category]);
        $siteName = $setting->webname ?? ($setting->company ?? 'Website');
        $publisherName = $setting->company ?? $siteName;
        $publisherLogo = !empty($setting->logo) ? url($setting->logo) : url('' . $blog_detail->image);
    @endphp
    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "WebSite",
      "@id": {!! json_encode(url('/') . '#website', $jsonFlags) !!},
      "url": {!! json_encode(url('/'), $jsonFlags) !!},
      "name": {!! json_encode($siteName, $jsonFlags) !!}
    },
    {
      "@type": "Organization",
      "@id": {!! json_encode(url('/') . '#organization', $jsonFlags) !!},
      "name": {!! json_encode($publisherName, $jsonFlags) !!},
      "url": {!! json_encode(url('/'), $jsonFlags) !!},
      "logo": {
        "@type": "ImageObject",
        "url": {!! json_encode($publisherLogo, $jsonFlags) !!}
      }
    },
    {
      "@type": "BreadcrumbList",
      "@id": {!! json_encode($postUrl . '#breadcrumb', $jsonFlags) !!},
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Trang chủ",
          "item": {!! json_encode($homeUrl, $jsonFlags) !!}
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": {!! json_encode($cleanText(languageName($blog_detail->category)), $jsonFlags) !!},
          "item": {!! json_encode($categoryUrl, $jsonFlags) !!}
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": {!! json_encode($postTitle, $jsonFlags) !!},
          "item": {!! json_encode($postUrl, $jsonFlags) !!}
        }
      ]
    },
    {
      "@type": "BlogPosting",
      "@id": {!! json_encode($postUrl . '#article', $jsonFlags) !!},
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": {!! json_encode($postUrl, $jsonFlags) !!}
      },
      "headline": {!! json_encode($postTitle, $jsonFlags) !!},
      "description": {!! json_encode($postDescription, $jsonFlags) !!},
      "articleSection": {!! json_encode($cleanText(languageName($blog_detail->category)), $jsonFlags) !!},
      "keywords": {!! json_encode(implode(', ', $blogTags ?? []), $jsonFlags) !!},
      "inLanguage": "vi-VN",
      "wordCount": {{ $postWordCount }},
      "datePublished": {!! json_encode(optional($blog_detail->created_at)->toIso8601String(), $jsonFlags) !!},
      "dateModified": {!! json_encode(optional($blog_detail->updated_at)->toIso8601String(), $jsonFlags) !!},
      "image": [
        {
          "@type": "ImageObject",
          "url": {!! json_encode(url(''.$blog_detail->image), $jsonFlags) !!}
        }
      ],
      "author": {
        "@type": "Person",
        "name": {!! json_encode($cleanText($blog_detail->author ?: 'Admin'), $jsonFlags) !!}
      },
      "publisher": {
        "@type": "Organization",
        "@id": {!! json_encode(url('/') . '#organization', $jsonFlags) !!}
      }
    }
  ]
}
</script>
@endsection
@section('css')
<link rel="stylesheet" href="/frontend/css/blog-list.css?v={{ filemtime(public_path('frontend/css/blog-list.css')) }}">
@endsection
@section('js')

@endsection
@section('content')
@php
    $postTitle = languageName($blog_detail->title);
    $postCateName = ($blog_detail->cate && !empty($blog_detail->cate->name)) ? languageName($blog_detail->cate->name) : '';
    $postImage = trim((string) $blog_detail->image);
    if ($postImage !== '' && !preg_match('#^https?://#i', $postImage)) {
        $postImage = url($postImage);
    }
    $shareUrl = url()->current();
@endphp
<div class="bvh-blog">
    <div class="bvh-blog-wrap">
        <ul class="bvh-blog-crumb">
            <li><a href="{{ route('home') }}">Trang chủ</a></li>
            @if ($postCateName !== '')
            <li><a href="{{ route('listCateBlog', ['slug' => $blog_detail->category]) }}">{{ $postCateName }}</a></li>
            @endif
            <li>{{ $postTitle }}</li>
        </ul>

        <div class="bvh-blog-layout">
            <article class="bvh-article">
                <div class="bvh-article-inner">
                    @if ($postCateName !== '')
                    <a class="bvh-blog-eyebrow" href="{{ route('listCateBlog', ['slug' => $blog_detail->category]) }}"><span></span>{{ $postCateName }}</a>
                    @endif
                    <h1>{{ $postTitle }}</h1>
                    <div class="bvh-article-meta">
                        <time datetime="{{ optional($blog_detail->created_at)->toDateString() }}">{{ date_format($blog_detail->created_at, 'd/m/Y') }}</time>
                        <span>{{ languageName($blog_detail->author ?: 'Admin') }}</span>
                    </div>
                    @if ($postImage !== '')
                    <figure class="bvh-article-cover">
                        <img src="{{ $postImage }}" alt="{{ $postTitle }}">
                    </figure>
                    @endif
                    <div class="bvh-article-body">
                        {!! languageName($blog_detail->content) !!}
                    </div>
                    @if (!empty($blogTags))
                    <div class="bvh-article-tags">
                        @foreach ($blogTags as $tag)
                        <a href="{{ route('listBlogTag', ['tag' => $tag]) }}">{{ $tag }}</a>
                        @endforeach
                    </div>
                    @endif
                    <div class="bvh-article-share">
                        <strong>Chia sẻ</strong>
                        <a href="https://www.facebook.com/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener" title="Facebook"><img src="{{ url('frontend/images/icon_face.png') }}" alt="Facebook"></a>
                        <a href="https://pinterest.com/pin/create/button/?url={{ $shareUrl }}" target="_blank" rel="noopener" title="Pinterest"><img src="{{ url('frontend/images/icon_print.png') }}" alt="Pinterest"></a>
                        <a href="https://twitter.com/share?text={{ $shareUrl }}" target="_blank" rel="noopener" title="Twitter"><img src="{{ url('frontend/images/icon_tw.png') }}" alt="Twitter"></a>
                    </div>
                </div>
            </article>

            @if (isset($blognew) && count($blognew))
            <aside class="bvh-blog-side">
                <h2>Bài viết mới</h2>
                @foreach ($blognew as $item)
                @if ($item->slug === $blog_detail->slug)
                    @continue
                @endif
                @php $sideTitle = languageName($item->title); @endphp
                <a class="bvh-blog-mini" href="{{ route('detailBlog', ['slug' => $item->slug]) }}">
                    <span class="bvh-blog-mini-img">
                        @if (!empty($item->image))
                        <img src="{{ preg_match('#^https?://#i', $item->image) ? $item->image : url($item->image) }}" alt="" loading="lazy">
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
