@extends('layouts.main')

@section('title', __('messages.dashboard.title') . ' - VUS Bemanning')

@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 mb-4">
                <h2>{{ __('messages.dashboard.title') }}</h2>
                <p>{{ __('messages.dashboard.welcome') }}, {{ auth()->user()->name }}!</p>
            </div>

            <div class="col-lg-6 col-12 mb-4">
                <div class="custom-block bg-white shadow-lg p-4">
                    <h5 class="mb-3">{{ __('messages.dashboard.my_info') }}</h5>
                    <p><strong>{{ __('messages.dashboard.name') }}:</strong> {{ auth()->user()->name }}</p>
                    <p><strong>{{ __('messages.dashboard.email') }}:</strong> {{ auth()->user()->email }}</p>
                    <a href="{{ route('profile.edit') }}" class="custom-btn btn mt-3">{{ __('messages.dashboard.edit_profile') }}</a>
                </div>
            </div>

            <div class="col-lg-6 col-12 mb-4">
                <div class="custom-block bg-white shadow-lg p-4">
                    <h5 class="mb-3">{{ __('messages.dashboard.quick_links') }}</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('jobs.index') }}" class="text-decoration-none">{{ __('messages.dashboard.view_jobs') }}</a></li>
                        <li class="mb-2"><a href="{{ route('jobs.apply-spontaneous') }}" class="text-decoration-none">{{ __('messages.dashboard.spontaneous_apply') }}</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-12 col-12">
                <div class="custom-block bg-white shadow-lg p-4">
                    <h5 class="mb-3">{{ __('messages.dashboard.my_applications') }}</h5>
                    @forelse(auth()->user()->applications()->latest()->get() as $application)
                        <div class="border-bottom pb-3 mb-3">
                            @if($application->job)
                                <h6>
                                    <a href="{{ route('jobs.show', $application->job) }}">{{ $application->job->title }}</a>
                                </h6>
                                <p class="mb-1"><small><i class="bi-geo-alt me-1"></i>{{ $application->job->location }}</small></p>
                            @else
                                <h6>{{ __('messages.dashboard.spontaneous_application') }}</h6>
                            @endif
                            <p class="mb-1">
                                <span class="badge 
                                    @if($application->status == 'accepted') bg-success
                                    @elseif($application->status == 'rejected') bg-danger
                                    @elseif($application->status == 'shortlisted') bg-primary
                                    @elseif($application->status == 'reviewed') bg-info
                                    @else bg-secondary
                                    @endif
                                ">{{ __('messages.dashboard.status.' . $application->status) }}</span>
                            </p>
                            <p class="mb-0"><small class="text-muted">{{ __('messages.dashboard.sent') }}: {{ $application->created_at->diffForHumans() }}</small></p>
                        </div>
                    @empty
                        <p class="text-muted">{{ __('messages.dashboard.no_applications') }}</p>
                        <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary">{{ __('messages.dashboard.search_jobs') }}</a>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
