@php
    // Get the record directly to access the personal_image_path field
    $record = $getRecord();
    $imagePath = $record ? $record->personal_image_path : null;
    
    // Handle array format if FileUpload returns array
    if (is_array($imagePath)) {
        $imagePath = !empty($imagePath) ? $imagePath[0] : null;
    }
    
    $url = $imagePath && is_string($imagePath) ? asset('storage/' . $imagePath) : null;
@endphp

@if($url)
    <div class="p-4 bg-gray-50 rounded-lg">
        <img src="{{ $url }}" 
             alt="Personal Image" 
             class="max-w-xs h-auto rounded-lg shadow-sm">
    </div>
@else
    <p class="text-sm text-gray-500">No personal image uploaded</p>
@endif

