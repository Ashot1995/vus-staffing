@php
    $currentLocale = app()->getLocale();
    $isEnglish = $currentLocale === 'en';
    $isSwedish = $currentLocale === 'sv';
@endphp

<div class="language-switcher-container" style="position: relative; display: inline-block;">
    <div class="language-switcher" style="display: flex; border-radius: 50px; overflow: hidden; border: 1px solid #e3e3e0; background: white; width: 110px; height: 36px; cursor: pointer; position: relative; box-shadow: 0px 0px 1px 0px rgba(0,0,0,0.03), 0px 1px 2px 0px rgba(0,0,0,0.06);">
        <a href="{{ route('language.switch', 'en') }}" 
           class="language-option {{ $isEnglish ? 'active' : '' }}" 
           style="flex: 1; display: flex; align-items: center; justify-content: center; text-decoration: none; color: {{ $isEnglish ? '#fff' : '#706f6c' }}; background: {{ $isEnglish ? '#1b1b18' : 'transparent' }}; transition: all 0.3s ease; position: relative; z-index: 2; font-size: 13px; font-weight: 500;">
            <span style="display: flex; align-items: center; gap: 5px;">
                <span style="font-size: 16px; line-height: 1;">🇬🇧</span>
                <span>EN</span>
            </span>
        </a>
        <a href="{{ route('language.switch', 'sv') }}" 
           class="language-option {{ $isSwedish ? 'active' : '' }}" 
           style="flex: 1; display: flex; align-items: center; justify-content: center; text-decoration: none; color: {{ $isSwedish ? '#fff' : '#706f6c' }}; background: {{ $isSwedish ? '#1b1b18' : 'transparent' }}; transition: all 0.3s ease; position: relative; z-index: 2; font-size: 13px; font-weight: 500;">
            <span style="display: flex; align-items: center; gap: 5px;">
                <span style="font-size: 16px; line-height: 1;">🇸🇪</span>
                <span>SV</span>
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
</style>

