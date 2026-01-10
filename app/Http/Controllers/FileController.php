<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;

class FileController extends Controller
{
    /**
     * Download CV file from application
     */
    public function downloadCv($applicationId)
    {
        $application = Application::findOrFail($applicationId);
        
        // Check if user has permission (owner or admin)
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }
        
        // Allow access if user owns the application OR is an admin
        if ($user->id !== $application->user_id && !$user->is_admin) {
            abort(403, 'Unauthorized');
        }
        
        if (!$application->cv_path) {
            abort(404, 'CV not found');
        }
        
        $filePath = $application->cv_path;
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }
        
        return Storage::disk('public')->download($filePath, basename($filePath));
    }
    
    /**
     * View CV file from application
     */
    public function viewCv($applicationId)
    {
        $application = Application::findOrFail($applicationId);
        
        // Check if user has permission (owner or admin)
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }
        
        // Allow access if user owns the application OR is an admin
        if ($user->id !== $application->user_id && !$user->is_admin) {
            abort(403, 'Unauthorized');
        }
        
        if (!$application->cv_path) {
            abort(404, 'CV not found');
        }
        
        $filePath = $application->cv_path;
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }
        
        $file = Storage::disk('public')->get($filePath);
        $mimeType = Storage::disk('public')->mimeType($filePath);
        
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . basename($filePath) . '"');
    }
    
    /**
     * Download user CV
     */
    public function downloadUserCv()
    {
        $user = Auth::user();
        
        if (!$user->cv_path) {
            abort(404, 'CV not found');
        }
        
        $filePath = $user->cv_path;
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }
        
        return Storage::disk('public')->download($filePath, basename($filePath));
    }
    
    /**
     * View user CV
     */
    public function viewUserCv()
    {
        $user = Auth::user();
        
        if (!$user->cv_path) {
            abort(404, 'CV not found');
        }
        
        $filePath = $user->cv_path;
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }
        
        $file = Storage::disk('public')->get($filePath);
        $mimeType = Storage::disk('public')->mimeType($filePath);
        
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . basename($filePath) . '"');
    }
    
    /**
     * Download additional file from application
     */
    public function downloadAdditionalFile($applicationId, $index)
    {
        $application = Application::findOrFail($applicationId);
        
        // Check if user has permission (owner or admin)
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }
        
        // Allow access if user owns the application OR is an admin
        if ($user->id !== $application->user_id && !$user->is_admin) {
            abort(403, 'Unauthorized');
        }
        
        $additionalFiles = $application->additional_files ?? [];
        $index = (int)$index;
        
        if (!isset($additionalFiles[$index])) {
            abort(404, 'File not found');
        }
        
        $filePath = $additionalFiles[$index];
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }
        
        return Storage::disk('public')->download($filePath, basename($filePath));
    }
    
    /**
     * View additional file from application
     */
    public function viewAdditionalFile($applicationId, $index)
    {
        $application = Application::findOrFail($applicationId);
        
        // Check if user has permission (owner or admin)
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }
        
        // Allow access if user owns the application OR is an admin
        if ($user->id !== $application->user_id && !$user->is_admin) {
            abort(403, 'Unauthorized');
        }
        
        $additionalFiles = $application->additional_files ?? [];
        $index = (int)$index;
        
        if (!isset($additionalFiles[$index])) {
            abort(404, 'File not found');
        }
        
        $filePath = $additionalFiles[$index];
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }
        
        $file = Storage::disk('public')->get($filePath);
        $mimeType = Storage::disk('public')->mimeType($filePath);
        
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . basename($filePath) . '"');
    }
}

