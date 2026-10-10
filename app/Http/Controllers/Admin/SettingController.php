<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\VideoLink;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit', [
            'settings' => [
                'church_name' => Setting::get('church_name', 'Christ Embassy Lekki'),
                'address' => Setting::get('church_address', 'LoveWorld Arena, Aare Bashiru Street, Chisco B/S, Lekki-Epe Expressway, Lagos'),
                'phone' => Setting::get('church_phone', '+234 907 641 5312'),
                'email' => Setting::get('church_email', 'hello@yourchurch.org'),
                'live_stream_url' => Setting::get('live_stream_url', ''),
                'espees_merchant_code' => Setting::get('espees_merchant_code', config('services.espees.code')),
                'auto_notify' => Setting::get('auto_notify', true),
                'ask_push' => Setting::get('ask_push', true),
                'ask_push_delay' => Setting::get('ask_push_delay', 15),
            ],
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'church_name' => ['required', 'string', 'max:150'], 'church_address' => ['required', 'string', 'max:400'],
            'church_phone' => ['required', 'string', 'max:30'], 'church_email' => ['required', 'email'],
            'live_stream_url' => ['nullable', 'url', 'max:500', fn ($a, $v, $fail) => $v && ! VideoLink::supported($v) ? $fail('Use a YouTube, Vimeo, .mp4, .webm or .m3u8 link.') : null],
            'espees_merchant_code' => ['required', 'string', 'max:100'], 'auto_notify' => ['nullable', 'boolean'], 'ask_push' => ['nullable', 'boolean'], 'ask_push_delay' => ['required', 'integer', 'min:0'],
        ]);
        $data['auto_notify'] = $request->boolean('auto_notify');
        $data['ask_push'] = $request->boolean('ask_push');

        foreach ($data as $key => $value) {
            Setting::put($key, $value);
        }

        return back()->with('status', 'Settings saved.');
    }
}
