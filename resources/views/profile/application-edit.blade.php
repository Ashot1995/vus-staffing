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
                        
                        <!-- Current Documents -->
                        @if($application->cv_path || ($application->additional_files && count($application->additional_files) > 0))
                            <div class="mb-3">
                                <label class="form-label small mb-2">{{ __('messages.profile.applications.current_documents') }}</label>
                                
                                <!-- Current CV -->
                                @if($application->cv_path)
                                    <div class="mb-2 p-2 bg-light rounded border">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <p class="mb-1 small"><strong>{{ __('messages.profile.applications.cv_document') }}:</strong> {{ basename($application->cv_path) }}</p>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('application.cv.view', $application->id) }}" target="_blank" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye me-1"></i>{{ __('messages.profile.applications.view') }}
                                                </a>
                                                <a href="{{ route('application.cv.download', $application->id) }}" class="btn btn-sm btn-secondary">
                                                    <i class="bi bi-download me-1"></i>{{ __('messages.profile.applications.download') }}
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger remove-existing-file" data-file-type="cv" data-application-id="{{ $application->id }}">
                                                    <i class="bi bi-trash me-1"></i>{{ __('messages.profile.applications.delete') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Additional Files -->
                                @if($application->additional_files && is_array($application->additional_files) && count($application->additional_files) > 0)
                                    @foreach($application->additional_files as $index => $filePath)
                                        <div class="mb-2 p-2 bg-light rounded border existing-additional-file" data-file-index="{{ $index }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="flex-grow-1">
                                                    <p class="mb-1 small"><strong>{{ __('messages.profile.applications.additional_document') }} {{ $index + 1 }}:</strong> {{ basename($filePath) }}</p>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('application.file.view', ['applicationId' => $application->id, 'index' => $index]) }}" target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye me-1"></i>{{ __('messages.profile.applications.view') }}
                                                    </a>
                                                    <a href="{{ route('application.file.download', ['applicationId' => $application->id, 'index' => $index]) }}" class="btn btn-sm btn-secondary">
                                                        <i class="bi bi-download me-1"></i>{{ __('messages.profile.applications.download') }}
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger remove-existing-file" data-file-type="additional" data-file-index="{{ $index }}" data-application-id="{{ $application->id }}">
                                                        <i class="bi bi-trash me-1"></i>{{ __('messages.profile.applications.delete') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endif

                        <!-- Documents Upload -->
                        <div class="mb-2">
                            <label class="form-label small">{{ __('messages.profile.applications.upload_documents') }} ({{ __('messages.profile.applications.optional') }})</label>
                            <input type="file" name="documents[]" id="documents" class="form-control form-control-sm @error('documents') is-invalid @enderror @error('documents.*') is-invalid @enderror" accept=".pdf,.doc,.docx,image/*" multiple>
                            <small class="form-text text-muted">{{ __('messages.profile.applications.upload_documents_help') }}</small>
                            <div id="file-list" class="mt-2"></div>
                            @error('documents')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('documents.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
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

        // File upload validation - max 3 files total, max 3MB each
        const documentsInput = document.getElementById('documents');
        const fileList = document.getElementById('file-list');
        const maxFiles = 3;
        const maxFileSize = 3 * 1024 * 1024; // 3MB in bytes
        
        // Count existing files
        function countExistingFiles() {
            let count = 0;
            if (document.querySelector('.existing-additional-file')) {
                count += document.querySelectorAll('.existing-additional-file').length;
            }
            if (document.querySelector('[data-file-type="cv"]')) {
                count += 1;
            }
            return count;
        }

        if (documentsInput && fileList) {
            documentsInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                const existingCount = countExistingFiles();
                const totalFiles = existingCount + files.length;
                fileList.innerHTML = '';

                // Check total files count (existing + new)
                if (totalFiles > maxFiles) {
                    fileList.innerHTML = '<div class="alert alert-danger">Maximum ' + maxFiles + ' files allowed total. You have ' + existingCount + ' existing file(s). Please select no more than ' + (maxFiles - existingCount) + ' file(s).</div>';
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
                        fileList.innerHTML += '<div class="text-success small mb-1"><i class="bi bi-check-circle"></i> ' + file.name + ' (' + fileSizeMB + ' MB)</div>';
                    }
                });

                if (hasError) {
                    e.target.value = '';
                    fileList.innerHTML += '<div class="alert alert-warning mt-2">Please select files that are 3 MB or smaller.</div>';
                } else if (files.length > 0) {
                    fileList.innerHTML += '<div class="text-muted small mt-2">Selected ' + files.length + ' file(s). Total files will be ' + totalFiles + ' of maximum ' + maxFiles + ' files.</div>';
                }
            });

            // Validate on form submit
            const form = documentsInput.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const files = Array.from(documentsInput.files);
                    const existingCount = countExistingFiles();
                    const totalFiles = existingCount + files.length;
                    
                    if (totalFiles > maxFiles) {
                        e.preventDefault();
                        fileList.innerHTML = '<div class="alert alert-danger">Maximum ' + maxFiles + ' files allowed total. You have ' + existingCount + ' existing file(s).</div>';
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

        // Handle remove existing file buttons
        document.querySelectorAll('.remove-existing-file').forEach(button => {
            button.addEventListener('click', function() {
                const fileType = this.getAttribute('data-file-type');
                const applicationId = this.getAttribute('data-application-id');
                const fileIndex = this.getAttribute('data-file-index');
                
                if (!confirm('{{ __("messages.profile.applications.confirm_delete_file") }}')) {
                    return;
                }

                // Create a hidden input to mark file for deletion
                const form = this.closest('form');
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = fileType === 'cv' ? 'delete_cv' : 'delete_additional_files[]';
                hiddenInput.value = fileType === 'cv' ? '1' : fileIndex;
                form.appendChild(hiddenInput);

                // Remove the visual element
                if (fileType === 'cv') {
                    this.closest('.mb-2').remove();
                } else {
                    this.closest('.existing-additional-file').remove();
                }
            });
        });
    });
</script>
@endpush

@endsection
