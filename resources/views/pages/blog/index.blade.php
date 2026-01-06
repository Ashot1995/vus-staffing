@extends('layouts.main')

@php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
@endphp

@section('title', __('messages.blog.title') . ' - VUS')

@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center mb-5">
                <h1>{{ __('messages.blog.title') }}</h1>
                <p class="lead">{{ __('messages.blog.subtitle') }}</p>
            </div>
        </div>

        <div class="row">
            @forelse($posts as $post)
                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <div class="custom-block bg-white shadow-lg h-100">
                        <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none">
                            @if($post->featured_image)
                                <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="img-fluid w-100 mb-3" style="height: 250px; object-fit: cover; border-radius: 8px;">
                            @else
                                <div class="bg-light mb-3" style="height: 250px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                    <i class="bi-image" style="font-size: 48px; color: #ccc;"></i>
                                </div>
                            @endif
                            
                            <div class="custom-block-body">
                                <h5 class="mb-2">{{ $post->title }}</h5>
                                <p class="text-muted small mb-3">
                                    <i class="bi-calendar me-2"></i>
                                    {{ $post->published_at ? $post->published_at->format('F d, Y') : $post->created_at->format('F d, Y') }}
                                </p>
                                @if($post->excerpt)
                                    <p class="mb-3">{{ Str::limit($post->excerpt, 150) }}</p>
                                @else
                                    <p class="mb-3">{{ Str::limit(strip_tags($post->content), 150) }}</p>
                                @endif
                                <span class="text-primary">{{ __('messages.blog.read_more') }} →</span>
                            </div>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-lg-12 col-12">
                    <div class="text-center py-5">
                        <p class="lead">{{ __('messages.blog.no_posts') }}</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($posts->hasPages())
            <div class="row mt-5">
                <div class="col-lg-12 col-12">
                    <nav aria-label="Blog pagination">
                        {{ $posts->links() }}
                    </nav>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

