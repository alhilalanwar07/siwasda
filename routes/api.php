<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('/login-level1', [AuthController::class, 'loginLevel1']);

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::get('/profil-agen', [AuthController::class, 'profilAgen']);
    Route::get('/pengumuman', [AuthController::class, 'listPengumuman']);
    Route::get('/kategori-kejadian', [AuthController::class, 'listKategoriKejadian']);
    Route::get('/list-desa', [AuthController::class, 'listDesa']);
    Route::post('/kirim-laporan', [AuthController::class, 'kirimLaporan']);
    Route::get('/list-laporan', [AuthController::class, 'listLaporan']);
});