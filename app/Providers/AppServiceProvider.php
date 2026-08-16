<?php

namespace App\Providers;

use App\Models\Post;
use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
	/**
	 * Register any application services.
	 */
	public function register(): void {
		//
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void {
		Gate::policy(Post::class, PostPolicy::class);

		RateLimiter::for('login', function (Request $request) {
			$name = strtolower((string) $request->input('login-name'));

			return [
				Limit::perMinute(5)->by($request->ip() . '|' . $name),
				Limit::perMinute(20)->by($request->ip())
			];
		});

		RateLimiter::for('register', function (Request $request) {
			return Limit::perMinute(3)->by($request->ip());
		});

		RateLimiter::for('posts', function (Request $request) {
			return Limit::perMinute(30)->by($request->user()?->id ?? $request->ip());
		});
	}
}
