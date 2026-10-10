<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

/**
 * The public blog: articles, excerpts, best-dresser posts, events and
 * announcements all live in the same `posts` table (see migration), so this
 * one controller lists and shows every type. Publishing here is what makes
 * something show up on the homepage (HomeController reads the same table).
 */
class BlogController extends Controller
{
    private const TABS = ['article' => 'Articles', 'excerpt' => 'Excerpts', 'announcement' => 'Announcements', 'event' => 'Events', 'best_dresser' => 'Best Dresser'];

    public function index(Request $request)
    {
        $type = $request->get('type');
        $q = $request->get('q');

        $query = Post::whereIn('type', array_keys(self::TABS))->published()->latest('published_at');
        if ($type && array_key_exists($type, self::TABS)) {
            $query->type($type);
        }
        if ($q) {
            $query->where(fn ($w) => $w->where('title', 'like', "%$q%")->orWhere('excerpt_text', 'like', "%$q%"));
        }

        $posts = $query->paginate(6)->withQueryString();
        $featured = (! $type && ! $q) ? Post::whereIn('type', array_keys(self::TABS))->published()->latest('published_at')->first() : null;
        if ($featured) {
            $posts = $query->where('id', '!=', $featured->id)->paginate(5)->withQueryString();
        }

        return view('blog.index', [
            'tabs' => self::TABS, 'activeType' => $type, 'q' => $q,
            'featured' => $featured, 'posts' => $posts,
            'categoryCounts' => Post::whereIn('type', array_keys(self::TABS))->published()->selectRaw('type, count(*) c')->groupBy('type')->pluck('c', 'type'),
            'recent' => Post::whereIn('type', array_keys(self::TABS))->published()->latest('published_at')->limit(4)->get(),
        ]);
    }

    public function show(Post $post)
    {
        abort_unless($post->status === 'published', 404);

        if ($post->type === 'higher_life') {
            return redirect()->route('higher-life.show', $post, 301);
        }

        $post->increment('views');

        $older = Post::where('type', $post->type)->published()->where('published_at', '<', $post->published_at)->latest('published_at')->first();
        $newer = Post::where('type', $post->type)->published()->where('published_at', '>', $post->published_at)->oldest('published_at')->first();
        $related = Post::where('type', $post->type)->published()->where('id', '!=', $post->id)->latest('published_at')->limit(3)->get();

        return view('blog.show', compact('post', 'older', 'newer', 'related'));
    }
}
