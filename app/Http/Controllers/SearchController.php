<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));
        $results = collect();

        if (strlen($q) >= 2) {
            $results = Post::published()->where(fn ($w) => $w->where('title', 'like', "%$q%")->orWhere('excerpt_text', 'like', "%$q%"))
                ->latest('published_at')->limit(20)->get()
                ->map(fn ($p) => ['type' => ucfirst(str_replace('_', ' ', $p->type)), 'title' => $p->title, 'meta' => $p->excerpt_text, 'url' => $p->publicUrl()])
                ->concat(
                    Department::where('name', 'like', "%$q%")->orWhere('note', 'like', "%$q%")->get()
                        ->map(fn ($d) => ['type' => 'Fellowship / Department', 'title' => $d->name, 'meta' => $d->note, 'url' => route('fellowships.index')])
                );
        }

        return view('pages.search', compact('q', 'results'));
    }
}
