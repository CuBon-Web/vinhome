@extends('layouts.main.master')
@section('title')
Ký gửi căn hộ Vinhomes Central Park
@endsection
@section('description')
Ký gửi căn hộ Vinhomes Central Park. Không phí trước, chỉ thu hoa hồng khi giao dịch thành công.
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('frontend/css/consignment.css') }}" />
@endsection
@section('content')
@php
    $phone = trim((string) ($setting->phone1 ?? ''));
    $phoneHref = preg_replace('/\s+/', '', $phone);
    $zaloDigits = preg_replace('/\D+/', '', $phone);
    $zaloHref = $zaloDigits !== '' ? 'https://zalo.me/'.$zaloDigits : 'https://zalo.me';
    $mail = trim((string) ($setting->email ?? ''));
    $address = trim((string) ($setting->address1 ?? ''));
    if ($address === '') {
        $address = 'Tòa Landmark 3, Vinhomes Central Park';
    }
@endphp
<div class="kg">
    <section class="kg-hero">
        <div class="bvh-wrap kg-narrow">
            <p class="kg-kicker"><span></span> Dịch vụ ký gửi</p>
            <h1>Ký gửi căn hộ</h1>
            <p class="kg-brand">Vinhomes Central Park</p>
            <p class="kg-lead">Bạn muốn bán hoặc cho thuê căn hộ tại Vinhomes Central Park?<br>Chúng tôi tiếp nhận ký gửi, tìm khách và chốt giao dịch – bạn chỉ trả hoa hồng khi thành công.</p>
            <p class="kg-chip">Không phí trước · Chỉ thu hoa hồng khi giao dịch thành công</p>
            <div class="kg-actions">
                <a class="kg-btn kg-btn-gold" href="#kg-start">Đăng ký ký gửi <span aria-hidden="true">→</span></a>
                @if ($phone !== '')
                <a class="kg-btn kg-btn-line" href="tel:{{ $phoneHref }}">Gọi {{ $phone }}</a>
                @endif
            </div>
            <ul class="kg-points">
                <li>
                    <b>8:00 – 21:00 mỗi ngày</b>
                    <span>Hỗ trợ không nghỉ lễ, phản hồi trong vòng 1 giờ</span>
                </li>
                <li>
                    <b>Pháp lý minh bạch</b>
                    <span>Hợp đồng ký gửi rõ ràng, không phát sinh chi phí ẩn</span>
                </li>
                <li>
                    <b>Khách hàng đa kênh</b>
                    <span>Website, Zalo, mạng xã hội và khách mới trực tiếp</span>
                </li>
            </ul>
        </div>
    </section>

    <section class="kg-why">
        <div class="bvh-wrap kg-split">
            <div>
                <h2>Tại sao ký gửi với chúng tôi?</h2>
                <p>Chúng tôi chỉ chuyên một dự án. Điều đó có nghĩa là chúng tôi biết chính xác giá thị trường từng tòa, từng tầng, từng hướng view – và tìm đúng khách cho căn của bạn.</p>
            </div>
            <ol>
                <li>
                    <span>01</span>
                    <div>
                        <b>Không rủi ro tài chính</b>
                        <p>Chúng tôi chỉ thu hoa hồng theo quy định khi giao dịch thành công. Không ra được giao dịch, bạn không mất bất kỳ chi phí nào.</p>
                    </div>
                </li>
                <li>
                    <span>02</span>
                    <div>
                        <b>Định giá chính xác theo thực tế</b>
                        <p>Không định giá theo cảm tính. Chúng tôi dựa vào lịch sử giao dịch thực tế, giỏ hàng cập nhật mỗi ngày để xác định mức giá đúng và đẩy nhanh giao dịch.</p>
                    </div>
                </li>
                <li>
                    <span>03</span>
                    <div>
                        <b>Tiếp cận khách hàng rộng</b>
                        <p>Căn của bạn được đăng trên website, Zalo, mạng xã hội và hệ thống khách mới đang tìm mua hoặc thuê tại Vinhomes Central Park.</p>
                    </div>
                </li>
                <li>
                    <span>04</span>
                    <div>
                        <b>Cập nhật tiến độ thường xuyên</b>
                        <p>Bạn sẽ được thông báo về lượt xem, phản hồi khách hàng và điều chỉnh lược kịp thời – không phải đợi đến khi có kết quả mới biết chuyện gì đang xảy ra.</p>
                    </div>
                </li>
            </ol>
        </div>
    </section>

    <section class="kg-steps">
        <div class="bvh-wrap">
            <h2>Quy trình ký gửi</h2>
            <p class="kg-sub">4 bước đơn giản – từ lúc liên hệ đến khi tiền về tay bạn.</p>
            <ol>
                <li>
                    <span>01</span>
                    <b>Liên hệ &amp; Ký gửi</b>
                    <p>Gọi hoặc nhắn Zalo. Chúng tôi xem thông tin căn, tình trạng pháp lý và ký hợp đồng ký gửi minh bạch.</p>
                </li>
                <li>
                    <span>02</span>
                    <b>Thẩm định &amp; Định giá</b>
                    <p>Khảo sát thực tế căn hộ, so sánh với thị trường và đề xuất mức giá tối ưu để chốt nhanh nhất có thể.</p>
                </li>
                <li>
                    <span>03</span>
                    <b>Đăng tin &amp; Tìm khách</b>
                    <p>Đăng đa kênh, dẫn khách đi xem thực tế, tư vấn và chốt giá theo đúng lợi ích của bạn.</p>
                </li>
                <li>
                    <span>04</span>
                    <b>Giao dịch thành công</b>
                    <p>Hỗ trợ hoàn thiện thủ tục pháp lý và bàn giao. Hoa hồng chỉ thanh toán sau khi giao dịch hoàn tất.</p>
                </li>
            </ol>
        </div>
    </section>

    <section class="kg-list" id="kg-list">
        <div class="bvh-wrap">
            <h2>Căn hộ đang ký gửi</h2>
            <p class="kg-sub">Giỏ hàng cập nhật mỗi ngày · 100% xác thực</p>

            @if ($saleProducts->count())
            <p class="kg-tag kg-tag-sale">Mua bán</p>
            <div class="bvl-wrap bvl-grid">
                <div class="bvl-grid-sec">
                    <div class="bvl-grid-inner">
                        <div class="bvl-card-grid">
                            @foreach ($saleProducts as $pro)
                            @include('layouts.product.item', [
                                'pro' => $pro,
                                'listingCard' => true,
                                'cateFallback' => 'mua',
                                'addressFallback' => $pro->typecate ? languageName($pro->typecate->name) : '',
                            ])
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if ($rentProducts->count())
            <p class="kg-tag kg-tag-rent">Cho thuê dài hạn</p>
            <div class="bvl-wrap bvl-grid">
                <div class="bvl-grid-sec">
                    <div class="bvl-grid-inner">
                        <div class="bvl-card-grid">
                            @foreach ($rentProducts as $pro)
                            @include('layouts.product.item', [
                                'pro' => $pro,
                                'listingCard' => true,
                                'cateFallback' => 'thue',
                                'addressFallback' => $pro->typecate ? languageName($pro->typecate->name) : '',
                            ])
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if (!$saleProducts->count() && !$rentProducts->count())
            <p class="kg-empty">Hiện chưa có căn nào trong danh sách ký gửi.</p>
            @endif

            <p class="kg-note">Căn hộ của bạn không có trong danh sách? Liên hệ để được tư vấn định giá và ký gửi.</p>
            <div class="kg-actions kg-actions-center">
                <a class="kg-btn kg-btn-gold" href="#kg-start">Ký gửi căn của tôi <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </section>

    <section class="kg-cta" id="kg-start">
        <div class="bvh-wrap">
            <h2>Bắt đầu ký gửi hôm nay</h2>
            <p>Gọi hoặc nhắn Zalo – chúng tôi phản hồi trong vòng 1 giờ và tư vấn hoàn toàn miễn phí.</p>
            <div class="kg-actions kg-actions-center">
                <a class="kg-btn kg-btn-gold" href="{{ $zaloHref }}" target="_blank" rel="noopener">Nhắn Zalo <span aria-hidden="true">→</span></a>
                @if ($phone !== '')
                <a class="kg-btn kg-btn-ghost" href="tel:{{ $phoneHref }}">Gọi {{ $phone }}</a>
                @endif
            </div>
            <p class="kg-cta-meta">
                @if ($mail !== ''){{ $mail }} · @endif{{ $address }}
            </p>
            <p class="kg-cta-hours">Hoạt động 8:00 – 21:00 mỗi ngày</p>
        </div>
    </section>
</div>
@endsection
