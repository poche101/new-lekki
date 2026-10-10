<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $status = $request->get('status', 'all');

        return view('admin.media.index', [
            'items' => MediaItem::when($q, fn ($w) => $w->where('title', 'like', "%$q%"))->when($status !== 'all', fn ($w) => $w->where('status', $status))->latest()->paginate(15)->withQueryString(),
            'q' => $q, 'status' => $status,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'], 'type' => ['required', 'in:video,audio,image'], 'category' => ['nullable', 'string'],
            'file_url' => ['nullable', 'string'], 'file' => ['nullable', 'file', 'max:512000'], 'status' => ['required', 'in:draft,scheduled,published,archived'],
        ]);

        if ($request->hasFile('file')) {
            $data['file_url'] = $request->file('file')->store('media', 'public');
        }

        MediaItem::create($data + ['created_by' => $request->user()->id]);

        return back()->with('status', 'Media saved.');
    }

    public function update(Request $request, MediaItem $mediaItem)
    {
        $mediaItem->update($request->validate(['title' => ['required', 'string', 'max:200'], 'category' => ['nullable', 'string'], 'status' => ['required', 'in:draft,scheduled,published,archived']]));

        return back()->with('status', 'Saved.');
    }

    public function archive(MediaItem $mediaItem)
    {
        $mediaItem->update(['status' => 'archived']);

        return back()->with('status', 'Archived.');
    }
}
