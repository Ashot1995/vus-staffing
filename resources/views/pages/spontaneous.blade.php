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

                    @include('components.success-alert')

                    @auth
                        <form method="POST" action="{{ route('jobs.submit-spontaneous') }}" enctype="multipart/form-data">
                            @csrf
                            
                            <h5 class="mb-3 mt-4">{{ __('messages.spontaneous.upload_files') }}</h5>
                            <p class="text-muted mb-4">{{ __('messages.spontaneous.upload_files_description') }}</p>

                            <div class="mb-4">
                                <label class="form-label">{{ __('messages.spontaneous.file') }} 1 *</label>
                                <input type="file" name="files[]" class="form-control @error('files.0') is-invalid @enderror" accept=".pdf,.doc,.docx,image/*" required>
                                <small class="form-text text-muted">{{ __('messages.apply.file_size_notice') }}</small>
                                @error('files.0')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">{{ __('messages.spontaneous.file') }} 2</label>
                                <input type="file" name="files[]" class="form-control @error('files.1') is-invalid @enderror" accept=".pdf,.doc,.docx,image/*">
                                <small class="form-text text-muted">{{ __('messages.apply.file_size_notice') }}</small>
                                @error('files.1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">{{ __('messages.spontaneous.file') }} 3</label>
                                <input type="file" name="files[]" class="form-control @error('files.2') is-invalid @enderror" accept=".pdf,.doc,.docx,image/*">
                                <small class="form-text text-muted">{{ __('messages.apply.file_size_notice') }}</small>
                                @error('files.2')
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
                                <label class="form-label">{{ __('messages.apply.start_date') }} *</label>
                                <select name="start_date_option" id="start_date_option" class="form-control @error('start_date_option') is-invalid @enderror" required>
                                    <option value="">{{ __('messages.apply.start_date_placeholder') }}</option>
                                    <option value="immediately" {{ old('start_date_option') == 'immediately' ? 'selected' : '' }}>{{ __('messages.apply.start_date_option.immediately') }}</option>
                                    <option value="one_month" {{ old('start_date_option') == 'one_month' ? 'selected' : '' }}>{{ __('messages.apply.start_date_option.one_month') }}</option>
                                    <option value="two_three_months" {{ old('start_date_option') == 'two_three_months' ? 'selected' : '' }}>{{ __('messages.apply.start_date_option.two_three_months') }}</option>
                                </select>
                                @error('start_date_option')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-5">
                                <div class="form-check">
                                    <input class="form-check-input @error('spontaneous_consent') is-invalid @enderror" type="checkbox" name="spontaneous_consent" id="spontaneous_consent" value="1" {{ old('spontaneous_consent') ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="spontaneous_consent">
                                        {{ __('messages.spontaneous.consent') }} *
                                    </label>
                                </div>
                                @error('spontaneous_consent')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
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
