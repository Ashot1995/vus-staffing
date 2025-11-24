@extends('layouts.main')

@section('title', __('messages.contact.title') . ' - VUS Bemanning')

@section('content')
<section class="section-bg-image">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center mb-5">
                <h1 class="text-white">{{ __('messages.contact.title') }}</h1>
                <p class="text-white">{{ __('messages.contact.subtitle') }}</p>
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

                        <button type="submit" class="custom-btn btn w-100">{{ __('messages.contact.send_message') }}</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-6 col-12">
                <div class="custom-block bg-white shadow-lg p-5 mb-4">
                    <h3 class="mb-4">{{ __('messages.contact.contact_info') }}</h3>
                    
                    <div class="mb-4">
                        <h5><i class="bi-envelope me-2"></i> {{ __('messages.contact.email') }}</h5>
                        <p><a href="mailto:info@vus-bemanning.se">info@vus-bemanning.se</a></p>
                    </div>

                    <div class="mb-4">
                        <h5><i class="bi-geo-alt me-2"></i> {{ __('messages.contact.address') }}</h5>
                        <p>{{ app()->getLocale() === 'en' ? 'Sweden' : (app()->getLocale() === 'de' ? 'Schweiz' : 'Sverige') }}</p>
                    </div>

                    <div class="mb-4">
                        <h5><i class="bi-clock me-2"></i> {{ __('messages.contact.hours') }}</h5>
                        <p>{{ __('messages.contact.hours_weekdays') }}<br>
                        {{ __('messages.contact.hours_weekend') }}</p>
                    </div>
                </div>

                <div class="custom-block bg-white shadow-lg p-5">
                    <h3 class="mb-4">{{ __('messages.contact.follow_us') }}</h3>
                    <ul class="social-icon">
                        <li><a href="#" class="social-icon-link bi-linkedin"></a></li>
                        <li><a href="#" class="social-icon-link bi-facebook"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
