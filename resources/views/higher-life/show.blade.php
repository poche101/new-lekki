@extends('layouts.app')
@section('title', $post->title . ' · Higher Life · Christ Embassy Lekki')
@section('content')
<div class="wrap">
  <article class="article hl-article" style="padding-top:36px">
    <div class="crumbs"><a href="{{ route('home') }}">Home</a> / <a href="{{ route('higher-life.index') }}">Higher Life</a></div>

    <div class="hl-player">
      @if ($post->video())
        @include('partials.video', ['video' => $post->video(), 'thumb' => $post->thumbnailUrl(), 'title' => $post->title])
      @else
        <div class="video-frame hl-novideo" @if ($post->thumbnailUrl()) style="background-image:url('{{ $post->thumbnailUrl() }}')" @endif><span>Video coming soon</span></div>
      @endif
    </div>

    <span class="hl-ep" style="margin-top:22px">@if ($post->meta('season'))Season {{ $post->meta('season') }} · @endif Episode {{ $post->meta('episode') }}</span>
    <h1 style="font-size:2rem;margin:6px 0 10px">{{ $post->title }}</h1>
    <div class="byline" style="margin-top:0">
      @if ($post->meta('speaker'))<span>{{ $post->meta('speaker') }}</span> &middot; @endif
      <span>{{ $post->published_at?->format('M j, Y') }}</span>
      @if ($post->meta('duration_mins')) &middot; <span>{{ $post->meta('duration_mins') }} min</span>@endif
    </div>

    @if ($post->excerpt_text)<p class="lede" style="margin-top:18px">{{ $post->excerpt_text }}</p>@endif
    @if ($post->body)<div class="article-body" style="margin-top:18px">{!! nl2br(e($post->body)) !!}</div>@endif

    @include('partials.comments', ['post' => $post])

    <div style="display:grid;gap:16px;grid-template-columns:1fr 1fr;margin-top:30px">
      @if ($older)<a class="card" href="{{ route('higher-life.show', $older) }}"><span style="color:var(--grey);font-size:.8rem">Previous episode</span><h3 style="font-size:1rem">{{ $older->title }}</h3></a>@else<span></span>@endif
      @if ($newer)<a class="card" href="{{ route('higher-life.show', $newer) }}"><span style="color:var(--grey);font-size:.8rem">Next episode</span><h3 style="font-size:1rem">{{ $newer->title }}</h3></a>@endif
    </div>
  </article>

  @if ($more->count())
    <section style="padding:30px 0 10px">
      <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:18px">
        <h2 style="font-size:1.4rem">More episodes</h2>
        <a href="{{ route('higher-life.index') }}" style="color:var(--blue);font-weight:600">Browse the archive</a>
      </div>
      <div class="hl-grid">
        @foreach ($more as $ep)
          @include('partials.episode-card', ['ep' => $ep])
        @endforeach
      </div>
    </section>
  @endif
</div>
@endsection
