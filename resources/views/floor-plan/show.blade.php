@extends('layouts.main.master')
@section('title')
{{ $plan->title ?: $plan->name }}
@endsection
@section('description')
{{ \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($plan->intro))), 160) }}
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('frontend/css/floor-plan.css') }}" />
@endsection
@section('js')
<script>
document.querySelectorAll('.fp-article img').forEach(function (image) {
    image.addEventListener('click', function () {
        var box = document.getElementById('bvh-lb');
        var target = box ? box.querySelector('.bvh-lb-img') : null;
        if (!box || !target) return;
        target.src = image.currentSrc || image.src;
        target.alt = image.alt || '';
        box.classList.add('open');
        document.body.style.overflow = 'hidden';
    });
});
</script>
@endsection
@section('content')
@php
    $article = is_string($plan->content) ? $plan->content : '';
    $footnoteHtml = is_string($plan->footnote) ? trim($plan->footnote) : '';
    $footnoteTitle = trim((string) $plan->footnote_title);
    if ($footnoteTitle !== '' && !preg_match('/<h[23]\b/i', $footnoteHtml)) {
        $footnoteHtml = '<h2>'.e($footnoteTitle).'</h2>'.$footnoteHtml;
    }
    if ($footnoteHtml !== '' && strpos($article, $footnoteHtml) === false) {
        $article .= $footnoteHtml;
    }
    $article = preg_replace('/<p[^>]*>\s*\[\[nhat-ky\]\]\s*<\/p>/i', '[[nhat-ky]]', $article);
    $toc = [];
    $headingIndex = 0;
    $paint = function ($html) use (&$toc, &$headingIndex) {
        $html = preg_replace_callback('/<h([23])([^>]*)>(.*?)<\/h\1>/is', function ($match) use (&$toc, &$headingIndex) {
            $headingIndex++;
            $id = 'fp-h-'.$headingIndex;
            $attrs = $match[2];
            if (preg_match('/\sid=(["\'])(.*?)\1/i', $attrs, $existing)) {
                $id = $existing[2];
            } else {
                $attrs .= ' id="'.$id.'"';
            }
            $toc[] = [
                'level' => (int) $match[1],
                'id' => $id,
                'text' => trim(html_entity_decode(strip_tags($match[3]), ENT_QUOTES, 'UTF-8')),
            ];
            return '<h'.$match[1].$attrs.'>'.$match[3].'</h'.$match[1].'>';
        }, $html);
        return preg_replace_callback('/>([^<]*)</u', function ($match) {
            return '>'.str_replace('*', '<span class="fp-star">*</span>', $match[1]).'<';
        }, $html);
    };
    $article = $paint($article);
    $tocTree = [];
    foreach ($toc as $item) {
        if ($item['level'] === 2 || !count($tocTree)) {
            $tocTree[] = ['item' => $item, 'children' => []];
        } else {
            $tocTree[count($tocTree) - 1]['children'][] = $item;
        }
    }
    $articleParts = preg_split('/\[\[nhat-ky\]\]/i', $article, 2);
    $articleBefore = $articleParts[0] ?? '';
    $articleAfter = $articleParts[1] ?? null;
@endphp
<div class="fp-page">
    <div class="bvh-wrap">
        @if ($plan->eyebrow)
        <p class="fp-eyebrow">{{ $plan->eyebrow }}</p>
        @endif
        <h1>{{ $plan->title ?: $plan->name }}</h1>
        @if ($plan->intro)
        <p class="fp-intro">{{ $plan->intro }}</p>
        @endif

        @if (count($tocTree))
        <nav class="fp-toc" aria-label="Nội dung bài viết">
            <p class="fp-toc-title">Nội dung bài viết</p>
            <ul>
                @foreach ($tocTree as $node)
                <li>
                    <a href="#{{ $node['item']['id'] }}">{{ $node['item']['text'] }}</a>
                    @if (count($node['children']))
                    <ul>
                        @foreach ($node['children'] as $child)
                        <li><a href="#{{ $child['id'] }}">{{ $child['text'] }}</a></li>
                        @endforeach
                    </ul>
                    @endif
                </li>
                @endforeach
            </ul>
        </nav>
        @endif

        @if ($articleBefore !== '')
        <div class="fp-article">{!! $articleBefore !!}</div>
        @endif

        @if (count($journalDays))
        <section class="fp-journal">
            <p class="fp-journal-label">Nhật ký giỏ hàng {{ count($journalDays) }} ngày gần nhất{{ $journalBuilding ? ' · Tòa '.$journalBuilding : '' }}</p>
            @include('floor-plan.partials.log')
            @if ($journalTotal > count($journalDays))
            <div class="fp-more-wrap">
                <a class="fp-more" href="{{ route('floorPlanJournal', ['slug' => $plan->slug]) }}">Xem toàn bộ nhật ký {{ $journalTotal }} ngày{{ $journalBuilding ? ' Tòa '.$journalBuilding : '' }} <span aria-hidden="true">→</span></a>
            </div>
            @endif
        </section>
        @endif

        @if ($articleAfter !== null && trim($articleAfter) !== '')
        <div class="fp-article">{!! $articleAfter !!}</div>
        @endif
    </div>
</div>
@endsection
