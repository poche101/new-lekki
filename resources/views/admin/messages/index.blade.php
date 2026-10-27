@extends('layouts.app')
@section('title', 'Messages · Admin')
@section('content')
<div class="wrap page-head"><h1>Contact Messages</h1></div>
<div class="wrap">
  <div class="card">
    @if (session('status'))<div style="margin-bottom:12px">{{ session('status') }}</div>@endif
    <table style="width:100%;border-collapse:collapse">
      <thead>
        <tr style="text-align:left">
          <th>Name</th><th>Email</th><th>Message</th><th>Received</th><th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($messages as $m)
          <tr style="{{ $m->read_at ? '' : 'font-weight:700' }}">
            <td>{{ $m->name }}</td>
            <td>{{ $m->email }}</td>
            <td>{{ \Illuminate\Support\Str::limit($m->message, 60) }}</td>
            <td>{{ $m->created_at->format('M j, Y g:i A') }}</td>
            <td><a href="{{ route('admin.messages.show', $m) }}">View</a></td>
          </tr>
        @empty
          <tr><td colspan="5">No messages yet.</td></tr>
        @endforelse
      </tbody>
    </table>
    <div style="margin-top:16px">{{ $messages->links() }}</div>
  </div>
</div>
@endsection
