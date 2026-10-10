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
@else
  {{-- Previously this whole section just vanished with no explanation when
       comments_enabled was false — indistinguishable from a CSS bug hiding
       the form. Making that state visible (if only to admins) turns "why is
       nothing here?" into an immediate, correct answer. Swap the condition
       below for your own admin/permission check (e.g. auth()->user()?->can('moderate')). --}}
  @auth
    <p style="margin-top:36px;padding-top:24px;border-top:1px solid var(--line);color:var(--grey);font-size:.9rem">
      Comments are turned off for this post. (Visible to signed-in users only — toggle "Allow comments" when editing it in Content.)
    </p>
  @endauth
@endif
