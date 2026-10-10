@extends('layouts.app')
@section('title', 'Fellowships & Departments · Christ Embassy Lekki')
@section('content')
<div class="wrap page-head"><div class="crumbs"><a href="{{ route('home') }}">Home</a> / Fellowships</div><h1>Fellowships and Departments</h1><p class="lede">Find your place to belong and serve. Call any group directly.</p></div>
<div class="wrap">
  @foreach ($departments as $d)
    <div class="dept-row">
      <div style="flex:1"><h4>{{ $d->name }}</h4><p style="color:var(--grey);font-size:.9rem;margin-top:4px">{{ $d->note }}</p>
        <a href="tel:{{ $d->phone }}" style="color:var(--blue);font-weight:600;font-size:.9rem;display:inline-block;margin-top:8px">{{ $d->formattedPhone() }}</a></div>
    </div>
  @endforeach
</div>
@endsection
