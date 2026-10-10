<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Real aggregation over the `page_views` table (see TrackPageView middleware).
 * There is no external analytics provider wired up — this only knows about
 * traffic that hit this Laravel app. Numbers will be small/empty on a fresh
 * install until the site has real visitors, which is expected and correct.
 */
class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $days = (int) $request->get('range', 14);
        $since = now()->subDays($days);

        return view('admin.analytics.index', [
            'range' => $days,
            'totalVisitors' => PageView::where('viewed_at', '>=', $since)->distinct('session_id')->count('session_id'),
            'totalViews' => PageView::where('viewed_at', '>=', $since)->count(),
            'daily' => collect(range($days - 1, 0))->map(function ($daysAgo) {
                $day = now()->copy()->subDays($daysAgo);

                return ['label' => $day->format('M j'), 'count' => PageView::whereDate('viewed_at', $day->toDateString())->distinct('session_id')->count('session_id')];
            }),
            'topPaths' => PageView::where('viewed_at', '>=', $since)->select('path', DB::raw('count(*) as c'))->groupBy('path')->orderByDesc('c')->limit(8)->get(),
            'devices' => PageView::where('viewed_at', '>=', $since)->select('device_type', DB::raw('count(*) as c'))->groupBy('device_type')->pluck('c', 'device_type'),
            'topContent' => Post::published()->orderByDesc('views')->limit(6)->get(['title', 'views', 'type']),
        ]);
    }
}
