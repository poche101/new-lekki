@extends('layouts.app')
@section('title', 'Prayer Request · Christ Embassy Lekki')
@section('content')
<div class="wrap page-head"><div class="crumbs"><a href="{{ route('home') }}">Home</a> / Prayer request</div><h1>Prayer Request</h1><p class="lede">Our prayer team reads and prays over every request.</p></div>
<div class="wrap">
  <div class="card" style="max-width:640px">
    @if ($errors->any())<div class="errors">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('prayer.store') }}">
      @csrf
      <div class="field" style="margin-bottom:12px"><label>Your name (optional)</label><input type="text" name="name"></div>
      <div class="field" style="margin-bottom:12px"><label>Email or phone (optional)</label><input type="text" name="contact"></div>
      <div class="field" style="margin-bottom:12px"><label>Category</label><select name="category"><option>Healing</option><option>Family</option><option>Career and finances</option><option>Guidance</option><option>Thanksgiving</option><option>Other</option></select></div>
      <div class="field" style="margin-bottom:12px"><label>Your request</label><textarea name="message" required></textarea></div>
      <button class="btn btn-blue btn-block" type="submit">Send request</button>
    </form>
  </div>
</div>
@endsection
