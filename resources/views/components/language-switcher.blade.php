@php
    $currentLocale = app()->getLocale() ?? 'sv';
    $isEnglish = $currentLocale === 'en';
    $isSwedish = $currentLocale === 'sv';
@endphp

<div class="language-switcher-container" style="position: relative; display: inline-block;">
    <div class="language-switcher" style="display: flex; border-radius: 50px; overflow: hidden; border: 1px solid #e3e3e0; background: white; width: 110px; height: 36px; cursor: pointer; position: relative; box-shadow: 0px 0px 1px 0px rgba(0,0,0,0.03), 0px 1px 2px 0px rgba(0,0,0,0.06);">
        <a href="{{ route('language.switch', 'sv') }}"
           class="language-option {{ $isSwedish ? 'active' : '' }}"
           style="flex: 1; display: flex; align-items: center; justify-content: center; text-decoration: none; color: {{ $isSwedish ? '#fff' : '#706f6c' }}; background: {{ $isSwedish ? '#1b1b18' : 'transparent' }}; transition: all 0.3s ease; position: relative; z-index: 2; font-size: 13px; font-weight: 500;">
            <span style="display: flex; align-items: center; gap: 5px;">
                <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg" style="flex-shrink: 0;">
                    <!-- Swedish blue background -->
                    <rect width="16" height="12" fill="#006AA7"/>
                    
                    <!-- Yellow Nordic cross - horizontal bar (centered vertically) -->
                    <rect x="0" y="5" width="16" height="2" fill="#FECC00"/>
                    
                    <!-- Yellow Nordic cross - vertical bar (offset to the left, approximately 5/16 from left) -->
                    <rect x="5" y="0" width="2" height="12" fill="#FECC00"/>
                </svg>
                <span>SV</span>
            </span>
        </a>
        <a href="{{ route('language.switch', 'en') }}"
           class="language-option {{ $isEnglish ? 'active' : '' }}"
           style="flex: 1; display: flex; align-items: center; justify-content: center; text-decoration: none; color: {{ $isEnglish ? '#fff' : '#706f6c' }}; background: {{ $isEnglish ? '#1b1b18' : 'transparent' }}; transition: all 0.3s ease; position: relative; z-index: 2; font-size: 13px; font-weight: 500;">
            <span style="display: flex; align-items: center; gap: 5px;">
                <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg" style="flex-shrink: 0;">
                    <!-- Navy blue background -->
                    <rect width="16" height="12" fill="#012169"/>
                    
                    <!-- White diagonal crosses (St. Andrew's - wide) -->
                    <path d="M0 0L16 12" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                    <path d="M16 0L0 12" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                    
                    <!-- Red diagonal crosses (St. Patrick's - narrower, centered on white) -->
                    <path d="M0 0L16 12" stroke="#C8102E" stroke-width="1.2" stroke-linecap="round"/>
                    <path d="M16 0L0 12" stroke="#C8102E" stroke-width="1.2" stroke-linecap="round"/>
                    
                    <!-- White border for horizontal red cross (St. George's) -->
                    <rect x="0" y="4.5" width="16" height="3" fill="white"/>
                    
                    <!-- Red horizontal cross (St. George's) -->
                    <rect x="0" y="5.25" width="16" height="1.5" fill="#C8102E"/>
                    
                    <!-- White border for vertical red cross (St. George's) -->
                    <rect x="6.5" y="0" width="3" height="12" fill="white"/>
                    
                    <!-- Red vertical cross (St. George's) -->
                    <rect x="7.25" y="0" width="1.5" height="12" fill="#C8102E"/>
                </svg>
                <span>EN</span>
            </span>
        </a>
    </div>
</div>

<style>
    .language-switcher:hover {
        border-color: #19140035;
    }

    .language-option:hover {
        opacity: 0.9;
    }

    .language-option.active {
        font-weight: 600;
    }

    /* Flag Base Styles */
    .flag {
        width: 20px;
        height: 14px;
        display: inline-block;
        position: relative;
        border-radius: 2px;
        overflow: hidden;
        flex-shrink: 0;
        border: 0.5px solid rgba(0,0,0,0.1);
        box-shadow: 0 0 1px rgba(0,0,0,0.1);
    }

    /* Swedish Flag - Blue with yellow Nordic cross */
    .flag-sweden {
        background: #006AA7; /* Swedish blue */
    }

    /* Horizontal bar of the cross */
    .flag-sweden::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        height: 3px;
        background: #FECC00; /* Swedish yellow */
        transform: translateY(-50%);
        box-shadow: 0 0 0 0.5px rgba(0,0,0,0.05);
    }

    /* Vertical bar of the cross (offset to the left) */
    .flag-sweden::after {
        content: '';
        position: absolute;
        left: 5px; /* Nordic cross offset - approximately 5/16 from left */
        top: 0;
        width: 3px;
        height: 100%;
        background: #FECC00; /* Swedish yellow */
        transform: translateX(-50%);
        box-shadow: 0 0 0 0.5px rgba(0,0,0,0.05);
    }

    /* UK/English Flag - Union Jack */
    .flag-uk {
        background: #012169; /* Navy blue background */
        position: relative;
    }

    /* Layer 1: White diagonal cross (St. Andrew's - wide) and Red cross with white borders (St. George's) */
    .flag-uk::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background:
            /* White diagonal cross - top-left to bottom-right (wide) */
            linear-gradient(
                45deg,
                transparent 42%,
                #FFFFFF 45%,
                #FFFFFF 55%,
                transparent 58%
            ),
            /* White diagonal cross - top-right to bottom-left (wide) */
            linear-gradient(
                -45deg,
                transparent 42%,
                #FFFFFF 45%,
                #FFFFFF 55%,
                transparent 58%
            ),
            /* White border for horizontal red cross */
            linear-gradient(
                to bottom,
                transparent 0%,
                transparent 35%,
                #FFFFFF 35%,
                #FFFFFF 40%,
                transparent 40%,
                transparent 60%,
                #FFFFFF 60%,
                #FFFFFF 65%,
                transparent 65%,
                transparent 100%
            ),
            /* White border for vertical red cross */
            linear-gradient(
                to right,
                transparent 0%,
                transparent 35%,
                #FFFFFF 35%,
                #FFFFFF 40%,
                transparent 40%,
                transparent 60%,
                #FFFFFF 60%,
                #FFFFFF 65%,
                transparent 65%,
                transparent 100%
            ),
            /* Red horizontal cross (St. George's) */
            linear-gradient(
                to bottom,
                transparent 0%,
                transparent 37.5%,
                #C8102E 37.5%,
                #C8102E 62.5%,
                transparent 62.5%,
                transparent 100%
            ),
            /* Red vertical cross (St. George's) */
            linear-gradient(
                to right,
                transparent 0%,
                transparent 37.5%,
                #C8102E 37.5%,
                #C8102E 62.5%,
                transparent 62.5%,
                transparent 100%
            );
    }

    /* Layer 2: Red diagonal cross (St. Patrick's - narrower, on top of white) */
    .flag-uk::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background:
            /* Red diagonal cross - top-left to bottom-right (narrow) */
            linear-gradient(
                45deg,
                transparent 47%,
                #C8102E 48%,
                #C8102E 52%,
                transparent 53%
            ),
            /* Red diagonal cross - top-right to bottom-left (narrow) */
            linear-gradient(
                -45deg,
                transparent 47%,
                #C8102E 48%,
                #C8102E 52%,
                transparent 53%
            );
    }
</style>

