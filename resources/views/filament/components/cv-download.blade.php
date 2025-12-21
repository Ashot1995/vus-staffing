@php
    use Illuminate\Support\Facades\Storage;
    
    // Get the record directly to access the cv_path field
    $record = $getRecord();
    $cvPath = $record ? $record->cv_path : null;
    
    // Handle array format if FileUpload returns array
    if (is_array($cvPath)) {
        $cvPath = !empty($cvPath) ? $cvPath[0] : null;
    }
    
    // Try multiple methods to get the URL
    $url = null;
    $fileName = null;
    
    if ($cvPath && is_string($cvPath)) {
        // First try Storage::url (most reliable)
        if (Storage::disk('public')->exists($cvPath)) {
            $url = Storage::disk('public')->url($cvPath);
        } else {
            // Fallback to asset() method
            $url = asset('storage/' . $cvPath);
        }
        $fileName = basename($cvPath);
    }
@endphp

@if($url && $fileName)
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ $url }}" target="_blank" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Download CV: {{ $fileName }}
        </a>
        <a href="{{ $url }}" target="_blank" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            View CV
        </a>
    </div>
@else
    <p class="text-sm text-gray-500">No CV uploaded</p>
@endif

