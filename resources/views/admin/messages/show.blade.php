@extends('layouts.app')
@section('title', 'Message from ' . $message->name)
@section('content')
<div class="wrap page-head">
  <div class="crumbs"><a href="{{ route('admin.messages.index') }}">Messages</a> / {{ $message->name }}</div>
  <h1>Message from {{ $message->name }}</h1>
</div>
<div class="wrap">
  <div class="card" style="max-width:720px">
    <p><strong>Email:</strong> <a href="mailto:{{ $message->email }}">{{ $message->email }}</a></p>
    <p><strong>Received:</strong> {{ $message->created_at->format('M j, Y g:i A') }}</p>
    <hr style="margin:16px 0">
    <p style="white-space:pre-line">{{ $message->message }}</p>

    <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" style="margin-top:20px"
          onsubmit="return confirm('Delete this message?')">
      @csrf
      @method('DELETE')
      <button class="btn" type="submit">Delete</button>
    </form>
  </div>
</div>
@endsection
