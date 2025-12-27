@extends('layouts.main')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', __('messages.about.title') . ' - V U S')

@push('structured-data')
<x-breadcrumbs :items="[
    ['name' => __('messages.nav.home'), 'url' => route('home')],
    ['name' => __('messages.nav.about'), 'url' => route('about')]
]" />
@endpush

@section('content')
<section class="section-padding">
    <div class="container">
        <!-- Text Content Section (Top) -->
        <div class="row">
            <div class="col-lg-10 col-12 mx-auto">
                <div class="text-center mb-5">
                    <h1 class="mb-4">{{ __('messages.about.title') }}</h1>
                    <p class="lead">{{ __('messages.about.subtitle') }}</p>
                </div>
                
                <div class="mb-5">
                    <p class="lead mb-4" style="color: #ff6b35; font-weight: 600;">{{ __('messages.about.welcome.description') }}</p>
                    
                    <p class="mb-4">{{ __('messages.about.welcome.description2') }}</p>

                    <p class="mb-4">{{ __('messages.about.welcome.description3') }}</p>

                    @if(!empty(__('messages.about.our_version.description')))
                        <h4 class="mt-4 mb-3">{{ __('messages.about.our_version.title') }}</h4>
                        <p>{{ __('messages.about.our_version.description') }}</p>
                    @endif

                    @if(!empty(__('messages.about.gol.description')))
                        <h4 class="mt-4 mb-3">{{ __('messages.about.gol.title') }}</h4>
                        <p>{{ __('messages.about.gol.description') }}</p>
                    @endif

                    @if(!empty(__('messages.about.ect.description')))
                        <h4 class="mt-4 mb-3">{{ __('messages.about.ect.title') }}</h4>
                        <p>{{ __('messages.about.ect.description') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Image Section (Below Text) -->
        @php
            $mainImage = \App\Models\PageImage::getImage('about', 'main_image');
        @endphp
        @if($mainImage)
        <div class="row mb-5">
            <div class="col-lg-12 col-12">
                <img src="{{ Storage::url($mainImage->image_path) }}" alt="{{ $mainImage->alt_text ?? 'V U S' }}" class="img-fluid w-100" style="max-height: 500px; object-fit: cover; border-radius: 8px;">
            </div>
        </div>
        @endif

        <!-- Team Members Section (Below Image) -->
        <div class="row mb-5">
            <div class="col-lg-12 col-12">
                <h2 class="text-center mb-3">{{ __('messages.about.team.title') }}</h2>
                <p class="text-center mb-5">{{ __('messages.about.team.subtitle') }}</p>
                
                <div class="row justify-content-center">
                    @php
                        $teamMember1 = \App\Models\PageImage::getImage('about', 'team_member_1');
                        $teamMember2 = \App\Models\PageImage::getImage('about', 'team_member_2');
                        $teamMember3 = \App\Models\PageImage::getImage('about', 'team_member_3');
                    @endphp
                    
                    <!-- Team Member 1 -->
                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                        <div class="text-center">
                            <div class="mb-3 mx-auto" style="width: 200px; height: 250px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                @if($teamMember1 && $teamMember1->image_path)
                                    <img src="{{ Storage::url($teamMember1->image_path) }}" alt="{{ $teamMember1->alt_text ?? __('messages.about.team.member1.name') }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span style="color: #999; font-size: 14px;">{{ __('messages.about.team.member1.name') }}</span>
                                @endif
                            </div>
                            <h5 class="mb-2">{{ __('messages.about.team.member1.name') }}</h5>
                            <p class="mb-1"><strong>{{ __('messages.about.team.member1.title') }}</strong></p>
                            <p class="text-muted small">{{ __('messages.about.team.member1.role') }}</p>
                        </div>
                    </div>
                    
                    <!-- Team Member 2 -->
                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                        <div class="text-center">
                            <div class="mb-3 mx-auto" style="width: 200px; height: 250px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                @if($teamMember2 && $teamMember2->image_path)
                                    <img src="{{ Storage::url($teamMember2->image_path) }}" alt="{{ $teamMember2->alt_text ?? __('messages.about.team.member2.name') }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span style="color: #999; font-size: 14px;">{{ __('messages.about.team.member2.name') }}</span>
                                @endif
                            </div>
                            <h5 class="mb-2">{{ __('messages.about.team.member2.name') }}</h5>
                            <p class="mb-1"><strong>{{ __('messages.about.team.member2.title') }}</strong></p>
                            <p class="text-muted small">{{ __('messages.about.team.member2.role') }}</p>
                        </div>
                    </div>
                    
                    <!-- Team Member 3 -->
                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                        <div class="text-center">
                            <div class="mb-3 mx-auto" style="width: 200px; height: 250px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                @if($teamMember3 && $teamMember3->image_path)
                                    <img src="{{ Storage::url($teamMember3->image_path) }}" alt="{{ $teamMember3->alt_text ?? __('messages.about.team.member3.name') }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span style="color: #999; font-size: 14px;">{{ __('messages.about.team.member3.name') }}</span>
                                @endif
                            </div>
                            <h5 class="mb-2">{{ __('messages.about.team.member3.name') }}</h5>
                            <p class="mb-1"><strong>{{ __('messages.about.team.member3.title') }}</strong></p>
                            <p class="text-muted small">{{ __('messages.about.team.member3.role') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="row mt-5">
            <div class="col-lg-12 col-12 text-center">
                <h3 class="mb-4">{{ __('messages.about.cta.title') }}</h3>
                <a href="{{ route('contact') }}" class="custom-btn btn btn-lg">{{ __('messages.about.cta.button') }}</a>
            </div>
        </div>
    </div>
</section>
@endsection
