<?php

namespace Tests\Feature\Controllers\CommentController;

use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('required authentication', function () {
    $post = Post::factory()->create();

    post(route('posts.comments.store', $post))
        ->assertRedirect(route('login'));
});

it('can store a comment', function () {
    /** @var Authenticatable $user */

    $user = User::factory()->create();
    $post = Post::factory()->create();

    actingAs($user)
        ->post(route('posts.comments.store', $post), [
            'body' => 'This is a comment'
        ]);

    $this->assertDatabaseHas('comments', [
        'body' => 'This is a comment',
        'user_id' => $user->id,
        'post_id' => $post->id
    ]);
});

it('redirects to the post show page', function () {
    /** @var Authenticatable $user */

    $user = User::factory()->create();
    $post = Post::factory()->create();

    actingAs($user)
        ->post(route('posts.comments.store', $post), [
            'body' => 'This is a comment'
        ])
        ->assertRedirect($post->showRoute());
});

it('requires a valid body', function ($value) {
    /** @var Authenticatable $user */

    $user = User::factory()->create();
    $post = Post::factory()->create();

    actingAs($user)
        ->post(route('posts.comments.store', $post), [
            'body' => $value
        ])
        ->assertInvalid('body');
})->with([
    null,
    1,
    1.1,
    true,
    str_repeat('a', 2501),
]);
