@extends('layouts.main')

@section('title', __('messages.apply.title') . ' - ' . $job->title)

@section('content')
<section class="section-padding" style="padding-top: 30px; padding-bottom: 30px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-12 mx-auto">
                <div class="custom-block bg-white shadow-lg p-3">
                    <h4 class="mb-2" style="text-transform: none; font-size: 1.25rem;">{{ __('messages.apply.apply_to') }}: {{ ucfirst(strtolower($job->title)) }}</h4>

                    @include('components.success-alert')

                    <form method="POST" action="{{ route('jobs.submit-application', $job) }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Personal Information Section -->
                        <h6 class="mb-2 mt-2" style="font-size: 0.95rem; font-weight: 600;">{{ __('messages.apply.personal_info') }}</h6>
                        
                        <div class="row mb-2">
                            <div class="col-md-6 mb-2">
                                <label class="form-label small">{{ __('messages.apply.first_name') }} ({{ __('messages.apply.required') }})</label>
                                <input type="text" name="first_name" class="form-control form-control-sm @error('first_name') is-invalid @enderror" value="{{ old('first_name', auth()->user()->name ?? '') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label small">{{ __('messages.apply.surname') }} ({{ __('messages.apply.required') }})</label>
                                <input type="text" name="surname" class="form-control form-control-sm @error('surname') is-invalid @enderror" value="{{ old('surname') }}" required>
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
                                        <option value="{{ $i }}" {{ old('birth_month') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('birth_month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label small">{{ __('messages.apply.day') }}</label>
                                <input type="number" name="birth_day" class="form-control form-control-sm @error('birth_day') is-invalid @enderror" value="{{ old('birth_day') }}" min="1" max="31" required>
                                @error('birth_day')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label small">{{ __('messages.apply.year') }}</label>
                                <input type="number" name="birth_year" class="form-control form-control-sm @error('birth_year') is-invalid @enderror" value="{{ old('birth_year') }}" min="1900" max="{{ date('Y') }}" required>
                                @error('birth_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Age Verification -->
                        <div class="mb-2">
                            <label class="form-label small">{{ __('messages.apply.is_18_or_older') }} ({{ __('messages.apply.required') }})</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('is_18_or_older') is-invalid @enderror" type="radio" name="is_18_or_older" id="is_18_yes" value="1" {{ old('is_18_or_older') == '1' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="is_18_yes">{{ __('messages.apply.is_18_or_older.yes') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('is_18_or_older') is-invalid @enderror" type="radio" name="is_18_or_older" id="is_18_no" value="0" {{ old('is_18_or_older') == '0' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="is_18_no">{{ __('messages.apply.is_18_or_older.no') }}</label>
                            </div>
                            @error('is_18_or_older')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6 mb-2">
                                <label class="form-label small">{{ __('messages.apply.email') }} ({{ __('messages.apply.required') }})</label>
                                <input type="email" name="email" class="form-control form-control-sm @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label small">{{ __('messages.apply.phone') }} ({{ __('messages.apply.required') }})</label>
                                <input type="tel" id="phone" name="phone" class="form-control form-control-sm @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="{{ __('messages.apply.phone_placeholder') }}" required>
                                <input type="hidden" name="phone_country_code" id="phone_country_code">
                                <small class="form-text text-muted">{{ __('messages.apply.phone_help') }}</small>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small">{{ __('messages.apply.address') }} ({{ __('messages.apply.required') }})</label>
                            <input type="text" name="address" class="form-control form-control-sm @error('address') is-invalid @enderror" value="{{ old('address') }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload Section -->
                        <h6 class="mb-2 mt-2" style="font-size: 0.95rem; font-weight: 600;">{{ __('messages.apply.uploads') }}</h6>

                        <div class="mb-2">
                            <label class="form-label small">{{ __('messages.apply.upload_documents') }} ({{ __('messages.apply.required') }})</label>
                            <input type="file" name="documents[]" id="documents" class="form-control form-control-sm @error('documents') is-invalid @enderror @error('documents.*') is-invalid @enderror" accept=".pdf,.doc,.docx,image/*" multiple required>
                            <small class="form-text text-muted">{{ __('messages.apply.documents_help') }}</small>
                            <div id="file-list" class="mt-1"></div>
                            @error('documents')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('documents.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label class="form-label small">{{ __('messages.apply.upload_personal_image') }}</label>
                            <input type="file" name="personal_image" class="form-control form-control-sm @error('personal_image') is-invalid @enderror" accept="image/*">
                            <small class="form-text text-muted">{{ __('messages.apply.personal_image_notice') }}</small>
                            @error('personal_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Driving License & Availability -->
                        <div class="row mb-2 mt-3">
                            <div class="col-md-6">
                                <h6 class="mb-2" style="font-size: 0.95rem;">{{ __('messages.apply.driving_license_privileges') }}</h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="driving_license_b" id="driving_license_b" value="1" {{ old('driving_license_b') ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="driving_license_b">{{ __('messages.apply.driving_license_b') }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="driving_license_own_car" id="driving_license_own_car" value="1" {{ old('driving_license_own_car') ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="driving_license_own_car">{{ __('messages.apply.driving_license_own_car') }}</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-2" style="font-size: 0.95rem;">{{ __('messages.apply.availability') }}</h6>
                                <div class="form-check">
                                    <input class="form-check-input @error('start_date_option') is-invalid @enderror" type="radio" name="start_date_option" id="availability_immediately" value="immediately" {{ old('start_date_option') == 'immediately' ? 'checked' : '' }} required>
                                    <label class="form-check-label small" for="availability_immediately">{{ __('messages.apply.start_date_option.immediately') }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input @error('start_date_option') is-invalid @enderror" type="radio" name="start_date_option" id="availability_one_month" value="one_month" {{ old('start_date_option') == 'one_month' ? 'checked' : '' }} required>
                                    <label class="form-check-label small" for="availability_one_month">{{ __('messages.apply.start_date_option.one_month') }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input @error('start_date_option') is-invalid @enderror" type="radio" name="start_date_option" id="availability_two_three_months" value="two_three_months" {{ old('start_date_option') == 'two_three_months' ? 'checked' : '' }} required>
                                    <label class="form-check-label small" for="availability_two_three_months">{{ __('messages.apply.start_date_option.two_three_months') }}</label>
                                </div>
                                @error('start_date_option')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Information (Optional) -->
                        <h6 class="mb-2 mt-2" style="font-size: 0.95rem; font-weight: 600;">{{ __('messages.apply.additional_information') }}</h6>
                        <div class="mb-2">
                            <textarea name="additional_information" rows="3" class="form-control form-control-sm @error('additional_information') is-invalid @enderror" placeholder="{{ __('messages.apply.additional_information_placeholder') }}">{{ old('additional_information') }}</textarea>
                            @error('additional_information')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Consent -->
                        <div class="mb-3">
                            <label class="form-label small mb-2">{{ __('messages.apply.consent.required') }} *</label>
                            <div class="form-check">
                                <input class="form-check-input @error('consent_type') is-invalid @enderror" type="radio" name="consent_type" id="consent_full" value="full" {{ old('consent_type') == 'full' ? 'checked' : '' }} required>
                                <label class="form-check-label small" for="consent_full">{{ __('messages.apply.consent.full') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('consent_type') is-invalid @enderror" type="radio" name="consent_type" id="consent_limited" value="limited" {{ old('consent_type') == 'limited' ? 'checked' : '' }} required>
                                <label class="form-check-label small" for="consent_limited">{{ __('messages.apply.consent.limited') }}</label>
                            </div>
                            @error('consent_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-3 mt-3">
                            <button type="submit" class="custom-btn btn w-100" style="background: #000; color: #fff; border: none;">{{ __('messages.apply.submit') }}</button>
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

        // File upload validation - max 3 files, max 3MB each
        const documentsInput = document.getElementById('documents');
        const fileList = document.getElementById('file-list');
        const maxFiles = 3;
        const maxFileSize = 3 * 1024 * 1024; // 3MB in bytes

        if (documentsInput && fileList) {
            documentsInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                fileList.innerHTML = '';

                // Validate number of files
                if (files.length > maxFiles) {
                    fileList.innerHTML = '<div class="alert alert-danger">Maximum ' + maxFiles + ' files allowed. Please select fewer files.</div>';
                    e.target.value = '';
                    return;
                }

                // Validate each file size and display
                let hasError = false;
                files.forEach((file, index) => {
                    const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    
                    if (file.size > maxFileSize) {
                        hasError = true;
                        fileList.innerHTML += '<div class="alert alert-danger">File "' + file.name + '" is too large (' + fileSizeMB + ' MB). Maximum size is 3 MB.</div>';
                    } else {
                        fileList.innerHTML += '<div class="text-success small"><i class="bi bi-check-circle"></i> ' + file.name + ' (' + fileSizeMB + ' MB)</div>';
                    }
                });

                if (hasError) {
                    e.target.value = '';
                    fileList.innerHTML += '<div class="alert alert-warning mt-2">Please select files that are 3 MB or smaller.</div>';
                } else if (files.length > 0) {
                    fileList.innerHTML += '<div class="text-muted small mt-2">Selected ' + files.length + ' of maximum ' + maxFiles + ' files.</div>';
                }
            });

            // Validate on form submit
            const form = documentsInput.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const files = Array.from(documentsInput.files);
                    
                    if (files.length === 0) {
                        e.preventDefault();
                        fileList.innerHTML = '<div class="alert alert-danger">Please select at least one file.</div>';
                        return false;
                    }

                    if (files.length > maxFiles) {
                        e.preventDefault();
                        fileList.innerHTML = '<div class="alert alert-danger">Maximum ' + maxFiles + ' files allowed.</div>';
                        return false;
                    }

                    for (let file of files) {
                        if (file.size > maxFileSize) {
                            e.preventDefault();
                            fileList.innerHTML = '<div class="alert alert-danger">File "' + file.name + '" exceeds the maximum size of 3 MB.</div>';
                            return false;
                        }
                    }
                });
            }
        }
    });
</script>
@endpush

@endsection
