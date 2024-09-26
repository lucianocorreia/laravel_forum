<?php

namespace Tests\Feature\Controllers\PostController;

use App\Models\Comment;

it('generates the html', function () {
    $comment = Comment::factory()->make(["body" => "## Hello"]);

    $comment->save();

    expect($comment->html)->toEqual(str($comment->body)->markdown());
});
