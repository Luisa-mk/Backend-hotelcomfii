<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoomController;

// Registro y login públicos
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas por token
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

//con estos edito el catalogo de usuarios
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/usuarios', [UserController::class, 'index']);
    Route::post('/usuarios', [UserController::class, 'store']);
    Route::put('/usuarios/{id}', [UserController::class, 'update']);
    Route::delete('/usuarios/{id}', [UserController::class, 'destroy']);
});

//habitaciones
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/habitaciones', [RoomController::class, 'index']);
    Route::post('/habitaciones', [RoomController::class, 'store']);
    Route::put('/habitaciones/{id}', [RoomController::class, 'update']);
    Route::delete('/habitaciones/{id}', [RoomController::class, 'destroy']);
});