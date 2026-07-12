<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller {
	public function me() {
		return response()->json([
			'authenticated' => auth()->guard()->check(),
			'user' => auth()->guard()->user()
		]);
	}

	public function show(User $user) {
		return response()->json([
			'id' => $user->id,
			'name' => $user->name,
			'display_name' => $user->display_name,
			'created_at' => $user->created_at,
			'updated_at' => $user->updated_at,
			'post_count' => $user->posts()->count(),
		]);
	}

	public function register(Request $request) {
		// Validate the registration data.
		// If the validation fails, Laravel won't move past this line.
		$request->merge([
			'name' => $request->name,
			'email' => strtolower($request->email),
		]);

		$data = $request->validate([
			// Rule::unique(table, column) says that this must be unique, no duplicates.
			// https://api.laravel.com/docs/12.x/Illuminate/Validation/Rule.html#method_unique
			// length must be 3-30 characters, contain letters, numbers, hyphens, and underscores
			'name' => [
				'required',
				'min:3',
				'max:30',
				'regex:/^[a-z0-9_-]+$/',
				Rule::unique('users', 'name')
			],
			// not unique unlike 'name', regex at the end prevents weird unicode abuse
			'display_name' => [
				'required',
				'min:1',
				'max:50',
				'regex:/^[\pL\pN\s._-]+$/u',
				'string'
			],
			// must look like an email
			'email' => [
				'required',
				'email',
				Rule::unique('users', 'email')
			],
			// length must be 8-200 characters
			'password' => [
				'required',
				'min:8',
				'max:200'
			]
		], [
			'name.regex' =>
				'Username can only contain lowercase letters, numbers, hyphens, and underscores.'
		]);

		// A Model is like a way of mapping out a data for an item, Laravel already did this for you
		// so you get the data that was typed by the user before, and store it in your database.
		$user = User::create($data);

		// Log the user in and return a response.
		// https://laravel.com/docs/12.x/responses
		auth()->guard()->login($user);
		// regenerate session idk lol
		$request->session()->regenerate();
		return response()->json([
			'user' => $user,
			'authenticated' => true
		], 201);
	}

	public function login(Request $request) {
		$credentials = $request->validate([
			'login-name' => 'required',
			'login-password' => 'required'
		]);

		$success = auth()->guard()->attempt([
			'name' => $credentials['login-name'],
			'password' => $credentials['login-password']
		]);
		// Check if name and password is in the database.
		// https://api.laravel.com/docs/12.x/Illuminate/Contracts/Auth/StatefulGuard.html#method_attempt
		if (!$success) {
			return response()->json([
				'message' => 'Invalid credentials',
				'authenticated' => false
			], 422);
		}

		// Create a new session ID for the user.
		// https://laravel.com/docs/12.x/session#regenerating-the-session-id
		// https://api.laravel.com/docs/12.x/Illuminate/Contracts/Session/Session.html#method_regenerate
		$request->session()->regenerate();

		return response()->json([
			'user' => auth()->guard()->user(),
			'authenticated' => true
		]);
	}

	public function logout(Request $request) {
		// log this user out
		auth()->guard()->logout();
		$request->session()->invalidate();
		$request->session()->regenerateToken();
		return response()->json(['authenticated' => false]);
	}
}