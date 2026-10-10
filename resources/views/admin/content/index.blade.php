@extends('layouts.admin')
@section('title', 'Content manager')
@section('content')
<p class="lede" style="margin-bottom:16px">Excerpts, Best Dresser entries and events published here appear on the public blog and homepage automatically. Excerpts and Higher Life episodes can carry a video link; Higher Life and Best Dresser entries need an image.</p>
<div class="a-toolbar">
  @foreach (['article' => 'Articles', 'excerpt' => 'Excerpts', 'event' => 'Events', 'best_dresser' => 'Best Dresser', 'announcement' => 'Announcements', 'higher_life' => 'Higher Life', 'page' => 'Pages', 'testimony' => 'Testimonials'] as $key => $label)
    <a class="btn {{ $type === $key ? 'btn-blue' : 'btn-ghost' }}" href="{{ route('admin.content.index', ['type' => $key]) }}">{{ $label }}</a>
  @endforeach
  <a class="btn btn-blue" style="margin-left:auto" href="{{ route('admin.content.create', ['type' => $type]) }}">+ New</a>
</div>

<table class="a-table">
  <thead><tr><th>Title</th><th>Status</th><th>Published</th><th>Author</th><th></th></tr></thead>
  <tbody>
    @forelse ($posts as $post)
      <tr>
        <td>
          <div style="display:flex;gap:12px;align-items:center">
            @if (in_array($post->type, ['excerpt', 'higher_life', 'best_dresser'], true))
              <div style="width:64px;aspect-ratio:16/9;border-radius:8px;background:#0b1120 center/cover no-repeat;flex:none;{{ $post->thumbnailUrl() ? "background-image:url('" . $post->thumbnailUrl() . "')" : '' }}"></div>
            @endif
            <div>{{ $post->title }}@if ($post->video())<span class="chip" style="margin-left:8px">Video</span>@endif</div>
          </div>
        </td>
        <td><span class="chip">{{ ucfirst($post->status) }}</span></td>
        <td>{{ $post->published_at?->format('M j, Y') ?? '—' }}</td>
        <td>{{ $post->author->name ?? '—' }}</td>
        <td style="text-align:right;white-space:nowrap">
          <a class="btn btn-ghost" href="{{ route('admin.content.edit', $post) }}">Edit</a>
          <button type="button" class="btn btn-ghost js-delete" style="color:#DC2626" data-action="{{ route('admin.content.destroy', $post) }}" data-name="{{ $post->title }}">Delete</button>
        </td>
      </tr>
    @empty
      <tr><td colspan="5" style="text-align:center;color:var(--grey);padding:30px">Nothing here yet.</td></tr>
    @endforelse
  </tbody>
</table>
<div style="margin-top:16px">{{ $posts->links() }}</div>
@include('admin.partials.delete-modal')
@endsection
