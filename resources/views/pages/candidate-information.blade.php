@extends('layouts.main')

@php
use Illuminate\Support\Facades\Storage;
$locale = app()->getLocale();
@endphp

@section('title', __('messages.candidate_info.title') . ' - V U S')

@push('structured-data')
<x-breadcrumbs :items="[
    ['name' => __('messages.nav.home'), 'url' => route('home')],
    ['name' => __('messages.nav.candidate_information'), 'url' => route('candidate-information')]
]" />
@endpush

@push('styles')
<style>
    .candidate-info-faq-section {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 80px 0;
    }

    .candidate-info-faq-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .candidate-info-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #000;
        margin-bottom: 3rem;
        text-align: center;
    }

    .candidate-info-title .bold-text {
        font-weight: 700;
    }

    .faq-item {
        background: #ffffff;
        border-radius: 12px;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .faq-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    .faq-question {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 1.5rem;
        cursor: pointer;
        user-select: none;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0.1);
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        font-size: 1rem;
        color: #212529;
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
    }

    .faq-question:hover {
        background: #f8f9fa;
    }

    .faq-question.active {
        background: #f8f9fa;
    }

    .faq-question-text {
        flex: 1;
        margin-right: 1rem;
    }

    .faq-chevron {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .faq-item.active .faq-chevron {
        background: #000;
        transform: rotate(180deg);
    }

    .faq-item.active .faq-chevron i {
        color: #fff;
    }

    .faq-chevron i {
        font-size: 14px;
        color: #666;
        transition: color 0.3s ease;
    }

    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease, padding 0.4s ease;
        padding: 0 1.5rem;
    }

    .faq-item.active .faq-answer {
        max-height: 1000px;
        padding: 0 1.5rem 1.5rem 1.5rem;
    }

    .faq-answer-content {
        color: #666;
        line-height: 1.6;
        font-size: 0.95rem;
        padding-top: 0.5rem;
    }

    @media (max-width: 768px) {
        .candidate-info-faq-section {
            padding: 60px 0;
        }

        .candidate-info-title {
            font-size: 2rem;
            margin-bottom: 2rem;
        }

        .faq-question {
            padding: 1rem 1.25rem;
            font-size: 0.95rem;
        }

        .faq-answer {
            padding: 0 1.25rem;
        }

        .faq-item.active .faq-answer {
            padding: 0 1.25rem 1.25rem 1.25rem;
        }

        .faq-chevron {
            width: 28px;
            height: 28px;
        }
    }
</style>
@endpush

@section('content')
<section class="candidate-info-faq-section">
    <div class="candidate-info-faq-container">
        <h1 class="candidate-info-title">
            @php
                $titleParts = explode(' ', __('messages.candidate_info.title'));
                $lastWord = array_pop($titleParts);
                $firstPart = implode(' ', $titleParts);
            @endphp
            {{ $firstPart }} <span class="bold-text">{{ $lastWord }}</span>
        </h1>

        @if($items->count() > 0)
            <div class="faq-list">
                @foreach($items as $index => $item)
                    <div class="faq-item" data-index="{{ $index }}">
                        <button class="faq-question" type="button" aria-expanded="false">
                            <span class="faq-question-text">
                                {{ $locale === 'sv' ? $item->title_sv : $item->title_en }}
                            </span>
                            <span class="faq-chevron">
                                <i class="bi-chevron-down"></i>
                            </span>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-content">
                                @if($item->image)
                                    <div class="mb-3" style="max-width: 300px; margin: 0 auto 1rem;">
                                        <img src="{{ Storage::url($item->image) }}"
                                             alt="{{ $locale === 'sv' ? $item->title_sv : $item->title_en }}"
                                             class="img-fluid w-100"
                                             style="border-radius: 8px;">
                                    </div>
                                @endif
                                <p>{!! $locale === 'sv' ? $item->description_sv : $item->description_en !!}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center">
                <p>{{ __('messages.candidate_info.no_items.description') }}</p>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const faqItems = document.querySelectorAll('.faq-item');

        faqItems.forEach(function(item) {
            const question = item.querySelector('.faq-question');

            function toggleFAQ(e) {
                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                const isActive = item.classList.contains('active');

                // Close all other items (optional - remove if you want multiple open)
                faqItems.forEach(function(otherItem) {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                        const otherQuestion = otherItem.querySelector('.faq-question');
                        const otherAnswer = otherItem.querySelector('.faq-answer');
                        otherQuestion.setAttribute('aria-expanded', 'false');
                        otherQuestion.classList.remove('active');
                        otherAnswer.style.maxHeight = '0';
                    }
                });

                // Toggle current item
                if (isActive) {
                    item.classList.remove('active');
                    question.setAttribute('aria-expanded', 'false');
                    question.classList.remove('active');
                    const answer = item.querySelector('.faq-answer');
                    answer.style.maxHeight = '0';
                } else {
                    item.classList.add('active');
                    question.setAttribute('aria-expanded', 'true');
                    question.classList.add('active');
                    const answer = item.querySelector('.faq-answer');
                    answer.style.maxHeight = answer.scrollHeight + 'px';
                }
            }

            // Handle click
            question.addEventListener('click', toggleFAQ, false);

            // Handle touch for mobile/iOS
            question.addEventListener('touchend', function(e) {
                e.preventDefault();
                toggleFAQ(e);
            }, { passive: false });
        });
    });
</script>
@endpush
