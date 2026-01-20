@extends('layouts.main')

@section('title', __('messages.privacy.title') . ' - VUS')

@section('content')
<section class="section-bg-image">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center mb-5">
                <h1>{{ __('messages.privacy.title') }}</h1>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-12 mx-auto">
                <div class="custom-block bg-white shadow-lg p-5">
                    @if($privacyPolicy)
                        <div class="privacy-content">
                            {!! app()->getLocale() === 'sv' ? $privacyPolicy->content_sv : $privacyPolicy->content_en !!}
                        </div>
                    @else
                        <div class="privacy-content">
                            <h2>{{ __('messages.privacy.introduction.title') }}</h2>
                            <p>{{ __('messages.privacy.introduction.text') }}</p>

                            <h2>{{ __('messages.privacy.data_collection.title') }}</h2>
                            <p>{{ __('messages.privacy.data_collection.text') }}</p>

                            <h2>{{ __('messages.privacy.data_usage.title') }}</h2>
                            <p>{{ __('messages.privacy.data_usage.text') }}</p>

                            <h2>{{ __('messages.privacy.cookies.title') }}</h2>
                            <p>{{ __('messages.privacy.cookies.text') }}</p>

                            <h2>{{ __('messages.privacy.rights.title') }}</h2>
                            <p>{{ __('messages.privacy.rights.text') }}</p>

                            <h2>{{ __('messages.privacy.contact.title') }}</h2>
                            <p>{{ __('messages.privacy.contact.text') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection





