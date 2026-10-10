@extends('layouts.admin')
@section('title', 'Media manager')
@section('content')
<div class="card" style="margin-bottom:20px">
  <h3 style="font-size:.95rem;margin-bottom:10px">Upload media</h3>
  <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">
    @csrf
    <div style="display:grid;grid-template-columns:2fr 1fr 1fr;gap:10px;margin-bottom:10px">
      <input type="text" name="title" placeholder="Title" required>
      <select name="type"><option value="video">Video</option><option value="audio">Audio</option><option value="image">Image</option></select>
      <input type="text" name="category" placeholder="Category">
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px">
      <input type="text" name="file_url" placeholder="External URL (YouTube/Vimeo/S3)">
      <input type="file" name="file">
    </div>
    <select name="status" style="margin-bottom:10px"><option value="draft">Draft</option><option value="scheduled">Scheduled</option><option value="published">Published</option></select>
    <button class="btn btn-blue" type="submit">Save media</button>
  </form>
</div>

<table class="a-table">
  <thead><tr><th>Title</th><th>Type</th><th>Status</th><th>Views</th><th></th></tr></thead>
  <tbody>
    @forelse ($items as $m)
      <tr>
        <td>{{ $m->title }}</td><td>{{ ucfirst($m->type) }}</td><td><span class="chip">{{ ucfirst($m->status) }}</span></td><td>{{ $m->views }}</td>
        <td>
          @if ($m->status !== 'archived')
            <form method="POST" action="{{ route('admin.media.archive', $m) }}" style="display:inline">@csrf<button class="btn btn-ghost" type="submit">Archive</button></form>
          @endif
        </td>
      </tr>
    @empty
      <tr><td colspan="5" style="text-align:center;color:var(--grey);padding:30px">No media yet.</td></tr>
    @endforelse
  </tbody>
</table>
<div style="margin-top:16px">{{ $items->links() }}</div>
@endsection
