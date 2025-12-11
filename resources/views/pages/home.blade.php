@extends('layouts.main')

@section('title', 'VUS - ' . __('messages.nav.home'))

@section('content')
<section class="hero" id="section_1">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-12 m-auto">
                <div class="hero-text">
                    <h1 class="mb-4">
                        <span class="text-white">Welcome to </span>
                        <span class="vus-brand">V U S</span>
                    </h1>
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
                        <p class="mb-3">"VUS helped us find the perfect candidates for our construction projects. Their service is professional and efficient."</p>
                        <h6 class="mb-0"><strong>Construction Company AB</strong></h6>
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
                        <p class="mb-3">"Excellent staffing solutions! VUS understands our needs and always delivers qualified personnel on time."</p>
                        <h6 class="mb-0"><strong>Logistics Solutions Ltd</strong></h6>
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
                        <p class="mb-3">"We've been working with VUS for over 2 years. They are reliable partners who truly care about matching the right people with the right jobs."</p>
                        <h6 class="mb-0"><strong>Tech Innovations Inc</strong></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding" id="section_5" style="background: #f5f5f5;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center mb-5">
                <h2 class="mb-3">{{ __('messages.home.partners.title') }}</h2>
                <p>{{ __('messages.home.partners.subtitle') }}</p>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-3 col-md-4 col-6 mb-4 text-center">
                <a href="https://example.com" target="_blank" class="d-block">
                    <div class="custom-block bg-white shadow-lg p-4 h-100 d-flex align-items-center justify-content-center" style="min-height: 120px;">
                        <span style="font-size: 24px; font-weight: bold; color: #000000;">Partner 1</span>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-6 mb-4 text-center">
                <a href="https://example.com" target="_blank" class="d-block">
                    <div class="custom-block bg-white shadow-lg p-4 h-100 d-flex align-items-center justify-content-center" style="min-height: 120px;">
                        <span style="font-size: 24px; font-weight: bold; color: #000000;">Partner 2</span>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-6 mb-4 text-center">
                <a href="https://example.com" target="_blank" class="d-block">
                    <div class="custom-block bg-white shadow-lg p-4 h-100 d-flex align-items-center justify-content-center" style="min-height: 120px;">
                        <span style="font-size: 24px; font-weight: bold; color: #000000;">Partner 3</span>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-6 mb-4 text-center">
                <a href="https://example.com" target="_blank" class="d-block">
                    <div class="custom-block bg-white shadow-lg p-4 h-100 d-flex align-items-center justify-content-center" style="min-height: 120px;">
                        <span style="font-size: 24px; font-weight: bold; color: #000000;">Partner 4</span>
                    </div>
                </a>
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
@endsection
