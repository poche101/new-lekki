@extends('layouts.app')
@section('title', 'Live · Christ Embassy Lekki')
@section('content')

<style>
  .live-page { --lv-gold: #b8913f; overflow-x: hidden; }
  .live-page * { box-sizing: border-box; }

  /* Header */
  .live-page .page-head { padding-left: clamp(16px, 4vw, 24px); padding-right: clamp(16px, 4vw, 24px); }
  .live-page .page-head h1 { font-size: clamp(2rem, 6vw, 3rem); line-height: 1.1; word-break: break-word; }
  .live-page .page-head .lede { max-width: 640px; font-size: clamp(.95rem, 2.4vw, 1.08rem); line-height: 1.75; }
  .live-page .crumbs { font-size: clamp(.8rem, 2vw, .9rem); flex-wrap: wrap; }

  /* Stage */
  .live-page .wrap { padding-left: clamp(16px, 4vw, 24px); padding-right: clamp(16px, 4vw, 24px); }
  .live-page .console { width: 100%; }
  .live-page .console-stage {
    position: relative; width: 100%; max-width: 100%; overflow: hidden;
    border-radius: clamp(12px, 2.4vw, 22px);
    box-shadow: 0 30px 60px -34px rgba(15,27,61,.55);
  }
  .live-page .console-player { width: 100%; }
  .live-page .console-player > *,
  .live-page .console-player .video-frame { width: 100%; max-width: 100%; }
  .live-page .console-player iframe,
  .live-page .console-player video {
    display: block; width: 100%; max-width: 100%; height: auto; aspect-ratio: 16 / 9; border: 0;
  }
  .live-page .console-badge {
    position: absolute; top: clamp(10px, 2vw, 18px); left: clamp(10px, 2vw, 18px); z-index: 3;
    font-size: clamp(.7rem, 1.8vw, .85rem);
  }

  /* Empty state */
  .live-page .console-empty {
    display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;
    gap: 8px; width: 100%; min-height: clamp(220px, 42vw, 440px); padding: clamp(20px, 5vw, 48px);
  }
  .live-page .console-empty b { font-size: clamp(1.1rem, 3.4vw, 1.6rem); line-height: 1.3; }
  .live-page .console-empty span { font-size: clamp(.88rem, 2.2vw, 1rem); line-height: 1.6; max-width: 520px; }

  /* Replays */
  .live-replays { padding: clamp(28px, 6vw, 48px) 0 clamp(40px, 8vw, 72px); }
  .live-replays h2 { font-size: clamp(1.2rem, 3.4vw, 1.6rem); margin: 0 0 clamp(14px, 3vw, 22px); }
  .live-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 280px), 1fr)); gap: clamp(14px, 2.4vw, 24px); }
  .live-card { min-width: 0; overflow: hidden; }
  .live-card .live-thumb { margin-bottom: 12px; border-radius: 12px; overflow: hidden; }
  .live-card .live-thumb iframe,
  .live-card .live-thumb video { display: block; width: 100%; max-width: 100%; aspect-ratio: 16 / 9; height: auto; border: 0; }
  .live-card h3 { font-size: clamp(.98rem, 2.4vw, 1.1rem); line-height: 1.4; margin: 0; overflow-wrap: anywhere; }

  @media (max-width: 480px) {
    .live-page .console-stage { border-radius: 12px; margin-left: -4px; margin-right: -4px; }
    .live-grid { grid-template-columns: 1fr; }
  }
  @media (min-width: 1400px) {
    .live-page .wrap { max-width: 1240px; }
  }
</style>

<div class="live-page">
  <div class="wrap page-head">
    <div class="crumbs"><a href="{{ route('home') }}">Home</a> / Live</div>
    <h1>Live</h1>
    <p class="lede">Join Christ Embassy Lekki online. Services stream here: {{ $schedule }}.</p>
  </div>

  <div class="wrap">
    <div class="console">
      <div class="console-stage has-video">
        @if ($video)
          <span class="console-badge is-live"><span>&bull;</span> Live</span>
          <div class="console-player">@include('partials.video', ['video' => $video, 'thumb' => null, 'title' => 'Livestream'])</div>
        @else
          <div class="console-empty"><b>No livestream right now</b><span>{{ $schedule }}</span>@if ($fallback)<span>{{ $fallback }}</span>@endif</div>
        @endif
      </div>
    </div>

    @if ($replays->count())
      <section class="live-replays">
        <h2>Recent excerpts</h2>
        <div class="live-grid">
          @foreach ($replays as $e)
            <div class="card live-card">
              <div class="live-thumb">
                @if ($e->video())
                  @include('partials.video', ['video' => $e->video(), 'thumb' => $e->thumbnailUrl(), 'title' => $e->title])
                @else
                  <a href="{{ route('blog.show', $e) }}" style="display:block;aspect-ratio:16/9;background:#17213f center/cover no-repeat @if ($e->thumbnailUrl()) url('{{ $e->thumbnailUrl() }}') @endif"></a>
                @endif
              </div>
              <a href="{{ route('blog.show', $e) }}"><h3>{{ $e->title }}</h3></a>
            </div>
          @endforeach
        </div>
      </section>
    @endif
  </div>
</div>
@endsection
