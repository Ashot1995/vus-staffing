<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('messages.profile.tab.applications') }}
            </h2>
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('profile.edit') }}" class="whitespace-nowrap py-4 px-1 border-b-2 {{ request()->routeIs('profile.edit') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                        {{ __('messages.profile.tab.profile') }}
                    </a>
                    <a href="{{ route('profile.applications') }}" class="whitespace-nowrap py-4 px-1 border-b-2 {{ request()->routeIs('profile.applications*') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
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
                    @if($applications->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.profile.applications.job') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.profile.applications.type') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.apply.first_name') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.apply.surname') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.apply.email') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.apply.phone') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.apply.date_of_birth') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.apply.driving_license_privileges') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.profile.applications.status') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.profile.applications.start_date') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.profile.applications.applied_date') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.profile.applications.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($applications as $application)
                                        <tr>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                @if($application->job)
                                                    <div class="text-sm font-medium text-gray-900">{{ $application->job->title }}</div>
                                                    <div class="text-sm text-gray-500">{{ $application->job->location }}</div>
                                                @else
                                                    <div class="text-sm font-medium text-gray-900">{{ __('messages.profile.applications.spontaneous') }}</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                @if($application->is_spontaneous)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ __('messages.profile.applications.spontaneous') }}</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ __('messages.profile.applications.job_application') }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $application->first_name ?? '-' }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $application->surname ?? '-' }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $application->email ?? '-' }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $application->phone ?? '-' }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $application->date_of_birth ? \Carbon\Carbon::parse($application->date_of_birth)->format('Y-m-d') : '-' }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class="flex flex-col gap-1">
                                                    @if($application->driving_license_b)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ __('messages.apply.driving_license_b') }}</span>
                                                    @endif
                                                    @if($application->driving_license_own_car)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ __('messages.apply.driving_license_own_car') }}</span>
                                                    @endif
                                                    @if(!$application->driving_license_b && !$application->driving_license_own_car)
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $application->status === 'accepted' ? 'bg-green-100 text-green-800' : 
                                                       ($application->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                                       ($application->status === 'shortlisted' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                                    {{ __('messages.profile.applications.status.' . $application->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($application->start_date_option)
                                                    <div>{{ match($application->start_date_option) {
                                                        'immediately' => __('messages.apply.start_date_option.immediately'),
                                                        'one_month' => __('messages.apply.start_date_option.one_month'),
                                                        'two_three_months' => __('messages.apply.start_date_option.two_three_months'),
                                                        default => $application->start_date_option,
                                                    } }}</div>
                                                    @if($application->start_date)
                                                        <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($application->start_date)->format('Y-m-d') }}</div>
                                                    @endif
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $application->created_at->format('Y-m-d H:i') }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('profile.applications.edit', $application->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    {{ __('messages.profile.applications.edit') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500 mb-4">{{ __('messages.profile.applications.no_applications') }}</p>
                            <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                {{ __('messages.profile.applications.search_jobs') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

