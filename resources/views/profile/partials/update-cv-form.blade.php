<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('messages.profile.cv') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('messages.profile.cv_description') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        @if($user->cv_path)
            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-700 mb-2">
                    <strong>{{ __('messages.profile.current_cv') }}</strong> {{ basename($user->cv_path) }}
                </p>
                <a href="{{ asset('storage/' . $user->cv_path) }}" target="_blank" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('messages.profile.view_current_cv') }}
                </a>
            </div>
        @endif

        <div>
            <x-input-label for="cv" :value="__('messages.profile.upload_new_cv')" />
            <input id="cv" name="cv" type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept=".pdf,.doc,.docx" />
            <p class="mt-1 text-xs text-gray-500">{{ __('messages.profile.cv_formats') }}</p>
            <x-input-error class="mt-2" :messages="$errors->get('cv')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('messages.profile.upload_new_cv') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('messages.profile.cv_updated') }}</p>
            @endif
        </div>
    </form>
</section>

