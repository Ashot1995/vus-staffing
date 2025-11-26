@extends('layouts.main')

@section('title', __('messages.apply.title') . ' - ' . $job->title)

@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-12 mx-auto">
                <div class="custom-block bg-white shadow-lg p-5">
                    <h2 class="mb-4">{{ __('messages.apply.apply_to') }}: {{ $job->title }}</h2>
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('jobs.submit-application', $job) }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label">{{ __('messages.apply.upload_cv') }} *</label>
                            <input type="file" name="cv" class="form-control @error('cv') is-invalid @enderror" accept=".pdf,.doc,.docx" required>
                            @error('cv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">{{ __('messages.apply.cover_letter') }} *</label>
                            <textarea name="cover_letter" rows="10" class="form-control @error('cover_letter') is-invalid @enderror" placeholder="{{ __('messages.apply.cover_letter_placeholder') }}" required>{{ old('cover_letter') }}</textarea>
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

                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="custom-btn btn">{{ __('messages.apply.submit') }}</button>
                            <a href="{{ route('jobs.show', $job) }}" class="btn btn-outline-secondary">{{ __('messages.apply.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
