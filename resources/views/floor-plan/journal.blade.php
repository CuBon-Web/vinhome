@extends('layouts.main.master')
@section('title')
Nhật ký giỏ hàng {{ $journalBuilding ?: ($plan->title ?: $plan->name) }}
@endsection
@section('description')
Nhật ký giỏ hàng {{ $journalTotal }} ngày của {{ $plan->title ?: $plan->name }}.
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('frontend/css/floor-plan.css') }}" />
@endsection
@section('content')
<div class="fp-page">
    <div class="bvh-wrap">
        <p class="fp-eyebrow">
            <a href="{{ route('floorPlan', ['slug' => $plan->slug]) }}">{{ $plan->title ?: $plan->name }}</a>
        </p>
        <h1>Nhật ký giỏ hàng {{ $journalBuilding ?: $plan->name }}</h1>
        <p class="fp-intro">{{ $journalTotal }} ngày{{ $journalBuilding ? ' · Tòa '.$journalBuilding : '' }}</p>

        @if (count($journalDays))
        <section class="fp-journal">
            @include('floor-plan.partials.log')
        </section>
        @else
        <p class="fp-intro">Chưa có nhật ký cho mặt bằng này.</p>
        @endif
    </div>
</div>
@endsection
