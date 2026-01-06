<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\AdminController;

// Home page
Route::get('/', function () {
    return redirect()->route('survey.step1');
})->name('home');

// Anonymous Survey Routes
Route::prefix('survey')->name('survey.')->group(function () {
    // Step 1 - Company Information
    Route::get('/step1', [SurveyController::class, 'showStep1'])->name('step1');
    Route::post('/step1', [SurveyController::class, 'processStep1'])->name('step1.process');
    
    // Step 2 - Skills Selection
    Route::get('/step2', [SurveyController::class, 'showStep2'])->name('step2');
    Route::post('/step2', [SurveyController::class, 'processStep2'])->name('step2.process');
    
    // Step 3 - Skills Details
    Route::get('/step3', [SurveyController::class, 'showStep3'])->name('step3');
    Route::post('/step3', [SurveyController::class, 'processStep3'])->name('step3.process');
    
    // Success page
    Route::get('/success', [SurveyController::class, 'success'])->name('success');
    
    // AJAX endpoints
    Route::get('/districts', [SurveyController::class, 'getDistricts'])->name('districts');
});

// Open Admin Routes - hech qanday himoya yo'q
Route::prefix('muxabbat')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/responses', [AdminController::class, 'responses'])->name('responses');
    Route::get('/responses/{id}', [AdminController::class, 'showResponse'])->name('responses.show');
    Route::get('/export', [AdminController::class, 'export'])->name('export');
    Route::get('/skills-statistics', [AdminController::class, 'skillsStatistics'])->name('skills.statistics');
    Route::get('/districts', [AdminController::class, 'getDistrictsForAdmin'])->name('districts');
    
    // Qo'shimcha route'lar
    Route::get('/skills/{skillId}/detail', [AdminController::class, 'skillDetail'])->name('skill.detail');
    Route::get('/skills/export', [AdminController::class, 'exportSkillsStatistics'])->name('skills.export');
    Route::post('/cache/clear', [AdminController::class, 'clearCache'])->name('cache.clear');
});

