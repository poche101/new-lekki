@extends('layouts.admin')
@section('title', 'Messages')
@section('content')
<p class="lede" style="margin-bottom:16px">Messages sent through the public Contact form. Unread messages are in bold; opening one marks it as read.</p>

<table class="a-table">
  <thead><tr><th>From</th><th>Message</th><th>Received</th><th></th></tr></thead>
  <tbody>
    @forelse ($messages as $m)
      <tr>
        <td>
          <b>{{ $m->name }}</b>
          @if (is_null($m->read_at)) <span class="chip">New</span> @endif
          <div style="color:var(--ink-soft);margin-top:4px">{{ $m->email }}</div>
        </td>
        <td style="{{ is_null($m->read_at) ? 'font-weight:700' : '' }}">{{ \Illuminate\Support\Str::limit($m->message, 80) }}</td>
        <td>{{ $m->created_at->diffForHumans() }}</td>
        <td style="white-space:nowrap">
          <a class="btn btn-ghost" href="{{ route('admin.messages.show', $m) }}">View</a>
          <form method="POST" action="{{ route('admin.messages.destroy', $m) }}" style="display:inline" onsubmit="return confirm('Delete this message?')">@csrf @method('DELETE')<button class="btn btn-ghost" style="color:#DC2626" type="submit">Delete</button></form>
        </td>
      </tr>
    @empty
      <tr><td colspan="4" style="text-align:center;color:var(--grey);padding:30px">No messages yet.</td></tr>
    @endforelse
  </tbody>
</table>
<div style="margin-top:16px">{{ $messages->links() }}</div>
@endsection
