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
            <div class="col-lg-10 col-12 mx-auto">
                <div class="custom-block bg-white shadow-lg p-5">
                    <h3 class="mb-4">{{ __('messages.spontaneous.send_title') }}</h3>
                    <p class="mb-4">{{ __('messages.spontaneous.description') }}</p>

                    @include('components.success-alert')

                    <form method="POST" action="{{ route('jobs.submit-spontaneous') }}" enctype="multipart/form-data" id="spontaneous-form">
                        @csrf
                        
                        @guest
                        <!-- Registration Section (Only for guests) -->
                        <h5 class="mb-3 mt-4">{{ __('messages.auth.register') }}</h5>
                        <p class="text-muted mb-4">{{ __('messages.spontaneous.register_description') }}</p>
                        
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('messages.auth.name') }} ({{ __('messages.apply.required') }})</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('messages.auth.email') }} ({{ __('messages.apply.required') }})</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('messages.auth.password') }} ({{ __('messages.apply.required') }})</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('messages.auth.password_confirmation') }} ({{ __('messages.apply.required') }})</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>
                        @endguest

                        <!-- Personal Information Section -->
                        <h5 class="mb-3 mt-4">{{ __('messages.apply.personal_info') }}</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('messages.apply.first_name') }} ({{ __('messages.apply.required') }})</label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', auth()->user()->name ?? '') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('messages.apply.surname') }} ({{ __('messages.apply.required') }})</label>
                                <input type="text" name="surname" class="form-control @error('surname') is-invalid @enderror" value="{{ old('surname') }}" required>
                                @error('surname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ __('messages.apply.date_of_birth') }} ({{ __('messages.apply.required') }})</label>
                                <select name="birth_month" class="form-control @error('birth_month') is-invalid @enderror" required>
                                    <option value="">{{ __('messages.apply.month') }}</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('birth_month') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('birth_month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ __('messages.apply.day') }}</label>
                                <input type="number" name="birth_day" class="form-control @error('birth_day') is-invalid @enderror" value="{{ old('birth_day') }}" min="1" max="31" required>
                                @error('birth_day')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ __('messages.apply.year') }}</label>
                                <input type="number" name="birth_year" class="form-control @error('birth_year') is-invalid @enderror" value="{{ old('birth_year') }}" min="1900" max="{{ date('Y') }}" required>
                                @error('birth_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Age Verification -->
                        <div class="mb-4">
                            <label class="form-label">{{ __('messages.apply.is_18_or_older') }} ({{ __('messages.apply.required') }})</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input @error('is_18_or_older') is-invalid @enderror" type="radio" name="is_18_or_older" id="is_18_yes" value="1" {{ old('is_18_or_older') == '1' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="is_18_yes">
                                    {{ __('messages.apply.is_18_or_older.yes') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('is_18_or_older') is-invalid @enderror" type="radio" name="is_18_or_older" id="is_18_no" value="0" {{ old('is_18_or_older') == '0' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="is_18_no">
                                    {{ __('messages.apply.is_18_or_older.no') }}
                                </label>
                            </div>
                            @error('is_18_or_older')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.apply.email') }} ({{ __('messages.apply.required') }})</label>
                            <input type="email" name="application_email" class="form-control @error('application_email') is-invalid @enderror" value="{{ old('application_email', auth()->user()->email ?? '') }}" required>
                            @error('application_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.apply.phone') }} ({{ __('messages.apply.required') }})</label>
                            <input type="tel" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="{{ __('messages.apply.phone_placeholder') }}" required>
                            <input type="hidden" name="phone_country_code" id="phone_country_code">
                            <small class="form-text text-muted d-block mt-1">{{ __('messages.apply.phone_help') }}</small>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">{{ __('messages.apply.address') }} ({{ __('messages.apply.required') }})</label>
                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload Section -->
                        <h5 class="mb-3 mt-4">{{ __('messages.apply.uploads') }}</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.apply.upload_cv') }} ({{ __('messages.apply.required') }})</label>
                            <input type="file" name="cv" class="form-control @error('cv') is-invalid @enderror" accept=".pdf,.doc,.docx" required>
                            <small class="form-text text-muted">{{ __('messages.apply.file_size_notice') }}</small>
                            @error('cv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.apply.upload_personal_image') }}</label>
                            <input type="file" name="personal_image" class="form-control @error('personal_image') is-invalid @enderror" accept="image/*">
                            <small class="form-text text-muted">{{ __('messages.apply.personal_image_notice') }}</small>
                            @error('personal_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Driving License Privileges -->
                        <h5 class="mb-3 mt-4" style="text-transform: capitalize;">{{ __('messages.apply.driving_license_privileges') }}</h5>
                        <div class="mb-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="driving_license_b" id="driving_license_b" value="1" {{ old('driving_license_b') ? 'checked' : '' }}>
                                <label class="form-check-label" for="driving_license_b" style="text-transform: capitalize;">
                                    {{ __('messages.apply.driving_license_b') }}
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="driving_license_own_car" id="driving_license_own_car" value="1" {{ old('driving_license_own_car') ? 'checked' : '' }}>
                                <label class="form-check-label" for="driving_license_own_car" style="text-transform: capitalize;">
                                    {{ __('messages.apply.driving_license_own_car') }}
                                </label>
                            </div>
                        </div>

                        <!-- Availability -->
                        <h5 class="mb-3 mt-4" style="text-transform: capitalize;">{{ __('messages.apply.availability') }}</h5>
                        <div class="mb-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input @error('start_date_option') is-invalid @enderror" type="radio" name="start_date_option" id="availability_immediately" value="immediately" {{ old('start_date_option') == 'immediately' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="availability_immediately" style="text-transform: capitalize;">
                                    {{ __('messages.apply.start_date_option.immediately') }}
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input @error('start_date_option') is-invalid @enderror" type="radio" name="start_date_option" id="availability_one_month" value="one_month" {{ old('start_date_option') == 'one_month' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="availability_one_month" style="text-transform: capitalize;">
                                    {{ __('messages.apply.start_date_option.one_month') }}
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input @error('start_date_option') is-invalid @enderror" type="radio" name="start_date_option" id="availability_two_three_months" value="two_three_months" {{ old('start_date_option') == 'two_three_months' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="availability_two_three_months" style="text-transform: capitalize;">
                                    {{ __('messages.apply.start_date_option.two_three_months') }}
                                </label>
                            </div>
                            @error('start_date_option')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Cover Letter -->
                        <h5 class="mb-3 mt-4">{{ __('messages.spontaneous.cover_letter') }} ({{ __('messages.apply.required') }})</h5>
                        <div class="mb-4">
                            <textarea name="cover_letter" rows="8" class="form-control @error('cover_letter') is-invalid @enderror" placeholder="{{ __('messages.spontaneous.cover_letter_placeholder') }}" required>{{ old('cover_letter') }}</textarea>
                            @error('cover_letter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Additional Information (Optional) -->
                        <h5 class="mb-3 mt-4">{{ __('messages.apply.additional_information') }}</h5>
                        <div class="mb-4">
                            <textarea name="additional_information" rows="5" class="form-control @error('additional_information') is-invalid @enderror" placeholder="{{ __('messages.apply.additional_information_placeholder') }}">{{ old('additional_information') }}</textarea>
                            @error('additional_information')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Consent -->
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

                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="custom-btn btn w-100" style="background: #000; color: #fff; border: none;">{{ __('messages.spontaneous.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const phoneInput = document.querySelector('#phone');
        if (phoneInput && window.intlTelInput) {
            const iti = window.intlTelInput(phoneInput, {
                initialCountry: 'se',
                preferredCountries: ['se', 'no', 'dk', 'fi'],
                utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/js/utils.js',
                separateDialCode: true,
                nationalMode: false
            });

            // Update hidden field with full phone number on form submit
            const form = phoneInput.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const phoneNumber = iti.getNumber();
                    document.getElementById('phone_country_code').value = iti.getSelectedCountryData().dialCode;
                    phoneInput.value = phoneNumber;
                });
            }
        }
    });
</script>
@endpush

@endsection
