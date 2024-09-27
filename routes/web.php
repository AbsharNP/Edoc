<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;



Route::get('/{any}' , [AppController::class, 'index'])->where('any', '.*');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::post('/add-doctor', [AdminController::class, 'docStore']);
Route::delete('/doc/{id}', [AdminController::class, 'docDestroy']);
Route::post('/doctors', [AdminController::class, 'docIndex']);
Route::post('/doctor', [AdminController::class, 'docIndex']);



Route::post('/add-department', [AdminController::class, 'depStore']);
Route::post('/departments', [AdminController::class, 'depIndex']);
Route::delete('/depdlt/{id}', [AdminController::class, 'depDestroy']);


 


