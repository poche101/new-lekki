@extends('layouts.admin')
@section('title', 'Message from ' . $message->name)
@section('content')
<div class="a-toolbar">
  <a class="btn btn-ghost" href="{{ route('admin.messages.index') }}">&larr; Back to messages</a>
</div>

<div class="card" style="max-width:720px">
  <p style="margin-bottom:6px"><b>{{ $message->name }}</b></p>
  <p style="margin-bottom:6px"><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></p>
  <p style="color:var(--ink-soft);margin-bottom:0">Received {{ $message->created_at->format('M j, Y g:i A') }} ({{ $message->created_at->diffForHumans() }})</p>

  <hr style="margin:18px 0;border:0;border-top:1px solid var(--line)">

  <p style="white-space:pre-line;margin:0">{{ $message->message }}</p>

  <div style="margin-top:22px;display:flex;gap:10px;flex-wrap:wrap">
    <a class="btn btn-blue" href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: Your message to Christ Embassy Lekki') }}">Reply by email</a>
    <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Delete this message?')">
      @csrf
      @method('DELETE')
      <button class="btn btn-ghost" style="color:#DC2626" type="submit">Delete</button>
    </form>
  </div>
</div>
@endsection
