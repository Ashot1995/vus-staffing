@extends('layouts.main')

@section('title', __('messages.about.title') . ' - VUS Bemanning')

@section('content')
<section class="section-bg-image">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center mb-5">
                <h1 class="text-white">{{ __('messages.about.title') }}</h1>
                <p class="text-white">{{ __('messages.about.subtitle') }}</p>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-12 mx-auto">
                <div class="custom-block bg-white shadow-lg p-5 mb-4">
                    <h2 class="mb-4">{{ __('messages.about.welcome.title') }}</h2>
                    <p>{{ __('messages.about.welcome.description') }}</p>
                    
                    <p>{{ __('messages.about.welcome.description2') }}</p>

                    <h4 class="mt-4 mb-3">{{ __('messages.about.vision.title') }}</h4>
                    <p>{{ __('messages.about.vision.description') }}</p>

                    <h4 class="mt-4 mb-3">{{ __('messages.about.values.title') }}</h4>
                    <ul>
                        <li>{{ __('messages.about.values.professionalism') }}</li>
                        <li>{{ __('messages.about.values.quality') }}</li>
                        <li>{{ __('messages.about.values.relationships') }}</li>
                        <li>{{ __('messages.about.values.service') }}</li>
                        <li>{{ __('messages.about.values.transparency') }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-lg-12 col-12 text-center">
                <h3 class="mb-4">{{ __('messages.about.cta.title') }}</h3>
                <a href="{{ route('contact') }}" class="custom-btn btn btn-lg">{{ __('messages.about.cta.button') }}</a>
            </div>
        </div>
    </div>
</section>
@endsection
