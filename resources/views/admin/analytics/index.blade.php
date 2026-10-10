@extends('layouts.admin')
@section('title', 'Analytics')
@section('content')
<p class="lede" style="margin-bottom:16px">Real numbers from this app's own page_views table — no external analytics service is connected. On a fresh install these will be near zero until the site has real visitors.</p>
<div class="a-toolbar">
  @foreach ([7, 14, 30] as $d)
    <a class="btn {{ $range === $d ? 'btn-blue' : 'btn-ghost' }}" href="{{ route('admin.analytics.index', ['range' => $d]) }}">Last {{ $d }} days</a>
  @endforeach
</div>
<div class="a-stats">
  <div class="a-stat"><div style="font-size:.78rem;color:var(--grey)">Visitors</div><div class="v">{{ $totalVisitors }}</div></div>
  <div class="a-stat"><div style="font-size:.78rem;color:var(--grey)">Page views</div><div class="v">{{ $totalViews }}</div></div>
</div>
<div class="card">
  <h3 style="font-size:.95rem;margin-bottom:14px">Visitors over time</h3>
  <div style="display:flex;align-items:flex-end;gap:4px;height:150px">
    @php($max = max(1, $daily->max('count')))
    @foreach ($daily as $d)
      <div title="{{ $d['label'] }}: {{ $d['count'] }}" style="flex:1;background:linear-gradient(180deg,var(--blue-soft),var(--blue));border-radius:5px 5px 0 0;height:{{ max(3, $d['count'] / $max * 100) }}%"></div>
    @endforeach
  </div>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:20px">
  <div class="card">
    <h3 style="font-size:.95rem;margin-bottom:10px">Top pages</h3>
    @forelse ($topPaths as $p)
      <div style="display:flex;justify-content:space-between;padding:8px 0;border-top:1px solid var(--line)"><span>/{{ $p->path }}</span><b>{{ $p->c }}</b></div>
    @empty
      <p style="color:var(--grey);font-size:.9rem">No traffic recorded in this range.</p>
    @endforelse
  </div>
  <div class="card">
    <h3 style="font-size:.95rem;margin-bottom:10px">Devices</h3>
    @forelse ($devices as $device => $count)
      <div style="display:flex;justify-content:space-between;padding:8px 0;border-top:1px solid var(--line)"><span>{{ ucfirst($device ?? 'Unknown') }}</span><b>{{ $count }}</b></div>
    @empty
      <p style="color:var(--grey);font-size:.9rem">No data yet.</p>
    @endforelse
  </div>
</div>
@endsection
