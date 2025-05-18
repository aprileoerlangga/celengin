<?php

use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route publik untuk aplikasi mobile
Route::post('/mobile/register', [MobileAuthController::class, 'register']);
Route::post('/mobile/login', [MobileAuthController::class, 'login']);

// Route terproteksi untuk aplikasi mobile
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/mobile/logout', [MobileAuthController::class, 'logout']);
    Route::get('/mobile/profile', [MobileAuthController::class, 'profile']);
});


// Route publik
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route terproteksi (memerlukan token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    
    // Mengarahkan ke ApiService yang sudah dibuat (Income, Outcome)
    Route::prefix('income')->group(function () {
        Route::get('/', \App\Filament\Resources\IncomeResource\Api\Handlers\PaginationHandler::class);
        Route::get('/{id}', \App\Filament\Resources\IncomeResource\Api\Handlers\DetailHandler::class);
        Route::post('/', \App\Filament\Resources\IncomeResource\Api\Handlers\CreateHandler::class);
        Route::put('/{id}', \App\Filament\Resources\IncomeResource\Api\Handlers\UpdateHandler::class);
        Route::delete('/{id}', \App\Filament\Resources\IncomeResource\Api\Handlers\DeleteHandler::class);
    });
    
    Route::prefix('outcome')->group(function () {
        Route::get('/', \App\Filament\Resources\OutcomeResource\Api\Handlers\PaginationHandler::class);
        Route::get('/{id}', \App\Filament\Resources\OutcomeResource\Api\Handlers\DetailHandler::class);
        Route::post('/', \App\Filament\Resources\OutcomeResource\Api\Handlers\CreateHandler::class);
        Route::put('/{id}', \App\Filament\Resources\OutcomeResource\Api\Handlers\UpdateHandler::class);
        Route::delete('/{id}', \App\Filament\Resources\OutcomeResource\Api\Handlers\DeleteHandler::class);
    });
    
    Route::prefix('pengguna')->group(function () {
        Route::get('/', \App\Filament\Resources\PenggunaResource\Api\Handlers\PaginationHandler::class);
        Route::get('/{id}', \App\Filament\Resources\PenggunaResource\Api\Handlers\DetailHandler::class);
        Route::post('/', \App\Filament\Resources\PenggunaResource\Api\Handlers\CreateHandler::class);
        Route::put('/{id}', \App\Filament\Resources\PenggunaResource\Api\Handlers\UpdateHandler::class);
        Route::delete('/{id}', \App\Filament\Resources\PenggunaResource\Api\Handlers\DeleteHandler::class);
    });
});