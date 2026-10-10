<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Admin') · Christ Embassy Lekki</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}">
</head>
<body style="background:var(--ivory)">
@php($user = auth()->user())
<aside class="a-side">
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
    <h1 style="font-size:1.2rem">@yield('title')</h1>
    <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn-line" style="border:1px solid var(--line);border-radius:10px" type="submit">Sign out ({{ $user->name }})</button></form>
  </div>
  @if (session('status'))<div class="notice" style="margin-bottom:18px">{{ session('status') }}</div>@endif
  @if ($errors->any())<div class="errors" style="margin-bottom:18px">{{ $errors->first() }}</div>@endif
  @yield('content')
</div>
</body>
</html>
