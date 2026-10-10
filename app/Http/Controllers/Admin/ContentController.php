<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PushNotification;
use App\Support\VideoLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/**
 * One controller for every content type in the `posts` table. Publishing an
 * excerpt, event, best-dresser entry or announcement here is what makes it
 * show up on the public blog and homepage (see HomeController/BlogController
 * — they query the same table).
 */
class ContentController extends Controller
{
    private array $types = ['article', 'excerpt', 'event', 'best_dresser', 'announcement', 'higher_life', 'page', 'testimony'];

    /** Types that can carry a video link + thumbnail image (excerpts: both optional; Higher Life: both required). */
    private array $videoTypes = ['excerpt', 'higher_life'];

    /** Types that take an uploaded image (events: optional flyer/banner; Sunday Best Dresser entries are photo-only). */
    private array $imageTypes = ['excerpt', 'event', 'higher_life', 'best_dresser'];

    /** Types where the image is optional, so the "remove image" checkbox is allowed. */
    private array $removableImageTypes = ['excerpt', 'event'];

    public function index(Request $request)
    {
        $type = $request->get('type', 'article');
        abort_unless(in_array($type, $this->types, true), 404);

        return view('admin.content.index', [
            'type' => $type,
            'types' => $this->types,
            'posts' => Post::type($type)->latest('created_at')->paginate(15)->withQueryString(),
        ]);
    }

    public function create(Request $request)
    {
        return view('admin.content.form', ['type' => $request->get('type', 'article'), 'post' => new Post()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $post = Post::create($data + ['author_id' => $request->user()->id]);

        $this->maybeNotify($post, $request);

        return redirect()->route('admin.content.index', ['type' => $post->type])->with('status', ucfirst($post->status) . '.');
    }

    public function edit(Post $post)
    {
        return view('admin.content.form', ['type' => $post->type, 'post' => $post]);
    }

    public function update(Request $request, Post $post)
    {
        $post->update($this->validated($request, $post));
        $this->maybeNotify($post, $request);

        return redirect()->route('admin.content.index', ['type' => $post->type])->with('status', 'Saved.');
    }

    public function destroy(Post $post)
    {
        if ($thumb = $post->meta('thumbnail')) {
            Storage::disk('public')->delete($thumb);
        }
        $post->delete();

        return back()->with('status', 'Deleted.');
    }

    private function validated(Request $request, ?Post $post = null): array
    {
        $isVideo = in_array($request->input('type'), $this->videoTypes, true);
        $isEpisode = $request->input('type') === 'higher_life';
        $hasImage = in_array($request->input('type'), $this->imageTypes, true);
        $needsThumbnail = in_array($request->input('type'), ['higher_life', 'best_dresser'], true) && ! $post?->meta('thumbnail');

        $data = $request->validate([
            'type' => ['required', 'in:' . implode(',', $this->types)],
            'title' => ['required', 'string', 'max:255'],
            'excerpt_text' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,scheduled,published,archived'],
            'published_at' => ['nullable', 'date'],
            'comments_enabled' => ['nullable', 'boolean'],
            'video_url' => [
                Rule::requiredIf($isEpisode), 'nullable', 'url', 'max:500',
                fn ($attr, $value, $fail) => $value && ! VideoLink::supported($value)
                    ? $fail('Use a YouTube, Vimeo, .mp4, .webm or .m3u8 link.') : null,
            ],
            'thumbnail' => [Rule::requiredIf($needsThumbnail), 'nullable', 'image', 'max:2048'],
        ], [
            'thumbnail.required' => $request->input('type') === 'best_dresser' ? 'Please upload the best dresser\'s photo.' : 'Higher Life episodes need a thumbnail image.',
            'video_url.required' => 'Higher Life episodes need a video link.',
        ]);
        $data['comments_enabled'] = $request->boolean('comments_enabled');
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        // type-specific meta, folded into the json `meta` column
        $meta = [];
        if ($data['type'] === 'event') {
            $meta = ['starts_at' => $request->input('event_starts_at'), 'location' => $request->input('event_location')];
        } elseif ($data['type'] === 'higher_life') {
            $meta = ['episode' => (int) $request->input('episode'), 'season' => (int) $request->input('season'), 'speaker' => $request->input('speaker'), 'duration_mins' => (int) $request->input('duration_mins')];
        } elseif ($data['type'] === 'best_dresser') {
            $meta = ['name' => $request->input('dresser_name'), 'month' => $request->input('month'), 'featured' => $request->boolean('featured')];
        } elseif (in_array($data['type'], ['article', 'announcement'], true)) {
            $meta = ['read_minutes' => (int) $request->input('read_minutes', 3)];
        }

        // video link: optional on excerpts (empty = no video), required on Higher Life episodes
        if ($isVideo && ! empty($data['video_url'])) {
            $meta['video_url'] = $data['video_url'];
        }

        // image: optional on excerpts and events, required on Higher Life episodes and Best Dresser entries.
        // A new upload replaces the old file; "remove image" (excerpts and events only) deletes it; otherwise keep what's there.
        if ($hasImage) {
            if ($request->hasFile('thumbnail')) {
                if ($old = $post?->meta('thumbnail')) {
                    Storage::disk('public')->delete($old);
                }
                $meta['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
            } elseif ($existing = $post?->meta('thumbnail')) {
                if (in_array($data['type'], $this->removableImageTypes, true) && $request->boolean('remove_thumbnail')) {
                    Storage::disk('public')->delete($existing);
                } else {
                    $meta['thumbnail'] = $existing;
                }
            }
        }

        // these two are form fields, not columns on `posts`
        unset($data['video_url'], $data['thumbnail']);

        $data['meta'] = $meta;

        return $data;
    }

    private function maybeNotify(Post $post, Request $request): void
    {
        if ($post->status !== 'published' || ! $request->boolean('send_push')) {
            return;
        }

        $topicName = match ($post->type) {
            'higher_life' => 'Higher Life', 'event' => 'Events', 'announcement' => 'Announcements', default => 'New Excerpt',
        };
        $topic = \App\Models\NotificationTopic::firstOrCreate(['name' => $topicName]);

        $notification = PushNotification::create([
            'title' => $post->title, 'message' => $post->excerpt_text ?: 'New content just published.',
            'topic_id' => $topic->id, 'audience' => 'all', 'url' => $post->publicUrl(),
            'post_id' => $post->id, 'sent_at' => now(), 'created_by' => $request->user()->id,
        ]);

        // Queue one delivery row per current subscriber. Actually pushing to the
        // browser requires VAPID keys + minishlink/web-push (see README) — not
        // wired up here, so these rows represent "should have been sent", not
        // confirmed delivery.
        \App\Models\PushSubscription::query()->pluck('id')->each(fn ($id) => $notification->deliveries()->create(['push_subscription_id' => $id, 'status' => 'queued']));
    }
}
