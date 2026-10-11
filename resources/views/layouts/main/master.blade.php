<!DOCTYPE html>
<html lang="vi">
   <head>
      @php
      $seoCanonical = trim($__env->yieldContent('canonical')) ?: seo_canonical_url();
      $seoRobots = trim($__env->yieldContent('robots')) ?: seo_robots_directive();
      $seoTitle = trim($__env->yieldContent('title'));
      $seoDescription = trim($__env->yieldContent('description'));
      $seoImage = trim($__env->yieldContent('image'));
      $seoSiteName = $setting->webname ?? ($setting->company ?? config('app.name'));
      @endphp
      <meta charset="UTF-8" />
      <meta name="theme-color" content="#d70018">
      <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>{{ $seoTitle }}</title>
      <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
      <meta http-equiv="Content-Language" content="vi" />
      <link rel="alternate" href="{{ $seoCanonical }}" hreflang="vi" />
      <meta name="description" content="{{ $seoDescription }}">
      <meta name="robots" content="{{ $seoRobots }}" />
      <meta name="googlebot" content="{{ $seoRobots }}">
      <meta name="revisit-after" content="3 days" />
      <meta name="rating" content="General">
      <meta name="application-name" content="{{ $seoSiteName }}" />
      <meta name="theme-color" content="#ed3235" />
      <meta name="msapplication-TileColor" content="#ed3235" />
      <meta name="apple-mobile-web-app-capable" content="yes" />
      <meta name="apple-mobile-web-app-title" content="{{ $seoSiteName }}" />
      @if ($seoImage)
      <link rel="apple-touch-icon-precomposed" href="{{ $seoImage }}" sizes="700x700">
      @endif
      <meta property="og:url" content="{{ $seoCanonical }}">
      <meta property="og:title" content="{{ $seoTitle }}">
      <meta property="og:description" content="{{ $seoDescription }}">
      <meta property="og:image" content="{{ $seoImage }}">
      <meta property="og:site_name" content="{{ $seoSiteName }}">
      <meta property="og:image:alt" content="{{ $seoTitle }}">
      <meta property="og:type" content="website" />
      <meta property="og:locale" content="vi_VN" />
      <meta name="twitter:card" content="summary_large_image" />
      <meta name="twitter:title" content="{{ $seoTitle }}" />
      <meta name="twitter:description" content="{{ $seoDescription }}" />
      <meta name="twitter:image" content="{{ $seoImage }}" />
      <meta name="twitter:url" content="{{ $seoCanonical }}" />
      <meta itemprop="name" content="{{ $seoTitle }}">
      <meta itemprop="description" content="{{ $seoDescription }}">
      <meta itemprop="image" content="{{ $seoImage }}">
      <meta itemprop="url" content="{{ $seoCanonical }}">
      <link rel="canonical" href="{{ $seoCanonical }}">
      @if ($seoImage)
      <link rel="image_src" href="{{ $seoImage }}" />
      @endif
      <link rel="shortcut icon" href="{{ url('' . $setting->favicon) }}" type="image/x-icon">
      <link rel="icon" href="{{ url('' . $setting->favicon) }}" type="image/x-icon">
      <meta name="csrf-token" content="{{ csrf_token() }}" />
      @hasSection('schema')
      @yield('schema')
      @else
      @include('partials.seo-organization')
      @endif
      <!-- Styles Include -->
      <link rel="icon" href="img/favicon.png" sizes="32x32">
    <link rel="stylesheet" href="/frontend/css/fonts.css">
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link rel="stylesheet" href="/frontend/css/header.css">
    <link rel="stylesheet" href="/frontend/css/footer.css">
    <link rel="stylesheet" href="/frontend/css/homepage.css">
    <link rel="stylesheet" href="/frontend/css/design-system.css">
    <link rel="stylesheet" href="/frontend/css/grid.css">
    <link rel="stylesheet" href="/frontend/css/extra.css">
    <link rel="stylesheet" href="/frontend/googletranslate/google-translate-lang.css">
      @yield('css')
   </head>
   <body class="home">
      @include('layouts.header.index')
     @yield('content')
 
     <!-- Lightbox cho gallery tiện ích — không có prev/next -->
     <div id="bvh-lb" class="bvh-lb" aria-hidden="true">
         <button class="bvh-lb-close" aria-label="Đóng">&#x2715;</button>
         <img class="bvh-lb-img" src="" alt="">
     </div>
 
    @include('layouts.footer.index')
      <div id="google_translate_element" class="notranslate" aria-hidden="true"></div>
      <script src="/frontend/js/header.js"></script>
      <script src="/frontend/js/homepage.js"></script>
      <script src="/frontend/googletranslate/google-translate-lang.js?v={{ filemtime(public_path('frontend/googletranslate/google-translate-lang.js')) }}"></script>
      @yield('js')
   </body>
</html>