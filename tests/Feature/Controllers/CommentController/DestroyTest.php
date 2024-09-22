<?php

namespace Tests\Feature\Controllers\CommentController;

use App\Models\Comment;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;

it('requires authentication', function () {
    $comment = Comment::factory()->create();

    delete(route('comments.destroy', $comment))
        ->assertRedirect(route('login'));
});

it('can delete a comment', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->delete(route('comments.destroy', $comment));

    $this->assertModelMissing($comment);
});

it('redirects to the post show page', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->delete(route('comments.destroy', $comment))
        ->assertRedirect(route('posts.show', $comment->post_id));
});

it("prevents delete a comment from another user", function () {
    /** @var User $user */
    $user = User::factory()->create();

    $comment = Comment::factory()->create();

    actingAs($user)
        ->delete(route('comments.destroy', $comment))
        ->assertForbidden();
});

it("prevents delete a comment posted an hour ago", function () {
    $this->freezeTime();
    $comment = Comment::factory()->create();

    $this->travel(1)->hours();

    actingAs($comment->user)
        ->delete(route('comments.destroy', $comment))
        ->assertForbidden();
});

it('redirects to the post show page with the page query parameter', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->delete(route('comments.destroy', ['comment' => $comment, 'page' => 2]))
        ->assertRedirect(route('posts.show', [ 'post'=> $comment->post_id, 'page' => 2]));
});
