@extends('layouts.main')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', __('messages.company_values.title') . ' - V U S')

@push('structured-data')
<x-breadcrumbs :items="[
    ['name' => __('messages.nav.home'), 'url' => route('home')],
    ['name' => __('messages.nav.company_values'), 'url' => route('company-values')]
]" />
@endpush

@section('content')
<section class="section-bg-image">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center mb-5">
                <h1>{{ __('messages.company_values.title') }}</h1>
                <p class="lead">{{ __('messages.company_values.subtitle') }}</p>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        @if($values->count() > 0)
            <div class="row">
                @foreach($values as $value)
                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                        <div class="custom-block bg-white shadow-lg h-100">
                            <div class="custom-block-body">
                                @if($value->image)
                                    <div class="mb-3" style="width: 100%; height: 200px; overflow: hidden; border-radius: 8px;">
                                        <img src="{{ Storage::url($value->image) }}" 
                                             alt="{{ app()->getLocale() === 'sv' ? $value->title_sv : $value->title_en }}" 
                                             class="img-fluid w-100 h-100" 
                                             style="object-fit: cover;">
                                    </div>
                                @endif
                                
                                <h4 class="mb-3">
                                    {{ app()->getLocale() === 'sv' ? $value->title_sv : $value->title_en }}
                                </h4>
                                
                                <p class="mb-0">
                                    {{ app()->getLocale() === 'sv' ? $value->description_sv : $value->description_en }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="custom-block bg-white shadow-lg p-5 text-center">
                        <h4>{{ __('messages.company_values.no_values.title') }}</h4>
                        <p>{{ __('messages.company_values.no_values.description') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

