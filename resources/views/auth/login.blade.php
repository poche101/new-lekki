@extends('layouts.app')
@section('title', 'Sign in · Christ Embassy Lekki')
@section('content')
<div class="wrap" style="padding:60px 0;max-width:440px">
  <h1 style="font-size:1.5rem;margin-bottom:20px">Sign in</h1>
  @if ($errors->any())<div class="errors">{{ $errors->first() }}</div>@endif
  <form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="field" style="margin-bottom:12px"><label>Email</label><input type="email" name="email" value="{{ old('email') }}" required autofocus></div>
    <div class="field" style="margin-bottom:12px"><label>Password</label><input type="password" name="password" required></div>
    <label style="display:flex;gap:8px;align-items:center;font-size:.9rem;margin-bottom:14px"><input type="checkbox" name="remember"> Remember me</label>
    <button class="btn btn-blue btn-block" type="submit">Sign in</button>
  </form>
  <p style="margin-top:16px;font-size:.9rem">New here? <a href="{{ route('register') }}" style="color:var(--blue)">Create an account</a></p>
</div>
@endsection
