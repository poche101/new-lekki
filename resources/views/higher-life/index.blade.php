@extends('layouts.app')
@section('title', 'Higher Life · Christ Embassy Lekki')
@section('content')
<div class="wrap page-head">
  <div class="crumbs"><a href="{{ route('home') }}">Home</a> / Higher Life</div>
  <h1>Higher Life</h1>
  <p class="lede">The full archive of Higher Life episodes from Christ Embassy Lekki. {{ $total }} {{ Str::plural('episode', $total) }} and counting — watch, revisit and share.</p>
</div>

<div class="wrap">
  <div class="blog-bar">
    <div class="blog-tabs">
      <a class="blog-tab {{ ! $activeSeason ? 'is-active' : '' }}" href="{{ route('higher-life.index', array_filter(['q' => $q])) }}">All seasons</a>
      @foreach ($seasons as $s => $count)
        <a class="blog-tab {{ (int) $activeSeason === (int) $s ? 'is-active' : '' }}" href="{{ route('higher-life.index', array_filter(['season' => $s, 'q' => $q])) }}">Season {{ $s }} <span style="color:var(--grey);font-weight:500">({{ $count }})</span></a>
      @endforeach
    </div>
    <form method="GET" action="{{ route('higher-life.index') }}">
      @if ($activeSeason)<input type="hidden" name="season" value="{{ $activeSeason }}">@endif
      <input class="field" style="border:1px solid var(--line);border-radius:12px;padding:9px 14px" type="search" name="q" value="{{ $q }}" placeholder="Search title or speaker">
    </form>
  </div>

  @if ($featured && $episodes->currentPage() === 1)
    <a class="hl-hero" href="{{ route('higher-life.show', $featured) }}">
      <div class="hl-hero-thumb" @if ($featured->thumbnailUrl()) style="background-image:url('{{ $featured->thumbnailUrl() }}')" @endif>
        <span class="play-btn"><svg viewBox="0 0 24 24"><path d="M6 4l14 8-14 8z"/></svg></span>
      </div>
      <div class="hl-hero-body">
        <span class="chip">Featured episode</span>
        <span class="hl-ep" style="margin-top:12px">@if ($featured->meta('season'))Season {{ $featured->meta('season') }} · @endif Episode {{ $featured->meta('episode') }}</span>
        <h2>{{ $featured->title }}</h2>
        <p>{{ $featured->excerpt_text }}</p>
        <div class="byline">@if ($featured->meta('speaker'))<span>{{ $featured->meta('speaker') }}</span> &middot; @endif<span>{{ $featured->published_at?->format('M j, Y') }}</span>@if ($featured->meta('duration_mins')) &middot; <span>{{ $featured->meta('duration_mins') }} min</span>@endif</div>
        <span class="btn btn-blue" style="margin-top:18px;align-self:flex-start">Watch episode</span>
      </div>
    </a>
  @endif

  @if ($episodes->count())
    <div class="hl-grid">
      @foreach ($episodes as $ep)
        @include('partials.episode-card', ['ep' => $ep])
      @endforeach
    </div>
    <div style="margin-top:28px">{{ $episodes->links() }}</div>
  @elseif (! $featured)
    <p style="color:var(--grey);padding:30px 0">{{ ($q || $activeSeason) ? 'No episodes match your search.' : 'No Higher Life episodes have been published yet.' }}</p>
  @endif
</div>
@endsection
