@extends('layouts.admin')
@section('title', 'Homepage manager')
@section('content')
<form method="POST" action="{{ route('admin.homepage.update') }}">
@csrf @method('PUT')
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
  <div class="card">
    <h3 style="font-size:.95rem;margin-bottom:12px">Section visibility</h3>
    @foreach ($sections as $i => $s)
      <label style="display:flex;gap:8px;align-items:center;font-size:.9rem;padding:8px 0;border-top:1px solid var(--line)">
        <input type="hidden" name="sections[{{ $i }}][label]" value="{{ $s['label'] }}">
        {{-- hidden 0 marker so an unchecked box still sends sections[$i][on] = 0, not nothing --}}
        <input type="hidden" name="sections[{{ $i }}][on]" value="0">
        <input type="checkbox" name="sections[{{ $i }}][on]" value="1" {{ $s['on'] ? 'checked' : '' }}> {{ $s['label'] }}
      </label>
    @endforeach
    <p style="font-size:.78rem;color:var(--grey);margin-top:10px">Reordering is stored the same way as visibility; drag-and-drop reordering is a small follow-up (swap array positions before submit).</p>
  </div>
  <div class="card">
    <h3 style="font-size:.95rem;margin-bottom:12px">Featured Higher Life episode</h3>
    <select name="featured_higher_life_id">
      <option value="">Latest episode (automatic)</option>
      @foreach ($higherLifeOptions as $ep)
        <option value="{{ $ep->id }}" {{ (string) $featured === (string) $ep->id ? 'selected' : '' }}>{{ $ep->title }}</option>
      @endforeach
    </select>
    <p style="font-size:.78rem;color:var(--grey);margin-top:10px">This episode plays in the Featured tab and at the top of the Higher Life section. Choose automatic to always use the newest episode.</p>
  </div>
</div>
<button class="btn btn-blue" style="margin-top:16px" type="submit">Save changes</button>
</form>
@endsection
