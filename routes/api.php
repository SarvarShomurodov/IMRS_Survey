<?php
// routes/api.php (sizning mavjud faylingiz)

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['throttle:60,1'])->group(function () {
    // Skills search API
    Route::get('/skills/search', [ApiController::class, 'searchSkills']);
    Route::post('/skills/by-ids', [ApiController::class, 'getSkillsByIds']);
    
    // Debug route (remove in production)
    Route::get('/skills/test', [ApiController::class, 'testSearch']);
    
    // Individual skill by ID
    Route::get('/skills/{id}', [ApiController::class, 'getSkillById'])
        ->where('id', '[0-9]+'); // faqat raqamlar
    
    // Cache management (admin only in production)
    Route::post('/skills/clear-cache', [ApiController::class, 'clearSkillsCache']);
        // ->middleware('admin'); // admin middleware mavjud bo'lsa uncomment qiling
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');