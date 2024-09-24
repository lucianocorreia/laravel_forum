<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;;

use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;


class CommentController extends BaseController
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Comment::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:2500']
        ]);

        Comment::create([
            ...$data,
            'user_id' => $request->user()->id,
            'post_id' => $post->id
        ]);

        return to_route('posts.show', $post)
            ->banner('Comment added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:2500']
        ]);

        $comment->update($data);

        return to_route('posts.show', [$comment->post_id, 'page' => $request->query('page')])
            ->banner('Comment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Comment $comment)
    {
        $comment->delete();

        return to_route('posts.show', ['post' => $comment->post_id, 'page' => $request->query('page')])
            ->banner('Comment deleted successfully');
    }
}
