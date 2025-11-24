@extends('layouts.main')

@section('title', $job->title . ' - VUS Bemanning')

@section('content')
<section class="section-bg-image">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center mb-5">
                <h1 class="text-white">{{ $job->title }}</h1>
                <p class="text-white"><i class="bi-geo-alt me-2"></i>{{ $job->location }} | {{ ucfirst($job->employment_type) }}</p>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
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
                        <a href="{{ route('jobs.apply', $job) }}" class="custom-btn btn w-100 mt-3">{{ __('messages.jobs.detail.apply_now') }}</a>
                    @else
                        <p class="text-center mt-3 mb-2">{{ __('messages.jobs.detail.login_required') }}</p>
                        <a href="{{ route('login') }}" class="custom-btn btn w-100">{{ __('messages.nav.login') }}</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary w-100 mt-2">{{ __('messages.jobs.detail.register') }}</a>
                    @endauth

                    <div class="mt-4">
                        <h6>{{ __('messages.jobs.detail.share') }}</h6>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('jobs.show', $job)) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi-facebook"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('jobs.show', $job)) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi-linkedin"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('jobs.show', $job)) }}&text={{ urlencode($job->title) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
