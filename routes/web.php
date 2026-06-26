<?php

use App\Http\Controllers\TwoFactorAccountController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->prefix('authenticator')->name('two-factor.')->group(function () {
    Route::get('/', [TwoFactorAccountController::class, 'index'])->name('index');
    Route::get('/create', [TwoFactorAccountController::class, 'create'])->name('create');
    Route::post('/', [TwoFactorAccountController::class, 'store'])->name('store');
    Route::get('/{account}/code', [TwoFactorAccountController::class, 'getCode'])->name('code');
    Route::delete('/{account}', [TwoFactorAccountController::class, 'destroy'])->name('destroy');
    Route::post('/export', [TwoFactorAccountController::class, 'export'])->name('export');
    Route::post('/import', [TwoFactorAccountController::class, 'import'])->name('import');
});

// Production migration route — run once after deploy
Route::get('/run-migrations', function () {
    Artisan::call('migrate', ['--force' => true]);
    return response()->json([
        'status' => 'ok',
        'output' => Artisan::output(),
    ]);
})->middleware('auth');

require __DIR__.'/auth.php';
