<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('messages.profile.applications.edit_application') }}
                </h2>
                <a href="{{ route('profile.applications') }}" class="text-sm text-gray-600 hover:text-gray-900 mt-1 inline-block">
                    ← {{ __('messages.profile.applications.back_to_list') }}
                </a>
            </div>
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('profile.edit') }}" class="whitespace-nowrap py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        {{ __('messages.profile.tab.profile') }}
                    </a>
                    <a href="{{ route('profile.applications') }}" class="whitespace-nowrap py-4 px-1 border-b-2 border-indigo-500 font-medium text-sm text-indigo-600">
                        {{ __('messages.profile.tab.applications') }}
                    </a>
                </nav>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($application->job)
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            {{ __('messages.apply.apply_to') }}: {{ $application->job->title }}
                        </h3>
                    @else
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            {{ __('messages.profile.applications.spontaneous') }}
                        </h3>
                    @endif

                    @if (session('status') === 'application-updated')
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm text-green-800">{{ __('messages.profile.applications.updated') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.applications.update', $application->id) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('patch')

                        <!-- Personal Information Section -->
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4">{{ __('messages.apply.personal_info') }}</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="first_name" :value="__('messages.apply.first_name') . ' (' . __('messages.apply.required') . ')'" />
                                    <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" value="{{ old('first_name', $application->first_name) }}" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                                </div>

                                <div>
                                    <x-input-label for="surname" :value="__('messages.apply.surname') . ' (' . __('messages.apply.required') . ')'" />
                                    <x-text-input id="surname" name="surname" type="text" class="mt-1 block w-full" value="{{ old('surname', $application->surname) }}" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('surname')" />
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-input-label :value="__('messages.apply.date_of_birth') . ' (' . __('messages.apply.required') . ')'" />
                                <div class="grid grid-cols-3 gap-4 mt-1">
                                    <div>
                                        <select name="birth_month" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                            <option value="">{{ __('messages.apply.month') }}</option>
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ old('birth_month', $application->date_of_birth ? $application->date_of_birth->format('n') : '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <x-text-input name="birth_day" type="number" class="block w-full" value="{{ old('birth_day', $application->date_of_birth ? $application->date_of_birth->format('j') : '') }}" min="1" max="31" placeholder="{{ __('messages.apply.day') }}" required />
                                    </div>
                                    <div>
                                        <x-text-input name="birth_year" type="number" class="block w-full" value="{{ old('birth_year', $application->date_of_birth ? $application->date_of_birth->format('Y') : '') }}" min="1900" max="{{ date('Y') }}" placeholder="{{ __('messages.apply.year') }}" required />
                                    </div>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="email" :value="__('messages.apply.email') . ' (' . __('messages.apply.required') . ')'" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email', $application->email ?? auth()->user()->email) }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="phone" :value="__('messages.apply.phone') . ' (' . __('messages.apply.required') . ')'" />
                                <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" value="{{ old('phone', $application->phone ? preg_replace('/^\+\d+\s/', '', $application->phone) : '') }}" placeholder="{{ __('messages.apply.phone_placeholder') }}" required />
                                <input type="hidden" name="phone_country_code" id="phone_country_code">
                                <p class="mt-1 text-xs text-gray-500">{{ __('messages.apply.phone_help') }}</p>
                                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="address" :value="__('messages.apply.address') . ' (' . __('messages.apply.required') . ')'" />
                                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" value="{{ old('address', $application->address) }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('address')" />
                            </div>
                        </div>

                        <!-- Upload Section -->
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4">{{ __('messages.apply.uploads') }}</h4>
                            
                            <!-- Current CV -->
                            @if($application->cv_path)
                                <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('messages.profile.applications.current_cv') }}
                                    </label>
                                    <a href="{{ asset('storage/' . $application->cv_path) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        {{ __('messages.profile.applications.download_cv') }}: {{ basename($application->cv_path) }}
                                    </a>
                                </div>
                            @endif

                            <!-- CV Upload -->
                            <div>
                                <x-input-label for="cv" :value="__('messages.profile.applications.upload_new_cv') . ' (' . __('messages.profile.applications.optional') . ')'" />
                                <input id="cv" name="cv" type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept=".pdf,.doc,.docx" />
                                <p class="mt-1 text-xs text-gray-500">{{ __('messages.profile.cv_formats') }}</p>
                                <x-input-error class="mt-2" :messages="$errors->get('cv')" />
                            </div>

                            <!-- Personal Image -->
                            <div class="mt-4">
                                <x-input-label for="personal_image" :value="__('messages.apply.upload_personal_image')" />
                                @if($application->personal_image_path)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $application->personal_image_path) }}" alt="Personal Image" class="h-20 w-20 object-cover rounded">
                                    </div>
                                @endif
                                <input id="personal_image" name="personal_image" type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept="image/*" />
                                <p class="mt-1 text-xs text-gray-500">{{ __('messages.apply.personal_image_notice') }}</p>
                                <x-input-error class="mt-2" :messages="$errors->get('personal_image')" />
                            </div>
                        </div>

                        <!-- Driving License Privileges -->
                        <div class="border-t border-gray-200 pt-6 mb-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4" style="text-transform: capitalize;">{{ __('messages.apply.driving_license_privileges') }}</h4>
                            <div class="flex flex-col space-y-3 mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="driving_license_b" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ old('driving_license_b', $application->driving_license_b) ? 'checked' : '' }}>
                                    <span class="ml-3 text-sm text-gray-600" style="text-transform: capitalize;">{{ __('messages.apply.driving_license_b') }}</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="driving_license_own_car" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ old('driving_license_own_car', $application->driving_license_own_car) ? 'checked' : '' }}>
                                    <span class="ml-3 text-sm text-gray-600" style="text-transform: capitalize;">{{ __('messages.apply.driving_license_own_car') }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Availability -->
                        <div class="border-t border-gray-200 pt-6 mb-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4" style="text-transform: capitalize;">{{ __('messages.apply.availability') }}</h4>
                            <div class="space-y-3 mb-4">
                                <label class="flex items-center">
                                    <input type="radio" name="start_date_option" value="immediately" class="form-radio text-indigo-600" {{ old('start_date_option', $application->start_date_option) == 'immediately' ? 'checked' : '' }} required>
                                    <span class="ml-3 text-sm text-gray-600" style="text-transform: capitalize;">{{ __('messages.apply.start_date_option.immediately') }}</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="start_date_option" value="one_month" class="form-radio text-indigo-600" {{ old('start_date_option', $application->start_date_option) == 'one_month' ? 'checked' : '' }} required>
                                    <span class="ml-3 text-sm text-gray-600" style="text-transform: capitalize;">{{ __('messages.apply.start_date_option.one_month') }}</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="start_date_option" value="two_three_months" class="form-radio text-indigo-600" {{ old('start_date_option', $application->start_date_option) == 'two_three_months' ? 'checked' : '' }} required>
                                    <span class="ml-3 text-sm text-gray-600" style="text-transform: capitalize;">{{ __('messages.apply.start_date_option.two_three_months') }}</span>
                                </label>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('start_date_option')" />
                        </div>

                        <!-- Other -->
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4">{{ __('messages.apply.other') }}</h4>
                            <div>
                                <x-input-label for="other" :value="__('messages.apply.other')" />
                                <textarea id="other" name="other" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="{{ __('messages.apply.other_placeholder') }}">{{ old('other', $application->other) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('other')" />
                            </div>
                        </div>

                        <!-- Consent -->
                        <div class="border-t border-gray-200 pt-6 mb-6">
                            <x-input-label :value="__('messages.apply.consent.required') . ' *'" class="mb-4" style="text-transform: capitalize;" />
                            <div class="mt-2 space-y-3 mb-4">
                                <label class="flex items-center">
                                    <input type="radio" name="consent_type" value="full" class="form-radio text-indigo-600" {{ old('consent_type', $application->consent_type) == 'full' ? 'checked' : '' }} required>
                                    <span class="ml-3 text-sm text-gray-600">{{ __('messages.apply.consent.full') }}</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="consent_type" value="limited" class="form-radio text-indigo-600" {{ old('consent_type', $application->consent_type) == 'limited' ? 'checked' : '' }} required>
                                    <span class="ml-3 text-sm text-gray-600">{{ __('messages.apply.consent.limited') }}</span>
                                </label>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('consent_type')" />
                        </div>

                        <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                            <x-primary-button>{{ __('messages.profile.applications.update_application') }}</x-primary-button>
                            <a href="{{ route('profile.applications') }}" class="text-gray-600 hover:text-gray-900">
                                {{ __('messages.apply.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
</x-app-layout>
