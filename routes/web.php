<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

// (TBH, this file looks garbled.)
// routes/web.php is like the URL map of your entire application.
// It tells Laravel "when user visits this URL, run this code".

Route::middleware('web')->group(function() {
	// This uses a controller, read more about it here: https://laravel.com/docs/12.x/controllers	
	// Instead of passing a function as a second argument, you can pass an array containing:
	// [Controller::class, 'methodName']
	// This makes it reusable, instead of writing multiple inline functions doing the same thing.
	Route::controller(UserController::class)->group(function () {
		// user-related
		Route::post('/login', 'login');
		Route::post('/register', 'register');
		Route::post('/logout', 'logout');
		Route::get('/me', 'me');
	});

	Route::middleware('auth')->group(function () {
		// post-related
		Route::post('/api/posts', [PostController::class, 'store']);
		Route::put('/api/posts/{post}', [PostController::class, 'update']);
		Route::delete('/api/posts/{post}', [PostController::class, 'destroy']);
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
