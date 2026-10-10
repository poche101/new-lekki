@extends('layouts.app')
@section('title', $post->title . ' · Christ Embassy Lekki')
@section('content')
<div class="wrap">
  <article class="article" style="padding-top:36px">
    <div class="crumbs"><a href="{{ route('home') }}">Home</a> / <a href="{{ route('blog.index') }}">Blog</a></div>
    <span class="chip">{{ ucfirst(str_replace('_', ' ', $post->type)) }}</span>
    <h1 style="font-size:2rem;margin:10px 0">{{ $post->title }}</h1>
    <div class="byline"><span>{{ $post->author->name ?? 'Christ Embassy Lekki' }}</span> &middot; <span>{{ $post->published_at?->format('M j, Y') }}</span> &middot; <span>{{ $post->readTime() }}</span></div>
    @if ($post->video())
      <div style="margin:24px 0">@include('partials.video', ['video' => $post->video(), 'thumb' => $post->thumbnailUrl(), 'title' => $post->title])</div>
    @elseif ($post->thumbnailUrl())
      <img src="{{ $post->thumbnailUrl() }}" alt="{{ $post->title }}" style="display:block;width:100%;aspect-ratio:16/9;object-fit:cover;border-radius:14px;margin:24px 0">
    @else
      <div class="post-thumb" style="aspect-ratio:16/9;margin:24px 0"></div>
    @endif

    @if ($post->type === 'event')
      <div class="card">
        <div style="display:flex;justify-content:space-between;padding:8px 0"><span>Date</span><b>{{ \Illuminate\Support\Carbon::parse($post->meta('starts_at'))->format('M j, Y') }}</b></div>
        <div style="display:flex;justify-content:space-between;padding:8px 0"><span>Location</span><b>{{ $post->meta('location') }}</b></div>
      </div>
    @endif

    <div class="article-body">{!! nl2br(e($post->body ?: $post->excerpt_text)) !!}</div>

    @if ($post->comments_enabled)
      <div style="margin-top:36px;border-top:1px solid var(--line);padding-top:24px">
        <h3 style="font-size:1.2rem;margin-bottom:16px">Comments ({{ $post->comments->count() }})</h3>
        @foreach ($post->comments as $c)
          <div style="display:flex;gap:12px;padding:12px 0;border-top:1px solid var(--line)">
            <div class="cc-avatar"></div>
            <div><b style="font-size:.9rem">{{ $c->authorName() }}{{ $c->is_admin_reply ? ' · Church team' : '' }}</b>
              <p style="font-size:.92rem;margin-top:2px">{{ $c->body }}</p></div>
          </div>
        @endforeach
        <form method="POST" action="{{ route('comments.store', $post) }}" style="margin-top:18px">
          @csrf
          @guest
            <div class="field" style="margin-bottom:10px"><input type="text" name="guest_name" placeholder="Your name (optional)"></div>
          @endguest
          <div class="field"><textarea name="body" placeholder="Add a comment…" required></textarea></div>
          <button class="btn btn-blue" type="submit" style="margin-top:10px">Post comment</button>
        </form>
      </div>
    @endif

    <div style="display:grid;gap:16px;grid-template-columns:1fr 1fr;margin-top:30px">
      @if ($older)<a class="card" href="{{ route('blog.show', $older) }}"><span style="color:var(--grey);font-size:.8rem">Older</span><h3 style="font-size:1rem">{{ $older->title }}</h3></a>@else<span></span>@endif
      @if ($newer)<a class="card" href="{{ route('blog.show', $newer) }}"><span style="color:var(--grey);font-size:.8rem">Newer</span><h3 style="font-size:1rem">{{ $newer->title }}</h3></a>@endif
    </div>
  </article>
</div>
@endsection
