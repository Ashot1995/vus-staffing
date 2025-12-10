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

                        <!-- Cover Letter -->
                        <div>
                            <x-input-label for="cover_letter" :value="__('messages.apply.cover_letter')" />
                            <textarea id="cover_letter" name="cover_letter" rows="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('cover_letter', $application->cover_letter) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('cover_letter')" />
                        </div>

                        <!-- Start Date Option -->
                        <div>
                            <x-input-label for="start_date_option" :value="__('messages.apply.start_date')" />
                            <select id="start_date_option" name="start_date_option" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required onchange="toggleCustomDate()">
                                <option value="immediately" {{ old('start_date_option', $application->start_date_option) == 'immediately' ? 'selected' : '' }}>{{ __('messages.apply.start_date_option.immediately') }}</option>
                                <option value="one_week" {{ old('start_date_option', $application->start_date_option) == 'one_week' ? 'selected' : '' }}>{{ __('messages.apply.start_date_option.one_week') }}</option>
                                <option value="one_month" {{ old('start_date_option', $application->start_date_option) == 'one_month' ? 'selected' : '' }}>{{ __('messages.apply.start_date_option.one_month') }}</option>
                                <option value="custom" {{ old('start_date_option', $application->start_date_option) == 'custom' ? 'selected' : '' }}>{{ __('messages.apply.start_date_option.custom') }}</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('start_date_option')" />
                        </div>

                        <!-- Custom Date -->
                        <div id="custom_date_container" style="display: {{ old('start_date_option', $application->start_date_option) == 'custom' ? 'block' : 'none' }};">
                            <x-input-label for="start_date" :value="__('messages.apply.start_date_custom')" />
                            <input id="start_date" name="start_date" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('start_date', $application->start_date ? $application->start_date->format('Y-m-d') : '') }}" min="{{ date('Y-m-d') }}">
                            <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                        </div>

                        <div class="flex items-center gap-4">
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

    <script>
        function toggleCustomDate() {
            const select = document.getElementById('start_date_option');
            const container = document.getElementById('custom_date_container');
            if (select.value === 'custom') {
                container.style.display = 'block';
                document.getElementById('start_date').required = true;
            } else {
                container.style.display = 'none';
                document.getElementById('start_date').required = false;
            }
        }
    </script>
</x-app-layout>


