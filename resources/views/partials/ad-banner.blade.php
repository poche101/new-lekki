@php
  $bannerAds = collect($ads ?? [])->values();
  $mediaUrl = fn ($p) => $p ? (preg_match('#^https?://#i', $p) ? $p : asset('storage/' . ltrim($p, '/'))) : null;
@endphp

@once
  @push('styles')
  <style>
    .adbar-wrap { padding: 14px 0 0; }
    .adbar-wrap[hidden] { display: none; }
    .adbar { position: relative; background: #fff; overflow: hidden; border: 1px solid #dfe3ea; border-radius: 6px; box-shadow: 0 1px 3px rgba(15,22,41,.06); }
    .adbar-tools { position: absolute; top: 0; right: 0; z-index: 5; display: flex; background: rgba(243,244,246,.95); border-left: 1px solid #dfe3ea; border-bottom: 1px solid #dfe3ea; font-size: 11px; color: #5b6272; border-bottom-left-radius: 6px; }
    .adbar-tag { padding: 3px 8px; text-transform: uppercase; letter-spacing: .08em; font-weight: 600; }
    .adbar-close { border: 0; border-left: 1px solid #dfe3ea; background: transparent; color: #5b6272; font: inherit; padding: 3px 9px; cursor: pointer; }
    .adbar-close:hover { background: #e6e9ee; color: #111; }

    .adbar-track { display: flex; transition: transform .6s cubic-bezier(.65,0,.35,1); will-change: transform; }
    .adbar-slide { position: relative; flex: 0 0 100%; min-height: 160px; display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 24px 28px; text-decoration: none; color: #fff; overflow: hidden; background: #0f1629; }
    .adbar-media { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0; }
    .adbar-slide::before { content: ''; position: absolute; inset: 0; z-index: 1; background: linear-gradient(90deg, rgba(8,12,28,.78) 0%, rgba(8,12,28,.45) 55%, rgba(8,12,28,.15) 100%); }
    .adbar-copy, .adbar-cta { position: relative; z-index: 2; }
    .adbar-headline { margin: 0; max-width: 30ch; font-size: clamp(1.2rem, 2.4vw, 1.9rem); line-height: 1.15; font-weight: 800; letter-spacing: -.01em; color: #fff; }
    .adbar-sub { margin: 8px 0 0; max-width: 46ch; font-size: .92rem; color: rgba(255,255,255,.8); }
    .adbar-cta { flex-shrink: 0; display: inline-block; padding: 13px 24px; border-radius: 4px; background: #2f9e5b; color: #fff; font-weight: 800; font-size: .8rem; letter-spacing: .1em; text-transform: uppercase; transition: background .2s ease, transform .2s ease; }
    .adbar-slide:hover .adbar-cta { background: #26844b; transform: translateY(-1px); }

    .adbar-slide--default { background: radial-gradient(700px 300px at 90% -20%, rgba(122,167,255,.35), transparent 60%), radial-gradient(500px 260px at 0% 120%, rgba(217,184,102,.22), transparent 60%), #0f1629; }
    .adbar-slide--default::before { display: none; }
    .adbar-kicker { display: inline-block; margin-bottom: 8px; font-size: 11px; font-weight: 700; letter-spacing: .22em; text-transform: uppercase; color: #d9b866; }

    .adbar-dots { position: absolute; left: 0; right: 0; bottom: 8px; z-index: 5; display: flex; justify-content: center; gap: 6px; }
    .adbar-dots button { width: 7px; height: 7px; padding: 0; border: 0; border-radius: 50%; background: rgba(255,255,255,.5); cursor: pointer; }
    .adbar-dots button.is-active { background: #fff; width: 20px; border-radius: 4px; }

    @media (max-width: 700px) {
      .adbar-slide { flex-direction: column; align-items: flex-start; justify-content: center; padding: 38px 18px 30px; min-height: 190px; }
      .adbar-cta { width: 100%; text-align: center; padding: 12px 16px; }
    }
    @media (prefers-reduced-motion: reduce) { .adbar-track { transition: none; } }
  </style>
  @endpush
@endonce

<div class="adbar-wrap" id="adBarWrap">
  <div class="wrap">
    <div class="adbar" id="adBar" role="complementary" aria-label="Advertisement">
      <div class="adbar-tools">
        <span class="adbar-tag">Ad</span>
        <button class="adbar-close" id="adBarClose" type="button" aria-label="Close ad">Close ad &times;</button>
      </div>

      <div class="adbar-track" id="adBarTrack">
        @foreach ($bannerAds as $ad)
          @php $img = $mediaUrl($ad->image ?? null); $vid = $mediaUrl($ad->video ?? null); @endphp
          <a class="adbar-slide" href="{{ $ad->link ?: '#' }}" target="_blank" rel="sponsored noopener">
            @if ($vid)
              <video class="adbar-media" src="{{ $vid }}" @if ($img) poster="{{ $img }}" @endif muted playsinline preload="metadata" data-video></video>
            @elseif ($img)
              <img class="adbar-media" src="{{ $img }}" alt="" loading="lazy">
            @endif
            <div class="adbar-copy"><p class="adbar-headline">{{ $ad->title }}</p></div>
            <span class="adbar-cta">{{ $ad->banner_label ?: 'Learn more' }}</span>
          </a>
        @endforeach

        {{-- Default slide: always last, invites visitors to advertise --}}
        <a class="adbar-slide adbar-slide--default" href="{{ route('contact.create') }}">
          <div class="adbar-copy">
            <span class="adbar-kicker">Advertise with us</span>
            <p class="adbar-headline">Your business could be seen here</p>
            <p class="adbar-sub">Place your ad with images or video and reach our congregation and visitors every day.</p>
          </div>
          <span class="adbar-cta">Place your ad</span>
        </a>
      </div>

      <div class="adbar-dots" id="adBarDots" aria-hidden="true">
        @for ($i = 0; $i < $bannerAds->count() + 1; $i++)
          <button type="button" data-i="{{ $i }}" class="{{ $i === 0 ? 'is-active' : '' }}"></button>
        @endfor
      </div>
    </div>
  </div>
</div>

@once
  @push('scripts')
  <script>
    (function () {
      var wrap = document.getElementById('adBarWrap');
      if (!wrap) return;
      var KEY = 'adbar-closed';
      try { if (sessionStorage.getItem(KEY) === '1') { wrap.hidden = true; return; } } catch (e) {}

      document.getElementById('adBarClose').addEventListener('click', function () {
        wrap.hidden = true;
        try { sessionStorage.setItem(KEY, '1'); } catch (e) {}
      });

      var track = document.getElementById('adBarTrack');
      var slides = track.querySelectorAll('.adbar-slide');
      var dots = wrap.querySelectorAll('.adbar-dots button');
      var current = 0, timer = null;

      function clear() { clearTimeout(timer); }

      function show(n) {
        current = (n + slides.length) % slides.length;
        track.style.transform = 'translateX(-' + (current * 100) + '%)';
        dots.forEach(function (d, i) { d.classList.toggle('is-active', i === current); });

        slides.forEach(function (s, i) {
          var v = s.querySelector('video[data-video]');
          if (!v) return;
          if (i === current) { v.currentTime = 0; var p = v.play(); if (p && p.catch) p.catch(function () {}); }
          else v.pause();
        });
        schedule();
      }

      function schedule() {
        clear();
        if (slides.length < 2) return;
        var v = slides[current].querySelector('video[data-video]');
        if (v) {
          v.onended = function () { show(current + 1); };
          timer = setTimeout(function () { show(current + 1); }, 20000);
        } else {
          timer = setTimeout(function () { show(current + 1); }, 6000);
        }
      }

      dots.forEach(function (d) {
        d.addEventListener('click', function (e) { e.preventDefault(); show(parseInt(d.dataset.i, 10)); });
      });

      wrap.addEventListener('mouseenter', clear);
      wrap.addEventListener('mouseleave', schedule);
      show(0);
    })();
  </script>
  @endpush
@endonce
