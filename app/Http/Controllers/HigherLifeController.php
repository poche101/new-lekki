<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\Request;

/**
 * Higher Life archive: every published episode, filterable by season and
 * searchable by title, summary or speaker, plus a page per episode.
 */
class HigherLifeController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));
        $season = $request->integer('season') ?: null;

        $base = Post::type('higher_life')->published();

        // seasons that have at least one published episode, newest first, with counts
        $seasons = (clone $base)->get(['id', 'meta'])
            ->map(fn ($p) => $p->meta('season'))->filter()->countBy()->sortKeysDesc();

        $query = (clone $base)->latest('published_at');
        if ($q !== '') {
            $query->where(fn ($w) => $w->where('title', 'like', "%$q%")
                ->orWhere('excerpt_text', 'like', "%$q%")
                ->orWhere('meta->speaker', 'like', "%$q%"));
        }
        if ($season) {
            $query->where('meta->season', $season);
        }

        // Spotlight the featured episode on the unfiltered archive
        $featured = null;
        if ($q === '' && ! $season) {
            $id = Setting::get('featured_higher_life_id');
            $featured = ($id ? (clone $base)->find($id) : null) ?? (clone $base)->latest('published_at')->first();
            if ($featured) {
                $query->where('id', '!=', $featured->id);
            }
        }

        return view('higher-life.index', [
            'episodes' => $query->paginate(12)->withQueryString(),
            'featured' => $featured,
            'seasons' => $seasons,
            'activeSeason' => $season,
            'q' => $q,
            'total' => (clone $base)->count(),
        ]);
    }

    public function show(Post $post)
    {
        abort_unless($post->type === 'higher_life' && $post->status === 'published', 404);
        $post->increment('views');

        $episodes = Post::type('higher_life')->published();
        $older = (clone $episodes)->where('published_at', '<', $post->published_at)->latest('published_at')->first();
        $newer = (clone $episodes)->where('published_at', '>', $post->published_at)->oldest('published_at')->first();
        $more = (clone $episodes)->where('id', '!=', $post->id)->latest('published_at')->limit(3)->get();

        return view('higher-life.show', compact('post', 'older', 'newer', 'more'));
    }
}
