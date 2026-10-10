@extends('layouts.admin')
@section('title', 'Settings')
@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" class="card" style="max-width:640px">
  @csrf @method('PUT')
  <h3 style="font-size:.95rem;margin-bottom:12px">Church details</h3>
  <div class="field" style="margin-bottom:12px"><label>Church name</label><input type="text" name="church_name" value="{{ $settings['church_name'] }}"></div>
  <div class="field" style="margin-bottom:12px"><label>Address (used for directions)</label><textarea name="church_address">{{ $settings['address'] }}</textarea></div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px">
    <div class="field"><label>Phone</label><input type="text" name="church_phone" value="{{ $settings['phone'] }}"></div>
    <div class="field"><label>Email</label><input type="email" name="church_email" value="{{ $settings['email'] }}"></div>
  </div>

  <h3 style="font-size:.95rem;margin-bottom:12px">Livestream</h3>
  <div class="field" style="margin-bottom:18px"><label>Livestream link</label><input type="url" name="live_stream_url" value="{{ old('live_stream_url', $settings['live_stream_url']) }}" placeholder="YouTube, Vimeo or .m3u8 link">
    <div style="font-size:.78rem;color:var(--grey);margin-top:4px">Plays in the Live tab on the homepage and on the Live page. Leave empty when nothing is streaming.</div></div>

  <h3 style="font-size:.95rem;margin-bottom:12px">Giving</h3>
  <div class="field" style="margin-bottom:18px"><label>Espees merchant code</label><input type="text" name="espees_merchant_code" value="{{ $settings['espees_merchant_code'] }}">
    <div style="font-size:.78rem;color:var(--grey);margin-top:4px">The Give button sends people to Espees with this code.</div></div>

  <h3 style="font-size:.95rem;margin-bottom:12px">Notifications</h3>
  <label style="display:flex;gap:8px;align-items:center;font-size:.9rem;margin-bottom:10px"><input type="checkbox" name="auto_notify" value="1" {{ $settings['auto_notify'] ? 'checked' : '' }}> Notify automatically when new content is posted</label>
  <label style="display:flex;gap:8px;align-items:center;font-size:.9rem;margin-bottom:10px"><input type="checkbox" name="ask_push" value="1" {{ $settings['ask_push'] ? 'checked' : '' }}> Ask visitors to enable notifications</label>
  <div class="field" style="margin-bottom:18px"><label>After how many seconds on the site</label><input type="number" name="ask_push_delay" value="{{ $settings['ask_push_delay'] }}"></div>

  <button class="btn btn-blue" type="submit">Save settings</button>
</form>
@endsection
