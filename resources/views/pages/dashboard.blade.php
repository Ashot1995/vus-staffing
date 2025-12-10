@extends('layouts.main')

@section('title', __('messages.dashboard.title') . ' - VUS')

@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 mb-4">
                <h2>{{ __('messages.dashboard.title') }}</h2>
                <p>{{ __('messages.dashboard.welcome') }}, {{ auth()->user()->name }}!</p>
            </div>

            <div class="col-lg-12 col-12 mb-4">
                <div class="custom-block bg-white shadow-lg p-4">
                    <h5 class="mb-3">{{ __('messages.dashboard.my_info') }}</h5>
                    <p><strong>{{ __('messages.dashboard.name') }}:</strong> {{ auth()->user()->name }}</p>
                    <p><strong>{{ __('messages.dashboard.email') }}:</strong> {{ auth()->user()->email }}</p>
                    <a href="{{ route('profile.edit') }}" class="custom-btn btn mt-3">{{ __('messages.dashboard.edit_profile') }}</a>
                </div>
            </div>

            <div class="col-lg-12 col-12 mb-4">
                <div class="custom-block bg-white shadow-lg p-4">
                    <h5 class="mb-3">{{ __('messages.dashboard.quick_links') }}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2"><a href="{{ route('jobs.index') }}" class="text-decoration-none"><i class="bi-briefcase me-2"></i>{{ __('messages.dashboard.view_jobs') }}</a></li>
                                <li class="mb-2"><a href="{{ route('jobs.apply-spontaneous') }}" class="text-decoration-none"><i class="bi-file-earmark-plus me-2"></i>{{ __('messages.dashboard.spontaneous_apply') }}</a></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2"><a href="{{ route('profile.edit') }}" class="text-decoration-none"><i class="bi-person me-2"></i>{{ __('messages.dashboard.edit_profile') }}</a></li>
                                @if(auth()->user()->is_admin)
                                    <li class="mb-2"><a href="{{ url('/admin') }}" target="_blank" class="text-decoration-none"><i class="bi-shield-check me-2"></i>{{ __('Admin Panel') }}</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
