@if(!session('cookie_consent'))
<div id="cookie-banner" class="cookie-banner" style="position: fixed; bottom: 0; left: 0; right: 0; background: #ffffff; border-top: 2px solid #000000; padding: 20px; z-index: 9999; box-shadow: 0 -4px 10px rgba(0,0,0,0.1);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 col-md-7 col-12 mb-3 mb-md-0">
                <p class="mb-0" style="color: #000000; font-size: 14px;">
                    {{ __('messages.cookie.text') }}
                    <a href="{{ route('privacy') }}" target="_blank" style="color: #000000; text-decoration: underline;">{{ __('messages.cookie.privacy_policy') }}</a>
                </p>
            </div>
            <div class="col-lg-4 col-md-5 col-12 text-md-end">
                <form method="POST" action="{{ route('cookie.consent') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="custom-btn btn" style="min-width: auto; padding: 8px 20px; font-size: 14px;">
                        {{ __('messages.cookie.accept') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif


