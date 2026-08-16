<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

// (TBH, this file looks garbled.)
// routes/web.php is like the URL map of your entire application.
// It tells Laravel "when user visits this URL, run this code".

Route::middleware('web')->group(function() {
	// This uses a controller, read more about it here: https://laravel.com/docs/12.x/controllers	
	// Instead of passing a function as a second argument, you can pass an array containing:
	// [Controller::class, 'methodName']
	// This makes it reusable, instead of writing multiple inline functions doing the same thing.
	Route::post('/login', [UserController::class, 'login'])->middleware('throttle:login');
	Route::post('/register', [UserController::class, 'register'])->middleware('throttle:register');
	Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');
	Route::get('/me', [UserController::class, 'me']);

	// maybe important later
	Route::get('/csrf-cookie', function (Request $request) {
		return response()->json([
			'csrf_token' => csrf_token(),
		]);
	});

	Route::middleware('auth')->group(function () {
		Route::post('/api/posts', [PostController::class, 'store'])->middleware('throttle:posts');
		Route::put('/api/posts/{post}', [PostController::class, 'update']) ->middleware('throttle:posts');
		Route::delete('/api/posts/{post}', [PostController::class, 'destroy']) ->middleware('throttle:posts');
	});
});

// DEBUG!
// http://127.0.0.1:8000/test-auth
Route::get('/test-auth', function () {
	return response()->json([
		'check' => auth()->guard()->check(),
		'user' => auth()->guard()->user(),
		'id' => auth()->guard()->id()
	]);
});

// THIS NEEDS TO BE LAST?
Route::view('/{any}','app')->where('any','^(?!api).*$');
