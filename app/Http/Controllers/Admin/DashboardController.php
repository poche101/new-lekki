<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Meeting;
use App\Models\PageView;
use App\Models\Post;
use App\Models\PushSubscription;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();

        return view('admin.dashboard', [
            'visitorsToday' => PageView::whereDate('viewed_at', $now->toDateString())->distinct('session_id')->count('session_id'),
            'visitorsWeek' => PageView::where('viewed_at', '>=', $now->copy()->subDays(7))->distinct('session_id')->count('session_id'),
            'visitorsMonth' => PageView::where('viewed_at', '>=', $now->copy()->subDays(30))->distinct('session_id')->count('session_id'),
            'subscriberCount' => PushSubscription::count(),
            'commentCount14d' => Comment::where('created_at', '>=', $now->copy()->subDays(14))->count(),
            'recentComments' => Comment::with('post', 'user')->latest()->limit(5)->get(),
            'topContent' => Post::published()->orderByDesc('views')->limit(4)->get(['title', 'views']),
            'upcoming' => Meeting::where('starts_at', '>=', $now)->orderBy('starts_at')->limit(4)->get()
                ->concat(Post::type('event')->published()->whereRaw("JSON_EXTRACT(meta,'$.starts_at') >= ?", [$now->toDateString()])->limit(4)->get()),
            'dailyVisitors' => collect(range(13, 0))->map(function ($daysAgo) use ($now) {
                $day = $now->copy()->subDays($daysAgo);

                return ['label' => $day->format('D'), 'count' => PageView::whereDate('viewed_at', $day->toDateString())->distinct('session_id')->count('session_id')];
            }),
        ]);
    }
}
