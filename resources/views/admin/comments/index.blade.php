@extends('layouts.admin')
@section('title', 'Comments')
@section('content')
<p class="lede" style="margin-bottom:16px">Comments post immediately — there is no approve/reject queue. You can reply as the church account or delete any comment.</p>

<div class="card" style="margin-bottom:20px">
  <h3 style="font-size:.95rem;margin-bottom:10px">Post a comment</h3>
  <form method="POST" action="{{ route('admin.comments.store') }}">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 2fr;gap:10px">
      <select name="post_id" class="field" style="border:1px solid var(--line);border-radius:12px;padding:11px" required>
        <option value="">Choose a post…</option>
        @foreach ($posts as $p)<option value="{{ $p->id }}" {{ (string) $postId === (string) $p->id ? 'selected' : '' }}>{{ $p->title }}</option>@endforeach
      </select>
      <input type="text" name="body" placeholder="Write a reply as the church account…" required>
    </div>
    <button class="btn btn-blue" style="margin-top:10px" type="submit">Post comment</button>
  </form>
</div>

<div class="a-toolbar">
  <a class="btn {{ !$postId ? 'btn-blue' : 'btn-ghost' }}" href="{{ route('admin.comments.index') }}">All comments</a>
  @foreach ($posts->take(6) as $p)
    <a class="btn {{ (string) $postId === (string) $p->id ? 'btn-blue' : 'btn-ghost' }}" href="{{ route('admin.comments.index', ['post_id' => $p->id]) }}">{{ str($p->title)->limit(22) }}</a>
  @endforeach
</div>

<table class="a-table">
  <thead><tr><th>Comment</th><th>On post</th><th>When</th><th></th></tr></thead>
  <tbody>
    @forelse ($comments as $c)
      <tr>
        <td><b>{{ $c->authorName() }}</b>{{ $c->is_admin_reply ? ' <span class="chip">Church reply</span>' : '' }}<div style="color:var(--ink-soft);margin-top:4px">{{ $c->body }}</div></td>
        <td>{{ $c->post->title ?? '(deleted post)' }}</td>
        <td>{{ $c->created_at->diffForHumans() }}</td>
        <td>
          <form method="POST" action="{{ route('admin.comments.destroy', $c) }}" onsubmit="return confirm('Delete this comment?')">@csrf @method('DELETE')<button class="btn btn-ghost" style="color:#DC2626" type="submit">Delete</button></form>
        </td>
      </tr>
    @empty
      <tr><td colspan="4" style="text-align:center;color:var(--grey);padding:30px">No comments.</td></tr>
    @endforelse
  </tbody>
</table>
<div style="margin-top:16px">{{ $comments->links() }}</div>
@endsection
