<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrayerController extends Controller
{
    public function create()
    {
        return view('pages.prayer');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:80'],
            'contact' => ['nullable', 'string', 'max:120'],
            'category' => ['required', 'string'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        // Stored via the same lightweight Setting/log approach used elsewhere in
        // this prototype would not scale; a dedicated `prayer_requests` table
        // (mirroring `comments`) is the natural next migration once this is real.
        \Illuminate\Support\Facades\Log::info('Prayer request submitted', $data);

        return back()->with('status', 'Thank you. Our prayer team has received your request.');
    }
}
