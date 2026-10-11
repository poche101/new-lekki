<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function edit()
    {
        $sections = collect(Setting::get('home_sections', collect([
            'Media console', 'Worship with Us', 'Welcome', 'Latest excerpts', 'Higher Life',
            'Upcoming events', 'Fellowships and Departments', 'Testimonies', 'Prayer request band',
        ])->map(fn ($l) => ['label' => $l, 'on' => true])->all()))
            // Drop the retired section if it's still stored in the saved order.
            ->reject(fn ($s) => ($s['label'] ?? null) === 'Sunday Best Dressers')
            ->values()
            ->all();

        return view('admin.homepage', [
            'sections' => $sections,
            'capsules' => Setting::get('capsules', []),
            'higherLifeOptions' => Post::type('higher_life')->published()->latest('published_at')->get(),
            'featured' => Setting::get('featured_higher_life_id'),
        ]);
    }

    public function update(Request $request)
    {
        Setting::put('home_sections', $request->input('sections', []));

        // Only touch capsules if the form actually sent them — the current
        // homepage.blade.php view only edits section visibility + featured
        // episode, so without this guard every save would silently wipe the
        // capsule config back to empty. A capsule-editing form (like the one
        // described earlier) can post `capsules[]` here when it's built.
        if ($request->has('capsules')) {
            Setting::put('capsules', $request->input('capsules', []));
        }

        Setting::put('featured_higher_life_id', $request->input('featured_higher_life_id'));

        return back()->with('status', 'Homepage updated.');
    }
}
