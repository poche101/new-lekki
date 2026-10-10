<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        abort_unless($post->comments_enabled, 403, 'Comments are turned off for this post.');

        $data = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
            'guest_name' => ['nullable', 'string', 'max:60'],
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $request->user()?->id,
            'guest_name' => $request->user() ? null : (($data['guest_name'] ?? null) ?: 'Guest'),
            'body' => $data['body'],
        ]);

        // The homepage console posts via fetch() and wants JSON back.
        if ($request->expectsJson()) {
            return response()->json(['comment' => [
                'name' => $comment->authorName(),
                'body' => $comment->body,
                'team' => false,
            ]], 201);
        }

        return back()->with('status', 'Comment posted.');
    }
}
