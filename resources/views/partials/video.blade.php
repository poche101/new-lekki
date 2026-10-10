{{--
  Player for a link entered in the admin.
  @include('partials.video', ['video' => $post->video(), 'thumb' => $post->thumbnailUrl(), 'title' => $post->title])
  YouTube/Vimeo with a thumbnail show the thumbnail first and load the real player on click (keeps pages fast).
--}}
@php($thumb = $thumb ?? null)
@php($title = $title ?? 'video')
@if ($video)
  @if ($video['kind'] === 'iframe')
    @if ($thumb)
      <button type="button" class="video-frame video-facade" data-embed="{{ $video['src'] }}"
              style="background-image:url('{{ $thumb }}')" aria-label="Play {{ $title }}">
        <span class="play-btn"><svg viewBox="0 0 24 24"><path d="M6 4l14 8-14 8z"/></svg></span>
      </button>
    @else
      <iframe class="video-frame" src="{{ $video['src'] }}" title="{{ $title }}" loading="lazy" allowfullscreen
              allow="accelerometer; autoplay; encrypted-media; picture-in-picture"></iframe>
    @endif
  @elseif ($video['kind'] === 'hls')
    <video class="video-frame" data-hls="{{ $video['src'] }}" controls playsinline @if ($thumb) poster="{{ $thumb }}" @endif></video>
  @else
    <video class="video-frame" controls playsinline preload="metadata" @if ($thumb) poster="{{ $thumb }}" @endif>
      <source src="{{ $video['src'] }}">
    </video>
  @endif
@endif
