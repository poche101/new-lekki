<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Christ Embassy Lekki')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Lora:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}">
@stack('styles')

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XEYZS7KCY7"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-XEYZS7KCY7');
</script>
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header">
  <div class="wrap">
    <a class="wordmark" href="{{ route('home') }}"><img src="/images/my-logo.png" alt="logo" style="width:85px; height:85px;"></a>
    <nav class="primary-nav" aria-label="Primary">
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('pages.show', 'about') }}">About</a></li>
        <li><a href="{{ route('fellowships.index') }}">Fellowships</a></li>
        <li><a href="{{ route('higher-life.index') }}">Higher Life</a></li>
        <li><a href="{{ route('blog.index') }}">Blog</a></li>
        <li><a href="{{ route('live.index') }}">Live</a></li>
        <li><a href="{{ route('contact.create') }}">Contact</a></li>
      </ul>
    </nav>
    <div class="header-actions">
      <a class="icon-btn" href="{{ route('search.index') }}" aria-label="Search"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg></a>
      @auth
        <form class="hide-mobile" method="POST" action="{{ route('logout') }}">@csrf<button class="btn-line" type="submit">Sign out</button></form>
      @else
        <a class="btn-line hide-mobile" href="{{ route('login') }}">Sign in</a>
      @endauth
      <button class="nav-toggle" id="navToggle" type="button" aria-label="Open menu" aria-controls="mobileMenu" aria-expanded="false">
        <svg viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
      </button>
    </div>
  </div>
</header>

{{-- Mobile menu --}}
<div class="mm-overlay" id="mmOverlay" hidden></div>
<aside class="mm-panel" id="mobileMenu" aria-label="Mobile menu" aria-hidden="true">
  <div class="mm-head">
    <a class="wordmark" href="{{ route('home') }}"> <img src="/images/my-logo.png" alt="logo" style="width:85px; height:85px;"></a>
    <button class="mm-close" id="mmClose" type="button" aria-label="Close menu">
      <svg viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>
    </button>
  </div>
  <ul class="mm-links">
    <li><a href="{{ route('home') }}" @if (request()->routeIs('home')) aria-current="page" @endif>Home</a></li>
    <li><a href="{{ route('pages.show', 'about') }}">About</a></li>
    <li><a href="{{ route('fellowships.index') }}" @if (request()->routeIs('fellowships.*')) aria-current="page" @endif>Fellowships</a></li>
    <li><a href="{{ route('higher-life.index') }}" @if (request()->routeIs('higher-life.*')) aria-current="page" @endif>Higher Life</a></li>
    <li><a href="{{ route('blog.index') }}" @if (request()->routeIs('blog.*')) aria-current="page" @endif>Blog</a></li>
    <li><a href="{{ route('live.index') }}" @if (request()->routeIs('live.*')) aria-current="page" @endif>Live</a></li>
    <li><a href="{{ route('prayer.create') }}">Prayer request</a></li>
    <li><a href="{{ route('contact.create') }}" @if (request()->routeIs('contact.*')) aria-current="page" @endif>Contact</a></li>
    <li><a href="{{ route('search.index') }}">Search</a></li>
  </ul>
  <div class="mm-foot">
    <a class="btn btn-blue" href="{{ route('giving.redirect') }}">Give</a>
    @auth
      <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn-line" type="submit">Sign out</button></form>
    @else
      <a class="btn-line" href="{{ route('login') }}">Sign in</a>
    @endauth
  </div>
</aside>

<main id="main">
@if (session('status'))
  <div class="wrap"><div class="notice" style="margin-top:16px">{{ session('status') }}</div></div>
@endif
@yield('content')
</main>

<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div>
        <a class="wordmark" href="{{ route('home') }}"> <img src="/images/my-logo.png" alt="logo" style="width:85px; height:85px;"></a>
        <div class="foot-word">Christ Embassy <span>Lekki</span></div>
        <p style="max-width:32ch;font-size:0.88rem;">A digital ministry platform for worship, teaching, and community — online and in person.</p>
        <a class="btn btn-blue" style="margin-top:20px;" href="{{ route('giving.redirect') }}">Give</a>
      </div>
      <div>
        <h5>Explore</h5>
        <ul>
          <li><a href="{{ route('higher-life.index') }}">Higher Life</a></li>
          <li><a href="{{ route('blog.index') }}">Blog &amp; Excerpts</a></li>
          <li><a href="{{ route('blog.index', ['type' => 'event']) }}">Events</a></li>
          <li><a href="{{ route('live.index') }}">Live</a></li>
        </ul>
      </div>
      <div>
        <h5>Church</h5>
        <ul>
          <li><a href="{{ route('pages.show', 'about') }}">About us</a></li>
          <li><a href="{{ route('fellowships.index') }}">Fellowships &amp; Departments</a></li>
          <li><a href="{{ route('prayer.create') }}">Prayer request</a></li>
          <li><a href="{{ route('contact.create') }}">Contact</a></li>
        </ul>
      </div>
      <div>
        <h5>Account &amp; legal</h5>
        <ul>
          <li><a href="{{ route('login') }}">Member sign in</a></li>
          <li><a href="{{ route('pages.show', 'privacy') }}">Privacy policy</a></li>
          <li><a href="{{ route('pages.show', 'terms') }}">Terms of use</a></li>
        </ul>
      </div>
    </div>
    <div class="foot-bottom">
      <span>&copy; {{ date('Y') }} Christ Embassy Lekki. All rights reserved.</span>
    </div>
  </div>
</footer>
<script src="{{ asset('js/video.js') }}?v={{ filemtime(public_path('js/video.js')) }}" defer></script>
<script>
(function () {
  var toggle = document.getElementById('navToggle'), panel = document.getElementById('mobileMenu'),
      overlay = document.getElementById('mmOverlay'), closeBtn = document.getElementById('mmClose');
  if (!toggle || !panel) return;
  function setOpen(open) {
    document.body.classList.toggle('mm-open', open);
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    panel.setAttribute('aria-hidden', open ? 'false' : 'true');
    overlay.hidden = !open;
    if (open) closeBtn.focus(); else toggle.focus({ preventScroll: true });
  }
  toggle.addEventListener('click', function () { setOpen(true); });
  closeBtn.addEventListener('click', function () { setOpen(false); });
  overlay.addEventListener('click', function () { setOpen(false); });
  panel.addEventListener('click', function (e) { if (e.target.closest('a')) setOpen(false); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && document.body.classList.contains('mm-open')) setOpen(false); });
  window.matchMedia('(min-width: 861px)').addEventListener('change', function (m) { if (m.matches && document.body.classList.contains('mm-open')) setOpen(false); });
})();
</script>
@stack('scripts')
</body>
</html>
