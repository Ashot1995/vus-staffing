@extends('layouts.main')

@section('title', 'VUS Entreprenad & Bemanning AB - ' . __('messages.nav.home'))

@section('content')
<section class="hero" id="section_1">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-12 m-auto">
                <div class="hero-text">
                    <h1 class="text-white mb-4"><u class="text-info">VUS Bemanning</u> - {{ __('messages.home.hero.title') }}</h1>
                    <div class="d-flex justify-content-center align-items-center">
                        <span class="date-text">{{ __('messages.home.hero.subtitle') }}</span>
                        <span class="location-text">{{ __('messages.home.hero.location') }}</span>
                    </div>
                    <a href="#section_2" class="custom-link bi-arrow-down arrow-icon"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="video-wrap">
        <video autoplay loop muted class="custom-video" poster="">
            <source src="{{ asset('videos/pexels-pavel-danilyuk-8716790.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
</section>

<section class="highlight" id="section_2">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card custom-block bg-white shadow-lg">
                    <div class="card-body">
                        <div class="custom-block-icon-wrap">
                            <div class="custom-block-icon">
                                <i class="bi-search"></i>
                            </div>
                        </div>
                        <h5 class="mb-3">{{ __('messages.home.find_job.title') }}</h5>
                        <p>{{ __('messages.home.find_job.description') }}</p>
                        <a href="{{ route('jobs.index') }}" class="custom-btn btn">{{ __('messages.home.find_job.button') }}</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
                <div class="card custom-block bg-white shadow-lg">
                    <div class="card-body">
                        <div class="custom-block-icon-wrap">
                            <div class="custom-block-icon">
                                <i class="bi-person-check"></i>
                            </div>
                        </div>
                        <h5 class="mb-3">{{ __('messages.home.register.title') }}</h5>
                        <p>{{ __('messages.home.register.description') }}</p>
                        <a href="{{ route('register') }}" class="custom-btn btn">{{ __('messages.home.register.button') }}</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
                <div class="card custom-block bg-white shadow-lg">
                    <div class="card-body">
                        <div class="custom-block-icon-wrap">
                            <div class="custom-block-icon">
                                <i class="bi-building"></i>
                            </div>
                        </div>
                        <h5 class="mb-3">{{ __('messages.home.employers.title') }}</h5>
                        <p>{{ __('messages.home.employers.description') }}</p>
                        <a href="{{ route('for-employers') }}" class="custom-btn btn">{{ __('messages.home.employers.button') }}</a>
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
                <div class="custom-block-wrap">
                    <div class="custom-block">
                        <div class="custom-block-body">
                            <h5 class="mb-3">{{ __('messages.home.professional_service.title') }}</h5>
                            <p>{{ __('messages.home.professional_service.description') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-0">
                <div class="custom-block-wrap">
                    <div class="custom-block">
                        <div class="custom-block-body">
                            <h5 class="mb-3">{{ __('messages.home.broad_competence.title') }}</h5>
                            <p>{{ __('messages.home.broad_competence.description') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
                <div class="custom-block-wrap">
                    <div class="custom-block">
                        <div class="custom-block-body">
                            <h5 class="mb-3">{{ __('messages.home.fast_matching.title') }}</h5>
                            <p>{{ __('messages.home.fast_matching.description') }}</p>
                        </div>
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
                <h2 class="text-white mb-4">{{ __('messages.home.cta.title') }}</h2>
                <p class="text-white">{{ __('messages.home.cta.description') }}</p>
                <a href="{{ route('jobs.index') }}" class="custom-btn btn btn-lg mt-3 me-3">{{ __('messages.home.cta.jobs') }}</a>
                <a href="{{ route('contact') }}" class="custom-btn btn btn-lg mt-3">{{ __('messages.home.cta.contact') }}</a>
            </div>
        </div>
    </div>
</section>
@endsection
