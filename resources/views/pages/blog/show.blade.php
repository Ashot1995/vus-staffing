@extends('layouts.main')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', ($post->meta_title ?: $post->title) . ' - VUS')

@if($post->meta_description)
@section('description', $post->meta_description)
@endif

@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-12 mx-auto">
                <article class="bg-white shadow-lg p-4 p-lg-5">
                    @if($post->featured_image)
                        <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="img-fluid w-100 mb-4" style="border-radius: 8px;">
                    @endif

                    <h1 class="mb-3">{{ $post->title }}</h1>

                    <div class="mb-4 text-muted">
                        <i class="bi-calendar me-2"></i>
                        <span>{{ $post->published_at ? $post->published_at->format('F d, Y') : $post->created_at->format('F d, Y') }}</span>
                    </div>

                    @if($post->excerpt)
                        <div class="lead mb-4 p-3 bg-light" style="border-left: 4px solid #FF6B35;">
                            {{ $post->excerpt }}
                        </div>
                    @endif

                    <div class="blog-content">
                        {!! $post->content !!}
                    </div>

                    <div class="mt-5 pt-4 border-top">
                        <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary">
                            <i class="bi-arrow-left me-2"></i>{{ __('messages.blog.back_to_blog') }}
                        </a>
                    </div>
                </article>

                @if($recentPosts->count() > 0)
                    <div class="mt-5">
                        <h3 class="mb-4">{{ __('messages.blog.recent_posts') }}</h3>
                        <div class="row">
                            @foreach($recentPosts as $recentPost)
                                <div class="col-lg-4 col-md-6 col-12 mb-4">
                                    <div class="custom-block bg-white shadow-lg h-100">
                                        <a href="{{ route('blog.show', $recentPost->slug) }}" class="text-decoration-none">
                                            @if($recentPost->featured_image)
                                                <img src="{{ Storage::url($recentPost->featured_image) }}" alt="{{ $recentPost->title }}" class="img-fluid w-100 mb-3" style="height: 200px; object-fit: cover; border-radius: 8px;">
                                            @endif
                                            <div class="custom-block-body">
                                                <h6 class="mb-2">{{ $recentPost->title }}</h6>
                                                <p class="text-muted small mb-0">
                                                    <i class="bi-calendar me-2"></i>
                                                    {{ $recentPost->published_at ? $recentPost->published_at->format('M d, Y') : $recentPost->created_at->format('M d, Y') }}
                                                </p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .blog-content {
        line-height: 1.8;
        color: #333;
    }
    .blog-content h2 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #000;
    }
    .blog-content h3 {
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        color: #000;
    }
    .blog-content p {
        margin-bottom: 1.5rem;
    }
    .blog-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1.5rem 0;
    }
    .blog-content ul, .blog-content ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }
    .blog-content li {
        margin-bottom: 0.5rem;
    }
    .blog-content blockquote {
        border-left: 4px solid #FF6B35;
        padding-left: 1.5rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #666;
    }
    .blog-content a {
        color: #FF6B35;
        text-decoration: underline;
    }
    .blog-content a:hover {
        color: #000;
    }
</style>
@endpush
@endsection

