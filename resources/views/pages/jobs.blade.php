@extends('layouts.main')

@section('title', __('messages.jobs.title') . ' - VUS')

@section('content')
<section class="section-bg-image">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center mb-5">
                <h1>{{ __('messages.jobs.title') }}</h1>
                <p>{{ __('messages.jobs.subtitle') }}</p>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-12 mb-4">
                <div class="custom-block bg-white shadow-lg p-4">
                    <h5 class="mb-3">{{ __('messages.jobs.filter.title') }}</h5>
                    <form method="GET" action="{{ route('jobs.index') }}">
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.jobs.filter.search') }}</label>
                            <input type="text" name="search" class="form-control" placeholder="{{ __('messages.jobs.filter.search_placeholder') }}" value="{{ request('search') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.jobs.filter.location') }}</label>
                            <input type="text" name="location" class="form-control" placeholder="{{ __('messages.jobs.filter.location_placeholder') }}" value="{{ request('location') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.jobs.filter.employment_type') }}</label>
                            <select name="employment_type" class="form-select">
                                <option value="">{{ __('messages.jobs.filter.all') }}</option>
                                <option value="full-time" {{ request('employment_type') == 'full-time' ? 'selected' : '' }}>{{ __('messages.jobs.filter.full_time') }}</option>
                                <option value="part-time" {{ request('employment_type') == 'part-time' ? 'selected' : '' }}>{{ __('messages.jobs.filter.part_time') }}</option>
                                <option value="contract" {{ request('employment_type') == 'contract' ? 'selected' : '' }}>{{ __('messages.jobs.filter.contract') }}</option>
                                <option value="temporary" {{ request('employment_type') == 'temporary' ? 'selected' : '' }}>{{ __('messages.jobs.filter.temporary') }}</option>
                            </select>
                        </div>
                        <button type="submit" class="btn custom-btn w-100">{{ __('messages.jobs.filter.button') }}</button>
                        <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary w-100 mt-2">{{ __('messages.jobs.filter.clear') }}</a>
                    </form>
                </div>
            </div>

            <div class="col-lg-9 col-12">
                @forelse($jobs as $job)
                    <div class="custom-block custom-block-full bg-white shadow-lg mb-4">
                        <div class="custom-block-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0" style="text-transform: none;">{{ ucfirst(strtolower($job->title)) }}</h4>
                                <span class="badge" style="background: #000000; color: #ffffff;">{{ ucfirst(str_replace('-', ' ', $job->employment_type)) }}</span>
                            </div>
                            <p class="mb-2"><i class="bi-geo-alt me-2"></i>{{ $job->location }}</p>
                            @if($job->salary)
                                <p class="mb-2"><i class="bi-currency-dollar me-2"></i>{{ $job->salary }}</p>
                            @endif
                            <p class="mb-3">{{ Str::limit($job->description, 200) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('jobs.show', $job) }}" class="custom-btn btn">{{ __('messages.jobs.read_more') }}</a>
                                @if($job->deadline)
                                    <small class="text-muted">{{ __('messages.jobs.deadline') }}: {{ $job->deadline->format('Y-m-d') }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="custom-block bg-white shadow-lg p-5 text-center">
                        <h4>{{ __('messages.jobs.no_jobs.title') }}</h4>
                        <p>{{ __('messages.jobs.no_jobs.description') }}</p>
                        <a href="{{ route('jobs.apply-spontaneous') }}" class="custom-btn btn mt-3">{{ __('messages.jobs.no_jobs.spontaneous') }}</a>
                    </div>
                @endforelse

                <div class="mt-4">
                    {{ $jobs->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
