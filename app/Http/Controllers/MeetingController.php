<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    public function index()
    {
        if (! Auth::check()) {
            return view('pages.meeting-gate');
        }

        $meetings = Meeting::where('starts_at', '>=', now())->orderBy('starts_at')->get()
            ->filter(fn ($m) => $m->isAccessibleBy(Auth::user()));

        return view('pages.meeting-room', compact('meetings'));
    }
}
