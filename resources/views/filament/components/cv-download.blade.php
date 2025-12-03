@php
    // Get the record directly to access the cv_path field
    $record = $getRecord();
    $cvPath = $record ? $record->cv_path : null;
    
    // Handle array format if FileUpload returns array
    if (is_array($cvPath)) {
        $cvPath = !empty($cvPath) ? $cvPath[0] : null;
    }
    
    $url = $cvPath && is_string($cvPath) ? asset('storage/' . $cvPath) : null;
    $fileName = $cvPath && is_string($cvPath) ? basename($cvPath) : null;
@endphp

@if($url && $fileName)
    <div class="flex items-center gap-2">
        <a href="{{ $url }}" target="_blank" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Download CV: {{ $fileName }}
        </a>
    </div>
@else
    <p class="text-sm text-gray-500">No CV uploaded</p>
@endif

