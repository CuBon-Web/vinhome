@php
    $callPhone = trim((string) ($setting->phone1 ?? ''));
    $callDigits = preg_replace('/\D+/', '', $callPhone);
    $callTel = $callPhone !== '' ? 'tel:' . preg_replace('/[^\d+]/', '', $callPhone) : '';
    $callZalo = $callDigits !== '' ? 'https://zalo.me/' . $callDigits : '';
    $callFacebook = trim((string) ($setting->facebook ?? ''));
    if ($callFacebook !== '' && !preg_match('#^https?://#i', $callFacebook)) {
        $callFacebook = 'https://' . ltrim($callFacebook, '/');
    }
@endphp
@if ($callZalo !== '' || $callTel !== '' || $callFacebook !== '')
<aside class="bvh-call" aria-label="Liên hệ nhanh">
    @if ($callZalo !== '')
    <a class="bvh-call__btn bvh-call__btn--zalo" href="{{ $callZalo }}" target="_blank" rel="noopener noreferrer" aria-label="Nhắn Zalo">
        <span class="bvh-call__ring" aria-hidden="true"></span>
        <span class="bvh-call__label" aria-hidden="true">Nhắn Zalo</span>
        <span class="bvh-call__mark" aria-hidden="true">Zalo</span>
    </a>
    @endif
    @if ($callTel !== '')
    <a class="bvh-call__btn bvh-call__btn--hotline" href="{{ $callTel }}" aria-label="Gọi {{ $callPhone }}">
        <span class="bvh-call__ring" aria-hidden="true"></span>
        <span class="bvh-call__ring bvh-call__ring--late" aria-hidden="true"></span>
        <span class="bvh-call__label" aria-hidden="true">Gọi {{ $callPhone }}</span>
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.6 10.8a15.1 15.1 0 006.6 6.6l2.2-2.2a1 1 0 011-.25 11.4 11.4 0 003.6.57 1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1 11.4 11.4 0 00.57 3.6 1 1 0 01-.25 1L6.6 10.8z"/></svg>
    </a>
    @endif
    @if ($callFacebook !== '')
    <a class="bvh-call__btn bvh-call__btn--facebook" href="{{ $callFacebook }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
        <span class="bvh-call__ring" aria-hidden="true"></span>
        <span class="bvh-call__label" aria-hidden="true">Facebook</span>
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14.5 8.5V6.8c0-.7.5-1.1 1.2-1.1H17V3h-2.1C12.4 3 11 4.5 11 6.7v1.8H9v2.6h2V21h3.1v-9.9h2.3l.4-2.6h-2.7z"/></svg>
    </a>
    @endif
</aside>
@endif
