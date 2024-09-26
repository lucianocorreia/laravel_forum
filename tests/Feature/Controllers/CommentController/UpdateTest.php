<?php

namespace Tests\Feature\Controllers\CommentController;

use App\Models\Comment;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\put;

it('requires authentication', function () {
    $comment = comment::factory()->create();

    put(route('comments.update', $comment))
        ->assertredirect(route('login'));
});

it('can update a comment', function () {
    $comment = comment::factory()->create(['body' => 'Old body']);
    $newBody = 'New body';

    actingAs($comment->user)
        ->put(route('comments.update', $comment), ['body' => $newBody]);

    $this->assertDatabaseHas(Comment::class, ['id' => $comment->id, 'body' => $newBody]);
});

it('redirect to the post show page', function () {
    $comment = comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', $comment), ['body' => 'New Body'])
        ->assertRedirect($comment->post->showRoute());
});

it('redirect to correct page of comments', function () {
    $comment = comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', ['comment' => $comment, 'page' => 2]), ['body' => 'New Body'])
        ->assertRedirect($comment->post->showRoute(['page' => 2]));
});

it('cannot update a comment from another user', function () {
    $comment = comment::factory()->create();
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->put(route('comments.update', ['comment' => $comment]), ['body' => 'New Body'])
        ->assertForbidden();
});

it('requires a valid body', function ($body) {
    $comment = comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', ['comment' => $comment]), ['body' => $body])
        ->assertInvalid('body');
})->with(
    [
        null, 
        true,
        1,
        1.5,
        str_repeat('a', 2501)
    ]
);
