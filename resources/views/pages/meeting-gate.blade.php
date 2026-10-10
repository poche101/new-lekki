@extends('layouts.app')
@section('title', 'Meeting · Christ Embassy Lekki')
@section('content')
<div class="wrap" style="padding:60px 0;text-align:center">
  <div class="card" style="max-width:440px;margin:0 auto;padding:36px">
    <h2 style="font-size:1.4rem">Members only</h2>
    <p style="color:var(--grey);margin:10px 0 20px">Sign in to see your meetings and join a room.</p>
    <a class="btn btn-blue" href="{{ route('login') }}">Sign in</a>
    <a class="btn btn-ghost" href="{{ route('register') }}">Register</a>
  </div>
</div>
@endsection
