<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Admin') · Christ Embassy Lekki</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}">
<style>
  /* Mobile admin menu */
  .a-menu-btn {
    display: none; align-items: center; justify-content: center;
    width: 40px; height: 40px; margin-right: 12px; flex-shrink: 0;
    background: #fff; border: 1px solid var(--line, #e2e2e2); border-radius: 10px; cursor: pointer;
  }
  .a-menu-btn svg { width: 22px; height: 22px; }
  .a-overlay { display: none; }
  .a-side-close { display: none; }
  .a-top-title { display: flex; align-items: center; min-width: 0; }
  .a-top-title h1 { margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

  @media (max-width: 900px) {
    .a-menu-btn { display: inline-flex; }
    .a-side {
      display: block !important;
      position: fixed !important; top: 0; left: 0; bottom: 0;
      width: 270px; max-width: 84vw; height: 100vh;
      overflow-y: auto; z-index: 1001;
      transform: translateX(-100%); transition: transform .25s ease;
      box-shadow: none;
    }
    body.nav-open .a-side { transform: translateX(0); box-shadow: 0 0 40px rgba(0,0,0,.35); }
    .a-main { margin-left: 0 !important; width: 100% !important; max-width: 100% !important; }
    .a-overlay {
      display: block; position: fixed; inset: 0; z-index: 1000;
      background: rgba(10,15,30,.55); opacity: 0; pointer-events: none; transition: opacity .25s ease;
    }
    body.nav-open .a-overlay { opacity: 1; pointer-events: auto; }
    body.nav-open { overflow: hidden; }
    .a-side-close {
      display: inline-flex; align-items: center; justify-content: center;
      position: absolute; top: 10px; right: 10px; width: 34px; height: 34px;
      background: rgba(255,255,255,.08); color: #fff; border: 0; border-radius: 8px; font-size: 1.3rem; cursor: pointer;
    }
    .a-top { gap: 8px; }
    .a-top form .btn-line { font-size: .8rem; padding: 8px 10px; }
  }
</style>
</head>
<body style="background:var(--ivory)">
@php($user = auth()->user())

<div class="a-overlay" id="aOverlay"></div>

<aside class="a-side" id="aSide" aria-label="Admin navigation">
  <button class="a-side-close" id="aClose" type="button" aria-label="Close menu">&times;</button>
  <div style="color:#fff;font-weight:700;padding:6px 12px 18px">Christ Embassy <span style="color:var(--blue-soft)">Lekki</span><small style="display:block;font-size:.68rem;color:#64748b">ADMIN &middot; {{ $user->roleLabel() }}</small></div>
  <nav>
    <div class="grp">Overview</div>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-current' : '' }}">Dashboard</a>
    <div class="grp">Website</div>
    @can('homepage')<a href="{{ route('admin.homepage.edit') }}" class="{{ request()->routeIs('admin.homepage.*') ? 'is-current' : '' }}">Homepage</a>@endcan
    @can('content')
      <a href="{{ route('admin.content.index') }}" class="{{ request()->routeIs('admin.content.*') ? 'is-current' : '' }}">Content</a>
      <a href="{{ route('admin.departments.index') }}" class="{{ request()->routeIs('admin.departments.*') ? 'is-current' : '' }}">Fellowships &amp; Depts</a>
    @endcan
    @can('media')<a href="{{ route('admin.media.index') }}" class="{{ request()->routeIs('admin.media.*') ? 'is-current' : '' }}">Media</a>@endcan
    @can('ads')<a href="{{ route('admin.ads.index') }}" class="{{ request()->routeIs('admin.ads.*') ? 'is-current' : '' }}">Ads</a>@endcan
    <div class="grp">Community</div>
    @can('members')<a href="{{ route('admin.members.index') }}" class="{{ request()->routeIs('admin.members.*') ? 'is-current' : '' }}">Members</a>@endcan
    @can('moderate')<a href="{{ route('admin.comments.index') }}" class="{{ request()->routeIs('admin.comments.*') ? 'is-current' : '' }}">Comments</a>@endcan
    @can('notify')<a href="{{ route('admin.notifications.index') }}" class="{{ request()->routeIs('admin.notifications.*') ? 'is-current' : '' }}">Notifications</a>@endcan
    <div class="grp">Insights &amp; access</div>
    @can('analytics')<a href="{{ route('admin.analytics.index') }}" class="{{ request()->routeIs('admin.analytics.*') ? 'is-current' : '' }}">Analytics</a>@endcan
    @if ($user->role === 'super_admin')
      <a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.*') ? 'is-current' : '' }}">Roles &amp; permissions</a>
      <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'is-current' : '' }}">Settings</a>
    @endif
  </nav>
  <div style="margin-top:20px;padding-top:14px;border-top:1px solid rgba(255,255,255,.1)"><a href="{{ route('home') }}" style="color:var(--blue-soft)">View website</a></div>
</aside>

<div class="a-main">
  <div class="a-top">
    <div class="a-top-title">
      <button class="a-menu-btn" id="aMenuBtn" type="button" aria-label="Open menu" aria-controls="aSide" aria-expanded="false">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <h1 style="font-size:1.2rem">@yield('title')</h1>
    </div>
    <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn-line" style="border:1px solid var(--line);border-radius:10px" type="submit">Sign out ({{ $user->name }})</button></form>
  </div>
  @if (session('status'))<div class="notice" style="margin-bottom:18px">{{ session('status') }}</div>@endif
  @if ($errors->any())<div class="errors" style="margin-bottom:18px">{{ $errors->first() }}</div>@endif
  @yield('content')
</div>

<script>
  (function () {
    var body = document.body;
    var btn = document.getElementById('aMenuBtn');
    var side = document.getElementById('aSide');
    var overlay = document.getElementById('aOverlay');
    var closeBtn = document.getElementById('aClose');

    function setOpen(open) {
      body.classList.toggle('nav-open', open);
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    btn.addEventListener('click', function () { setOpen(!body.classList.contains('nav-open')); });
    overlay.addEventListener('click', function () { setOpen(false); });
    closeBtn.addEventListener('click', function () { setOpen(false); });
    side.addEventListener('click', function (e) { if (e.target.closest('a')) setOpen(false); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setOpen(false); });
    window.addEventListener('resize', function () { if (window.innerWidth > 900) setOpen(false); });
  })();
</script>
</body>
</html>
