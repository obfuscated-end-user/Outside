<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post}', [PostController::class, 'show']);

Route::get('/users/{user:name}', [UserController::class, 'show']);
Route::get('/users/{user:name}/posts', [PostController::class, 'userPosts']);
Route::get('/users/{user:name}/profile', [UserController::class, 'profile']);
