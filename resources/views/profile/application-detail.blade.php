<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('messages.profile.applications.view_details') }}
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Basic Information -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('messages.profile.applications.basic_info') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.profile.applications.job') }}</label>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($application->job)
                                {{ $application->job->title }}
                                <br><span class="text-gray-500">{{ $application->job->location }}</span>
                            @else
                                {{ __('messages.profile.applications.spontaneous') }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.profile.applications.type') }}</label>
                        <p class="mt-1">
                            @if($application->is_spontaneous)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ __('messages.profile.applications.spontaneous') }}</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ __('messages.profile.applications.job_application') }}</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.profile.applications.status') }}</label>
                        <p class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $application->status === 'accepted' ? 'bg-green-100 text-green-800' : 
                                   ($application->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                   ($application->status === 'shortlisted' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ __('messages.profile.applications.status.' . $application->status) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.profile.applications.applied_date') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $application->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- CV & Cover Letter -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('messages.profile.applications.cv_cover_letter') }}</h3>
                
                <div class="mb-6">
                    <h4 class="text-md font-medium text-gray-900 mb-2">{{ __('messages.profile.applications.cv_document') }}</h4>
                    @if($application->cv_path)
                        <p class="text-sm text-gray-600 mb-2">{{ __('messages.profile.applications.current_cv') }}</p>
                        <a href="{{ asset('storage/' . $application->cv_path) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            {{ __('messages.profile.applications.download_cv') }}: {{ basename($application->cv_path) }}
                        </a>
                    @else
                        <p class="text-sm text-gray-500">{{ __('messages.profile.applications.no_cv') }}</p>
                    @endif
                </div>

                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-2">{{ __('messages.profile.applications.cover_letter') }}</h4>
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50" style="max-height: 400px; overflow-y: auto;">
                        <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $application->cover_letter ?: __('messages.profile.applications.no_cover_letter') }}</p>
                    </div>
                </div>
            </div>

            <!-- Dates & Timeline -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('messages.profile.applications.dates_timeline') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.profile.applications.start_date_option') }}</label>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($application->start_date_option)
                                {{ match($application->start_date_option) {
                                    'immediately' => __('messages.apply.start_date_option.immediately'),
                                    'one_week' => __('messages.apply.start_date_option.one_week'),
                                    'one_month' => __('messages.apply.start_date_option.one_month'),
                                    'custom' => __('messages.apply.start_date_option.custom'),
                                    default => $application->start_date_option,
                                } }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.profile.applications.expected_start_date') }}</label>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($application->start_date)
                                {{ \Carbon\Carbon::parse($application->start_date)->format('Y-m-d') }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.profile.applications.application_date') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $application->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.profile.applications.last_updated') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $application->updated_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

