@extends('layouts.admin')
@section('title', 'Notifications')
@section('content')
<p class="lede" style="margin-bottom:16px">{{ $subscriberCount }} subscribers. Actually delivering to a browser needs VAPID keys configured (see README) — until then, "send" queues delivery rows but nothing is pushed to a real device.</p>
<div style="display:grid;grid-template-columns:1.3fr 1fr;gap:20px">
  <div class="card">
    <h3 style="font-size:.95rem;margin-bottom:10px">Compose</h3>
    <form method="POST" action="{{ route('admin.notifications.store') }}">
      @csrf
      <div class="field" style="margin-bottom:10px"><label>Title</label><input type="text" name="title" maxlength="80" required></div>
      <div class="field" style="margin-bottom:10px"><label>Message</label><textarea name="message" maxlength="160" required></textarea></div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px">
        <select name="topic_id"><option value="">No topic</option>@foreach ($topics as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach</select>
        <select name="audience"><option value="all">All subscribers</option><option value="topic">Topic subscribers</option><option value="members">Members only</option></select>
      </div>
      <div class="field" style="margin-bottom:14px"><label>Destination link</label><input type="text" name="url" placeholder="/blog/some-post"></div>
      <label style="display:flex;gap:8px;align-items:center;font-size:.9rem;margin-bottom:14px"><input type="checkbox" name="send_now" value="1" checked> Send now</label>
      <button class="btn btn-blue" type="submit">Send</button>
    </form>
  </div>
  <div class="card">
    <h3 style="font-size:.95rem;margin-bottom:10px">History</h3>
    @forelse ($history as $n)
      <div style="padding:10px 0;border-top:1px solid var(--line)">
        <b style="font-size:.88rem">{{ $n->title }}</b>
        <div style="font-size:.78rem;color:var(--grey)">{{ $n->topic->name ?? 'No topic' }} &middot; {{ $n->deliveries_count }} queued &middot; {{ $n->sent_at?->diffForHumans() ?? 'Scheduled' }}</div>
      </div>
    @empty
      <p style="color:var(--grey);font-size:.9rem">Nothing sent yet.</p>
    @endforelse
  </div>
</div>
@endsection
