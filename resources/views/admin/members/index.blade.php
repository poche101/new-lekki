@extends('layouts.admin')
@section('title', 'Members and access')
@section('content')
<div style="display:grid;grid-template-columns:1.3fr 1fr;gap:20px">
  <div>
    <div class="card">
      <h3 style="font-size:.95rem;margin-bottom:10px">Invite member</h3>
      <form method="POST" action="{{ route('admin.members.store') }}">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:10px">
          <input type="text" name="name" placeholder="Full name" required>
          <input type="email" name="email" placeholder="Email" required>
          <select name="role">@foreach ($roles as $key => $label)@if ($key !== 'super_admin')<option value="{{ $key }}">{{ $label }}</option>@endif @endforeach<option value="member">Member</option><option value="leader">Leader</option></select>
        </div>
        <button class="btn btn-blue" type="submit">Send invite</button>
      </form>
    </div>

    <div class="a-toolbar" style="margin-top:16px">
      <form method="GET"><input type="text" name="q" value="{{ $q }}" placeholder="Search members" style="border:1px solid var(--line);border-radius:10px;padding:10px 14px"></form>
    </div>
    <table class="a-table">
      <thead><tr><th>Name</th><th>Role</th><th>Status</th><th>Last active</th><th></th></tr></thead>
      <tbody>
        @forelse ($members as $m)
          <tr>
            <td>{{ $m->name }}<div style="font-size:.78rem;color:var(--grey)">{{ $m->email }}</div></td>
            <td><span class="chip">{{ $m->roleLabel() }}</span></td>
            <td><span class="{{ $m->is_active ? 'switch-on' : 'switch-off' }}">{{ $m->is_active ? 'Active' : 'Inactive' }}</span></td>
            <td>{{ $m->last_active_at?->diffForHumans() ?? '—' }}</td>
            <td style="white-space:nowrap">
              <form method="POST" action="{{ route('admin.members.toggle', $m) }}" style="display:inline">@csrf<button class="btn btn-ghost" type="submit">{{ $m->is_active ? 'Deactivate' : 'Activate' }}</button></form>
              <form method="POST" action="{{ route('admin.members.reset', $m) }}" style="display:inline">@csrf<button class="btn btn-ghost" type="submit">Reset password</button></form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" style="text-align:center;color:var(--grey);padding:30px">No members match.</td></tr>
        @endforelse
      </tbody>
    </table>
    <div style="margin-top:16px">{{ $members->links() }}</div>
  </div>

  <div class="card">
    <h3 style="font-size:.95rem;margin-bottom:10px">Meeting access</h3>
    @foreach ($meetings as $m)
      <form method="POST" action="{{ route('admin.meetings.access', $m) }}" style="padding:12px 0;border-top:1px solid var(--line)">
        @csrf @method('PUT')
        <b style="font-size:.9rem">{{ $m->title }}</b>
        <div style="font-size:.78rem;color:var(--grey);margin-bottom:8px">{{ $m->starts_at->format('D, M j · g:i A') }}</div>
        @foreach ($groups as $g)
          <label style="font-size:.82rem;margin-right:10px"><input type="checkbox" name="access_groups[]" value="{{ $g }}" {{ in_array($g, $m->access_groups ?? []) ? 'checked' : '' }}> {{ $g }}</label>
        @endforeach
        <button class="btn btn-ghost" style="margin-top:8px" type="submit">Save</button>
      </form>
    @endforeach
  </div>
</div>
@endsection
