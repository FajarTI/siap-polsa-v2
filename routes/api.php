<?php

use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\MataKuliahController;
use App\Http\Controllers\Api\RiwayatPendidikanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/mahasiswa', MahasiswaController::class);
Route::apiResource('/riwayat-pendidikan', RiwayatPendidikanController::class);
Route::apiResource('/mata-kuliah', MataKuliahController::class);