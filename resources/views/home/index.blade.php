@extends('layouts.app')
@section('title', 'Christ Embassy Lekki')
@section('content')

@foreach ($sections as $section)
  @php $label = $section['label']; @endphp

  @if ($label === 'Media console')
    <section class="hero">
      <div class="wrap">
        <div class="console">
          <div class="console-stage has-video console-stage--compact" id="consoleStage" oncontextmenu="return false">
            <span class="console-badge is-live" id="consoleBadge"><span>&bull;</span> Live now</span>
            {{-- The active tab's video is mounted here by public/js/video.js. --}}
            <div class="console-player" id="consolePlayer" data-no-download="true" oncontextmenu="return false"></div>
          </div>
          <div class="console-caption" id="consoleCaption"></div>
          <div class="console-tabs" role="tablist" style="display:flex;gap:10px;padding:14px 16px;flex-wrap:wrap">
            @php
              // Fallback so the tab strip still renders if the controller returns no capsules.
              $capsuleTabs = count($capsules ?? []) ? $capsules : [
                ['label' => 'Live'],
                ['label' => 'Excerpts'],
                ['label' => 'Featured'],
              ];
            @endphp
            @foreach ($capsuleTabs as $i => $cap)
              <button class="console-tab {{ $i === 0 ? 'is-active' : '' }}" type="button" role="tab" data-i="{{ $i }}"
                      data-label="{{ $cap['label'] }}"
                      data-kind="{{ $cap['video']['kind'] ?? '' }}"
                      data-src="{{ $cap['video']['src'] ?? '' }}"
                      data-thumb="{{ $cap['thumb'] ?? '' }}"
                      data-title="{{ $cap['title'] ?? '' }}"
                      data-schedule="{{ $cap['schedule'] ?? '' }}"
                      data-fallback="{{ $cap['fallback'] ?? '' }}"
                      data-comment-url="{{ $cap['comment_url'] ?? '' }}"
                      data-comments-on="{{ !empty($cap['comments_on']) ? '1' : '0' }}"
                      data-comments="{{ json_encode($cap['comments'] ?? []) }}"
                      style="padding:9px 18px;border-radius:999px;border:1px solid rgba(255,255,255,.25);font-weight:600;font-size:.9rem;cursor:pointer;
                             background:{{ $i === 0 ? '#fff' : 'rgba(255,255,255,.08)' }};
                             color:{{ $i === 0 ? '#111' : '#fff' }};">
                {{ $cap['label'] }}
              </button>
            @endforeach
          </div>
        </div>
        <div class="console-comments">
          <div style="display:flex;justify-content:space-between;margin-bottom:10px">
            <b style="font-size:.9rem">Comments</b>
            <span id="ccCount" style="font-size:.78rem;color:var(--grey)"></span>
          </div>
          <div id="ccList"></div>
          <p id="ccNote" style="display:none;color:var(--grey);font-size:.85rem;margin-top:8px"></p>

          {{-- Posts to the active tab's post via comments.store (see video.js). --}}
          <form id="ccForm" class="cc-input" style="margin-top:14px" autocomplete="off">
            @csrf
            @guest
              <input type="text" name="guest_name" maxlength="60" placeholder="Your name (optional)" style="width:100%;margin-bottom:8px;padding:9px 12px;border:1px solid var(--line, #e2e2e2);border-radius:8px">
            @endguest
            <div style="display:flex;gap:8px">
              <input type="text" name="body" maxlength="1000" required placeholder="Add a comment…" style="flex:1;padding:9px 12px;border:1px solid var(--line, #e2e2e2);border-radius:8px">
              <button class="btn btn-blue" type="submit">Post</button>
            </div>
            <p id="ccError" style="color:#c0392b;font-size:.8rem;margin-top:6px"></p>
          </form>
        </div>
      </div>
    </section>
    @push('styles')
    <style>
      .console-stage--compact{aspect-ratio:16/9;max-height:none}
      #consolePlayer{position:absolute;inset:0}
      #consolePlayer video,#consolePlayer iframe{
        width:100% !important;height:100% !important;object-fit:cover !important;border:0;
      }
    </style>
    @endpush
    @push('scripts')
    <script>
      (function () {
        function hardenVideo(video) {
          if (video.dataset.hardened) return;
          video.dataset.hardened = '1';
          video.setAttribute('controlsList', 'nodownload noremoteplayback');
          video.disablePictureInPicture = true;
          video.addEventListener('contextmenu', e => e.preventDefault());
        }
        document.querySelectorAll('video').forEach(hardenVideo);
        new MutationObserver(mutations => {
          mutations.forEach(m => m.addedNodes.forEach(node => {
            if (!(node instanceof HTMLElement)) return;
            if (node.tagName === 'VIDEO') hardenVideo(node);
            node.querySelectorAll && node.querySelectorAll('video').forEach(hardenVideo);
          }));
        }).observe(document.body, { childList: true, subtree: true });
        document.addEventListener('contextmenu', e => {
          if (e.target.tagName === 'VIDEO') e.preventDefault();
        }, true);
      })();
    </script>
    @endpush

  @elseif ($label === 'Worship with Us')
    <section style="padding-top:0">
      <div class="wrap">
        <div class="worship">
          <div class="worship-info">
            <div class="worship-icon"><svg viewBox="0 0 24 24"><path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/></svg></div>
            <h2>Worship with Us</h2>
            <p class="w-name">Christ Embassy Lekki</p>
            <p class="w-addr">LoveWorld Arena, Aare Bashiru Street, Chisco B/S<br>Lekki-Epe Expressway, Lagos</p>
          </div>
          <div class="worship-action">
            <form class="field" id="routeForm">
              <label for="fromInput">Get directions</label>
              <input id="fromInput" type="text" placeholder="Enter your location (e.g. Ikeja)">
              <button class="btn btn-blue btn-block" style="margin-top:12px" type="submit">Get Route</button>
            </form>
            <p style="margin-top:16px"><a href="tel:+2349076415312" style="text-decoration:underline">+234 907 641 5312</a></p>
          </div>
        </div>
      </div>
    </section>

  @elseif ($label === 'Welcome')
    <section>
      <div class="wrap">
        <span class="chip" style="margin-bottom:10px">Who we are</span>
        <h2 style="font-size:2rem;max-width:16ch">Our Vision</h2>
        <p class="lede">Our Vision is to take the divine presence of God to the nations and peoples of the world, and to demonstrate the character of the Spirit</p>
      </div>
    </section>

  @elseif ($label === 'Latest excerpts')
    <section>
      <div class="wrap">
        <h2 style="margin-bottom:20px">Latest excerpts</h2>
        <div class="grid">
          @forelse ($excerpts as $e)
            <div class="card">
              <div style="margin-bottom:10px">
                @if ($e->video())
                  @include('partials.video', ['video' => $e->video(), 'thumb' => $e->thumbnailUrl(), 'title' => $e->title])
                @elseif ($e->thumbnailUrl())
                  <a href="{{ route('blog.show', $e) }}"><img class="post-img" src="{{ $e->thumbnailUrl() }}" alt="{{ $e->title }}" loading="lazy"></a>
                @else
                  <div class="post-thumb"></div>
                @endif
              </div>
              <a href="{{ route('blog.show', $e) }}"><h3 style="font-size:1.05rem">{{ $e->title }}</h3></a>
              <p style="color:var(--grey);font-size:.85rem;margin-top:6px">{{ $e->published_at?->format('M j') }}</p>
            </div>
          @empty
            <p style="color:var(--grey)">No excerpts published yet.</p>
          @endforelse
        </div>
      </div>
    </section>

  @elseif ($label === 'Higher Life')
    @push('styles')
    <style>
      .hlx {
        --hlx-bg: var(--ink, #0f1629);
        --hlx-accent: var(--blue-soft, #7aa7ff);
        --hlx-gold: #d9b866;
        position: relative; overflow: hidden; color: #fff;
        background:
          radial-gradient(900px 420px at 85% -10%, rgba(122,167,255,.16), transparent 60%),
          radial-gradient(700px 380px at -5% 110%, rgba(217,184,102,.10), transparent 60%),
          var(--hlx-bg);
        padding: 88px 0 96px;
      }
      .hlx::before {
        content: ''; position: absolute; inset: 0 0 auto 0; height: 1px;
        background: linear-gradient(90deg, transparent, rgba(217,184,102,.55), transparent);
      }

      .hlx-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; flex-wrap: wrap; margin-bottom: 36px; }
      .hlx-eyebrow { display: block; font-size: 12px; font-weight: 600; letter-spacing: .28em; text-transform: uppercase; color: var(--hlx-gold); margin-bottom: 12px; }
      .hlx-title { font-family: 'Playfair Display', Georgia, 'Times New Roman', serif; font-size: clamp(2rem, 4vw, 2.8rem); font-weight: 600; line-height: 1.1; margin: 0; color: #fff; }
      .hlx-title::after { content: ''; display: block; width: 56px; height: 2px; margin-top: 16px; background: linear-gradient(90deg, var(--hlx-gold), transparent); }
      .hlx-all {
        display: inline-flex; align-items: center; gap: 8px; color: var(--hlx-accent); font-weight: 600; font-size: .95rem;
        padding: 9px 18px; border: 1px solid rgba(122,167,255,.35); border-radius: 999px; text-decoration: none;
        transition: background .25s ease, color .25s ease, border-color .25s ease;
      }
      .hlx-all span { transition: transform .25s ease; }
      .hlx-all:hover { background: rgba(122,167,255,.12); border-color: var(--hlx-accent); }
      .hlx-all:hover span { transform: translateX(4px); }

      .hlx-feature {
        display: grid; grid-template-columns: 1.35fr 1fr; gap: 0; align-items: stretch;
        background: linear-gradient(145deg, rgba(255,255,255,.07), rgba(255,255,255,.025));
        border: 1px solid rgba(255,255,255,.10); border-radius: 26px; overflow: hidden;
        box-shadow: 0 40px 80px -40px rgba(0,0,0,.7), inset 0 1px 0 rgba(255,255,255,.06);
      }
      .hlx-player { padding: 18px; }
      .hlx-player > *, .hlx-player .video-frame {
        border-radius: 16px; overflow: hidden; width: 100%;
      }
      .hlx-player video, .hlx-player iframe, .hlx-player .video-frame { display: block; border-radius: 16px; }
      .hlx-player { box-shadow: none; }
      .hlx-player .hl-novideo {
        position: relative; display: flex; align-items: center; justify-content: center; aspect-ratio: 16/9;
        background-size: cover; background-position: center; background-color: #17213f; text-decoration: none;
      }
      .hlx-player .hl-novideo::before { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(8,12,28,.15), rgba(8,12,28,.65)); }
      .hlx-player .hl-novideo span {
        position: relative; z-index: 1; color: #fff; font-weight: 600; padding: 12px 26px; border-radius: 999px;
        background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.35); backdrop-filter: blur(6px);
      }

      .hlx-info { padding: 44px 44px 44px 30px; display: flex; flex-direction: column; justify-content: center; }
      .hlx-pill {
        align-self: flex-start; display: inline-flex; align-items: center; gap: 8px;
        font-size: 11px; font-weight: 700; letter-spacing: .2em; text-transform: uppercase;
        color: var(--hlx-gold); background: rgba(217,184,102,.10); border: 1px solid rgba(217,184,102,.35);
        padding: 7px 14px; border-radius: 999px; margin-bottom: 22px;
      }
      .hlx-pill i { width: 6px; height: 6px; border-radius: 50%; background: var(--hlx-gold); box-shadow: 0 0 0 4px rgba(217,184,102,.2); }
      .hlx-ep { font-size: 12px; font-weight: 600; letter-spacing: .2em; text-transform: uppercase; color: var(--hlx-accent); }
      .hlx-info h3 { font-family: 'Playfair Display', Georgia, serif; font-size: clamp(1.5rem, 2.4vw, 2.1rem); line-height: 1.2; font-weight: 600; color: #fff; margin: 10px 0 0; }
      .hlx-info p { color: rgba(255,255,255,.68); line-height: 1.85; font-size: 1rem; margin: 16px 0 0; }
      .hlx-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 30px; }
      .hlx-btn {
        display: inline-flex; align-items: center; gap: 10px; padding: 13px 26px; border-radius: 999px;
        font-weight: 600; font-size: .95rem; text-decoration: none; transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
      }
      .hlx-btn svg { width: 16px; height: 16px; }
      .hlx-btn-primary { background: linear-gradient(135deg, #e6c978, #b8913f); color: #1a1405; box-shadow: 0 12px 28px -12px rgba(217,184,102,.7); }
      .hlx-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 18px 34px -12px rgba(217,184,102,.8); }

      .hlx-empty { color: rgba(255,255,255,.6); padding: 40px; text-align: center; border: 1px dashed rgba(255,255,255,.2); border-radius: 20px; }

      .hlx-more-label { display: flex; align-items: center; gap: 16px; margin: 56px 0 24px; font-size: 12px; font-weight: 600; letter-spacing: .26em; text-transform: uppercase; color: rgba(255,255,255,.55); }
      .hlx-more-label::after { content: ''; flex: 1; height: 1px; background: linear-gradient(90deg, rgba(255,255,255,.18), transparent); }
      .hlx .hl-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 24px; margin-top: 0 !important; }
      .hlx .hl-grid > * { transition: transform .3s ease; }
      .hlx .hl-grid > *:hover { transform: translateY(-6px); }

      @media (max-width: 900px) {
        .hlx { padding: 64px 0 72px; }
        .hlx-feature { grid-template-columns: 1fr; }
        .hlx-info { padding: 8px 28px 34px; }
      }
      @media (max-width: 520px) {
        .hlx-player { padding: 12px; }
        .hlx-info { padding: 8px 20px 28px; }
        .hlx-btn { width: 100%; justify-content: center; }
      }
    </style>
    @endpush

    <section class="hlx">
      <div class="wrap">

        <div class="hlx-head">
          <div>
            <span class="hlx-eyebrow">Featured Series</span>
            <h2 class="hlx-title">Higher Life</h2>
          </div>
          <a href="{{ route('higher-life.index') }}" class="hlx-all">View all episodes <span>&rarr;</span></a>
        </div>

        @if ($higherLifeFeatured)
          <div class="hlx-feature">
            <div class="hlx-player">
              @if ($higherLifeFeatured->video())
                @include('partials.video', ['video' => $higherLifeFeatured->video(), 'thumb' => $higherLifeFeatured->thumbnailUrl(), 'title' => $higherLifeFeatured->title])
              @else
                <a class="video-frame hl-novideo" href="{{ route('higher-life.show', $higherLifeFeatured) }}" @if ($higherLifeFeatured->thumbnailUrl()) style="background-image:url('{{ $higherLifeFeatured->thumbnailUrl() }}')" @endif><span>Watch episode</span></a>
              @endif
            </div>

            <div class="hlx-info">
              <span class="hlx-pill"><i></i> Latest Episode</span>
              <span class="hlx-ep">
                @if ($higherLifeFeatured->meta('season'))Season {{ $higherLifeFeatured->meta('season') }} · @endif
                Episode {{ $higherLifeFeatured->meta('episode') }}
              </span>
              <h3>{{ $higherLifeFeatured->title }}</h3>
              @if ($higherLifeFeatured->excerpt_text)
                <p>{{ $higherLifeFeatured->excerpt_text }}</p>
              @endif
              <div class="hlx-actions">
                <a class="hlx-btn hlx-btn-primary" href="{{ route('higher-life.show', $higherLifeFeatured) }}">
                  <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                  Episode page
                </a>
              </div>
            </div>
          </div>
        @else
          <div class="hlx-empty">No Higher Life episodes published yet.</div>
        @endif

        @if ($higherLifeList->count())
          <div class="hlx-more-label">More Episodes</div>
          <div class="hl-grid hl-grid-dark">
            @foreach ($higherLifeList as $ep)
              @include('partials.episode-card', ['ep' => $ep])
            @endforeach
          </div>
        @endif

      </div>
    </section>


  @elseif ($label === 'Upcoming events')
    <section>
      <div class="wrap">
        <h2 style="margin-bottom:20px">Upcoming events</h2>
        @forelse ($events as $ev)
          <div class="event-row" style="align-items:center">
            @if ($ev->thumbnailUrl())
              <img src="{{ $ev->thumbnailUrl() }}" alt="{{ $ev->title }}" loading="lazy" style="width:112px;aspect-ratio:16/9;object-fit:cover;border-radius:10px;flex-shrink:0">
            @endif
            <div class="event-date"><div class="d">{{ \Illuminate\Support\Carbon::parse($ev->meta('starts_at'))->format('d') }}</div><div style="font-size:.72rem;color:var(--grey)">{{ \Illuminate\Support\Carbon::parse($ev->meta('starts_at'))->format('M') }}</div></div>
            <div style="flex:1"><h4 style="font-size:1.02rem">{{ $ev->title }}</h4><p style="color:var(--grey);font-size:.85rem">{{ $ev->meta('location') }}</p></div>
            <a class="btn btn-ghost" href="{{ $ev->publicUrl() }}">Details</a>
          </div>
        @empty
          <p style="color:var(--grey)">No upcoming events.</p>
        @endforelse
      </div>
    </section>

  @elseif ($label === 'Fellowships and Departments')
    <section>
      <div class="wrap">
        <h2 style="margin-bottom:20px">Fellowships and Departments</h2>
        @foreach ($departments as $d)
          <div class="dept-row">
            <div style="flex:1"><h4>{{ $d->name }}</h4><p style="color:var(--grey);font-size:.9rem;margin-top:4px">{{ $d->note }}</p>
              <a href="tel:{{ $d->phone }}" style="color:var(--blue);font-weight:600;font-size:.9rem;display:inline-block;margin-top:8px">{{ $d->formattedPhone() }}</a></div>
          </div>
        @endforeach
        @if ($departmentsTotal > 5)
          <a class="btn btn-ghost" href="{{ route('fellowships.index') }}">View more ({{ $departmentsTotal - 5 }} more)</a>
        @endif
      </div>
    </section>

  @elseif ($label === 'Testimonies')
    <section style="background:var(--paper)">
      <div class="wrap">
        @forelse ($testimonies->take(1) as $t)
          <p style="font-family:'Lora',Georgia,serif;font-style:italic;font-size:1.3rem;max-width:34ch">&ldquo;{{ $t->body }}&rdquo;</p>
          <p style="margin-top:14px;color:var(--grey)">&mdash; {{ $t->title }}</p>
        @empty
          <p style="color:var(--grey)">No testimonies published yet.</p>
        @endforelse
      </div>
    </section>

  @elseif ($label === 'Prayer request band')
    <section style="background:var(--ink);color:#fff">
      <div class="wrap">
        <h3 style="color:#fff;font-size:1.4rem">Send us a prayer request.</h3>
        <p style="color:rgba(255,255,255,.65);margin-top:10px">Our prayer team reads and prays over every request.</p>
        <a class="btn btn-ghost" style="border-color:rgba(255,255,255,.4);color:#fff;margin-top:16px" href="{{ route('prayer.create') }}">Request prayer</a>
      </div>
    </section>
  @endif
@endforeach

@foreach ($ads as $ad)
  {{-- Ad popups: rendered but hidden; site.js shows/alternates them per admin > Ads config --}}
  <template data-ad data-title="{{ $ad->title }}" data-banner="{{ $ad->banner_label }}" data-link="{{ $ad->link }}"></template>
@endforeach

@push('scripts')
<script src="{{ asset('js/site.js') }}"></script>
@endpush
@endsection
