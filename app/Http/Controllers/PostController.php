<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

// The PostController handles all post-related web requests, (create, edit, update, delete).
// TBD, some functions don't have comments describing what they do.
class PostController extends Controller {
	private function validatePost(Request $request): array {
		// Checks if 'body' is filled.
		// https://api.laravel.com/docs/12.x/Illuminate/Http/Request.html#method_validate
		$data = $request->validate(['body' => ['required', 'max:400']]);
		// Strip any potential HTML and PHP stuff the user might enter.
		// https://www.php.net/manual/en/function.strip-tags.php
		$data['body'] = strip_tags($data['body']);

		return $data;
	}

	// TBD
	private function authorizePost(Post $post): void {
		abort_if(auth()->guard()->id() !== $post->user_id, 403);
	}

	// Generate Post ID.
	private function generatePostId(): string {
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
		do {
			$id = '';
			for ($i = 0; $i < 15; $i++)
				$id .= $chars[random_int(0, strlen($chars) - 1)];
		} while (Post::where('id', $id)->exists());

		return $id;
	}

	public function index() {
		return Post::withUser()->latest()->get();
	}

	public function show(Post $post) {
		return $post->loadUser();
	}

	public function userPosts(User $user) {
		return $user->usersPosts()->withUser()->latest()->get();
	}

	public function store(Request $request) {
		$data = $this->validatePost($request);
		// use the current user's id as `user_id`
		$data['id'] = $this->generatePostId();
		$data['user_id'] = auth()->guard()->id();

		// Use Post model to create this field and save to database.
		// I think this is it here?
		// https://api.laravel.com/docs/12.x/Illuminate/Database/Eloquent/Builder.html#method_create
		$post = Post::create($data);

		// Return a response as a JSON.
		// https://laravel.com/docs/12.x/responses
		return response()->json($post->loadUser(), 201);
	}

	// Saves edited posts.
	// $post is the post we're trying to update and $request gives us the incoming form data,
	// whatever the user typed in for their new values.
	public function update(Post $post, Request $request) {
		$this->authorizePost($post);
		// Update the post with the values provided.
		// https://api.laravel.com/docs/12.x/Illuminate/Database/Eloquent/Builder.html#method_update
		$post->update($this->validatePost($request));

		return $post->loadUser();
	}

	public function destroy(Post $post) {
		$this->authorizePost($post);
		$post->delete();
		return response()->json(['message' => 'Deleted']);
	}
}