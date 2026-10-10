@extends('layouts.app')
@section('title', 'Contact · Christ Embassy Lekki')
@section('content')
<div class="wrap page-head"><div class="crumbs"><a href="{{ route('home') }}">Home</a> / Contact</div><h1>Contact</h1></div>
<div class="wrap">
  <div class="card" style="max-width:640px">
    @if ($errors->any())<div class="errors">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('contact.store') }}">
      @csrf
      <div class="field" style="margin-bottom:12px"><label>Name</label><input type="text" name="name" required></div>
      <div class="field" style="margin-bottom:12px"><label>Email</label><input type="email" name="email" required></div>
      <div class="field" style="margin-bottom:12px"><label>Message</label><textarea name="message" required></textarea></div>
      <button class="btn btn-blue btn-block" type="submit">Send message</button>
    </form>
  </div>
</div>
@endsection
