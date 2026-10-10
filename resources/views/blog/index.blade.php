@extends('layouts.app')
@section('title', 'Blog · Christ Embassy Lekki')
@section('content')
<div class="wrap page-head">
  <div class="crumbs"><a href="{{ route('home') }}">Home</a> / Blog</div>
  <h1>Christ Embassy Lekki Blog</h1>
  <p class="lede">Articles, excerpts, announcements and events. Everything published here also appears on the homepage.</p>
</div>
<div class="wrap">
  <div class="blog-bar">
    <div class="blog-tabs">
      <a class="blog-tab {{ !$activeType ? 'is-active' : '' }}" href="{{ route('blog.index') }}">All</a>
      @foreach ($tabs as $key => $label)
        <a class="blog-tab {{ $activeType === $key ? 'is-active' : '' }}" href="{{ route('blog.index', ['type' => $key]) }}">{{ $label }}</a>
      @endforeach
    </div>
    <form method="GET" action="{{ route('blog.index') }}">
      <input class="field" style="border:1px solid var(--line);border-radius:12px;padding:9px 14px" type="search" name="q" value="{{ $q }}" placeholder="Search the blog">
    </form>
  </div>

  @if ($featured)
    <article class="feat">
      <a class="feat-img" href="{{ route('blog.show', $featured) }}" @if ($featured->thumbnailUrl()) style="background-image:url('{{ $featured->thumbnailUrl() }}');background-size:cover;background-position:center" @endif></a>
      <div class="feat-body">
        <span class="chip">Featured</span>
        <h2 style="font-size:1.8rem;margin:10px 0"><a href="{{ route('blog.show', $featured) }}">{{ $featured->title }}</a></h2>
        <p>{{ $featured->excerpt_text }}</p>
        <div class="byline"><span>{{ $featured->author->name ?? 'Christ Embassy Lekki' }}</span> &middot; <span>{{ $featured->published_at?->format('M j, Y') }}</span> &middot; <span>{{ $featured->readTime() }}</span></div>
      </div>
    </article>
  @endif

  <div class="blog-layout">
    <div>
      @forelse ($posts as $post)
        <article class="post">
          <a class="post-thumb" href="{{ route('blog.show', $post) }}" @if ($post->thumbnailUrl()) style="background-image:url('{{ $post->thumbnailUrl() }}');background-size:cover;background-position:center" @endif></a>
          <div>
            <span class="chip">{{ $tabs[$post->type] ?? ucfirst($post->type) }}</span>
            <h3 style="margin:6px 0 8px"><a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a></h3>
            <p style="color:var(--ink-soft)">{{ $post->excerpt_text }}</p>
            <div class="byline"><span>{{ $post->author->name ?? 'Christ Embassy Lekki' }}</span> &middot; <span>{{ $post->published_at?->format('M j, Y') }}</span></div>
          </div>
        </article>
      @empty
        <p style="color:var(--grey);padding:30px 0">No posts found.</p>
      @endforelse
      <div style="margin-top:20px">{{ $posts->links() }}</div>
    </div>

    <aside>
      <div class="widget">
        <h4 style="font-size:.74rem;text-transform:uppercase;color:var(--grey);letter-spacing:.1em">Categories</h4>
        @foreach ($tabs as $key => $label)
          <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line);font-size:.9rem">
            <a href="{{ route('blog.index', ['type' => $key]) }}">{{ $label }}</a><span style="color:var(--grey)">{{ $categoryCounts[$key] ?? 0 }}</span>
          </div>
        @endforeach
      </div>
      <div class="widget">
        <h4 style="font-size:.74rem;text-transform:uppercase;color:var(--grey);letter-spacing:.1em">Recent posts</h4>
        @foreach ($recent as $r)
          <a href="{{ route('blog.show', $r) }}" style="display:block;padding:10px 0;border-bottom:1px solid var(--line)"><b style="display:block;font-size:.9rem">{{ $r->title }}</b><span style="font-size:.78rem;color:var(--grey)">{{ $r->published_at?->format('M j') }}</span></a>
        @endforeach
      </div>
    </aside>
  </div>
</div>
@endsection
