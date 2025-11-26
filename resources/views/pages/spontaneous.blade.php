@extends('layouts.main')

@section('title', __('messages.spontaneous.title') . ' - VUS')

@section('content')
<section class="section-bg-image">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center mb-5">
                <h1>{{ __('messages.spontaneous.title') }}</h1>
                <p>{{ __('messages.spontaneous.subtitle') }}</p>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-12 mx-auto">
                <div class="custom-block bg-white shadow-lg p-5">
                    <h3 class="mb-4">{{ __('messages.spontaneous.send_title') }}</h3>
                    <p class="mb-4">{{ __('messages.spontaneous.description') }}</p>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @auth
                        <form method="POST" action="{{ route('jobs.submit-spontaneous') }}" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="form-label">{{ __('messages.spontaneous.upload_cv') }} *</label>
                                <input type="file" name="cv" class="form-control @error('cv') is-invalid @enderror" accept=".pdf,.doc,.docx" required>
                                @error('cv')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">{{ __('messages.spontaneous.cover_letter') }} *</label>
                                <textarea name="cover_letter" rows="10" class="form-control @error('cover_letter') is-invalid @enderror" placeholder="{{ __('messages.spontaneous.cover_letter_placeholder') }}" required>{{ old('cover_letter') }}</textarea>
                                @error('cover_letter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input @error('gdpr_consent') is-invalid @enderror" type="checkbox" name="gdpr_consent" id="gdpr_consent" value="1" required>
                                    <label class="form-check-label" for="gdpr_consent">
                                        {{ __('messages.apply.gdpr_consent') }} <a href="{{ route('privacy') }}" target="_blank" style="text-decoration: underline;">{{ __('messages.cookie.privacy_policy') }}</a>
                                    </label>
                                    @error('gdpr_consent')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="custom-btn btn mt-3">{{ __('messages.spontaneous.submit') }}</button>
                        </form>
                    @else
                        <div class="alert alert-info">
                            <p>{{ __('messages.spontaneous.login_required') }}</p>
                            <div class="d-flex gap-3 mt-3">
                                <a href="{{ route('login') }}" class="btn custom-btn">{{ __('messages.nav.login') }}</a>
                                <a href="{{ route('register') }}" class="btn btn-outline-secondary">{{ __('messages.spontaneous.register') }}</a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
