@props(['url' => null, 'title' => null])

@php
    $shareUrl = urlencode($url ?? url()->current());
    $shareTitle = urlencode($title ?? config('seo.brand', 'VUS Bemanning'));
@endphp

<div class="social-share-block">
    <p class="social-share-label mb-2">
        <i class="bi-share me-2"></i>{{ __('messages.share.title') }}
    </p>
    <div class="social-share-buttons d-flex flex-wrap gap-2">

        {{-- Facebook --}}
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
           target="_blank" rel="noopener noreferrer"
           class="social-share-btn social-share-facebook"
           title="Facebook">
            <i class="bi-facebook"></i>
        </a>

        {{-- Twitter / X --}}
        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}"
           target="_blank" rel="noopener noreferrer"
           class="social-share-btn social-share-twitter"
           title="Twitter / X">
            <i class="bi-twitter"></i>
        </a>

        {{-- LinkedIn --}}
        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}"
           target="_blank" rel="noopener noreferrer"
           class="social-share-btn social-share-linkedin"
           title="LinkedIn">
            <i class="bi-linkedin"></i>
        </a>

        {{-- WhatsApp --}}
        <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}"
           target="_blank" rel="noopener noreferrer"
           class="social-share-btn social-share-whatsapp"
           title="WhatsApp">
            <i class="bi-whatsapp"></i>
        </a>

        {{-- Telegram --}}
        <a href="https://t.me/share/url?url={{ $shareUrl }}&text={{ $shareTitle }}"
           target="_blank" rel="noopener noreferrer"
           class="social-share-btn social-share-telegram"
           title="Telegram">
            <i class="bi-telegram"></i>
        </a>

        {{-- Pinterest --}}
        <a href="https://pinterest.com/pin/create/button/?url={{ $shareUrl }}&description={{ $shareTitle }}"
           target="_blank" rel="noopener noreferrer"
           class="social-share-btn social-share-pinterest"
           title="Pinterest">
            <i class="bi-pinterest"></i>
        </a>

        {{-- Reddit --}}
        <a href="https://www.reddit.com/submit?url={{ $shareUrl }}&title={{ $shareTitle }}"
           target="_blank" rel="noopener noreferrer"
           class="social-share-btn social-share-reddit"
           title="Reddit">
            <i class="bi-reddit"></i>
        </a>

        {{-- Instagram – no web share API, copy link instead --}}
        <button type="button"
                class="social-share-btn social-share-instagram"
                title="Instagram"
                onclick="socialShareCopyLink('{{ $url ?? url()->current() }}', this)">
            <i class="bi-instagram"></i>
        </button>

        {{-- Slack – no web share API, copy link instead --}}
        <button type="button"
                class="social-share-btn social-share-slack"
                title="Slack"
                onclick="socialShareCopyLink('{{ $url ?? url()->current() }}', this)">
            <i class="bi-slack"></i>
        </button>

        {{-- Email --}}
        <a href="mailto:?subject={{ $shareTitle }}&body={{ $shareUrl }}"
           class="social-share-btn social-share-email"
           title="Email">
            <i class="bi-envelope"></i>
        </a>

        {{-- Copy link --}}
        <button type="button"
                class="social-share-btn social-share-copy"
                title="{{ __('messages.share.copy_link') }}"
                onclick="socialShareCopyLink('{{ $url ?? url()->current() }}', this)">
            <i class="bi-link-45deg"></i>
        </button>

    </div>
</div>

<style>
.social-share-block { margin: 1rem 0; }
.social-share-label { font-size: 0.875rem; font-weight: 600; color: #555; margin-bottom: 0.5rem; }
.social-share-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 36px; height: 36px; border-radius: 50%;
    font-size: 1rem; color: #fff; border: none; cursor: pointer;
    text-decoration: none; transition: opacity 0.2s, transform 0.2s;
}
.social-share-btn:hover { opacity: 0.85; transform: translateY(-2px); color: #fff; }
.social-share-facebook  { background: #1877F2; }
.social-share-twitter   { background: #000; }
.social-share-linkedin  { background: #0A66C2; }
.social-share-whatsapp  { background: #25D366; }
.social-share-telegram  { background: #26A5E4; }
.social-share-pinterest { background: #E60023; }
.social-share-reddit    { background: #FF4500; }
.social-share-instagram { background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); }
.social-share-slack     { background: #4A154B; }
.social-share-email     { background: #555; }
.social-share-copy      { background: #333; }
</style>

<script>
function socialShareCopyLink(url, btn) {
    navigator.clipboard.writeText(url).then(function() {
        const icon = btn.querySelector('i');
        const orig = icon.className;
        icon.className = 'bi-check-lg';
        setTimeout(function() { icon.className = orig; }, 1500);
    }).catch(function() {
        const el = document.createElement('textarea');
        el.value = url;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
    });
}
</script>
