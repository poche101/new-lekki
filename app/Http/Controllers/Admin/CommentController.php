<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

/**
 * Deliberately simple: no approve/reject, no status, no moderation queue.
 * Admins (anyone with the `moderate` permission) can see every comment, post
 * one themselves as the church account, or delete any comment outright.
 */
class CommentController extends Controller
{
    public function index(Request $request)
    {
        $postId = $request->get('post_id');

        return view('admin.comments.index', [
            'comments' => Comment::with('post', 'user')->when($postId, fn ($q) => $q->where('post_id', $postId))->latest()->paginate(20)->withQueryString(),
            'posts' => Post::whereIn('type', ['article', 'excerpt', 'event', 'higher_life'])->latest('published_at')->limit(50)->get(),
            'postId' => $postId,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['post_id' => ['required', 'exists:posts,id'], 'body' => ['required', 'string', 'max:1000']]);

        Comment::create([
            'post_id' => $data['post_id'],
            'user_id' => $request->user()->id,
            'body' => $data['body'],
            'is_admin_reply' => true,
        ]);

        return back()->with('status', 'Comment posted.');
    }

    public function destroy(Request $request, Comment $comment)
    {
        $comment->update(['deleted_by' => $request->user()->id]);
        $comment->delete();

        return back()->with('status', 'Comment deleted.');
    }
}
