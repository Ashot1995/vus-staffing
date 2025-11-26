@extends('layouts.main')

@section('title', __('messages.employers.title') . ' - VUS')

@section('content')
<section class="section-bg-image">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center mb-5">
                <h1>{{ __('messages.employers.title') }}</h1>
                <p>{{ __('messages.employers.subtitle') }}</p>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center mb-5">
                <h2 class="mb-3">{{ __('messages.employers.services.title') }}</h2>
                <p class="lead">{{ __('messages.employers.services.subtitle') }}</p>
            </div>

            <div class="col-lg-4 col-md-6 col-12 mb-4">
                <div class="custom-block bg-white shadow-lg">
                    <div class="custom-block-body text-center p-4">
                        <div class="custom-block-icon-wrap">
                            <div class="custom-block-icon">
                                <i class="bi-person-plus"></i>
                            </div>
                        </div>
                        <h5 class="mb-3">{{ __('messages.employers.recruitment.title') }}</h5>
                        <p>{{ __('messages.employers.recruitment.description') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12 mb-4">
                <div class="custom-block bg-white shadow-lg">
                    <div class="custom-block-body text-center p-4">
                        <div class="custom-block-icon-wrap">
                            <div class="custom-block-icon">
                                <i class="bi-people"></i>
                            </div>
                        </div>
                        <h5 class="mb-3">{{ __('messages.employers.staffing.title') }}</h5>
                        <p>{{ __('messages.employers.staffing.description') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12 mb-4">
                <div class="custom-block bg-white shadow-lg">
                    <div class="custom-block-body text-center p-4">
                        <div class="custom-block-icon-wrap">
                            <div class="custom-block-icon">
                                <i class="bi-briefcase"></i>
                            </div>
                        </div>
                        <h5 class="mb-3">{{ __('messages.employers.consulting.title') }}</h5>
                        <p>{{ __('messages.employers.consulting.description') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-lg-8 col-12 mx-auto">
                <div class="custom-block bg-white shadow-lg p-5">
                    <h3 class="mb-4 text-center">{{ __('messages.employers.why_choose.title') }}</h3>
                    <ul class="list-unstyled">
                        <li class="mb-3"><i class="bi-check-circle-fill me-2"></i> {{ __('messages.employers.why_choose.experience') }}</li>
                        <li class="mb-3"><i class="bi-check-circle-fill me-2"></i> {{ __('messages.employers.why_choose.network') }}</li>
                        <li class="mb-3"><i class="bi-check-circle-fill me-2"></i> {{ __('messages.employers.why_choose.fast') }}</li>
                        <li class="mb-3"><i class="bi-check-circle-fill me-2"></i> {{ __('messages.employers.why_choose.personal') }}</li>
                        <li class="mb-3"><i class="bi-check-circle-fill me-2"></i> {{ __('messages.employers.why_choose.support') }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-lg-12 col-12 text-center">
                <h3 class="mb-4">{{ __('messages.employers.cta.title') }}</h3>
                <p class="lead mb-4">{{ __('messages.employers.cta.description') }}</p>
                <a href="{{ route('contact') }}" class="custom-btn btn btn-lg">{{ __('messages.employers.cta.button') }}</a>
            </div>
        </div>
    </div>
</section>
@endsection
