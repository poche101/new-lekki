{{-- Higher Life episode card: thumbnail, episode number, title. @include('partials.episode-card', ['ep' => $episode]) --}}
@php($thumb = $ep->thumbnailUrl())
<a class="hl-card" href="{{ route('higher-life.show', $ep) }}">
  <div class="hl-card-thumb" @if ($thumb) style="background-image:url('{{ $thumb }}')" @endif>
    <span class="play-btn play-btn-sm"><svg viewBox="0 0 24 24"><path d="M6 4l14 8-14 8z"/></svg></span>
    @if ($ep->meta('duration_mins'))<span class="hl-duration">{{ $ep->meta('duration_mins') }} min</span>@endif
  </div>
  <div class="hl-card-body">
    <span class="hl-ep">@if ($ep->meta('season'))Season {{ $ep->meta('season') }} · @endif Episode {{ $ep->meta('episode') }}</span>
    <h3>{{ $ep->title }}</h3>
    <p>@if ($ep->meta('speaker')){{ $ep->meta('speaker') }} · @endif{{ $ep->published_at?->format('M j, Y') }}</p>
  </div>
</a>
