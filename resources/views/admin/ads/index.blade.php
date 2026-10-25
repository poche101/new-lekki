@extends('layouts.admin')
@section('title', 'Ads')
@section('content')
<div style="display:grid;grid-template-columns:1.3fr 1fr;gap:20px">
  <div>
    <div class="card">
      <h3 style="font-size:.95rem;margin-bottom:10px">New ad</h3>
      <form method="POST" action="{{ route('admin.ads.store') }}" enctype="multipart/form-data">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px">
          <input type="text" name="title" placeholder="Title" required>
          <input type="text" name="banner_label" placeholder="Banner label (button text)">
        </div>
        <input type="text" name="link" placeholder="https://…" style="margin-bottom:10px">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px">
          <div class="field"><label>Image (slides in the banner, max 4 MB)</label><input type="file" name="image" accept="image/*"></div>
          <div class="field"><label>Video (mp4 or webm, max 20 MB)</label><input type="file" name="video" accept="video/mp4,video/webm"></div>
        </div>
        <p style="color:var(--grey);font-size:.8rem;margin:0 0 10px">If you add both, the video plays and the image is used as a fallback. Use a wide image, about 1200&times;300.</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px">
          <input type="date" name="starts_on"><input type="date" name="ends_on">
        </div>
        <button class="btn btn-blue" type="submit">Create ad</button>
      </form>
    </div>

    <table class="a-table" style="margin-top:16px">
      <thead><tr><th></th><th>Title</th><th>Status</th><th>Impressions</th><th>Clicks</th><th>CTR</th><th></th></tr></thead>
      <tbody>
        @forelse ($ads as $ad)
          <tr>
            <td style="width:64px">
              @if ($ad->image)
                <img src="{{ asset('storage/' . ltrim($ad->image, '/')) }}" alt="" style="width:56px;height:36px;object-fit:cover;border-radius:6px">
              @elseif ($ad->video)
                <span class="chip">Video</span>
              @endif
            </td>
            <td>{{ $ad->title }}</td><td><span class="chip">{{ ucfirst($ad->status) }}</span></td>
            <td>{{ number_format($ad->impressions) }}</td><td>{{ number_format($ad->clicks) }}</td><td>{{ $ad->clickThroughRate() }}</td>
            <td style="white-space:nowrap">
              @if ($ad->status !== 'scheduled')
                <form method="POST" action="{{ route('admin.ads.toggle', $ad) }}" style="display:inline">@csrf<button class="btn btn-ghost" type="submit">{{ $ad->status === 'active' ? 'Pause' : 'Activate' }}</button></form>
              @endif
              <form method="POST" action="{{ route('admin.ads.destroy', $ad) }}" style="display:inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-ghost" style="color:#DC2626" type="submit">Delete</button></form>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" style="text-align:center;color:var(--grey);padding:30px">No ads yet. The default "Advertise here" banner is showing.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="card">
    <h3 style="font-size:.95rem;margin-bottom:10px">Popup rules</h3>
    <form method="POST" action="{{ route('admin.ads.config') }}">
      @csrf @method('PUT')
      <div class="field" style="margin-bottom:10px"><label>First popup after (seconds)</label><input type="number" name="first_delay" value="{{ $config['first_delay'] }}"></div>
      <div class="field" style="margin-bottom:10px"><label>Then every (seconds)</label><input type="number" name="every" value="{{ $config['every'] }}"></div>
      <div class="field" style="margin-bottom:10px"><label>Rotation</label><select name="rotation"><option {{ $config['rotation'] === 'Alternate in order' ? 'selected' : '' }}>Alternate in order</option><option {{ $config['rotation'] === 'Random' ? 'selected' : '' }}>Random</option></select></div>
      <div class="field" style="margin-bottom:14px"><label>Max popups per visit</label><input type="number" name="max_per_session" value="{{ $config['max_per_session'] }}"></div>
      <button class="btn btn-blue" type="submit">Save rules</button>
    </form>
  </div>
</div>
@endsection
