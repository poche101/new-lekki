@extends('layouts.app')
@section('title', 'Register · Christ Embassy Lekki')
@section('content')
<div class="wrap" style="padding:60px 0;max-width:440px">
  <h1 style="font-size:1.5rem;margin-bottom:20px">Create an account</h1>
  @if ($errors->any())<div class="errors">{{ $errors->first() }}</div>@endif
  <form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="field" style="margin-bottom:12px"><label>Full name</label><input type="text" name="name" value="{{ old('name') }}" required></div>
    <div class="field" style="margin-bottom:12px"><label>Email</label><input type="email" name="email" value="{{ old('email') }}" required></div>
    <div class="field" style="margin-bottom:12px"><label>Password</label><input type="password" name="password" required></div>
    <div class="field" style="margin-bottom:14px"><label>Confirm password</label><input type="password" name="password_confirmation" required></div>
    <button class="btn btn-blue btn-block" type="submit">Create account</button>
  </form>
</div>
@endsection
