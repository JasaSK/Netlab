<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return response()->json([
        'message' => 'Email berhasil diverifikasi.'
    ]);
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');
Route::post('/email/resend', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return response()->json(['message' => 'Email sudah diverifikasi.']);
    }

    $request->user()->sendEmailVerificationNotification();

    return response()->json(['message' => 'Link verifikasi dikirim ulang.']);
})->middleware(['auth:sanctum'])->name('verification.send');


use App\Http\Controllers\AuthController;
// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Authenticated user actions
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user/get', [AuthController::class, 'getUser']);
    Route::patch('/user/update', [AuthController::class, 'updateUser']);
    Route::delete('/user/destroy', [AuthController::class, 'deleteUser']);

    // Admin-only route
    Route::get('/admin-only', function () {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }
        return response()->json(['message' => 'Selamat datang, Admin!']);
    });
});


use App\Http\Controllers\CategoryController;

Route::get('/category', [CategoryController::class, 'index']);
Route::post('/category/create', [CategoryController::class, 'store']);
Route::patch('/category/update/{id}', [CategoryController::class, 'update']);
Route::delete('/category/delete/{id}', [CategoryController::class, 'destroy']);
Route::get('/category/show/{id}', [CategoryController::class, 'show']);


use App\Http\Controllers\MatkulCategoryController;

Route::get('matkul-categories', [MatkulCategoryController::class, 'index']);
Route::post('matkul-categories/create', [MatkulCategoryController::class, 'store']);
Route::get('matkul-categories/show/{id}', [MatkulCategoryController::class, 'show']);
Route::patch('matkul-categories/update/{id}', [MatkulCategoryController::class, 'update']);
Route::delete('matkul-categories/destroy/{id}', [MatkulCategoryController::class, 'destroy']);


use App\Http\Controllers\EquipmentController;

Route::get('equipment', [EquipmentController::class, 'index']);
Route::post('equipment/create', [EquipmentController::class, 'store']);
Route::get('equipment/show/{id}', [EquipmentController::class, 'show']);
Route::put('equipment/update/{id}', [EquipmentController::class, 'update']);
Route::delete('equipment/destroy/{id}', [EquipmentController::class, 'destroy']);


use App\Http\Controllers\MateriMatkulController;

Route::get('materi-matkul', [MateriMatkulController::class, 'index']);
Route::post('materi-matkul/create', [MateriMatkulController::class, 'store']);
Route::get('materi-matkul/show/{id}', [MateriMatkulController::class, 'show']);
Route::put('materi-matkul/update/{id}', [MateriMatkulController::class, 'update']);
Route::delete('materi-matkul/destroy/{id}', [MateriMatkulController::class, 'destroy']);

use App\Http\Controllers\PendaftaranController;

Route::get('pendaftaran', [PendaftaranController::class, 'index']);
Route::post('pendaftaran/create', [PendaftaranController::class, 'store']);
Route::get('pendaftaran/show/{id}', [PendaftaranController::class, 'show']);
Route::put('pendaftaran/update/{id}', [PendaftaranController::class, 'update']);
Route::delete('pendaftaran/destroy/{id}', [PendaftaranController::class, 'destroy']);

use App\Http\Controllers\AboutController;

Route::get('abouts', [AboutController::class, 'index']);
Route::post('abouts/create', [AboutController::class, 'store']);
Route::get('abouts/show/{id}', [AboutController::class, 'show']);
Route::put('abouts/update/{id}', [AboutController::class, 'update']);
Route::delete('abouts/destroy/{id}', [AboutController::class, 'destroy']);
