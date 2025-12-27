<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/language/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch');

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/om-oss', [PageController::class, 'about'])->name('about');
Route::get('/for-arbetsgivare', [PageController::class, 'forEmployers'])->name('for-employers');

Route::get('/jobb', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobb/{job}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/spontanansok', [JobController::class, 'spontaneous'])->name('jobs.apply-spontaneous');

Route::get('/kontakt', [ContactController::class, 'index'])->name('contact');
Route::post('/kontakt', [ContactController::class, 'send'])->name('contact.send');

Route::post('/cookie/consent', [\App\Http\Controllers\CookieController::class, 'consent'])->name('cookie.consent');
Route::post('/newsletter/subscribe', [\App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/privacy', [\App\Http\Controllers\PageController::class, 'privacy'])->name('privacy');

Route::get('/profile/reminder/confirm/{user}', [ProfileController::class, 'confirmReminder'])->name('profile.reminder.confirm');

Route::get('/dashboard', [PageController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile/applications', [ProfileController::class, 'applications'])->name('profile.applications');
    Route::get('/profile/applications/{id}/edit', [ProfileController::class, 'editApplication'])->name('profile.applications.edit');
    Route::patch('/profile/applications/{id}', [ProfileController::class, 'updateApplication'])->name('profile.applications.update');
    
    Route::get('/jobb/{job}/ansok', [JobController::class, 'apply'])->name('jobs.apply');
    Route::post('/jobb/{job}/ansok', [JobController::class, 'submitApplication'])->name('jobs.submit-application');
    Route::post('/spontanansok', [JobController::class, 'submitSpontaneous'])->name('jobs.submit-spontaneous');
});

require __DIR__.'/auth.php';
