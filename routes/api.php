<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;

/* Route::controller(UserController::class)->group(function () {
	Route::post('/register', 'register');
	Route::post('/login', 'login');
	Route::post('/logout', 'logout');
	Route::get('/me', 'me');
}); */

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post}', [PostController::class, 'show']);

Route::get('/users/{user:name}', [UserController::class, 'show']);
Route::get('/users/{user:name}/posts', [PostController::class, 'userPosts']);

/* Route::middleware('auth:web')->group(function () {
	Route::post('/posts', [PostController::class, 'store']);
	Route::put('/posts/{post}', [PostController::class, 'update']);
	Route::delete('/posts/{post}', [PostController::class, 'destroy']);
}); */