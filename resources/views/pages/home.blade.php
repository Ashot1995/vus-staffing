@extends('layouts.main')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'VUS - ' . __('messages.nav.home'))

@section('content')
<section class="hero" id="section_1">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-12 m-auto">
                <div class="hero-text">
                    <h1 class="mb-4">
                        <span class="text-white">{{ __('messages.home.hero.title_prefix') }}</span>
                        <span class="vus-brand" style="white-space: nowrap;">{{ __('messages.home.hero.title_vus') }}</span>
                    </h1>
                    <div class="d-flex justify-content-center align-items-center gap-3 mb-4">
                        <a href="{{ route('for-employers') }}" class="custom-btn btn">{{ __('messages.home.hero.button_for_employers') }}</a>
                        <a href="{{ route('jobs.index') }}" class="custom-btn btn">{{ __('messages.home.hero.button_available_jobs') }}</a>
                    </div>
                    <a href="#section_2" class="custom-link bi-arrow-down arrow-icon"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="video-wrap">
        <video autoplay loop muted class="custom-video" poster="">
            <source src="{{ asset('videos/pexels-pavel-danilyuk-8716790.mp4') }}" type="video/mp4">
            {{ __('messages.common.video_not_supported') }}
        </video>
    </div>
</section>

<section class="highlight" id="section_2">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-0">
                <div class="card custom-block bg-white shadow-lg h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="custom-block-icon-wrap">
                            <div class="custom-block-icon">
                                <i class="bi-search"></i>
                            </div>
                        </div>
                        <h5 class="mb-3">{{ __('messages.home.find_job.title') }}</h5>
                        <p class="flex-grow-1">{{ __('messages.home.find_job.description') }}</p>
                        <a href="{{ route('jobs.index') }}" class="custom-btn btn mt-auto">{{ __('messages.home.find_job.button') }}</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-0">
                <div class="card custom-block bg-white shadow-lg h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="custom-block-icon-wrap">
                            <div class="custom-block-icon">
                                <i class="bi-person-check"></i>
                            </div>
                        </div>
                        <h5 class="mb-3">{{ __('messages.home.register.title') }}</h5>
                        <p class="flex-grow-1">{{ __('messages.home.register.description') }}</p>
                        <a href="{{ route('register') }}" class="custom-btn btn mt-auto">{{ __('messages.home.register.button') }}</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
                <div class="card custom-block bg-white shadow-lg h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="custom-block-icon-wrap">
                            <div class="custom-block-icon">
                                <i class="bi-building"></i>
                            </div>
                        </div>
                        <h5 class="mb-3">{{ __('messages.home.employers.title') }}</h5>
                        <p class="flex-grow-1">{{ __('messages.home.employers.description') }}</p>
                        <a href="{{ route('for-employers') }}" class="custom-btn btn mt-auto">{{ __('messages.home.employers.button') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about-section section-padding" id="section_3">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center">
                <h2 class="mb-4">{{ __('messages.home.why_choose.title') }}</h2>
            </div>

            <div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-0">
                <div class="custom-block-wrap h-100">
                    <div class="custom-block h-100">
                        <div class="custom-block-body d-flex flex-column h-100">
                            <h5 class="mb-3">{{ __('messages.home.professional_service.title') }}</h5>
                            <p class="flex-grow-1">{{ __('messages.home.professional_service.description') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-0">
                <div class="custom-block-wrap h-100">
                    <div class="custom-block h-100">
                        <div class="custom-block-body d-flex flex-column h-100">
                            <h5 class="mb-3">{{ __('messages.home.broad_competence.title') }}</h5>
                            <p class="flex-grow-1">{{ __('messages.home.broad_competence.description') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
                <div class="custom-block-wrap h-100">
                    <div class="custom-block h-100">
                        <div class="custom-block-body d-flex flex-column h-100">
                            <h5 class="mb-3">{{ __('messages.home.fast_matching.title') }}</h5>
                            <p class="flex-grow-1">{{ __('messages.home.fast_matching.description') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding" id="section_4">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center mb-5">
                <h2 class="mb-3">{{ __('messages.home.reviews.title') }}</h2>
                <p>{{ __('messages.home.reviews.subtitle') }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-0">
                <div class="custom-block bg-white shadow-lg h-100">
                    <div class="custom-block-body p-4">
                        <div class="mb-3">
                            <i class="bi-star-fill" style="color: #000000;"></i>
                            <i class="bi-star-fill" style="color: #000000;"></i>
                            <i class="bi-star-fill" style="color: #000000;"></i>
                            <i class="bi-star-fill" style="color: #000000;"></i>
                            <i class="bi-star-fill" style="color: #000000;"></i>
                        </div>
                        <p class="mb-3">"{{ __('messages.home.reviews.review1.text') }}"</p>
                        <h6 class="mb-0"><strong>{{ __('messages.home.reviews.review1.company') }}</strong></h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-0">
                <div class="custom-block bg-white shadow-lg h-100">
                    <div class="custom-block-body p-4">
                        <div class="mb-3">
                            <i class="bi-star-fill" style="color: #000000;"></i>
                            <i class="bi-star-fill" style="color: #000000;"></i>
                            <i class="bi-star-fill" style="color: #000000;"></i>
                            <i class="bi-star-fill" style="color: #000000;"></i>
                            <i class="bi-star-fill" style="color: #000000;"></i>
                        </div>
                        <p class="mb-3">"{{ __('messages.home.reviews.review2.text') }}"</p>
                        <h6 class="mb-0"><strong>{{ __('messages.home.reviews.review2.company') }}</strong></h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="custom-block bg-white shadow-lg h-100">
                    <div class="custom-block-body p-4">
                        <div class="mb-3">
                            <i class="bi-star-fill" style="color: #000000;"></i>
                            <i class="bi-star-fill" style="color: #000000;"></i>
                            <i class="bi-star-fill" style="color: #000000;"></i>
                            <i class="bi-star-fill" style="color: #000000;"></i>
                            <i class="bi-star-fill" style="color: #000000;"></i>
                        </div>
                        <p class="mb-3">"{{ __('messages.home.reviews.review3.text') }}"</p>
                        <h6 class="mb-0"><strong>{{ __('messages.home.reviews.review3.company') }}</strong></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="call-to-action section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-12 mx-auto text-center">
                <h2 class="mb-4">{{ __('messages.home.cta.title') }}</h2>
                <p>{{ __('messages.home.cta.description') }}</p>
                <a href="{{ route('jobs.index') }}" class="custom-btn btn btn-lg mt-3 me-3">{{ __('messages.home.cta.jobs') }}</a>
                <a href="{{ route('contact') }}" class="custom-btn btn btn-lg mt-3">{{ __('messages.home.cta.contact') }}</a>
            </div>
        </div>
    </div>
</section>

@if(isset($partners) && $partners->count() > 0)
<section class="section-padding" id="section_5" style="background: #f5f5f5;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center mb-5">
                <h2 class="mb-3">{{ __('messages.home.partners.title') }}</h2>
                <p class="lead">{{ __('messages.home.partners.subtitle') }}</p>
            </div>
        </div>

        <div class="row justify-content-center">
            @foreach($partners as $partner)
            <div class="col-lg-3 col-md-4 col-6 mb-4">
                <div class="custom-block bg-white shadow-lg p-4 h-100 d-flex align-items-center justify-content-center" style="min-height: 150px;">
                    @if($partner->logo)
                        <a href="{{ $partner->website_url ?? '#' }}" target="{{ $partner->website_url ? '_blank' : '_self' }}" class="text-decoration-none w-100 h-100 d-flex align-items-center justify-content-center">
                            <img src="{{ Storage::url($partner->logo) }}" alt="{{ $partner->name }}" class="img-fluid" style="max-height: 100px; max-width: 100%; object-fit: contain;">
                        </a>
                    @else
                        <div class="text-center w-100">
                            <h5 class="mb-0">{{ $partner->name }}</h5>
                            @if($partner->website_url)
                                <a href="{{ $partner->website_url }}" target="_blank" class="text-decoration-none text-muted small">{{ __('messages.home.partners.visit_website') }}</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
