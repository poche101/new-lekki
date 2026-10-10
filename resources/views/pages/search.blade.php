@extends('layouts.app')
@section('title', 'Search · Christ Embassy Lekki')
@section('content')
<div class="wrap page-head"><h1>Search</h1></div>
<div class="wrap">
  <form method="GET" action="{{ route('search.index') }}" style="margin-bottom:24px">
    <input class="field" style="border:1px solid var(--line);border-radius:12px;padding:12px 16px;width:100%;max-width:480px" type="search" name="q" value="{{ $q }}" placeholder="Search the whole site">
  </form>
  <div class="grid">
    @forelse ($results as $r)
      <a class="card" href="{{ $r['url'] }}"><span class="chip">{{ $r['type'] }}</span><h3 style="font-size:1.05rem;margin-top:8px">{{ $r['title'] }}</h3><p style="color:var(--grey);font-size:.85rem;margin-top:6px">{{ $r['meta'] }}</p></a>
    @empty
      <p style="color:var(--grey)">{{ strlen($q) >= 2 ? 'No results found.' : 'Type at least two letters to search.' }}</p>
    @endforelse
  </div>
</div>
@endsection
