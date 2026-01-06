@extends('layouts.main')

@section('title', __('messages.profile.applications.edit_application') . ' - VUS')

@section('content')
<section class="section-padding" style="padding-top: 30px; padding-bottom: 30px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-12 mx-auto">
                <div class="custom-block bg-white shadow-lg p-3">
                    @if($application->job)
                        <h4 class="mb-2" style="text-transform: none; font-size: 1.25rem;">{{ __('messages.apply.apply_to') }}: {{ ucfirst(strtolower($application->job->title)) }}</h4>
                    @else
                        <h4 class="mb-2" style="font-size: 1.25rem;">{{ __('messages.profile.applications.spontaneous') }}</h4>
                    @endif

                    @if (session('status') === 'application-updated')
                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                            {{ __('messages.profile.applications.updated') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.applications.update', $application->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <!-- Personal Information Section -->
                        <h6 class="mb-2 mt-2" style="font-size: 0.95rem; font-weight: 600;">{{ __('messages.apply.personal_info') }}</h6>
                        
                        <div class="row mb-2">
                            <div class="col-md-6 mb-2">
                                <label class="form-label small">{{ __('messages.apply.first_name') }} ({{ __('messages.apply.required') }})</label>
                                <input type="text" name="first_name" class="form-control form-control-sm @error('first_name') is-invalid @enderror" value="{{ old('first_name', $application->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label small">{{ __('messages.apply.surname') }} ({{ __('messages.apply.required') }})</label>
                                <input type="text" name="surname" class="form-control form-control-sm @error('surname') is-invalid @enderror" value="{{ old('surname', $application->surname) }}" required>
                                @error('surname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 mb-2">
                                <label class="form-label small">{{ __('messages.apply.date_of_birth') }} ({{ __('messages.apply.required') }})</label>
                                <select name="birth_month" class="form-control form-control-sm @error('birth_month') is-invalid @enderror" required>
                                    <option value="">{{ __('messages.apply.month') }}</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('birth_month', $application->date_of_birth ? $application->date_of_birth->format('n') : '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('birth_month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label small">{{ __('messages.apply.day') }}</label>
                                <input type="number" name="birth_day" class="form-control form-control-sm @error('birth_day') is-invalid @enderror" value="{{ old('birth_day', $application->date_of_birth ? $application->date_of_birth->format('j') : '') }}" min="1" max="31" required>
                                @error('birth_day')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label small">{{ __('messages.apply.year') }}</label>
                                <input type="number" name="birth_year" class="form-control form-control-sm @error('birth_year') is-invalid @enderror" value="{{ old('birth_year', $application->date_of_birth ? $application->date_of_birth->format('Y') : '') }}" min="1900" max="{{ date('Y') }}" required>
                                @error('birth_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small">{{ __('messages.apply.email') }} ({{ __('messages.apply.required') }})</label>
                            <input type="email" name="email" class="form-control form-control-sm @error('email') is-invalid @enderror" value="{{ old('email', $application->email ?? auth()->user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label class="form-label small">{{ __('messages.apply.phone') }} ({{ __('messages.apply.required') }})</label>
                            <input type="tel" id="phone" name="phone" class="form-control form-control-sm @error('phone') is-invalid @enderror" value="{{ old('phone', $application->phone ? preg_replace('/^\+\d+\s/', '', $application->phone) : '') }}" placeholder="{{ __('messages.apply.phone_placeholder') }}" required>
                            <input type="hidden" name="phone_country_code" id="phone_country_code">
                            <small class="form-text text-muted d-block mt-1">{{ __('messages.apply.phone_help') }}</small>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label class="form-label small">{{ __('messages.apply.address') }} ({{ __('messages.apply.required') }})</label>
                            <input type="text" name="address" class="form-control form-control-sm @error('address') is-invalid @enderror" value="{{ old('address', $application->address) }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload Section -->
                        <h6 class="mb-2 mt-2" style="font-size: 0.95rem; font-weight: 600;">{{ __('messages.apply.uploads') }}</h6>
                        
                        <!-- Current CV -->
                        @if($application->cv_path)
                            <div class="mb-2 p-3 bg-light rounded">
                                <label class="form-label small mb-2">{{ __('messages.profile.applications.current_cv') }}</label>
                                <div class="d-flex gap-2 mb-2">
                                    <a href="{{ route('application.cv.view', $application->id) }}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye me-1"></i>{{ __('messages.profile.applications.view_cv') }}
                                    </a>
                                    <a href="{{ route('application.cv.download', $application->id) }}" class="btn btn-sm btn-secondary">
                                        <i class="bi bi-download me-1"></i>{{ __('messages.profile.applications.download_cv') }}
                                    </a>
                                </div>
                                <p class="text-muted small mb-0">{{ basename($application->cv_path) }}</p>
                            </div>
                        @endif

                        <!-- CV Upload -->
                        <div class="mb-2">
                            <label class="form-label small">{{ __('messages.profile.applications.upload_new_cv') }} ({{ __('messages.profile.applications.optional') }})</label>
                            <input type="file" name="cv" class="form-control form-control-sm @error('cv') is-invalid @enderror" accept=".pdf,.doc,.docx">
                            <small class="form-text text-muted">{{ __('messages.profile.cv_formats') }}</small>
                            @error('cv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Personal Image -->
                        <div class="mb-2">
                            <label class="form-label small">{{ __('messages.apply.upload_personal_image') }}</label>
                            @if($application->personal_image_path)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $application->personal_image_path) }}" alt="Personal Image" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                </div>
                            @endif
                            <input type="file" name="personal_image" class="form-control form-control-sm @error('personal_image') is-invalid @enderror" accept="image/*">
                            <small class="form-text text-muted">{{ __('messages.apply.personal_image_notice') }}</small>
                            @error('personal_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Driving License Privileges -->
                        <h6 class="mb-2 mt-2" style="font-size: 0.95rem; font-weight: 600;">{{ __('messages.apply.driving_license_privileges') }}</h6>
                        <div class="mb-2">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="driving_license_b" id="driving_license_b" value="1" {{ old('driving_license_b', $application->driving_license_b) ? 'checked' : '' }}>
                                <label class="form-check-label small" for="driving_license_b">
                                    {{ __('messages.apply.driving_license_b') }}
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="driving_license_own_car" id="driving_license_own_car" value="1" {{ old('driving_license_own_car', $application->driving_license_own_car) ? 'checked' : '' }}>
                                <label class="form-check-label small" for="driving_license_own_car">
                                    {{ __('messages.apply.driving_license_own_car') }}
                                </label>
                            </div>
                        </div>

                        <!-- Availability -->
                        <h6 class="mb-2 mt-2" style="font-size: 0.95rem; font-weight: 600;">{{ __('messages.apply.availability') }}</h6>
                        <div class="mb-2">
                            <div class="form-check mb-2">
                                <input class="form-check-input @error('start_date_option') is-invalid @enderror" type="radio" name="start_date_option" id="availability_immediately" value="immediately" {{ old('start_date_option', $application->start_date_option) == 'immediately' ? 'checked' : '' }} required>
                                <label class="form-check-label small" for="availability_immediately">
                                    {{ __('messages.apply.start_date_option.immediately') }}
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input @error('start_date_option') is-invalid @enderror" type="radio" name="start_date_option" id="availability_one_month" value="one_month" {{ old('start_date_option', $application->start_date_option) == 'one_month' ? 'checked' : '' }} required>
                                <label class="form-check-label small" for="availability_one_month">
                                    {{ __('messages.apply.start_date_option.one_month') }}
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input @error('start_date_option') is-invalid @enderror" type="radio" name="start_date_option" id="availability_two_three_months" value="two_three_months" {{ old('start_date_option', $application->start_date_option) == 'two_three_months' ? 'checked' : '' }} required>
                                <label class="form-check-label small" for="availability_two_three_months">
                                    {{ __('messages.apply.start_date_option.two_three_months') }}
                                </label>
                            </div>
                            @error('start_date_option')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Additional Information (Optional) -->
                        <h6 class="mb-2 mt-2" style="font-size: 0.95rem; font-weight: 600;">{{ __('messages.apply.additional_information') }}</h6>
                        <div class="mb-2">
                            <textarea name="other" rows="3" class="form-control form-control-sm @error('other') is-invalid @enderror" placeholder="{{ __('messages.apply.other_placeholder') }}">{{ old('other', $application->other) }}</textarea>
                            @error('other')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Consent -->
                        <div class="mb-3">
                            <label class="form-label small mb-2">{{ __('messages.apply.consent.required') }} *</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input @error('consent_type') is-invalid @enderror" type="radio" name="consent_type" id="consent_full" value="full" {{ old('consent_type', $application->consent_type) == 'full' ? 'checked' : '' }} required>
                                <label class="form-check-label small" for="consent_full">
                                    {{ __('messages.apply.consent.full') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('consent_type') is-invalid @enderror" type="radio" name="consent_type" id="consent_limited" value="limited" {{ old('consent_type', $application->consent_type) == 'limited' ? 'checked' : '' }} required>
                                <label class="form-check-label small" for="consent_limited">
                                    {{ __('messages.apply.consent.limited') }}
                                </label>
                            </div>
                            @error('consent_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-3 mt-3">
                            <button type="submit" class="custom-btn btn w-100" style="background: #000; color: #fff; border: none;">{{ __('messages.profile.applications.update_application') }}</button>
                            <a href="{{ route('profile.applications') }}" class="custom-btn btn w-100" style="background: #6c757d; color: #fff; border: none; text-decoration: none; display: flex; align-items: center; justify-content: center;">{{ __('messages.apply.cancel') }}</a>
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
            // Extract country code from existing phone if available
            const existingPhone = phoneInput.value;
            let initialCountry = 'se';
            if (existingPhone && existingPhone.startsWith('+')) {
                // Try to detect country from existing number
                initialCountry = 'se'; // Default to Sweden
            }

            const iti = window.intlTelInput(phoneInput, {
                initialCountry: initialCountry,
                preferredCountries: ['se', 'no', 'dk', 'fi'],
                utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/js/utils.js',
                separateDialCode: true,
                nationalMode: false
            });

            // Set existing phone number if available
            if (existingPhone) {
                iti.setNumber(existingPhone);
            }

            // Update hidden field with country code on form submit
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
