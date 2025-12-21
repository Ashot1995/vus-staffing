@php
    $cookieConsent = request()->cookie('cookie_consent') ?? session('cookie_consent');
    $showBanner = !$cookieConsent;
@endphp

@if($showBanner)
<div id="cookie-banner" class="cookie-banner" style="position: fixed; bottom: 0; left: 0; right: 0; background: #ffffff; border-top: 2px solid #000000; padding: 20px; z-index: 9999; box-shadow: 0 -4px 10px rgba(0,0,0,0.1);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 col-md-7 col-12 mb-3 mb-md-0">
                <p class="mb-0" style="color: #000000; font-size: 14px;">
                    {{ __('messages.cookie.text') }}
                    <a href="{{ route('privacy') }}" style="color: #000000; text-decoration: underline;">{{ __('messages.cookie.privacy_policy') }}</a>
                </p>
            </div>
            <div class="col-lg-4 col-md-5 col-12 text-md-end">
                <div class="d-flex gap-2 justify-content-md-end justify-content-start flex-wrap">
                    <form method="POST" action="{{ route('cookie.consent') }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="consent_type" value="accept">
                        <button type="submit" class="custom-btn btn" style="min-width: auto; padding: 8px 20px; font-size: 14px;">
                            {{ __('messages.cookie.accept') }}
                        </button>
                    </form>
                    <button type="button" class="custom-btn btn btn-outline-dark" style="min-width: auto; padding: 8px 20px; font-size: 14px;" onclick="showCookieSettings()">
                        {{ __('messages.cookie.customize') }}
                    </button>
                    <form method="POST" action="{{ route('cookie.consent') }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="consent_type" value="reject">
                        <button type="submit" class="custom-btn btn btn-outline-dark" style="min-width: auto; padding: 8px 20px; font-size: 14px;">
                            {{ __('messages.cookie.reject') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Cookie Settings Modal (always available) -->
<div id="cookie-settings-modal" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.cookie.settings.title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('cookie.consent') }}">
                @csrf
                <input type="hidden" name="consent_type" value="customize">
                <div class="modal-body">
                    <p>{{ __('messages.cookie.settings.description') }}</p>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="essential" id="cookie-essential" value="1" checked disabled>
                            <label class="form-check-label" for="cookie-essential">
                                <strong>{{ __('messages.cookie.settings.essential.title') }}</strong>
                                <small class="d-block text-muted">{{ __('messages.cookie.settings.essential.description') }}</small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="analytics" id="cookie-analytics-opt" value="1">
                            <label class="form-check-label" for="cookie-analytics-opt">
                                <strong>{{ __('messages.cookie.settings.analytics.title') }}</strong>
                                <small class="d-block text-muted">{{ __('messages.cookie.settings.analytics.description') }}</small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="marketing" id="cookie-marketing" value="1">
                            <label class="form-check-label" for="cookie-marketing">
                                <strong>{{ __('messages.cookie.settings.marketing.title') }}</strong>
                                <small class="d-block text-muted">{{ __('messages.cookie.settings.marketing.description') }}</small>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cookie.settings.cancel') }}</button>
                    <button type="submit" class="custom-btn btn">{{ __('messages.cookie.settings.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showCookieSettings() {
    var modalElement = document.getElementById('cookie-settings-modal');
    if (modalElement) {
        var modal = new bootstrap.Modal(modalElement);
        modal.show();
    }
}
</script>
