<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Department;
use App\Models\Post;
use App\Models\Setting;
use App\Support\VideoLink;

class HomeController extends Controller
{
    public function index()
    {
        $sections = collect(Setting::get('home_sections', $this->defaultSections()))
            ->filter(fn ($s) => $s['on'] && $s['label'] !== 'Sunday Best Dressers')->values();

        $excerpts = Post::latestOfType('excerpt', 6)->get();
        $featured = $this->featuredEpisode();

        return view('home.index', [
            'sections' => $sections,
            'capsules' => $this->resolveCapsules($excerpts, $featured),
            'excerpts' => $excerpts,
            'higherLifeFeatured' => $featured,
            'higherLifeList' => Post::latestOfType('higher_life', 4)
                ->when($featured, fn ($q) => $q->where('id', '!=', $featured->id))->limit(3)->get(),
            'events' => Post::type('event')->published()
                ->whereRaw("JSON_EXTRACT(meta, '$.starts_at') >= ?", [now()->toDateString()])
                ->orderByRaw("JSON_EXTRACT(meta, '$.starts_at') asc")->limit(3)->get(),
            'departments' => Department::ordered()->limit(5)->get(),
            'departmentsTotal' => Department::count(),
            'testimonies' => Post::type('testimony')->published()->latest('published_at')->get(),
            'ads' => Ad::active()->get(),
        ]);
    }

    /** The episode chosen in admin > Homepage, or the newest published one. */
    private function featuredEpisode(): ?Post
    {
        $id = Setting::get('featured_higher_life_id');
        $chosen = $id ? Post::type('higher_life')->published()->find($id) : null;

        return $chosen ?? Post::type('higher_life')->published()->latest('published_at')->first();
    }

    /** The post that holds comments for the Live tab (setting: live_comments_post_id). */
    private function liveCommentsPost(): ?Post
    {
        $id = Setting::get('live_comments_post_id');

        return $id ? Post::find($id) : null;
    }

    /**
     * Attaches a playable video and its comments to each console tab:
     *  Live      -> the livestream link set in admin > Settings; comments come from
     *               the post set in live_comments_post_id (none if it isn't set)
     *  Excerpts  -> the newest excerpt that has a video link
     *  Featured  -> the featured Higher Life episode
     */
    private function resolveCapsules($excerpts, ?Post $featured): array
    {
        // An empty saved value must not override the defaults.
        $stored = Setting::get('capsules');
        $capsules = (is_array($stored) && count($stored)) ? $stored : $this->defaultCapsules();

        return collect($capsules)
            ->filter(fn ($c) => $c['on'] ?? true)
            ->map(function ($cap) use ($excerpts, $featured) {
                $key = strtolower(($cap['source'] ?? '') . ' ' . ($cap['label'] ?? ''));
                $video = $thumb = $title = null;
                $post = null;          // supplies the video, thumbnail and title
                $commentPost = null;   // supplies the comments

                if (str_contains($key, 'live')) {
                    $video = VideoLink::parse(Setting::get('live_stream_url'));
                    $title = 'Livestream';
                    $commentPost = $this->liveCommentsPost();
                } elseif (str_contains($key, 'excerpt')) {
                    $post = $excerpts->first(fn ($p) => $p->video());
                } else {
                    $post = $featured;
                }

                if ($post) {
                    $video = $post->video();
                    $thumb = $post->thumbnailUrl();
                    $title = $post->title;
                    $commentPost = $post;
                }

                $comments = [];
                $commentUrl = null;
                $commentsOn = false;

                if ($commentPost) {
                    $commentsOn = (bool) $commentPost->comments_enabled;
                    $commentUrl = route('comments.store', $commentPost);
                    $comments = $commentPost->comments()->with('user')->latest()->limit(30)->get()
                        ->map(fn ($c) => [
                            'name' => $c->authorName(),
                            'body' => $c->body,
                            'team' => (bool) $c->is_admin_reply,
                        ])->all();
                }

                return $cap + [
                    'video' => $video,
                    'thumb' => $thumb,
                    'title' => $title,
                    'comment_url' => $commentUrl,
                    'comments_on' => $commentsOn,
                    'comments' => $comments,
                ];
            })->values()->all();
    }

    private function defaultSections(): array
    {
        return collect(['Media console', 'Worship with Us', 'Welcome', 'Latest excerpts', 'Higher Life', 'Upcoming events', 'Fellowships and Departments', 'Testimonies', 'Prayer request band'])
            ->map(fn ($l) => ['label' => $l, 'on' => true])->all();
    }

    private function defaultCapsules(): array
    {
        return [
            ['label' => 'Live', 'source' => 'Livestream', 'on' => true, 'schedule' => 'Sundays 9:00 AM & Wednesday 6:30 PM', 'fallback' => "Last Sunday's service replay"],
            ['label' => 'Excerpts', 'source' => 'Latest excerpt', 'on' => true, 'schedule' => 'Always on', 'fallback' => 'Most recent excerpt'],
            ['label' => 'Featured', 'source' => 'Higher Life episode', 'on' => true, 'schedule' => 'Always on', 'fallback' => 'Latest Higher Life episode'],
        ];
    }
}
