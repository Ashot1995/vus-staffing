@extends('layouts.main')

@section('title', $job->title . ' - VUS')

@section('content')
<section class="section-bg-image">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center mb-5">
                <h1>{{ $job->title }}</h1>
                <p><i class="bi-geo-alt me-2"></i>{{ $job->location }} | {{ ucfirst($job->employment_type) }}</p>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        @include('components.success-alert')
        <div class="row">
            <div class="col-lg-8 col-12">
                <div class="custom-block bg-white shadow-lg p-5 mb-4">
                    <h3 class="mb-3">{{ __('messages.jobs.detail.description') }}</h3>
                    <p>{!! nl2br(e($job->description)) !!}</p>

                    @if($job->responsibilities)
                        <h4 class="mt-4 mb-3">{{ __('messages.jobs.detail.responsibilities') }}</h4>
                        <p>{!! nl2br(e($job->responsibilities)) !!}</p>
                    @endif

                    @if($job->requirements)
                        <h4 class="mt-4 mb-3">{{ __('messages.jobs.detail.requirements') }}</h4>
                        <p>{!! nl2br(e($job->requirements)) !!}</p>
                    @endif

                    @if($job->salary)
                        <h4 class="mt-4 mb-3">{{ __('messages.jobs.detail.salary') }}</h4>
                        <p>{{ $job->salary }}</p>
                    @endif
                </div>
            </div>

            <div class="col-lg-4 col-12">
                <div class="custom-block bg-white shadow-lg p-4 sticky-top">
                    <h5 class="mb-3">{{ __('messages.jobs.detail.info') }}</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><strong>{{ __('messages.jobs.detail.location') }}:</strong> {{ $job->location }}</li>
                        <li class="mb-2"><strong>{{ __('messages.jobs.detail.type') }}:</strong> {{ ucfirst($job->employment_type) }}</li>
                        @if($job->deadline)
                            <li class="mb-2"><strong>{{ __('messages.jobs.deadline') }}:</strong> {{ $job->deadline->format('Y-m-d') }}</li>
                        @endif
                        <li class="mb-2"><strong>{{ __('messages.jobs.detail.published') }}:</strong> {{ $job->created_at->diffForHumans() }}</li>
                    </ul>

                    @auth
                        @if($existingApplication)
                            <div class="alert alert-info mt-3 mb-2">
                                <p class="mb-2">{{ __('messages.jobs.detail.already_applied') }}</p>
                                <a href="{{ route('profile.applications.edit', $existingApplication->id) }}" class="btn btn-sm btn-outline-primary w-100">
                                    {{ __('messages.jobs.detail.edit_application') }}
                                </a>
                            </div>
                        @else
                        <a href="{{ route('jobs.apply', $job) }}" class="custom-btn btn w-100 mt-3">{{ __('messages.jobs.detail.apply_now') }}</a>
                        @endif
                    @else
                        <p class="text-center mt-3 mb-2">{{ __('messages.jobs.detail.login_required') }}</p>
                        <a href="{{ route('login') }}" class="custom-btn btn w-100">{{ __('messages.nav.login') }}</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary w-100 mt-2">{{ __('messages.jobs.detail.register') }}</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
