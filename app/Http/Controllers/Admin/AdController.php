<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function index()
    {
        return view('admin.ads.index', ['ads' => Ad::latest()->get(), 'config' => Setting::get('ad_config', ['first_delay' => 20, 'every' => 90, 'rotation' => 'Alternate in order', 'max_per_session' => 3])]);
    }

    private function rules(): array
    {
        return ['title' => ['required', 'string', 'max:150'], 'banner_label' => ['nullable', 'string', 'max:60'], 'link' => ['nullable', 'string', 'max:255'], 'starts_on' => ['nullable', 'date'], 'ends_on' => ['nullable', 'date']];
    }

    public function store(Request $request)
    {
        Ad::create($request->validate($this->rules()) + ['status' => 'scheduled']);

        return back()->with('status', 'Ad created.');
    }

    public function update(Request $request, Ad $ad)
    {
        $ad->update($request->validate($this->rules()));

        return back()->with('status', 'Ad saved.');
    }

    public function toggle(Ad $ad)
    {
        $ad->update(['status' => $ad->status === 'active' ? 'paused' : 'active']);

        return back()->with('status', 'Ad ' . $ad->status . '.');
    }

    public function destroy(Ad $ad)
    {
        $ad->delete();

        return back()->with('status', 'Ad deleted.');
    }

    public function updateConfig(Request $request)
    {
        Setting::put('ad_config', $request->validate([
            'first_delay' => ['required', 'integer', 'min:0'], 'every' => ['required', 'integer', 'min:10'],
            'rotation' => ['required', 'in:Alternate in order,Random'], 'max_per_session' => ['required', 'integer', 'min:1'],
        ]));

        return back()->with('status', 'Ad rules saved.');
    }
}
