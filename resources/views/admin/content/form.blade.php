@extends('layouts.admin')
@section('title', $post->exists ? 'Edit content' : 'New content')
@section('content')
<form method="POST" action="{{ $post->exists ? route('admin.content.update', $post) : route('admin.content.store') }}" class="card" style="max-width:720px" enctype="multipart/form-data">
  @csrf
  @if ($post->exists) @method('PUT') @endif
  <input type="hidden" name="type" value="{{ $type }}">

  <div class="field" style="margin-bottom:14px"><label>Title</label><input type="text" name="title" value="{{ old('title', $post->title) }}" required></div>
  <div class="field" style="margin-bottom:14px"><label>Short summary (shown in listings)</label><input type="text" name="excerpt_text" value="{{ old('excerpt_text', $post->excerpt_text) }}"></div>
  <div class="field" style="margin-bottom:14px"><label>Body</label><textarea name="body" style="min-height:160px">{{ old('body', $post->body) }}</textarea></div>

  @if (in_array($type, ['excerpt', 'higher_life'], true))
    @php $isEpisode = $type === 'higher_life'; @endphp
    <div class="field" style="margin-bottom:14px">
      <label>Video link {{ $isEpisode ? '' : '(optional)' }}</label>
      <input type="url" name="video_url" value="{{ old('video_url', $post->meta('video_url')) }}" placeholder="https://www.youtube.com/watch?v=..." {{ $isEpisode ? 'required' : '' }}>
      <div style="font-size:.78rem;color:var(--grey);margin-top:4px">YouTube, Vimeo, or a direct .mp4 / .webm / .m3u8 link. It plays on the website automatically.{{ $isEpisode ? '' : ' Leave empty for an image-only or text-only excerpt.' }}</div>
    </div>
    <div class="field" style="margin-bottom:14px">
      <label>{{ $isEpisode ? 'Thumbnail' : 'Image (optional)' }}</label>
      <input type="file" name="thumbnail" accept="image/*" {{ $isEpisode && ! $post->meta('thumbnail') ? 'required' : '' }}>
      @if ($post->meta('thumbnail'))
        <img src="{{ $post->thumbnailUrl() }}" alt="" style="width:200px;aspect-ratio:16/9;object-fit:cover;border-radius:10px;margin-top:8px">
        @unless ($isEpisode)
          <label style="display:flex;gap:8px;align-items:center;font-size:.85rem;margin-top:8px;font-weight:500"><input type="checkbox" name="remove_thumbnail" value="1" style="width:auto"> Remove this image</label>
        @endunless
      @endif
      <div style="font-size:.78rem;color:var(--grey);margin-top:4px">16:9 image, up to 2 MB.{{ $isEpisode ? ' Shown on the homepage, the archive and before the video plays.' : ' Shown on the excerpt card; if a video link is set, it is the picture shown before the video plays.' }}</div>
    </div>
  @endif

  @if ($type === 'event')
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
      <div class="field"><label>Event date</label><input type="date" name="event_starts_at" value="{{ old('event_starts_at', $post->meta('starts_at')) }}"></div>
      <div class="field"><label>Time and place</label><input type="text" name="event_location" value="{{ old('event_location', $post->meta('location')) }}" placeholder="9:00 AM, Main Auditorium"></div>
    </div>
    <div class="field" style="margin-bottom:14px">
      <label>Event photo (optional)</label>
      <input type="file" name="thumbnail" accept="image/*">
      @if ($post->meta('thumbnail'))
        <img src="{{ $post->thumbnailUrl() }}" alt="" style="width:200px;aspect-ratio:16/9;object-fit:cover;border-radius:10px;margin-top:8px">
        <label style="display:flex;gap:8px;align-items:center;font-size:.85rem;margin-top:8px;font-weight:500"><input type="checkbox" name="remove_thumbnail" value="1" style="width:auto"> Remove this photo</label>
      @endif
      <div style="font-size:.78rem;color:var(--grey);margin-top:4px">A flyer or banner image (16:9 works best), up to 2 MB. It appears next to the event in the Upcoming events section on the homepage.</div>
    </div>
  @elseif ($type === 'higher_life')
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
      <div class="field"><label>Episode number</label><input type="number" name="episode" value="{{ old('episode', $post->meta('episode')) }}"></div>
      <div class="field"><label>Season</label><input type="number" name="season" value="{{ old('season', $post->meta('season')) }}"></div>
      <div class="field"><label>Speaker</label><input type="text" name="speaker" value="{{ old('speaker', $post->meta('speaker')) }}"></div>
      <div class="field"><label>Duration (minutes)</label><input type="number" name="duration_mins" value="{{ old('duration_mins', $post->meta('duration_mins')) }}"></div>
    </div>
  @elseif ($type === 'best_dresser')
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
      <div class="field"><label>Name</label><input type="text" name="dresser_name" value="{{ old('dresser_name', $post->meta('name')) }}"></div>
      <div class="field"><label>Month</label><input type="text" name="month" value="{{ old('month', $post->meta('month')) }}"></div>
    </div>
    <label style="display:flex;gap:8px;align-items:center;font-size:.9rem;margin-bottom:14px"><input type="checkbox" name="featured" value="1" {{ $post->meta('featured') ? 'checked' : '' }}> Feature this week</label>
    <div class="field" style="margin-bottom:14px">
      <label>Photo</label>
      <input type="file" name="thumbnail" accept="image/*" {{ $post->meta('thumbnail') ? '' : 'required' }}>
      @if ($post->meta('thumbnail'))<img src="{{ $post->thumbnailUrl() }}" alt="" style="width:140px;aspect-ratio:4/5;object-fit:cover;border-radius:10px;margin-top:8px">@endif
      <div style="font-size:.78rem;color:var(--grey);margin-top:4px">A portrait photo (4:5 works best), up to 2 MB. It appears in the Sunday Best Dressers section on the homepage.</div>
    </div>
  @endif

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
    <div class="field"><label>Status</label><select name="status">
      @foreach (['draft', 'scheduled', 'published', 'archived'] as $s)<option value="{{ $s }}" {{ old('status', $post->status ?: 'draft') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>@endforeach
    </select></div>
    <div class="field"><label>Publish date/time (if scheduled)</label><input type="datetime-local" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}"></div>
  </div>

  <label style="display:flex;gap:8px;align-items:center;font-size:.9rem;margin-bottom:14px"><input type="checkbox" name="comments_enabled" value="1" {{ old('comments_enabled', $post->exists ? $post->comments_enabled : true) ? 'checked' : '' }}> Allow comments on this post</label>
  <label style="display:flex;gap:8px;align-items:center;font-size:.9rem;margin-bottom:18px"><input type="checkbox" name="send_push" value="1"> Send a push notification when published</label>

  <button class="btn btn-blue" type="submit">Save</button>
  <a class="btn btn-ghost" href="{{ route('admin.content.index', ['type' => $type]) }}">Cancel</a>
</form>
@endsection
