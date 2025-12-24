@extends('layouts.main')

@section('title', __('messages.contact.title') . ' - VUS')

@section('content')
<section class="section-bg-image">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center mb-5">
                <h1>{{ __('messages.contact.title') }}</h1>
                <p>{{ __('messages.contact.subtitle') }}</p>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-12 mb-4">
                <div class="custom-block bg-white shadow-lg p-5 h-100">
                    <h3 class="mb-4">{{ __('messages.contact.send_message') }}</h3>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.send') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.contact.form.type') }} *</label>
                            <div class="form-check">
                                <input class="form-check-input @error('type') is-invalid @enderror" type="radio" name="type" id="type_private" value="private" {{ old('type') == 'private' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="type_private">
                                    {{ __('messages.contact.form.type_private') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('type') is-invalid @enderror" type="radio" name="type" id="type_company" value="company" {{ old('type') == 'company' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="type_company">
                                    {{ __('messages.contact.form.type_company') }}
                                </label>
                            </div>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.contact.form.name') }} *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.contact.email') }} *</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.contact.form.phone') }}</label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.contact.form.subject') }} *</label>
                            <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.contact.form.message') }} *</label>
                            <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('privacy_consent') is-invalid @enderror" type="checkbox" name="privacy_consent" id="privacy_consent" value="1" {{ old('privacy_consent') ? 'checked' : '' }} required>
                                <label class="form-check-label" for="privacy_consent">
                                    {{ __('messages.contact.form.privacy_consent_text') }} <a href="{{ route('privacy') }}" target="_blank" style="text-decoration: underline;">{{ __('messages.cookie.privacy_policy') }}</a> *
                                </label>
                            </div>
                            @error('privacy_consent')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <p class="text-muted small mb-3">{{ __('messages.contact.form.response_time') }}</p>

                        <button type="submit" class="custom-btn btn w-100">{{ __('messages.contact.send_message') }}</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-6 col-12">
                <div class="custom-block bg-white shadow-lg p-5 mb-4">
                    <h3 class="mb-4">{{ __('messages.contact.contact_info') }}</h3>

                    @if($contactSetting)
                        <div class="mb-4">
                            <h5><i class="bi-envelope me-2"></i> {{ __('messages.contact.email') }}</h5>
                            <p><a href="mailto:{{ $contactSetting->email }}">{{ $contactSetting->email }}</a></p>
                        </div>

                        @if($contactSetting->phone)
                            <div class="mb-4">
                                <h5><i class="bi-telephone me-2"></i> {{ __('messages.contact.form.phone') }}</h5>
                                <p><a href="tel:{{ $contactSetting->phone }}">{{ $contactSetting->phone }}</a></p>
                            </div>
                        @endif

                        <div class="mb-4">
                            <h5><i class="bi-geo-alt me-2"></i> {{ __('messages.contact.address') }}</h5>
                            <p>{{ app()->getLocale() === 'en' ? ($contactSetting->address_en ?? __('messages.common.country.sweden')) : ($contactSetting->address_sv ?? __('messages.common.country.sweden')) }}</p>
                        </div>

                        <div class="mb-4">
                            <h5><i class="bi-clock me-2"></i> {{ __('messages.contact.hours') }}</h5>
                            <p>
                                @if(app()->getLocale() === 'en')
                                    {{ $contactSetting->hours_weekdays_en ?? __('messages.contact.hours_weekdays') }}<br>
                                    {{ $contactSetting->hours_weekend_en ?? __('messages.contact.hours_weekend') }}
                                @else
                                    {{ $contactSetting->hours_weekdays_sv ?? __('messages.contact.hours_weekdays') }}<br>
                                    {{ $contactSetting->hours_weekend_sv ?? __('messages.contact.hours_weekend') }}
                                @endif
                            </p>
                        </div>
                    @else
                        {{-- Fallback to default values if no settings exist --}}
                        <div class="mb-4">
                            <h5><i class="bi-envelope me-2"></i> {{ __('messages.contact.email') }}</h5>
                            <p><a href="mailto:abdulrazek.mahmoud@vus-bemanning.se">abdulrazek.mahmoud@vus-bemanning.se</a></p>
                        </div>

                        <div class="mb-4">
                            <h5><i class="bi-geo-alt me-2"></i> {{ __('messages.contact.address') }}</h5>
                            <p>{{ __('messages.common.country.sweden') }}</p>
                        </div>

                        <div class="mb-4">
                            <h5><i class="bi-clock me-2"></i> {{ __('messages.contact.hours') }}</h5>
                            <p>{{ __('messages.contact.hours_weekdays') }}<br>
                            {{ __('messages.contact.hours_weekend') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
