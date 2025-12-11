<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('messages.profile.title') }}
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
