@extends('layouts.app')
@section('title', 'Meeting · Christ Embassy Lekki')
@section('content')
<div class="wrap page-head"><h1>Meeting</h1><p class="lede">Meetings you have access to, based on your groups.</p></div>
<div class="wrap grid">
  @forelse ($meetings as $m)
    <div class="card"><h3 style="font-size:1.05rem">{{ $m->title }}</h3><p style="color:var(--grey);font-size:.9rem;margin-top:6px">{{ $m->starts_at->format('D, M j · g:i A') }}</p>
      @if ($m->room_url)<a class="btn btn-blue" style="margin-top:14px" href="{{ $m->room_url }}">Join room</a>@endif
    </div>
  @empty
    <p style="color:var(--grey)">No upcoming meetings you have access to.</p>
  @endforelse
</div>
@endsection
