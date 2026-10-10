<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Setting;
use App\Support\VideoLink;

class LiveController extends Controller
{
    public function index()
    {
        $live = collect(Setting::get('capsules', []))->first(fn ($c) => str_contains(strtolower(($c['source'] ?? '') . ' ' . ($c['label'] ?? '')), 'live'));

        return view('pages.live', [
            'video' => VideoLink::parse(Setting::get('live_stream_url')),
            'schedule' => $live['schedule'] ?? 'Sundays 9:00 AM',
            'fallback' => $live['fallback'] ?? null,
            'replays' => Post::latestOfType('excerpt', 3)->get()->filter(fn ($p) => $p->video()),
        ]);
    }
}
