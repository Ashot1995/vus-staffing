@extends('layouts.main')

@section('title', __('messages.search.title') . ($query ? ': ' . $query : '') . ' - VUS')

@section('content')
<section class="section-padding" style="margin-top: 60px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 mb-4">
                <h1>{{ __('messages.search.title') }}</h1>
                
                @if($query)
                    <p class="lead">{{ __('messages.search.results_for') }}: <strong>"{{ $query }}"</strong></p>
                    <p class="text-muted">{{ __('messages.search.found_results', ['count' => $totalResults]) }}</p>
                @else
                    <p class="lead">{{ __('messages.search.enter_query') }}</p>
                @endif
                
                <!-- Search Form -->
                <form method="GET" action="{{ route('search') }}" class="mb-4">
                    <div class="row">
                        <div class="col-lg-10 col-md-9 col-12 mb-2">
                            <input type="text" name="q" class="form-control form-control-lg" 
                                   placeholder="{{ __('messages.search.placeholder') }}" 
                                   value="{{ $query }}" required autofocus>
                        </div>
                        <div class="col-lg-2 col-md-3 col-12">
                            <button type="submit" class="btn custom-btn w-100" style="background: #000; color: #fff; border: none;">
                                <i class="bi-search me-1"></i>{{ __('messages.search.button') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($query)
            @if($totalResults > 0)
                <div class="row">
                    <div class="col-lg-12 col-12">
                        @foreach($results as $result)
                            <div class="custom-block bg-white shadow-lg mb-4">
                                <div class="custom-block-body">
                                    <div class="d-flex align-items-start mb-2">
                                        @if($result['type'] === 'blog')
                                            <span class="badge bg-primary me-2">{{ __('messages.search.type.blog') }}</span>
                                        @elseif($result['type'] === 'job')
                                            <span class="badge bg-success me-2">{{ __('messages.search.type.job') }}</span>
                                        @else
                                            <span class="badge bg-secondary me-2">{{ __('messages.search.type.page') }}</span>
                                        @endif
                                        <h5 class="mb-0"><a href="{{ $result['url'] }}" class="text-decoration-none text-dark">{{ $result['title'] }}</a></h5>
                                    </div>
                                    <p class="text-muted mb-3">{{ $result['excerpt'] }}</p>
                                    <a href="{{ $result['url'] }}" class="btn btn-sm btn-outline-primary">
                                        {{ __('messages.search.view_page') }} <i class="bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <div class="custom-block bg-white shadow-lg text-center py-5">
                            <i class="bi-search" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                            <h4>{{ __('messages.search.no_results') }}</h4>
                            <p class="text-muted">{{ __('messages.search.try_different') }}</p>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</section>
@endsection

