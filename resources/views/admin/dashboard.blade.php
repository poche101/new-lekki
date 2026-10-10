@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<div class="a-stats">
  <div class="a-stat"><div style="font-size:.78rem;color:var(--grey)">Visitors today</div><div class="v">{{ $visitorsToday }}</div></div>
  <div class="a-stat"><div style="font-size:.78rem;color:var(--grey)">This week</div><div class="v">{{ $visitorsWeek }}</div></div>
  <div class="a-stat"><div style="font-size:.78rem;color:var(--grey)">This month</div><div class="v">{{ $visitorsMonth }}</div></div>
  <div class="a-stat"><div style="font-size:.78rem;color:var(--grey)">Push subscribers</div><div class="v">{{ $subscriberCount }}</div></div>
  <div class="a-stat"><div style="font-size:.78rem;color:var(--grey)">Comments (14d)</div><div class="v">{{ $commentCount14d }}</div></div>
</div>

<div style="display:grid;gap:20px;grid-template-columns:1.3fr 1fr">
  <div class="card">
    <h3 style="font-size:1rem;margin-bottom:14px">Visitors, last 14 days</h3>
    <div style="display:flex;align-items:flex-end;gap:6px;height:140px">
      @php($max = max(1, $dailyVisitors->max('count')))
      @foreach ($dailyVisitors as $d)
        <div title="{{ $d['label'] }}: {{ $d['count'] }}" style="flex:1;background:linear-gradient(180deg,var(--blue-soft),var(--blue));border-radius:6px 6px 0 0;height:{{ max(3, $d['count'] / $max * 100) }}%"></div>
      @endforeach
    </div>
  </div>
  <div class="card">
    <h3 style="font-size:1rem;margin-bottom:14px">Recent comments</h3>
    @forelse ($recentComments as $c)
      <div style="padding:10px 0;border-top:1px solid var(--line)"><b style="font-size:.88rem">{{ $c->authorName() }}</b>
        <p style="font-size:.85rem;color:var(--ink-soft);margin-top:2px">{{ str($c->body)->limit(70) }}</p>
        <span style="font-size:.76rem;color:var(--grey)">on {{ $c->post->title ?? '(deleted post)' }}</span></div>
    @empty
      <p style="color:var(--grey);font-size:.9rem">No comments yet.</p>
    @endforelse
    <a href="{{ route('admin.comments.index') }}" style="display:block;margin-top:12px;color:var(--blue);font-weight:600;font-size:.88rem">Open comments &rarr;</a>
  </div>
</div>

<div style="display:grid;gap:20px;grid-template-columns:1fr 1fr;margin-top:20px">
  <div class="card">
    <h3 style="font-size:1rem;margin-bottom:14px">Top content by views</h3>
    @forelse ($topContent as $p)
      <div style="display:flex;justify-content:space-between;padding:8px 0;border-top:1px solid var(--line)"><span style="font-size:.9rem">{{ $p->title }}</span><b>{{ $p->views }}</b></div>
    @empty
      <p style="color:var(--grey);font-size:.9rem">No published content yet.</p>
    @endforelse
  </div>
  <div class="card">
    <h3 style="font-size:1rem;margin-bottom:14px">Upcoming</h3>
    @forelse ($upcoming as $u)
      <div style="padding:8px 0;border-top:1px solid var(--line)"><b style="font-size:.9rem">{{ $u->title }}</b>
        <div style="font-size:.8rem;color:var(--grey)">{{ $u->starts_at?->format('D, M j · g:i A') ?? \Illuminate\Support\Carbon::parse($u->meta('starts_at') ?? now())->format('D, M j') }}</div></div>
    @empty
      <p style="color:var(--grey);font-size:.9rem">Nothing scheduled.</p>
    @endforelse
  </div>
</div>
@endsection
